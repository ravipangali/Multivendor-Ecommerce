<?php

namespace App\Http\Controllers\SaasSeller;

use App\Http\Controllers\Controller;
use App\Models\SaasOrder;
use App\Models\SaasWallet;
use App\Models\SaasWithdrawal;
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
        $sellerId = Auth::id();

        // Get or create the seller's wallet
        $wallet = SaasWallet::getOrCreate($sellerId);

        // Get withdrawal requests
        $withdrawalRequests = SaasWithdrawal::where('user_id', $sellerId)
            ->latest()
            ->paginate(10);

        return view('saas_seller.saas_withdrawal.saas_index', compact(
            'wallet',
            'withdrawalRequests'
        ));
    }

    /**
     * Show the form for requesting a withdrawal.
     */
    public function create()
    {
        $sellerId = Auth::id();

        // Get or create the seller's wallet
        $wallet = SaasWallet::getOrCreate($sellerId);

        // Check if there's a pending withdrawal already
        $pendingWithdrawal = SaasWithdrawal::where('user_id', $sellerId)
            ->where('status', 'pending')
            ->first();

        if ($pendingWithdrawal) {
            return redirect()->route('seller.withdrawals.index')
                ->with('info', 'You already have a pending withdrawal request. Please wait for it to be processed before requesting another withdrawal.');
        }

        // Get available payment methods
        $paymentMethods = [
            'bank_transfer' => 'Bank Transfer',
            'paypal' => 'PayPal',
            'stripe' => 'Stripe'
        ];

        return view('saas_seller.saas_withdrawal.saas_create', compact(
            'wallet',
            'paymentMethods'
        ));
    }

    /**
     * Store a newly created withdrawal request.
     */
    public function store(Request $request)
    {
        $sellerId = Auth::id();

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:bank_transfer,paypal,stripe',
            'payment_details' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Get the seller's wallet
        $wallet = SaasWallet::getOrCreate($sellerId);

        if ($request->amount > $wallet->available_for_withdrawal) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Withdrawal amount exceeds available balance');
        }

        try {
            // Create the withdrawal request with transaction
            $withdrawal = SaasWithdrawal::requestWithdrawal(
                $sellerId,
                $request->amount,
                $request->payment_method,
                ['details' => $request->payment_details],
                $request->notes
            );

            return redirect()->route('seller.withdrawals.index')
                ->with('success', 'Withdrawal request submitted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating withdrawal request: ' . $e->getMessage());
        }
    }

    /**
     * Display withdrawal history.
     */
    public function history()
    {
        $sellerId = Auth::id();

        $withdrawals = SaasWithdrawal::where('user_id', $sellerId)
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
            $withdrawal->rejectWithdrawal('Cancelled by seller', 'Seller initiated cancellation');

            return redirect()->route('seller.withdrawals.index')
                ->with('success', 'Withdrawal request cancelled successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error cancelling withdrawal: ' . $e->getMessage());
        }
    }
}
