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
     * Display the seller's profile.
     */
    public function index()
    {
        $user = Auth::user();
        $sellerProfile = $user->sellerProfile;

        if (!$sellerProfile) {
            // Create a new profile if it doesn't exist
            $sellerProfile = new SaasSellerProfile();
            $sellerProfile->user_id = $user->id;
            $sellerProfile->store_name = $user->name . "'s Store";
            $sellerProfile->is_approved = false;
            $sellerProfile->save();
        }

        return view('saas_seller.saas_seller_profile.saas_index', compact('sellerProfile', 'user'));
    }

    /**
     * Show the form for editing the seller's profile.
     */
    public function edit()
    {
        $user = Auth::user();
        $sellerProfile = $user->sellerProfile;

        if (!$sellerProfile) {
            // Create a new profile if it doesn't exist
            $sellerProfile = new SaasSellerProfile();
            $sellerProfile->user_id = $user->id;
            $sellerProfile->store_name = $user->name . "'s Store";
            $sellerProfile->is_approved = false;
            $sellerProfile->save();
        }

        return view('saas_seller.saas_seller_profile.saas_edit', compact('sellerProfile', 'user'));
    }

    /**
     * Update the seller's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $sellerProfile = $user->sellerProfile;

        if (!$sellerProfile) {
            $sellerProfile = new SaasSellerProfile();
            $sellerProfile->user_id = $user->id;
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
        $sellerProfile->save();

        if ($request->hasFile('store_logo')) {
            $sellerProfile->saveStoreLogo($request->file('store_logo'));
        }

        if ($request->hasFile('store_banner')) {
            $sellerProfile->saveStoreBanner($request->file('store_banner'));
        }

        toast('Profile updated successfully', 'success');
        return redirect()->route('seller.profile');
    }
}
