<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SaasSellerProfile;
use App\Models\SaasSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class SaasSellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'seller')->with('sellerProfile');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('sellerProfile', function($subQ) use ($search) {
                      $subQ->where('store_name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        // Approval filter
        if ($request->has('approval') && $request->approval !== '') {
            $query->whereHas('sellerProfile', function($q) use ($request) {
                $q->where('is_approved', $request->approval);
            });
        }

        $sellers = $query->latest()->paginate(15);
        return view('saas_admin.saas_seller.saas_index', compact('sellers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $settings = SaasSetting::first();
        $defaultCommission = $settings ? $settings->seller_commission : 0;

        return view('saas_admin.saas_seller.saas_create', compact('defaultCommission'));
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
            'commission' => 'nullable|numeric|min:0|max:100',
            'store_name' => 'required|string|max:255',
            'store_description' => 'nullable|string',
            'address' => 'nullable|string',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'store_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_approved' => 'required|boolean',
        ]);

        $seller = new User();
        $seller->name = $request->name;
        $seller->email = $request->email;
        $seller->password = Hash::make($request->password);
        $seller->role = 'seller';
        $seller->phone = $request->phone;
        $seller->is_active = $request->is_active;

        // Set commission from request or default from settings
        if ($request->filled('commission')) {
            $seller->commission = $request->commission;
        } else {
            $settings = SaasSetting::first();
            $seller->commission = $settings ? $settings->seller_commission : 0;
        }

        $seller->save();

        if ($request->hasFile('profile_photo')) {
            $profilePhoto = $request->file('profile_photo');
            $filename = 'profile_photos/' . uniqid() . '.' . $profilePhoto->getClientOriginalExtension();
            $profilePhoto->storeAs( $filename);
            $seller->profile_photo = $filename;
            $seller->save();
        }

        // Create seller profile
        $sellerProfile = new SaasSellerProfile();
        $sellerProfile->user_id = $seller->id;
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

        toast('Seller created successfully', 'success');
        return redirect()->route('admin.sellers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $seller)
    {
        // Ensure this is a seller
        if ($seller->role !== 'seller') {
            abort(404);
        }

        $seller->load('sellerProfile', 'products', 'sellerOrders.items.product');

        // Get seller statistics
        $totalProducts = $seller->products()->count();
        $activeProducts = $seller->products()->where('is_active', true)->count();
        $totalOrders = $seller->sellerOrders()->count();
        $totalRevenue = $seller->sellerOrders()->where('order_status', '!=', 'cancelled')->sum('total');
        $pendingOrders = $seller->sellerOrders()->where('order_status', 'pending')->count();
        $completedOrders = $seller->sellerOrders()->where('order_status', 'delivered')->count();

        return view('saas_admin.saas_seller.saas_show', compact(
            'seller', 'totalProducts', 'activeProducts', 'totalOrders',
            'totalRevenue', 'pendingOrders', 'completedOrders'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $seller)
    {
        // Ensure this is a seller
        if ($seller->role !== 'seller') {
            abort(404);
        }

        $seller->load('sellerProfile');
        $settings = SaasSetting::first();
        $defaultCommission = $settings ? $settings->seller_commission : 0;

        return view('saas_admin.saas_seller.saas_edit', compact('seller', 'defaultCommission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $seller)
    {
        // Ensure this is a seller
        if ($seller->role !== 'seller') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $seller->id,
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|boolean',
            'commission' => 'nullable|numeric|min:0|max:100',
            'store_name' => 'required|string|max:255',
            'store_description' => 'nullable|string',
            'address' => 'nullable|string',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'store_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_approved' => 'required|boolean',
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            $seller->password = Hash::make($request->password);
        }

        $seller->name = $request->name;
        $seller->email = $request->email;
        $seller->phone = $request->phone;
        $seller->is_active = $request->is_active;
        $seller->commission = $request->commission;

        if ($request->hasFile('profile_photo')) {
            // Delete old profile photo if exists
            if ($seller->profile_photo) {
                Storage::disk('public')->delete($seller->profile_photo);
            }

            $profilePhoto = $request->file('profile_photo');
            $filename = 'profile_photos/' . uniqid() . '.' . $profilePhoto->getClientOriginalExtension();
            $profilePhoto->storeAs($filename);
            $seller->profile_photo = $filename;
        }

        $seller->save();

        // Update or create seller profile
        $sellerProfile = $seller->sellerProfile;
        if (!$sellerProfile) {
            $sellerProfile = new SaasSellerProfile();
            $sellerProfile->user_id = $seller->id;
        }

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

        toast('Seller updated successfully', 'success');
        return redirect()->route('admin.sellers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $seller)
    {
        // Ensure this is a seller
        if ($seller->role !== 'seller') {
            abort(404);
        }

        // Check if seller has products or orders before deleting
        if ($seller->products()->count() > 0 || $seller->sellerOrders()->count() > 0) {
            toast('Cannot delete seller because they have products or orders', 'error');
            return redirect()->route('admin.sellers.index');
        }

        // Delete profile photo if exists
        if ($seller->profile_photo) {
            Storage::disk('public')->delete($seller->profile_photo);
        }

        // Delete seller profile if exists
        if ($seller->sellerProfile) {
            // Delete store images
            if ($seller->sellerProfile->store_logo) {
                Storage::disk('public')->delete($seller->sellerProfile->store_logo);
            }
            if ($seller->sellerProfile->store_banner) {
                Storage::disk('public')->delete($seller->sellerProfile->store_banner);
            }
            $seller->sellerProfile->delete();
        }

        $seller->delete();

        toast('Seller deleted successfully', 'success');
        return redirect()->route('admin.sellers.index');
    }

    /**
     * Toggle seller approval status.
     */
    public function toggleApproval(User $seller)
    {
        // Ensure this is a seller
        if ($seller->role !== 'seller') {
            abort(404);
        }

        $sellerProfile = $seller->sellerProfile;
        if ($sellerProfile) {
            $oldStatus = $sellerProfile->is_approved;
            $sellerProfile->is_approved = !$sellerProfile->is_approved;
            $sellerProfile->save();

            $status = $sellerProfile->is_approved ? 'approved' : 'denied';

            // Send email notification to seller
            try {
                $seller->load('sellerProfile');
                if ($seller->email) {
                    Mail::to($seller->email)->send(
                        new \App\Mail\SaasSellerApprovalNotification($seller, $status)
                    );

                    Log::info('Seller approval status email sent successfully', [
                        'seller_id' => $seller->id,
                        'seller_name' => $seller->name,
                        'seller_email' => $seller->email,
                        'status' => $status,
                        'previous_status' => $oldStatus ? 'approved' : 'denied'
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to send seller approval email', [
                    'seller_id' => $seller->id,
                    'seller_email' => $seller->email,
                    'status' => $status,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            toast("Seller has been {$status} successfully", 'success');
        } else {
            toast('Seller profile not found', 'error');
        }

        return redirect()->route('admin.sellers.index');
    }
}
