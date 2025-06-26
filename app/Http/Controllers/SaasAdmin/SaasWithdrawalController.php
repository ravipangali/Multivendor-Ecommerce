<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasWithdrawal;
use App\Models\SaasSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaasWithdrawalController extends Controller
{
    /**
     * Display a listing of withdrawal requests.
     */
    public function index(Request $request)
    {
        $query = SaasWithdrawal::with(['user', 'paymentMethod', 'processedBy'])
                              ->sellerWithdrawals();

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
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('notes', 'like', "%{$search}%")
                ->orWhere('admin_notes', 'like', "%{$search}%");
            });
        }

        $withdrawals = $query->latest()->paginate(20);

        $statuses = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'processing' => 'Processing',
            'completed' => 'Completed',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled'
        ];

        // Statistics
        $totalWithdrawals = SaasWithdrawal::sellerWithdrawals()->count();
        $pendingWithdrawals = SaasWithdrawal::sellerWithdrawals()->where('status', 'pending')->count();
        $approvedWithdrawals = SaasWithdrawal::sellerWithdrawals()->where('status', 'approved')->count();
        $totalWithdrawalAmount = SaasWithdrawal::sellerWithdrawals()->where('status', 'approved')->sum('requested_amount');
        $totalGatewayFees = SaasWithdrawal::sellerWithdrawals()->where('status', 'approved')->sum('gateway_fee');

        // Get settings for gateway fee display
        $settings = SaasSetting::first();
        $currentGatewayFee = $settings ? $settings->gateway_transaction_fee : 0;

        return view('saas_admin.saas_withdrawal.saas_index', compact(
            'withdrawals',
            'statuses',
            'totalWithdrawals',
            'pendingWithdrawals',
            'approvedWithdrawals',
            'totalWithdrawalAmount',
            'totalGatewayFees',
            'currentGatewayFee'
        ));
    }

    /**
     * Display the specified withdrawal request.
     */
    public function show(SaasWithdrawal $withdrawal)
    {
        $withdrawal->load(['user.sellerProfile', 'paymentMethod', 'processedBy']);

        return view('saas_admin.saas_withdrawal.saas_show', compact('withdrawal'));
    }

    /**
     * Approve a withdrawal request.
     */
    public function approve(Request $request, SaasWithdrawal $withdrawal)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
            'admin_attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120', // 5MB max
        ]);

        try {
            $attachmentFile = $request->hasFile('admin_attachment') ? $request->file('admin_attachment') : null;

            $withdrawal->approveWithdrawal(
                Auth::id(),
                $request->admin_notes,
                $attachmentFile
            );

            return redirect()->route('admin.withdrawals.show', $withdrawal)
                           ->with('success', 'Withdrawal request approved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error approving withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Reject a withdrawal request.
     */
    public function reject(Request $request, SaasWithdrawal $withdrawal)
    {
        $request->validate([
            'rejected_reason' => 'required|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        try {
            $withdrawal->rejectWithdrawal(
                Auth::id(),
                $request->rejected_reason,
                $request->admin_notes
            );

            return redirect()->route('admin.withdrawals.show', $withdrawal)
                           ->with('success', 'Withdrawal request rejected successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error rejecting withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Download admin attachment.
     */
    public function downloadAttachment(SaasWithdrawal $withdrawal)
    {
        if (!$withdrawal->admin_attachment) {
            return redirect()->back()->with('error', 'No attachment found.');
        }

        $filePath = storage_path('app/public/' . $withdrawal->admin_attachment);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Attachment file not found.');
        }

        return response()->download($filePath);
    }
}
