<?php

namespace App\Http\Controllers\SaasSeller;

use App\Http\Controllers\Controller;
use App\Models\SaasSellerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SaasSellerProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $sellerProfile = $user->sellerProfile;

        if (!$sellerProfile) {
            return redirect()->route('seller.profile.create');
        }

        return view('saas_seller.saas_seller_profile.saas_index', compact('user', 'sellerProfile'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->sellerProfile) {
            return redirect()->route('seller.profile.edit');
        }

        return view('saas_seller.saas_seller_profile.saas_create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->sellerProfile) {
            return redirect()->route('seller.profile.edit');
        }

        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_description' => 'nullable|string',
            'address' => 'nullable|string',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'store_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $sellerProfile = new SaasSellerProfile();
        $sellerProfile->user_id = $user->id;
        $sellerProfile->store_name = $request->store_name;
        $sellerProfile->store_description = $request->store_description;
        $sellerProfile->address = $request->address;
        $sellerProfile->is_approved = false; // Pending admin approval
        $sellerProfile->save();

        if ($request->hasFile('store_logo')) {
            $sellerProfile->saveStoreLogo($request->file('store_logo'));
        }

        if ($request->hasFile('store_banner')) {
            $sellerProfile->saveStoreBanner($request->file('store_banner'));
        }

        return redirect()->route('seller.profile.index')
            ->with('success', 'Seller profile created successfully. It is pending admin approval.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasSellerProfile $sellerProfile)
    {
        // Check if the seller profile belongs to the authenticated user
        if ($sellerProfile->user_id !== Auth::id()) {
            return redirect()->route('seller.profile.index')
                ->with('error', 'You are not authorized to view this profile.');
        }

        return view('saas_seller.saas_seller_profile.saas_show', compact('sellerProfile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $user = Auth::user();
        $sellerProfile = $user->sellerProfile;

        if (!$sellerProfile) {
            return redirect()->route('seller.profile.create');
        }

        return view('saas_seller.saas_seller_profile.saas_edit', compact('user', 'sellerProfile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $sellerProfile = $user->sellerProfile;

        if (!$sellerProfile) {
            return redirect()->route('seller.profile.create');
        }

        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_description' => 'nullable|string',
            'address' => 'nullable|string',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'store_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $sellerProfile->store_name = $request->store_name;
        $sellerProfile->store_description = $request->store_description;
        $sellerProfile->address = $request->address;
        // Approval status is maintained (only admin can change)
        $sellerProfile->save();

        if ($request->hasFile('store_logo')) {
            $sellerProfile->saveStoreLogo($request->file('store_logo'));
        }

        if ($request->hasFile('store_banner')) {
            $sellerProfile->saveStoreBanner($request->file('store_banner'));
        }

        return redirect()->route('seller.profile.index')
            ->with('success', 'Seller profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        // Sellers should not be able to delete their profile
        // If needed, they can contact admin to deactivate their account
        return redirect()->route('seller.profile.index')
            ->with('error', 'Profile deletion is not allowed. Please contact admin for account deactivation.');
    }
}
