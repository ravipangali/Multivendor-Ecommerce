<?php

namespace App\Http\Controllers\SaasCustomer;

use App\Http\Controllers\Controller;
use App\Models\SaasRefund;
use App\Models\SaasOrder;
use App\Models\SaasPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SaasRefundController extends Controller
{
    /**
     * Display a listing of customer's refund requests.
     */
    public function index()
    {
        $customerId = Auth::id();

        try {
            $refunds = SaasRefund::where('customer_id', $customerId)
                                ->with(['order', 'seller', 'paymentMethod', 'processedBy'])
                                ->latest()
                                ->paginate(10);

            // Statistics
            $totalRefunds = SaasRefund::where('customer_id', $customerId)->count();
            $pendingRefunds = SaasRefund::where('customer_id', $customerId)->where('status', 'pending')->count();
            $approvedRefunds = SaasRefund::where('customer_id', $customerId)->whereIn('status', ['approved', 'processed'])->count();
            $totalRefundAmount = SaasRefund::where('customer_id', $customerId)->whereIn('status', ['approved', 'processed'])->sum('refund_amount');
        } catch (\Exception $e) {
            // If table doesn't exist or other error, set default values
            $refunds = collect()->paginate(10);
            $totalRefunds = 0;
            $pendingRefunds = 0;
            $approvedRefunds = 0;
            $totalRefundAmount = 0;
        }

        return view('saas_customer.saas_refund.saas_index', compact(
            'refunds',
            'totalRefunds',
            'pendingRefunds',
            'approvedRefunds',
            'totalRefundAmount'
        ));
    }

        /**
     * Show the form for creating a new refund request.
     */
    public function create(Request $request)
    {
        $customerId = Auth::id();
        $selectedOrderId = $request->input('order_id');

        // Get eligible orders
        $eligibleOrders = SaasOrder::where('customer_id', $customerId)
                                 ->whereIn('order_status', ['delivered', 'completed'])
                                 ->whereNotExists(function ($query) {
                                     $query->select('id')
                                           ->from('saas_refunds')
                                           ->whereColumn('saas_refunds.order_id', 'saas_orders.id')
                                           ->whereIn('status', ['pending', 'approved', 'processed']);
                                 })
                                 ->with(['items.product'])
                                 ->latest()
                                 ->get();

        // Get customer's payment methods
        $paymentMethods = SaasPaymentMethod::where('user_id', $customerId)
                                          ->where('is_active', true)
                                          ->get();

        // Get recent refunds count for stats
        $recentRefunds = SaasRefund::where('customer_id', $customerId)
                                  ->where('created_at', '>=', now()->subMonth())
                                  ->count();

        if ($paymentMethods->isEmpty()) {
            return redirect()->route('customer.orders')
                           ->with('error', 'You need to add a payment method before requesting a refund. Please add your bank account or payment details in your profile settings first.');
        }

        return view('saas_customer.saas_refund.saas_create', compact('eligibleOrders', 'paymentMethods', 'recentRefunds', 'selectedOrderId'));
    }

    /**
     * Store a newly created refund request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:saas_orders,id',
            'payment_method_id' => 'required|exists:saas_payment_methods,id',
            'customer_reason' => 'required|string|min:10|max:1000',
        ], [
            'order_id.required' => 'Please select an order to request a refund for.',
            'order_id.exists' => 'The selected order does not exist.',
            'payment_method_id.required' => 'Please select a payment method for the refund.',
            'payment_method_id.exists' => 'The selected payment method does not exist.',
            'customer_reason.required' => 'Please provide a reason for requesting the refund.',
            'customer_reason.min' => 'The refund reason must be at least 10 characters long.',
            'customer_reason.max' => 'The refund reason cannot exceed 1000 characters.',
        ]);

        try {
            // Additional validation to ensure order belongs to customer
            $order = SaasOrder::where('id', $request->order_id)
                             ->where('customer_id', Auth::id())
                             ->first();

            if (!$order) {
                return redirect()->back()
                               ->with('error', 'The selected order does not belong to your account.')
                               ->withInput();
            }

            // Check if order is eligible for refund
            if (!in_array($order->order_status, ['delivered', 'completed'])) {
                return redirect()->back()
                               ->with('error', 'Only delivered or completed orders can be refunded.')
                               ->withInput();
            }

            // Check if refund already exists
            $existingRefund = SaasRefund::where('order_id', $request->order_id)
                                       ->whereIn('status', ['pending', 'approved', 'processed'])
                                       ->first();

            if ($existingRefund) {
                return redirect()->route('customer.refunds.show', $existingRefund)
                               ->with('info', 'A refund request already exists for this order.');
            }

            // Verify payment method belongs to customer
            $paymentMethod = SaasPaymentMethod::where('id', $request->payment_method_id)
                                             ->where('user_id', Auth::id())
                                             ->where('is_active', true)
                                             ->first();

            if (!$paymentMethod) {
                return redirect()->back()
                               ->with('error', 'The selected payment method is not valid or has been deactivated.')
                               ->withInput();
            }

            $refund = SaasRefund::createRefundRequest(
                Auth::id(),
                $request->order_id,
                $request->payment_method_id,
                $request->customer_reason
            );

            return redirect()->route('customer.refunds.show', $refund)
                           ->with('success', 'Your refund request has been submitted successfully. We will review it within 24-48 hours and notify you of the decision.');

        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                           ->with('error', $e->getMessage())
                           ->withInput();
        } catch (\Exception $e) {
            Log::error('Refund creation failed: ' . $e->getMessage(), [
                'customer_id' => Auth::id(),
                'order_id' => $request->order_id,
                'payment_method_id' => $request->payment_method_id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                           ->with('error', 'An unexpected error occurred while submitting your refund request. Please try again or contact support if the problem persists.')
                           ->withInput();
        }
    }

    /**
     * Display the specified refund request.
     */
    public function show(SaasRefund $refund)
    {
        // Ensure the refund belongs to the authenticated customer
        if ($refund->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized access to refund request.');
        }

        $refund->load(['order.items.product', 'seller', 'paymentMethod', 'processedBy']);

        return view('saas_customer.saas_refund.saas_show', compact('refund'));
    }

    /**
     * Download admin attachment if available.
     */
    public function downloadAttachment(SaasRefund $refund)
    {
        // Ensure the refund belongs to the authenticated customer
        if ($refund->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized access to refund request.');
        }

        if (!$refund->admin_attachment) {
            abort(404, 'Attachment not found');
        }

        $filePath = storage_path('app/public/' . $refund->admin_attachment);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download($filePath);
    }
}
