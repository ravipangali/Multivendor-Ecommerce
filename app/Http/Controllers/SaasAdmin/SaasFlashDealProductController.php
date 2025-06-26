<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasFlashDealProduct;
use App\Models\SaasFlashDeal;
use App\Models\SaasProduct;
use Illuminate\Http\Request;

class SaasFlashDealProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $flashDealId = $request->flash_deal_id;
        $flashDeal = null;

        if ($flashDealId) {
            $flashDeal = SaasFlashDeal::findOrFail($flashDealId);
            $flashDealProducts = SaasFlashDealProduct::with(['flashDeal', 'product'])
                ->where('flash_deal_id', $flashDealId)
                ->latest()
                ->paginate(10);
        } else {
            $flashDealProducts = SaasFlashDealProduct::with(['flashDeal', 'product'])
                ->latest()
                ->paginate(10);
        }

        return view('saas_admin.saas_flash_deal_product.saas_index', compact('flashDealProducts', 'flashDeal'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $flashDealId = $request->flash_deal_id;

        if ($flashDealId) {
            $flashDeal = SaasFlashDeal::findOrFail($flashDealId);
        } else {
            $flashDeal = null;
            $flashDeals = SaasFlashDeal::all();
        }

        $products = SaasProduct::where('is_active', true)
            ->where('seller_publish_status', SaasProduct::SELLER_PUBLISH_STATUS_APPROVED)
            ->get();
        $discountTypes = ['flat', 'percentage'];

        return view('saas_admin.saas_flash_deal_product.saas_create', compact(
            'flashDeal',
            'products',
            'discountTypes',
            isset($flashDeals) ? ['flashDeals' => $flashDeals] : []
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'flash_deal_id' => 'required|exists:saas_flash_deals,id',
            'product_id' => 'required|exists:saas_products,id',
            'discount_type' => 'required|in:flat,percentage',
            'discount_value' => 'required|numeric|min:0',
        ]);

        // Check if product is already in the flash deal
        $exists = SaasFlashDealProduct::where('flash_deal_id', $request->flash_deal_id)
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            toast('This product is already in the flash deal', 'error');
            return back();
        }

        $flashDealProduct = new SaasFlashDealProduct();
        $flashDealProduct->flash_deal_id = $request->flash_deal_id;
        $flashDealProduct->product_id = $request->product_id;
        $flashDealProduct->discount_type = $request->discount_type;
        $flashDealProduct->discount_value = $request->discount_value;
        $flashDealProduct->save();

        toast('Product added to flash deal successfully', 'success');
        return redirect()->route('admin.flash-deal-products.index', ['flash_deal_id' => $request->flash_deal_id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasFlashDealProduct $flashDealProduct)
    {
        $flashDealProduct->load(['flashDeal', 'product']);
        return view('saas_admin.saas_flash_deal_product.saas_show', compact('flashDealProduct'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasFlashDealProduct $flashDealProduct)
    {
        $flashDealProduct->load(['flashDeal', 'product']);
        $discountTypes = ['flat', 'percentage'];

        return view('saas_admin.saas_flash_deal_product.saas_edit', compact(
            'flashDealProduct',
            'discountTypes'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasFlashDealProduct $flashDealProduct)
    {
        $request->validate([
            'discount_type' => 'required|in:flat,percentage',
            'discount_value' => 'required|numeric|min:0',
        ]);

        $flashDealProduct->discount_type = $request->discount_type;
        $flashDealProduct->discount_value = $request->discount_value;
        $flashDealProduct->save();

        toast('Flash deal product updated successfully', 'success');
        return redirect()->route('admin.flash-deal-products.index', ['flash_deal_id' => $flashDealProduct->flash_deal_id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasFlashDealProduct $flashDealProduct)
    {
        $flashDealId = $flashDealProduct->flash_deal_id;
        $flashDealProduct->delete();

        toast('Product removed from flash deal successfully', 'success');
        return redirect()->route('admin.flash-deal-products.index', ['flash_deal_id' => $flashDealId]);
    }
}
