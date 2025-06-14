<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasCoupon;
use App\Models\User;
use Illuminate\Http\Request;

class SaasCouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = SaasCoupon::with('seller')->latest()->paginate(10);
        return view('saas_admin.saas_coupon.saas_index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sellers = User::where('role', 'seller')->get();
        $discountTypes = ['flat', 'percentage'];
        return view('saas_admin.saas_coupon.saas_create', compact('sellers', 'discountTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:saas_coupons',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:flat,percentage',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'seller_id' => 'nullable|exists:users,id',
        ]);

        $coupon = new SaasCoupon();
        $coupon->code = $request->code;
        $coupon->description = $request->description;
        $coupon->discount_type = $request->discount_type;
        $coupon->discount_value = $request->discount_value;
        $coupon->start_date = $request->start_date;
        $coupon->end_date = $request->end_date;
        $coupon->usage_limit = $request->usage_limit;
        $coupon->seller_id = $request->seller_id;
        $coupon->save();

        toast('Coupon created successfully', 'success');
        return redirect()->route('admin.coupons.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasCoupon $coupon)
    {
        $coupon->load('seller');

        // Get recent orders that used this coupon
        $recentOrders = \App\Models\SaasOrder::where('coupon_code', $coupon->code)
            ->with(['customer', 'seller'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get usage statistics
        $couponService = app(\App\Services\SaasCouponService::class);
        $usageStats = $couponService->getCouponUsageStats($coupon);

        return view('saas_admin.saas_coupon.saas_show', compact('coupon', 'recentOrders', 'usageStats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasCoupon $coupon)
    {
        $sellers = User::where('role', 'seller')->get();
        $discountTypes = ['flat', 'percentage'];
        return view('saas_admin.saas_coupon.saas_edit', compact('coupon', 'sellers', 'discountTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasCoupon $coupon)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:saas_coupons,code,' . $coupon->id,
            'description' => 'nullable|string',
            'discount_type' => 'required|in:flat,percentage',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'seller_id' => 'nullable|exists:users,id',
        ]);

        $coupon->code = $request->code;
        $coupon->description = $request->description;
        $coupon->discount_type = $request->discount_type;
        $coupon->discount_value = $request->discount_value;
        $coupon->start_date = $request->start_date;
        $coupon->end_date = $request->end_date;
        $coupon->usage_limit = $request->usage_limit;
        $coupon->seller_id = $request->seller_id;
        $coupon->save();

        toast('Coupon updated successfully', 'success');
        return redirect()->route('admin.coupons.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasCoupon $coupon)
    {
        // You might want to check if coupon is used in any orders before deleting
        $coupon->delete();

        toast('Coupon deleted successfully', 'success');
        return redirect()->route('admin.coupons.index');
    }
}
