<?php

namespace App\Http\Controllers\SaasSeller;

use App\Http\Controllers\Controller;
use App\Models\SaasAttribute;
use App\Models\SaasAttributeValue;
use App\Models\SaasProduct;
use App\Models\SaasProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SaasProductVariationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SaasProduct $product)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('seller.products.index')
                ->with('error', 'You are not authorized to view variations for this product.');
        }

        $variations = $product->variations()->with(['attribute', 'attributeValue'])->get();
        return view('saas_seller.saas_product_variation.saas_index', compact('product', 'variations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(SaasProduct $product)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('seller.products.index')
                ->with('error', 'You are not authorized to add variations to this product.');
        }

        $attributes = SaasAttribute::with('values')->get();
        return view('saas_seller.saas_product_variation.saas_create', compact('product', 'attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, SaasProduct $product)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('seller.products.index')
                ->with('error', 'You are not authorized to add variations to this product.');
        }

        $request->validate([
            'attribute_id' => 'required|exists:saas_attributes,id',
            'attribute_value_id' => 'required|exists:saas_attribute_values,id',
            'sku' => 'required|string|unique:saas_product_variations',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        // Verify the attribute value belongs to the attribute
        $attributeValue = SaasAttributeValue::find($request->attribute_value_id);
        if ($attributeValue->attribute_id != $request->attribute_id) {
            return redirect()->back()
                ->with('error', 'Selected attribute value does not belong to the selected attribute.')
                ->withInput();
        }

        // Check if a variation with the same attribute and value already exists
        $existingVariation = SaasProductVariation::where('product_id', $product->id)
            ->where('attribute_id', $request->attribute_id)
            ->where('attribute_value_id', $request->attribute_value_id)
            ->first();

        if ($existingVariation) {
            return redirect()->back()
                ->with('error', 'A variation with the selected attribute and value already exists.')
                ->withInput();
        }

        $variation = new SaasProductVariation();
        $variation->product_id = $product->id;
        $variation->attribute_id = $request->attribute_id;
        $variation->attribute_value_id = $request->attribute_value_id;
        $variation->sku = $request->sku;
        $variation->price = $request->price;
        $variation->stock = $request->stock;
        $variation->save();

        return redirect()->route('seller.products.variations.index', $product->id)
            ->with('success', 'Product variation added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasProduct $product, SaasProductVariation $variation)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('seller.products.index')
                ->with('error', 'You are not authorized to view this product variation.');
        }

        // Check if the variation belongs to this product
        if ($variation->product_id !== $product->id) {
            return redirect()->route('seller.products.variations.index', $product->id)
                ->with('error', 'This variation does not belong to the specified product.');
        }

        $variation->load(['attribute', 'attributeValue']);
        return view('saas_seller.saas_product_variation.saas_show', compact('product', 'variation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasProduct $product, SaasProductVariation $variation)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('seller.products.index')
                ->with('error', 'You are not authorized to edit this product variation.');
        }

        // Check if the variation belongs to this product
        if ($variation->product_id !== $product->id) {
            return redirect()->route('seller.products.variations.index', $product->id)
                ->with('error', 'This variation does not belong to the specified product.');
        }

        $variation->load(['attribute', 'attributeValue']);
        $attributes = SaasAttribute::with('values')->get();

        return view('saas_seller.saas_product_variation.saas_edit', compact('product', 'variation', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasProduct $product, SaasProductVariation $variation)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('seller.products.index')
                ->with('error', 'You are not authorized to update this product variation.');
        }

        // Check if the variation belongs to this product
        if ($variation->product_id !== $product->id) {
            return redirect()->route('seller.products.variations.index', $product->id)
                ->with('error', 'This variation does not belong to the specified product.');
        }

        $request->validate([
            'attribute_id' => 'required|exists:saas_attributes,id',
            'attribute_value_id' => 'required|exists:saas_attribute_values,id',
            'sku' => 'required|string|unique:saas_product_variations,sku,' . $variation->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        // Verify the attribute value belongs to the attribute
        $attributeValue = SaasAttributeValue::find($request->attribute_value_id);
        if ($attributeValue->attribute_id != $request->attribute_id) {
            return redirect()->back()
                ->with('error', 'Selected attribute value does not belong to the selected attribute.')
                ->withInput();
        }

        // Check if a variation with the same attribute and value already exists (excluding this one)
        $existingVariation = SaasProductVariation::where('product_id', $product->id)
            ->where('attribute_id', $request->attribute_id)
            ->where('attribute_value_id', $request->attribute_value_id)
            ->where('id', '!=', $variation->id)
            ->first();

        if ($existingVariation) {
            return redirect()->back()
                ->with('error', 'A variation with the selected attribute and value already exists.')
                ->withInput();
        }

        $variation->attribute_id = $request->attribute_id;
        $variation->attribute_value_id = $request->attribute_value_id;
        $variation->sku = $request->sku;
        $variation->price = $request->price;
        $variation->stock = $request->stock;
        $variation->save();

        return redirect()->route('seller.products.variations.index', $product->id)
            ->with('success', 'Product variation updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasProduct $product, SaasProductVariation $variation)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('seller.products.index')
                ->with('error', 'You are not authorized to delete this product variation.');
        }

        // Check if the variation belongs to this product
        if ($variation->product_id !== $product->id) {
            return redirect()->route('seller.products.variations.index', $product->id)
                ->with('error', 'This variation does not belong to the specified product.');
        }

        // Check if the variation is used in any orders
        if ($variation->orderItems()->count() > 0) {
            return redirect()->route('seller.products.variations.index', $product->id)
                ->with('error', 'Cannot delete variation because it has associated orders');
        }

        $variation->delete();

        return redirect()->route('seller.products.variations.index', $product->id)
            ->with('success', 'Product variation deleted successfully');
    }

    /**
     * Bulk update stock for variations.
     */
    public function bulkUpdateStock(Request $request, SaasProduct $product)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('seller.products.index')
                ->with('error', 'You are not authorized to update variations for this product.');
        }

        $request->validate([
            'stocks' => 'required|array',
            'stocks.*' => 'required|integer|min:0',
        ]);

        $variations = $product->variations;

        foreach ($variations as $variation) {
            if (isset($request->stocks[$variation->id])) {
                $variation->stock = $request->stocks[$variation->id];
                $variation->save();
            }
        }

        return redirect()->route('seller.products.variations.index', $product->id)
            ->with('success', 'Stock updated successfully for all variations');
    }

    /**
     * Get attribute values for a specific attribute.
     */
    public function getAttributeValues($attributeId)
    {
        $attributeValues = SaasAttributeValue::where('attribute_id', $attributeId)->get();
        return response()->json($attributeValues);
    }
}
