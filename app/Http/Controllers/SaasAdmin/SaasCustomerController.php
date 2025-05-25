<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SaasCustomerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class SaasCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->with('customerProfile');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $customers = $query->latest()->paginate(15);
        return view('saas_admin.saas_customer.saas_index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('saas_admin.saas_customer.saas_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|boolean',
            'shipping_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
        ]);

        $customer = new User();
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->password = Hash::make($request->password);
        $customer->role = 'customer';
        $customer->phone = $request->phone;
        $customer->is_active = $request->is_active;
        $customer->save();

        if ($request->hasFile('profile_photo')) {
            $profilePhoto = $request->file('profile_photo');
            $filename = 'profile_photos/' . uniqid() . '.' . $profilePhoto->getClientOriginalExtension();
            $profilePhoto->storeAs( $filename);
            $customer->profile_photo = $filename;
            $customer->save();
        }

        // Create customer profile if addresses are provided
        if ($request->shipping_address || $request->billing_address) {
            $customerProfile = new SaasCustomerProfile();
            $customerProfile->user_id = $customer->id;
            $customerProfile->shipping_address = $request->shipping_address;
            $customerProfile->billing_address = $request->billing_address;
            $customerProfile->save();
        }

        toast('Customer created successfully', 'success');
        return redirect()->route('admin.customers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $customer)
    {
        // Ensure this is a customer
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $customer->load('customerProfile', 'customerOrders.items.product');

        // Get order statistics
        $totalOrders = $customer->customerOrders()->count();
        $totalSpent = $customer->customerOrders()->where('order_status', '!=', 'cancelled')->sum('total');
        $pendingOrders = $customer->customerOrders()->where('order_status', 'pending')->count();
        $completedOrders = $customer->customerOrders()->where('order_status', 'delivered')->count();

        return view('saas_admin.saas_customer.saas_show', compact(
            'customer', 'totalOrders', 'totalSpent', 'pendingOrders', 'completedOrders'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $customer)
    {
        // Ensure this is a customer
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $customer->load('customerProfile');
        return view('saas_admin.saas_customer.saas_edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $customer)
    {
        // Ensure this is a customer
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|boolean',
            'shipping_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            $customer->password = Hash::make($request->password);
        }

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->is_active = $request->is_active;

        if ($request->hasFile('profile_photo')) {
            // Delete old profile photo if exists
            if ($customer->profile_photo) {
                Storage::disk('public')->delete($customer->profile_photo);
            }

            $profilePhoto = $request->file('profile_photo');
            $filename = 'profile_photos/' . uniqid() . '.' . $profilePhoto->getClientOriginalExtension();
            $profilePhoto->storeAs( $filename);
            $customer->profile_photo = $filename;
        }

        $customer->save();

        // Update or create customer profile
        $customerProfile = $customer->customerProfile;
        if (!$customerProfile) {
            $customerProfile = new SaasCustomerProfile();
            $customerProfile->user_id = $customer->id;
        }

        $customerProfile->shipping_address = $request->shipping_address;
        $customerProfile->billing_address = $request->billing_address;
        $customerProfile->save();

        toast('Customer updated successfully', 'success');
        return redirect()->route('admin.customers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $customer)
    {
        // Ensure this is a customer
        if ($customer->role !== 'customer') {
            abort(404);
        }

        // Check if customer has orders before deleting
        if ($customer->customerOrders()->count() > 0) {
            toast('Cannot delete customer because they have orders', 'error');
            return redirect()->route('admin.customers.index');
        }

        // Delete profile photo if exists
        if ($customer->profile_photo) {
            Storage::disk('public')->delete($customer->profile_photo);
        }

        // Delete customer profile if exists
        if ($customer->customerProfile) {
            $customer->customerProfile->delete();
        }

        $customer->delete();

        toast('Customer deleted successfully', 'success');
        return redirect()->route('admin.customers.index');
    }
}