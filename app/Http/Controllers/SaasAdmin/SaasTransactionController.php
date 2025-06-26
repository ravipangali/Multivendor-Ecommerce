<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SaasTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SaasTransaction::with(['user', 'order']);

        // Filter by transaction type
        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }

        // Filter by user (seller)
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->dateRange($request->start_date, $request->end_date);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('order', function($orderQuery) use ($search) {
                      $orderQuery->where('order_number', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->latest('transaction_date')->paginate(20);

        // Get filter options
        $sellers = User::where('role', 'seller')->orderBy('name')->get();
        $transactionTypes = [
            SaasTransaction::TYPE_DEPOSIT => 'Deposit',
            SaasTransaction::TYPE_WITHDRAWAL => 'Withdrawal',
            SaasTransaction::TYPE_COMMISSION => 'Commission',
            SaasTransaction::TYPE_REFUND => 'Refund',
        ];
        $statuses = [
            SaasTransaction::STATUS_PENDING => 'Pending',
            SaasTransaction::STATUS_COMPLETED => 'Completed',
            SaasTransaction::STATUS_FAILED => 'Failed',
            SaasTransaction::STATUS_CANCELLED => 'Cancelled',
        ];

        // Calculate statistics
        $totalTransactions = SaasTransaction::count();
        $totalDeposits = SaasTransaction::ofType(SaasTransaction::TYPE_DEPOSIT)->completed()->sum('amount');
        $totalWithdrawals = SaasTransaction::ofType(SaasTransaction::TYPE_WITHDRAWAL)->completed()->sum('amount');
        $totalCommissions = SaasTransaction::ofType(SaasTransaction::TYPE_COMMISSION)->completed()->sum('amount');

        return view('saas_admin.saas_transaction.saas_index', compact(
            'transactions',
            'sellers',
            'transactionTypes',
            'statuses',
            'totalTransactions',
            'totalDeposits',
            'totalWithdrawals',
            'totalCommissions'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasTransaction $transaction)
    {
        $transaction->load(['user', 'order.customer', 'order.seller']);

        return view('saas_admin.saas_transaction.saas_show', compact('transaction'));
    }

    /**
     * Get admin transactions (where user_id is null)
     */
    public function adminTransactions(Request $request)
    {
        $query = SaasTransaction::whereNull('user_id')->with(['order']);

        // Filter by transaction type
        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->dateRange($request->start_date, $request->end_date);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('order', function($orderQuery) use ($search) {
                      $orderQuery->where('order_number', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->latest('transaction_date')->paginate(20);

        $transactionTypes = [
            SaasTransaction::TYPE_DEPOSIT => 'Deposit',
            SaasTransaction::TYPE_WITHDRAWAL => 'Withdrawal',
            SaasTransaction::TYPE_COMMISSION => 'Commission',
            SaasTransaction::TYPE_REFUND => 'Refund',
        ];
        $statuses = [
            SaasTransaction::STATUS_PENDING => 'Pending',
            SaasTransaction::STATUS_COMPLETED => 'Completed',
            SaasTransaction::STATUS_FAILED => 'Failed',
            SaasTransaction::STATUS_CANCELLED => 'Cancelled',
        ];

        // Calculate admin-specific statistics
        $adminQuery = SaasTransaction::whereNull('user_id');

        $totalTransactions = $adminQuery->count();
        $totalDeposits = (clone $adminQuery)->ofType(SaasTransaction::TYPE_DEPOSIT)->completed()->sum('amount');
        $totalWithdrawals = (clone $adminQuery)->ofType(SaasTransaction::TYPE_WITHDRAWAL)->completed()->sum('amount');
        $totalCommissions = (clone $adminQuery)->ofType(SaasTransaction::TYPE_COMMISSION)->completed()->sum('amount');

        return view('saas_admin.saas_transaction.saas_admin_transactions', compact(
            'transactions',
            'transactionTypes',
            'statuses',
            'totalTransactions',
            'totalDeposits',
            'totalWithdrawals',
            'totalCommissions'
        ));
    }

    /**
     * Export transactions to CSV
     */
    public function export(Request $request)
    {
        $query = SaasTransaction::with(['user', 'order']);

        // Apply same filters as index
        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->dateRange($request->start_date, $request->end_date);
        }

        $transactions = $query->latest('transaction_date')->get();

        $filename = 'transactions_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID', 'Date', 'User', 'Type', 'Amount', 'Balance Before', 'Balance After',
                'Order Number', 'Commission %', 'Commission Amount', 'Status', 'Description'
            ]);

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->id,
                    $transaction->transaction_date->format('Y-m-d H:i:s'),
                    $transaction->user ? $transaction->user->name : 'Admin',
                    ucfirst($transaction->transaction_type),
                    $transaction->amount,
                    $transaction->balance_before,
                    $transaction->balance_after,
                    $transaction->order ? $transaction->order->order_number : '',
                    $transaction->commission_percentage,
                    $transaction->commission_amount,
                    ucfirst($transaction->status),
                    $transaction->description,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
