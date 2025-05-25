<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasCustomerProfile;
use App\Models\User;
use Illuminate\Http\Request;

class SaasCustomerProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customerProfiles = SaasCustomerProfile::with('user')->latest()->paginate(10);
        return view('saas_admin.saas_customer_profile.saas_index', compact('customerProfiles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = User::where('role', 'customer')
            ->whereDoesntHave('customerProfile')
            ->get();

        if ($customers->isEmpty()) {
            toast('All customers already have profiles', 'info');
            return redirect()->route('admin.customer-profiles.index');
        }

        return view('saas_admin.saas_customer_profile.saas_create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:saas_customer_profiles',
            'shipping_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
        ]);

        $customerProfile = new SaasCustomerProfile();
        $customerProfile->user_id = $request->user_id;
        $customerProfile->shipping_address = $request->shipping_address;
        $customerProfile->billing_address = $request->billing_address;
        $customerProfile->save();

        toast('Customer profile created successfully', 'success');
        return redirect()->route('admin.customer-profiles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasCustomerProfile $customerProfile)
    {
        $customerProfile->load('user', 'user.orders');
        return view('saas_admin.saas_customer_profile.saas_show', compact('customerProfile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasCustomerProfile $customerProfile)
    {
        return view('saas_admin.saas_customer_profile.saas_edit', compact('customerProfile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasCustomerProfile $customerProfile)
    {
        $request->validate([
            'shipping_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
        ]);

        $customerProfile->shipping_address = $request->shipping_address;
        $customerProfile->billing_address = $request->billing_address;
        $customerProfile->save();

        toast('Customer profile updated successfully', 'success');
        return redirect()->route('admin.customer-profiles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasCustomerProfile $customerProfile)
    {
        // Check if customer has orders or other related data before deleting
        if ($customerProfile->user->customerOrders()->count() > 0) {
            toast('Cannot delete customer profile because customer has orders', 'error');
            return redirect()->route('admin.customer-profiles.index');
        }

        $customerProfile->delete();

        toast('Customer profile deleted successfully', 'success');
        return redirect()->route('admin.customer-profiles.index');
    }
}
