<?php

namespace App\Http\Controllers\SaasSeller;

use App\Http\Controllers\Controller;
use App\Models\SaasPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class SaasPaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentMethods = SaasPaymentMethod::where('user_id', Auth::id())
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('saas_seller.saas_payment_method.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('saas_seller.saas_payment_method.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate payment method data
            $this->validatePaymentMethod($request);

            // Create a new payment method
            $paymentMethod = new SaasPaymentMethod();
            $paymentMethod->user_id = Auth::id();

            // Populate payment method data
            $this->populatePaymentMethodData($request, $paymentMethod);

            // If this is the first payment method or is_default is checked, set as default
            if ($request->has('is_default') || SaasPaymentMethod::where('user_id', Auth::id())->count() === 0) {
                // Set all other payment methods to non-default
                SaasPaymentMethod::where('user_id', Auth::id())
                    ->update(['is_default' => false]);

                $paymentMethod->is_default = true;
            }

            // Save the payment method
            $paymentMethod->save();

            // Redirect with success message
            return redirect()->route('seller.payment-methods.index')
                ->with('success', 'Payment method created successfully.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error creating payment method: ' . $e->getMessage());

            // Redirect with error message
            return redirect()->back()->withInput()
                ->with('error', 'Error creating payment method: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasPaymentMethod $paymentMethod)
    {
        // Verify ownership or admin status
        if (Gate::denies('view', $paymentMethod)) {
            abort(403, 'Unauthorized action. You can only view your own payment methods.');
        }

        return view('saas_seller.saas_payment_method.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasPaymentMethod $paymentMethod)
    {
        // Verify ownership or admin status
        if (Gate::denies('update', $paymentMethod)) {
            abort(403, 'Unauthorized action. You can only edit your own payment methods.');
        }

        return view('saas_seller.saas_payment_method.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasPaymentMethod $paymentMethod)
    {
        try {
            // Verify ownership or admin status
            if (Gate::denies('update', $paymentMethod)) {
                abort(403, 'Unauthorized action. You can only update your own payment methods.');
            }

            // Validate payment method data
            $this->validatePaymentMethod($request);

            // Populate payment method with validated data
            $this->populatePaymentMethodData($request, $paymentMethod);

            // Handle default status
            if ($request->has('is_default')) {
                $paymentMethod->setAsDefault();
            } elseif ($paymentMethod->is_default) {
                // If this was a default payment method and now unselected,
                // only allow if there are other payment methods
                $count = SaasPaymentMethod::where('user_id', Auth::id())->count();
                if ($count > 1) {
                    $paymentMethod->is_default = false;
                }
            }

            // Save the payment method
            $paymentMethod->save();

            // Redirect with success message
            return redirect()->route('seller.payment-methods.index')
                ->with('success', 'Payment method updated successfully.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error updating payment method: ' . $e->getMessage());

            // Redirect with error message
            return redirect()->back()->withInput()
                ->with('error', 'Error updating payment method: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasPaymentMethod $paymentMethod)
    {
        try {
            // Verify ownership or admin status
            if (Gate::denies('delete', $paymentMethod)) {
                abort(403, 'Unauthorized action. You can only delete your own payment methods.');
            }

            // Check if this is the default payment method
            if ($paymentMethod->is_default) {
                $count = SaasPaymentMethod::where('user_id', Auth::id())->count();
                if ($count > 1) {
                    return redirect()->route('seller.payment-methods.index')
                        ->with('error', 'Cannot delete the default payment method. Set another payment method as default first.');
                }
            }

            // Check if this payment method has associated withdrawals
            if ($paymentMethod->withdrawals()->exists()) {
                return redirect()->route('seller.payment-methods.index')
                    ->with('error', 'Cannot delete payment method because it has associated withdrawals.');
            }

            // Delete the payment method
            $paymentMethod->delete();

            // Redirect with success message
            return redirect()->route('seller.payment-methods.index')
                ->with('success', 'Payment method deleted successfully.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error deleting payment method: ' . $e->getMessage());

            // Redirect with error message
            return redirect()->route('seller.payment-methods.index')
                ->with('error', 'Error deleting payment method: ' . $e->getMessage());
        }
    }

    /**
     * Set a payment method as default.
     */
    public function setDefault(SaasPaymentMethod $paymentMethod)
    {
        try {
            // Verify ownership or admin status
            if (Gate::denies('update', $paymentMethod)) {
                abort(403, 'Unauthorized action. You can only update your own payment methods.');
            }

            // Set this payment method as default
            $paymentMethod->setAsDefault();

            // Redirect with success message
            return redirect()->route('seller.payment-methods.index')
                ->with('success', 'Default payment method updated.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error setting default payment method: ' . $e->getMessage());

            // Redirect with error message
            return redirect()->route('seller.payment-methods.index')
                ->with('error', 'Error setting default payment method: ' . $e->getMessage());
        }
    }

    /**
     * Validate payment method data based on type.
     */
    private function validatePaymentMethod(Request $request)
    {
        $rules = [
            'type' => 'required|in:bank_transfer,esewa,khalti,cash,other',
            'title' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ];

        switch ($request->type) {
            case 'bank_transfer':
                $rules['bank_name'] = 'required|string|max:255';
                $rules['bank_branch'] = 'required|string|max:255';
                $rules['account_number'] = 'required|string|max:50';
                break;

            case 'esewa':
            case 'khalti':
                $rules['mobile_number'] = 'required|string|regex:/^[0-9]{10}$/';
                break;
        }

        $messages = [
            'mobile_number.regex' => 'The mobile number must be a valid 10-digit number.',
            'bank_name.required' => 'Bank name is required for bank transfer payment methods.',
            'bank_branch.required' => 'Bank branch is required for bank transfer payment methods.',
            'account_number.required' => 'Account number is required for bank transfer payment methods.',
            'mobile_number.required' => 'Mobile number is required for mobile payment methods.',
        ];

        $request->validate($rules, $messages);
    }

    /**
     * Populate payment method data based on type.
     */
    private function populatePaymentMethodData(Request $request, SaasPaymentMethod $paymentMethod)
    {
        // Always set these fields
        $paymentMethod->type = $request->type;
        $paymentMethod->title = $request->title;
        $paymentMethod->account_name = $request->account_name;
        $paymentMethod->is_active = $request->boolean('is_active', true);
        $paymentMethod->notes = $request->notes;

        // Reset all type-specific fields first to avoid old data persisting
        $paymentMethod->bank_name = null;
        $paymentMethod->bank_branch = null;
        $paymentMethod->account_number = null;
        $paymentMethod->mobile_number = null;

        // Then set the type-specific fields
        switch ($request->type) {
            case 'bank_transfer':
                $paymentMethod->bank_name = $request->bank_name;
                $paymentMethod->bank_branch = $request->bank_branch;
                $paymentMethod->account_number = $request->account_number;
                break;

            case 'esewa':
            case 'khalti':
                $paymentMethod->mobile_number = $request->mobile_number;
                break;
        }
    }
}
