<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasRefund;
use App\Models\SaasOrder;
use App\Models\SaasPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaasRefundController extends Controller
{
    /**
     * Display a listing of refund requests.
     */
    public function index(Request $request)
    {
        $query = SaasRefund::with(['customer', 'order', 'seller', 'paymentMethod', 'processedBy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('customer', function($customerQuery) use ($search) {
                    $customerQuery->where('name', 'like', "%{$search}%")
                                  ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('order', function($orderQuery) use ($search) {
                    $orderQuery->where('order_number', 'like', "%{$search}%");
                })
                ->orWhere('customer_reason', 'like', "%{$search}%");
            });
        }

        $refunds = $query->latest()->paginate(20);

        $statuses = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'processed' => 'Processed',
            'rejected' => 'Rejected'
        ];

        // Statistics
        $totalRefunds = SaasRefund::count();
        $pendingRefunds = SaasRefund::where('status', 'pending')->count();
        $approvedRefunds = SaasRefund::where('status', 'approved')->count();
        $totalRefundAmount = SaasRefund::where('status', 'approved')->sum('refund_amount');

        return view('saas_admin.saas_refund.saas_index', compact(
            'refunds',
            'statuses',
            'totalRefunds',
            'pendingRefunds',
            'approvedRefunds',
            'totalRefundAmount'
        ));
    }

    /**
     * Display the specified refund request.
     */
    public function show(SaasRefund $refund)
    {
        $refund->load(['customer', 'order.items.product', 'seller', 'paymentMethod', 'processedBy']);

        return view('saas_admin.saas_refund.saas_show', compact('refund'));
    }

    /**
     * Show the form for editing the specified refund request.
     */
    public function edit(SaasRefund $refund)
    {
        if (!in_array($refund->status, ['pending'])) {
            return redirect()->route('admin.refunds.show', $refund)
                           ->with('error', 'Only pending refunds can be edited.');
        }

        $refund->load(['customer', 'order', 'seller', 'paymentMethod']);

        return view('saas_admin.saas_refund.saas_edit', compact('refund'));
    }

    /**
     * Approve a refund request.
     */
    public function approve(Request $request, SaasRefund $refund)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
            'admin_attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120', // 5MB max
        ]);

        try {
            $attachmentFile = $request->hasFile('admin_attachment') ? $request->file('admin_attachment') : null;

            $refund->approveRefund(
                Auth::id(),
                $request->admin_notes,
                $attachmentFile
            );

            return redirect()->route('admin.refunds.show', $refund)
                           ->with('success', 'Refund request approved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error approving refund: ' . $e->getMessage());
        }
    }

    /**
     * Reject a refund request.
     */
    public function reject(Request $request, SaasRefund $refund)
    {
        $request->validate([
            'rejected_reason' => 'required|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        try {
            $refund->rejectRefund(
                Auth::id(),
                $request->rejected_reason,
                $request->admin_notes
            );

            return redirect()->route('admin.refunds.show', $refund)
                           ->with('success', 'Refund request rejected successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error rejecting refund: ' . $e->getMessage());
        }
    }

    /**
     * Download admin attachment.
     */
    public function downloadAttachment(SaasRefund $refund)
    {
        if (!$refund->admin_attachment) {
            return redirect()->back()->with('error', 'No attachment found.');
        }

        $filePath = storage_path('app/public/' . $refund->admin_attachment);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Attachment file not found.');
        }

        return response()->download($filePath);
    }
}
