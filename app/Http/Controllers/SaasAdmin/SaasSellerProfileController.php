<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasSellerProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SaasSellerProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sellerProfiles = SaasSellerProfile::with('user')->latest()->paginate(10);
        return view('saas_admin.saas_seller_profile.saas_index', compact('sellerProfiles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sellers = User::where('role', 'seller')
            ->whereDoesntHave('sellerProfile')
            ->get();

        if ($sellers->isEmpty()) {
            toast('All sellers already have profiles', 'info');
            return redirect()->route('admin.sellers.index');
        }

        return view('saas_admin.saas_seller_profile.saas_create', compact('sellers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:saas_seller_profiles',
            'store_name' => 'required|string|max:255',
            'store_description' => 'nullable|string',
            'address' => 'nullable|string',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'store_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_approved' => 'required|boolean',
        ]);

        $sellerProfile = new SaasSellerProfile();
        $sellerProfile->user_id = $request->user_id;
        $sellerProfile->store_name = $request->store_name;
        $sellerProfile->store_description = $request->store_description;
        $sellerProfile->address = $request->address;
        $sellerProfile->is_approved = $request->is_approved;
        $sellerProfile->save();

        if ($request->hasFile('store_logo')) {
            $sellerProfile->saveStoreLogo($request->file('store_logo'));
        }

        if ($request->hasFile('store_banner')) {
            $sellerProfile->saveStoreBanner($request->file('store_banner'));
        }

        toast('Seller profile created successfully', 'success');
        return redirect()->route('admin.sellers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasSellerProfile $sellerProfile)
    {
        $sellerProfile->load('user', 'user.products');
        return view('saas_admin.saas_seller_profile.saas_show', compact('sellerProfile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasSellerProfile $sellerProfile)
    {
        return view('saas_admin.saas_seller_profile.saas_edit', compact('sellerProfile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasSellerProfile $sellerProfile)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_description' => 'nullable|string',
            'address' => 'nullable|string',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'store_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_approved' => 'required|boolean',
        ]);

        $sellerProfile->store_name = $request->store_name;
        $sellerProfile->store_description = $request->store_description;
        $sellerProfile->address = $request->address;
        $sellerProfile->is_approved = $request->is_approved;
        $sellerProfile->save();

        if ($request->hasFile('store_logo')) {
            $sellerProfile->saveStoreLogo($request->file('store_logo'));
        }

        if ($request->hasFile('store_banner')) {
            $sellerProfile->saveStoreBanner($request->file('store_banner'));
        }

        toast('Seller profile updated successfully', 'success');
        return redirect()->route('admin.sellers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasSellerProfile $sellerProfile)
    {
        // Check if seller has products or orders before deleting
        if ($sellerProfile->user->products()->count() > 0 || $sellerProfile->user->sellerOrders()->count() > 0) {
            toast('Cannot delete seller profile because seller has products or orders', 'error');
            return redirect()->route('admin.sellers.index');
        }

        // Delete store logo and banner if they exist
        if ($sellerProfile->store_logo) {
            Storage::disk('public')->delete($sellerProfile->store_logo);
        }

        if ($sellerProfile->store_banner) {
            Storage::disk('public')->delete($sellerProfile->store_banner);
        }

        $sellerProfile->delete();

        toast('Seller profile deleted successfully', 'success');
        return redirect()->route('admin.sellers.index');
    }
}
