<?php

namespace App\Http\Controllers\SaasSeller;

use App\Http\Controllers\Controller;
use App\Models\SaasCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaasCouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sellerId = Auth::id();
        $coupons = SaasCoupon::where('seller_id', $sellerId)->latest()->paginate(10);
        return view('saas_seller.saas_coupon.saas_index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $discountTypes = ['flat', 'percentage'];
        return view('saas_seller.saas_coupon.saas_create', compact('discountTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $sellerId = Auth::id();

        $request->validate([
            'code' => 'required|string|max:255|unique:saas_coupons',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:flat,percentage',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'nullable|integer|min:1',
        ]);

        $coupon = new SaasCoupon();
        $coupon->code = $request->code;
        $coupon->description = $request->description;
        $coupon->discount_type = $request->discount_type;
        $coupon->discount_value = $request->discount_value;
        $coupon->start_date = $request->start_date;
        $coupon->end_date = $request->end_date;
        $coupon->usage_limit = $request->usage_limit;
        $coupon->seller_id = $sellerId;
        $coupon->save();

        return redirect()->route('seller.coupons.index')->with('success', 'Coupon created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasCoupon $coupon)
    {
        // Check if the coupon belongs to the authenticated seller
        if ($coupon->seller_id !== Auth::id()) {
            return redirect()->route('seller.coupons.index')->with('error', 'You are not authorized to view this coupon.');
        }

        return view('saas_seller.saas_coupon.saas_show', compact('coupon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasCoupon $coupon)
    {
        // Check if the coupon belongs to the authenticated seller
        if ($coupon->seller_id !== Auth::id()) {
            return redirect()->route('seller.coupons.index')->with('error', 'You are not authorized to edit this coupon.');
        }

        $discountTypes = ['flat', 'percentage'];
        return view('saas_seller.saas_coupon.saas_edit', compact('coupon', 'discountTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasCoupon $coupon)
    {
        // Check if the coupon belongs to the authenticated seller
        if ($coupon->seller_id !== Auth::id()) {
            return redirect()->route('seller.coupons.index')->with('error', 'You are not authorized to update this coupon.');
        }

        $request->validate([
            'code' => 'required|string|max:255|unique:saas_coupons,code,' . $coupon->id,
            'description' => 'nullable|string',
            'discount_type' => 'required|in:flat,percentage',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'nullable|integer|min:1',
        ]);

        $coupon->code = $request->code;
        $coupon->description = $request->description;
        $coupon->discount_type = $request->discount_type;
        $coupon->discount_value = $request->discount_value;
        $coupon->start_date = $request->start_date;
        $coupon->end_date = $request->end_date;
        $coupon->usage_limit = $request->usage_limit;
        $coupon->save();

        return redirect()->route('seller.coupons.index')->with('success', 'Coupon updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasCoupon $coupon)
    {
        // Check if the coupon belongs to the authenticated seller
        if ($coupon->seller_id !== Auth::id()) {
            return redirect()->route('seller.coupons.index')->with('error', 'You are not authorized to delete this coupon.');
        }

        // Check if coupon has been used
        if ($coupon->usage_count > 0) {
            return redirect()->route('seller.coupons.index')->with('error', 'Cannot delete coupon because it has been used by customers');
        }

        $coupon->delete();

        return redirect()->route('seller.coupons.index')->with('success', 'Coupon deleted successfully');
    }
}
