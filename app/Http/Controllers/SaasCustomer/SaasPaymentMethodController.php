<?php

namespace App\Http\Controllers\SaasCustomer;

use App\Http\Controllers\Controller;
use App\Models\SaasPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SaasPaymentMethodController extends Controller
{
    /**
     * Display a listing of customer's payment methods.
     */
    public function index()
    {
        $customerId = Auth::id();

        try {
            $paymentMethods = SaasPaymentMethod::where('user_id', $customerId)
                                             ->latest()
                                             ->paginate(10);

            // Statistics
            $totalPaymentMethods = SaasPaymentMethod::where('user_id', $customerId)->count();
            $activePaymentMethods = SaasPaymentMethod::where('user_id', $customerId)->where('is_active', true)->count();
            $defaultPaymentMethod = SaasPaymentMethod::where('user_id', $customerId)->where('is_default', true)->first();

            // Payment method types count
            $bankAccounts = SaasPaymentMethod::where('user_id', $customerId)->where('type', 'bank_account')->count();
            $mobileWallets = SaasPaymentMethod::where('user_id', $customerId)->where('type', 'mobile_wallet')->count();
            $cards = SaasPaymentMethod::where('user_id', $customerId)->where('type', 'card')->count();

        } catch (\Exception $e) {
            // If table doesn't exist or other error, set default values
            $paymentMethods = collect()->paginate(10);
            $totalPaymentMethods = 0;
            $activePaymentMethods = 0;
            $defaultPaymentMethod = null;
            $bankAccounts = 0;
            $mobileWallets = 0;
            $cards = 0;
        }

        return view('saas_customer.saas_payment_methods.saas_index', compact(
            'paymentMethods',
            'totalPaymentMethods',
            'activePaymentMethods',
            'defaultPaymentMethod',
            'bankAccounts',
            'mobileWallets',
            'cards'
        ));
    }

    /**
     * Show the form for creating a new payment method.
     */
    public function create()
    {
        return view('saas_customer.saas_payment_methods.saas_create');
    }

    /**
     * Store a newly created payment method.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:bank_transfer,esewa,khalti',
            'details' => 'required|array',
            'is_default' => 'nullable|boolean',
        ]);

        try {
            // If this is set as default, unset others
            if ($request->boolean('is_default')) {
                SaasPaymentMethod::where('user_id', Auth::id())
                                ->where('is_default', true)
                                ->update(['is_default' => false]);
            }

            // If this is the first payment method, make it default
            $isFirstMethod = SaasPaymentMethod::where('user_id', Auth::id())->count() === 0;

            $paymentMethod = SaasPaymentMethod::create([
                'user_id' => Auth::id(),
                'type' => $request->type,
                'details' => $request->details,
                'is_default' => $request->boolean('is_default') || $isFirstMethod,
                'status' => true, // Assuming new methods are active by default
            ]);

            return redirect()->route('customer.payment-methods.index')
                           ->with('success', 'Payment method added successfully.');

        } catch (\Exception $e) {
            Log::error('Payment method creation failed: ' . $e->getMessage(), [
                'customer_id' => Auth::id(),
                'type' => $request->type,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                           ->with('error', 'An error occurred while adding the payment method. Please try again.')
                           ->withInput();
        }
    }

    /**
     * Display the specified payment method.
     */
    public function show(SaasPaymentMethod $paymentMethod)
    {
        // Ensure the payment method belongs to the authenticated customer
        if ($paymentMethod->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to payment method.');
        }

        return view('saas_customer.saas_payment_methods.saas_show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified payment method.
     */
    public function edit(SaasPaymentMethod $paymentMethod)
    {
        // Ensure the payment method belongs to the authenticated customer
        if ($paymentMethod->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to payment method.');
        }

        return view('saas_customer.saas_payment_methods.saas_edit', compact('paymentMethod'));
    }

    /**
     * Update the specified payment method.
     */
    public function update(Request $request, SaasPaymentMethod $paymentMethod)
    {
        // Ensure the payment method belongs to the authenticated customer
        if ($paymentMethod->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to payment method.');
        }

        $request->validate([
            'type' => 'required|in:bank_transfer,esewa,khalti',
            'details' => 'required|array',
            'is_default' => 'nullable|boolean',
        ]);

        try {
            // If this is set as default, unset others
            if ($request->boolean('is_default') && !$paymentMethod->is_default) {
                SaasPaymentMethod::where('user_id', Auth::id())
                                ->where('id', '!=', $paymentMethod->id)
                                ->update(['is_default' => false]);
            }

            $paymentMethod->update([
                'type' => $request->type,
                'details' => $request->details,
                'is_default' => $request->boolean('is_default'),
            ]);

            return redirect()->route('customer.payment-methods.index')
                           ->with('success', 'Payment method updated successfully.');

        } catch (\Exception $e) {
            Log::error('Payment method update failed: ' . $e->getMessage(), [
                'customer_id' => Auth::id(),
                'payment_method_id' => $paymentMethod->id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                           ->with('error', 'An error occurred while updating the payment method. Please try again.')
                           ->withInput();
        }
    }

    /**
     * Remove the specified payment method.
     */
    public function destroy(SaasPaymentMethod $paymentMethod)
    {
        // Ensure the payment method belongs to the authenticated customer
        if ($paymentMethod->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to payment method.');
        }

        try {
            $isDefault = $paymentMethod->is_default;
            $paymentMethod->delete();

            // If deleted method was default, set another one as default
            if ($isDefault) {
                $newDefault = SaasPaymentMethod::where('user_id', Auth::id())
                                             ->where('is_active', true)
                                             ->first();
                if ($newDefault) {
                    $newDefault->update(['is_default' => true]);
                }
            }

            return redirect()->route('customer.payment-methods.index')
                           ->with('success', 'Payment method deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Payment method deletion failed: ' . $e->getMessage(), [
                'customer_id' => Auth::id(),
                'payment_method_id' => $paymentMethod->id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                           ->with('error', 'An error occurred while deleting the payment method. Please try again.');
        }
    }

    /**
     * Set a payment method as default.
     */
    public function setDefault(SaasPaymentMethod $paymentMethod)
    {
        // Ensure the payment method belongs to the authenticated customer
        if ($paymentMethod->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to payment method.');
        }

        try {
            // Unset all other defaults
            SaasPaymentMethod::where('user_id', Auth::id())
                            ->update(['is_default' => false]);

            // Set this one as default
            $paymentMethod->update(['is_default' => true]);

            return redirect()->route('customer.payment-methods.index')
                           ->with('success', 'Default payment method updated successfully.');

        } catch (\Exception $e) {
            Log::error('Set default payment method failed: ' . $e->getMessage(), [
                'customer_id' => Auth::id(),
                'payment_method_id' => $paymentMethod->id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                           ->with('error', 'An error occurred while setting the default payment method.');
        }
    }

    /**
     * Toggle payment method active status.
     */
    public function toggleStatus(SaasPaymentMethod $paymentMethod)
    {
        // Ensure the payment method belongs to the authenticated customer
        if ($paymentMethod->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to payment method.');
        }

        try {
            $paymentMethod->update(['status' => !$paymentMethod->status]);

            $status = $paymentMethod->status ? 'activated' : 'deactivated';

            return redirect()->route('customer.payment-methods.index')
                           ->with('success', "Payment method {$status} successfully.");

        } catch (\Exception $e) {
            Log::error('Toggle payment method status failed: ' . $e->getMessage(), [
                'customer_id' => Auth::id(),
                'payment_method_id' => $paymentMethod->id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                           ->with('error', 'An error occurred while updating the payment method status.');
        }
    }
}
