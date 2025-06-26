<?php

namespace App\Http\Controllers\SaasSeller;

use App\Http\Controllers\Controller;
use App\Models\SaasOrder;
use App\Models\SaasWithdrawal;
use App\Models\SaasPaymentMethod;
use App\Models\SaasSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaasWithdrawalController extends Controller
{
    /**
     * Display a listing of the withdrawals.
     */
    public function index()
    {
        $seller = Auth::user();

        // Get seller's withdrawals
        $withdrawalRequests = SaasWithdrawal::where('user_id', $seller->id)
                                   ->sellerWithdrawals()
                                   ->with(['paymentMethod', 'processedBy'])
                                   ->latest()
                                   ->paginate(10);

        // Get statistics
        $totalWithdrawals = SaasWithdrawal::where('user_id', $seller->id)->sellerWithdrawals()->count();
        $totalWithdrawn = SaasWithdrawal::where('user_id', $seller->id)
                                       ->sellerWithdrawals()
                                       ->where('status', 'approved')
                                       ->sum('requested_amount');
        $pendingAmount = SaasWithdrawal::where('user_id', $seller->id)
                                      ->sellerWithdrawals()
                                      ->where('status', 'pending')
                                      ->sum('requested_amount');

        $balance = $seller->balance;

        return view('saas_seller.saas_withdrawal.saas_index', compact(
            'withdrawalRequests',
            'totalWithdrawals',
            'totalWithdrawn',
            'pendingAmount',
            'balance'
        ));
    }

    /**
     * Show the form for requesting a withdrawal.
     */
    public function create()
    {
        $seller = Auth::user();

        // Check if there's a pending withdrawal already
        $pendingWithdrawal = SaasWithdrawal::where('user_id', $seller->id)
                                          ->sellerWithdrawals()
                                          ->where('status', 'pending')
                                          ->first();

        if ($pendingWithdrawal) {
            return redirect()->route('seller.withdrawals.index')
                           ->with('info', 'You already have a pending withdrawal request. Please wait for it to be processed before requesting another withdrawal.');
        }

        // Get seller's active payment methods
        $paymentMethods = SaasPaymentMethod::where('user_id', $seller->id)
                                          ->where('is_active', true)
                                          ->get();

        if ($paymentMethods->isEmpty()) {
            return redirect()->route('seller.payment-methods.create')
                           ->with('error', 'Please add a payment method before requesting a withdrawal.');
        }

        // Get settings for minimum withdrawal and gateway fee
        $settings = SaasSetting::first();
        $minimumWithdrawal = $settings ? $settings->minimum_withdrawal_amount : 100;
        $gatewayFee = $settings ? $settings->gateway_transaction_fee : 0;

        // Calculate available balance
        $availableBalance = $seller->balance ?? 0;

        return view('saas_seller.saas_withdrawal.saas_create', compact(
            'paymentMethods',
            'availableBalance',
            'minimumWithdrawal',
            'gatewayFee'
        ));
    }

    /**
     * Store a newly created withdrawal request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'requested_amount' => 'required|numeric|min:1',
            'payment_method_id' => 'required|exists:saas_payment_methods,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $seller = Auth::user();

        // Check minimum withdrawal amount
        $settings = SaasSetting::first();
        $minimumAmount = $settings ? $settings->minimum_withdrawal_amount : 100;

        if ($request->requested_amount < $minimumAmount) {
            return redirect()->back()
                           ->with('error', "Minimum withdrawal amount is Rs. {$minimumAmount}")
                           ->withInput();
        }

        // Check if seller has sufficient balance
        if ($seller->balance < $request->requested_amount) {
            return redirect()->back()
                           ->with('error', 'Insufficient balance for withdrawal')
                           ->withInput();
        }

        try {
            $withdrawal = SaasWithdrawal::requestSellerWithdrawal(
                $seller->id,
                $request->requested_amount,
                $request->payment_method_id,
                $request->notes
            );

            return redirect()->route('seller.withdrawals.show', $withdrawal)
                           ->with('success', 'Withdrawal request submitted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error creating withdrawal request: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display withdrawal history.
     */
    public function history()
    {
        $seller = Auth::user();

        $withdrawals = SaasWithdrawal::where('user_id', $seller->id)
                                   ->sellerWithdrawals()
                                   ->with(['paymentMethod', 'processedBy'])
                                   ->latest()
                                   ->paginate(15);

        return view('saas_seller.saas_withdrawal.saas_history', compact('withdrawals'));
    }

    /**
     * Display the specified withdrawal.
     */
    public function show(SaasWithdrawal $withdrawal)
    {
        // Check if the withdrawal belongs to the authenticated seller
        if ($withdrawal->user_id !== Auth::id()) {
            return redirect()->route('seller.withdrawals.index')
                           ->with('error', 'You are not authorized to view this withdrawal.');
        }

        $withdrawal->load(['paymentMethod', 'processedBy']);

        return view('saas_seller.saas_withdrawal.saas_show', compact('withdrawal'));
    }

    /**
     * Cancel a pending withdrawal request.
     */
    public function cancel(SaasWithdrawal $withdrawal)
    {
        // Check if the withdrawal belongs to the authenticated seller
        if ($withdrawal->user_id !== Auth::id()) {
            return redirect()->route('seller.withdrawals.index')
                           ->with('error', 'You are not authorized to cancel this withdrawal.');
        }

        // Check if the withdrawal is still pending
        if ($withdrawal->status !== 'pending') {
            return redirect()->route('seller.withdrawals.index')
                           ->with('error', 'Only pending withdrawals can be cancelled.');
        }

        try {
            $withdrawal->cancelWithdrawal();

            return redirect()->route('seller.withdrawals.index')
                           ->with('success', 'Withdrawal request cancelled successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error cancelling withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Download admin attachment if available.
     */
    public function downloadAttachment(SaasWithdrawal $withdrawal)
    {
        // Check if the withdrawal belongs to the authenticated seller
        if ($withdrawal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to withdrawal.');
        }

        if (!$withdrawal->admin_attachment) {
            abort(404, 'Attachment not found');
        }

        $filePath = storage_path('app/public/' . $withdrawal->admin_attachment);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download($filePath);
    }
}
