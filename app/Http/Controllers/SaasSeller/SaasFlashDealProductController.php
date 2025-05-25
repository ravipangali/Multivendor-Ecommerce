<?php

namespace App\Http\Controllers\SaasSeller;

use App\Http\Controllers\Controller;
use App\Models\SaasFlashDeal;
use App\Models\SaasFlashDealProduct;
use App\Models\SaasProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaasFlashDealProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SaasFlashDeal $flashDeal)
    {
        // Check if the flash deal belongs to the authenticated seller
        if ($flashDeal->seller_id !== Auth::id()) {
            return redirect()->route('seller.flash-deals.index')
                ->with('error', 'You are not authorized to view products for this flash deal.');
        }

        $flashDeal->load('products.product');
        return view('saas_seller.saas_flash_deal_product.saas_index', compact('flashDeal'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(SaasFlashDeal $flashDeal)
    {
        // Check if the flash deal belongs to the authenticated seller
        if ($flashDeal->seller_id !== Auth::id()) {
            return redirect()->route('seller.flash-deals.index')
                ->with('error', 'You are not authorized to add products to this flash deal.');
        }

        // Get products that belong to this seller and are not already in this flash deal
        $sellerId = Auth::id();
        $existingProductIds = $flashDeal->products()->pluck('product_id')->toArray();
        $products = SaasProduct::where('seller_id', $sellerId)
            ->where('is_active', true)
            ->whereNotIn('id', $existingProductIds)
            ->get();

        return view('saas_seller.saas_flash_deal_product.saas_create', compact('flashDeal', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, SaasFlashDeal $flashDeal)
    {
        // Check if the flash deal belongs to the authenticated seller
        if ($flashDeal->seller_id !== Auth::id()) {
            return redirect()->route('seller.flash-deals.index')
                ->with('error', 'You are not authorized to add products to this flash deal.');
        }

        $sellerId = Auth::id();

        $request->validate([
            'product_id' => 'required|exists:saas_products,id',
            'discount_type' => 'required|in:flat,percentage',
            'discount_value' => 'required|numeric|min:0',
        ]);

        // Verify the product belongs to this seller
        $product = SaasProduct::where('id', $request->product_id)
            ->where('seller_id', $sellerId)
            ->first();

        if (!$product) {
            return redirect()->back()
                ->with('error', 'You can only add your own products to flash deals.')
                ->withInput();
        }

        // Additional validation for percentage discount
        if ($request->discount_type === 'percentage' && $request->discount_value > 100) {
            return redirect()->back()
                ->with('error', 'Percentage discount cannot exceed 100%.')
                ->withInput();
        }

        // Additional validation for flat discount
        if ($request->discount_type === 'flat' && $request->discount_value >= $product->price) {
            return redirect()->back()
                ->with('error', 'Flat discount cannot be equal to or greater than the product price.')
                ->withInput();
        }

        // Create flash deal product
        $flashDealProduct = new SaasFlashDealProduct();
        $flashDealProduct->flash_deal_id = $flashDeal->id;
        $flashDealProduct->product_id = $request->product_id;
        $flashDealProduct->discount_type = $request->discount_type;
        $flashDealProduct->discount_value = $request->discount_value;
        $flashDealProduct->save();

        return redirect()->route('seller.flash-deals.products.index', $flashDeal->id)
            ->with('success', 'Product added to flash deal successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasFlashDeal $flashDeal, SaasFlashDealProduct $product)
    {
        // Check if the flash deal belongs to the authenticated seller
        if ($flashDeal->seller_id !== Auth::id()) {
            return redirect()->route('seller.flash-deals.index')
                ->with('error', 'You are not authorized to view this flash deal product.');
        }

        // Check if the product belongs to this flash deal
        if ($product->flash_deal_id !== $flashDeal->id) {
            return redirect()->route('seller.flash-deals.products.index', $flashDeal->id)
                ->with('error', 'This product does not belong to the specified flash deal.');
        }

        $product->load('product');
        return view('saas_seller.saas_flash_deal_product.saas_show', compact('flashDeal', 'product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasFlashDeal $flashDeal, SaasFlashDealProduct $product)
    {
        // Check if the flash deal belongs to the authenticated seller
        if ($flashDeal->seller_id !== Auth::id()) {
            return redirect()->route('seller.flash-deals.index')
                ->with('error', 'You are not authorized to edit this flash deal product.');
        }

        // Check if the product belongs to this flash deal
        if ($product->flash_deal_id !== $flashDeal->id) {
            return redirect()->route('seller.flash-deals.products.index', $flashDeal->id)
                ->with('error', 'This product does not belong to the specified flash deal.');
        }

        $product->load('product');
        return view('saas_seller.saas_flash_deal_product.saas_edit', compact('flashDeal', 'product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasFlashDeal $flashDeal, SaasFlashDealProduct $product)
    {
        // Check if the flash deal belongs to the authenticated seller
        if ($flashDeal->seller_id !== Auth::id()) {
            return redirect()->route('seller.flash-deals.index')
                ->with('error', 'You are not authorized to update this flash deal product.');
        }

        // Check if the product belongs to this flash deal
        if ($product->flash_deal_id !== $flashDeal->id) {
            return redirect()->route('seller.flash-deals.products.index', $flashDeal->id)
                ->with('error', 'This product does not belong to the specified flash deal.');
        }

        $request->validate([
            'discount_type' => 'required|in:flat,percentage',
            'discount_value' => 'required|numeric|min:0',
        ]);

        // Additional validation for percentage discount
        if ($request->discount_type === 'percentage' && $request->discount_value > 100) {
            return redirect()->back()
                ->with('error', 'Percentage discount cannot exceed 100%.')
                ->withInput();
        }

        // Additional validation for flat discount
        if ($request->discount_type === 'flat' && $request->discount_value >= $product->product->price) {
            return redirect()->back()
                ->with('error', 'Flat discount cannot be equal to or greater than the product price.')
                ->withInput();
        }

        // Update flash deal product
        $product->discount_type = $request->discount_type;
        $product->discount_value = $request->discount_value;
        $product->save();

        return redirect()->route('seller.flash-deals.products.index', $flashDeal->id)
            ->with('success', 'Flash deal product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasFlashDeal $flashDeal, SaasFlashDealProduct $product)
    {
        // Check if the flash deal belongs to the authenticated seller
        if ($flashDeal->seller_id !== Auth::id()) {
            return redirect()->route('seller.flash-deals.index')
                ->with('error', 'You are not authorized to remove products from this flash deal.');
        }

        // Check if the product belongs to this flash deal
        if ($product->flash_deal_id !== $flashDeal->id) {
            return redirect()->route('seller.flash-deals.products.index', $flashDeal->id)
                ->with('error', 'This product does not belong to the specified flash deal.');
        }

        $product->delete();

        return redirect()->route('seller.flash-deals.products.index', $flashDeal->id)
            ->with('success', 'Product removed from flash deal successfully');
    }
}
