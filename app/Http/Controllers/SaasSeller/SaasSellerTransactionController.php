<?php

namespace App\Http\Controllers\SaasSeller;

use App\Http\Controllers\Controller;
use App\Models\SaasTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SaasSellerTransactionController extends Controller
{
    /**
     * Display a listing of seller's transactions.
     */
    public function index(Request $request)
    {
        $seller = Auth::user();

        $query = SaasTransaction::where('user_id', $seller->id)->with(['order']);

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

        // Calculate seller-specific statistics
        $totalEarnings = SaasTransaction::where('user_id', $seller->id)
            ->ofType(SaasTransaction::TYPE_COMMISSION)
            ->completed()
            ->sum('amount');

        $totalWithdrawals = SaasTransaction::where('user_id', $seller->id)
            ->ofType(SaasTransaction::TYPE_WITHDRAWAL)
            ->completed()
            ->sum('amount');

        $currentBalance = $seller->balance ?? 0;

        $totalTransactions = SaasTransaction::where('user_id', $seller->id)->count();

        return view('saas_seller.saas_transaction.saas_index', compact(
            'transactions',
            'transactionTypes',
            'statuses',
            'totalEarnings',
            'totalWithdrawals',
            'currentBalance',
            'totalTransactions'
        ));
    }

    /**
     * Display the specified transaction.
     */
    public function show(SaasTransaction $transaction)
    {
        // Ensure the transaction belongs to the authenticated seller
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to transaction.');
        }

        $transaction->load(['order.customer']);

        return view('saas_seller.saas_transaction.saas_show', compact('transaction'));
    }

    /**
     * Export seller's transactions to CSV
     */
    public function export(Request $request)
    {
        $seller = Auth::user();
        $query = SaasTransaction::where('user_id', $seller->id)->with(['order']);

        // Apply same filters as index
        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->dateRange($request->start_date, $request->end_date);
        }

        $transactions = $query->latest('transaction_date')->get();

        $filename = 'seller_transactions_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID', 'Date', 'Type', 'Amount', 'Balance Before', 'Balance After',
                'Order Number', 'Commission %', 'Commission Amount', 'Status', 'Description'
            ]);

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->id,
                    $transaction->transaction_date->format('Y-m-d H:i:s'),
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
