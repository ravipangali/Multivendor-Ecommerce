<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasPaymentMethod;
use App\Models\User;
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
        $userId = request('user_id');
        $userRole = null;

        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                $userRole = $user->role;
                $paymentMethods = SaasPaymentMethod::where('user_id', $userId)
                    ->orderBy('is_default', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                return redirect()->route('admin.dashboard')->with('error', 'User not found');
            }
        } else {
            $paymentMethods = SaasPaymentMethod::where('user_id', Auth::id())
                ->orderBy('is_default', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('saas_admin.saas_payment_method.index', compact('paymentMethods', 'userId', 'userRole'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userId = request('user_id');
        $userRole = null;

        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                $userRole = $user->role;
            } else {
                return redirect()->route('admin.dashboard')->with('error', 'User not found');
            }
        }

        return view('saas_admin.saas_payment_method.create', compact('userId', 'userRole'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $this->validatePaymentMethod($request);

            // Create a new payment method
            $paymentMethod = new SaasPaymentMethod();
            $paymentMethod->user_id = $request->has('user_id') ? $request->user_id : Auth::id();

            // Populate the payment method with validated data
            $this->populatePaymentMethodData($request, $paymentMethod);

            // If this is the first payment method or is_default is checked, set as default
            if ($request->has('is_default') || SaasPaymentMethod::where('user_id', $paymentMethod->user_id)->count() === 0) {
                // Set all other payment methods to non-default
                SaasPaymentMethod::where('user_id', $paymentMethod->user_id)
                    ->update(['is_default' => false]);

                $paymentMethod->is_default = true;
            }

            // Save the payment method
            $paymentMethod->save();

            // Show success message
            toast('Payment method created successfully.', 'success');

            // If we're managing another user's payment methods, redirect to their details page
            if ($request->has('user_id') && $request->has('user_role')) {
                $userId = $request->user_id;
                $userRole = $request->user_role;

                if ($userRole === 'customer') {
                    return redirect()->route('admin.customers.show', $userId);
                } elseif ($userRole === 'seller') {
                    return redirect()->route('admin.sellers.show', $userId);
                }
            }

            return redirect()->route('admin.payment-methods.index');
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error creating payment method: ' . $e->getMessage());

            // Show error message
            toast('Error creating payment method: ' . $e->getMessage(), 'error');

            // Redirect back with input data
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasPaymentMethod $paymentMethod)
    {
        if (Gate::denies('view', $paymentMethod)) {
            abort(403, 'Unauthorized action.');
        }

        return view('saas_admin.saas_payment_method.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasPaymentMethod $paymentMethod)
    {
        if (Gate::denies('update', $paymentMethod)) {
            abort(403, 'Unauthorized action.');
        }

        return view('saas_admin.saas_payment_method.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasPaymentMethod $paymentMethod)
    {
        try {
            // Check authorization
            if (Gate::denies('update', $paymentMethod)) {
                abort(403, 'Unauthorized action.');
            }

            // Validate the request data
            $this->validatePaymentMethod($request);

            // Populate the payment method with validated data
            $this->populatePaymentMethodData($request, $paymentMethod);

            // Handle default status
            if ($request->has('is_default')) {
                $paymentMethod->setAsDefault();
            } elseif ($paymentMethod->is_default) {
                // If this was a default payment method and now unselected,
                // only allow if there are other payment methods
                $count = SaasPaymentMethod::where('user_id', $paymentMethod->user_id)->count();
                if ($count > 1) {
                    $paymentMethod->is_default = false;
                }
            }

            // Save the payment method
            $paymentMethod->save();

            // Show success message
            toast('Payment method updated successfully.', 'success');

            // If we're managing another user's payment methods, redirect to their details page
            if ($request->has('user_id') && $request->has('user_role')) {
                $userId = $request->user_id;
                $userRole = $request->user_role;

                if ($userRole === 'customer') {
                    return redirect()->route('admin.customers.show', $userId);
                } elseif ($userRole === 'seller') {
                    return redirect()->route('admin.sellers.show', $userId);
                }
            }

            return redirect()->route('admin.payment-methods.index');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error updating payment method: ' . $e->getMessage());

            // Show error message
            toast('Error updating payment method: ' . $e->getMessage(), 'error');

            // Redirect back with input data
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasPaymentMethod $paymentMethod)
    {
        if (Gate::denies('delete', $paymentMethod)) {
            abort(403, 'Unauthorized action.');
        }

        // Check if this is the default payment method and there are others
        if ($paymentMethod->is_default) {
            $count = SaasPaymentMethod::where('user_id', $paymentMethod->user_id)->count();
            if ($count > 1) {
                toast('Cannot delete the default payment method. Set another payment method as default first.', 'error');
                return redirect()->route('admin.payment-methods.index');
            }
        }

        // Check if this payment method has associated withdrawals
        if ($paymentMethod->withdrawals()->exists()) {
            toast('Cannot delete payment method because it has associated withdrawals.', 'error');
            return redirect()->route('admin.payment-methods.index');
        }

        $userId = $paymentMethod->user_id;
        $user = User::find($userId);
        $userRole = $user ? $user->role : null;

        $paymentMethod->delete();

        toast('Payment method deleted successfully.', 'success');

        // If we're managing another user's payment methods, redirect to their details page
        if ($userId !== Auth::id() && $userRole) {
            if ($userRole === 'customer') {
                return redirect()->route('admin.customers.show', $userId);
            } elseif ($userRole === 'seller') {
                return redirect()->route('admin.sellers.show', $userId);
            }
        }

        return redirect()->route('admin.payment-methods.index');
    }

    /**
     * Set a payment method as default.
     */
    public function setDefault(SaasPaymentMethod $paymentMethod)
    {
        if (Gate::denies('update', $paymentMethod)) {
            abort(403, 'Unauthorized action.');
        }

        $paymentMethod->setAsDefault();

        $userId = $paymentMethod->user_id;
        $user = User::find($userId);
        $userRole = $user ? $user->role : null;

        toast('Default payment method updated.', 'success');

        // If we're managing another user's payment methods, redirect to their details page
        if ($userId !== Auth::id() && $userRole) {
            if ($userRole === 'customer') {
                return redirect()->route('admin.customers.show', $userId);
            } elseif ($userRole === 'seller') {
                return redirect()->route('admin.sellers.show', $userId);
            }
        }

        return redirect()->route('admin.payment-methods.index');
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

        // Add specific validation rules based on payment method type
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
