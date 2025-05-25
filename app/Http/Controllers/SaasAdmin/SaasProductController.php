<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasProduct;
use App\Models\SaasCategory;
use App\Models\SaasSubCategory;
use App\Models\SaasChildCategory;
use App\Models\SaasBrand;
use App\Models\SaasUnit;
use App\Models\SaasAttribute;
use App\Models\SaasProductImage;
use App\Models\SaasProductVariation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SaasProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = SaasProduct::with(['category', 'brand'])->latest()->paginate(10);
        return view('saas_admin.saas_product.saas_index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = SaasCategory::all();
        $brands = SaasBrand::all();
        $units = SaasUnit::all();
        $attributes = SaasAttribute::with('values')->get();
        $sellers = User::where('role', 'seller')->orderBy('name')->get();

        return view('saas_admin.saas_product.saas_create', compact(
            'categories', 'brands', 'units', 'attributes', 'sellers'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:saas_categories,id',
            'subcategory_id' => 'nullable|exists:saas_sub_categories,id',
            'child_category_id' => 'nullable|exists:saas_child_categories,id',
            'brand_id' => 'required|exists:saas_brands,id',
            'unit_id' => 'required|exists:saas_units,id',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:flat,percentage',
            'SKU' => 'required|string|unique:saas_products',
            'stock' => 'required|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'has_variations' => 'sometimes|boolean',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'variations' => 'required_if:has_variations,1|array',
            'variations.*.attribute_id' => 'required_if:has_variations,1|exists:saas_attributes,id',
            'variations.*.attribute_value_id' => 'required_if:has_variations,1|exists:saas_attribute_values,id',
            'variations.*.price' => 'required_if:has_variations,1|numeric|min:0',
            'variations.*.stock' => 'required_if:has_variations,1|integer|min:0',
            'variations.*.sku' => 'required_if:has_variations,1|string',
            'variations.*.discount' => 'nullable|numeric|min:0',
            'variations.*.discount_type' => 'nullable|in:flat,percentage',
        ]);

        $product = new SaasProduct();
        $product->name = $request->name;
        $product->seller_id = $request->seller_id;
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->child_category_id = $request->child_category_id;
        $product->brand_id = $request->brand_id;
        $product->unit_id = $request->unit_id;
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->has_variations = $request->has_variations ?? false;

        // Always set price, discount, and SKU regardless of variations
        $product->price = $request->price;
        $product->discount = $request->discount ?? 0;
        $product->discount_type = $request->discount_type ?? 'flat';
        $product->SKU = $request->SKU;
        $product->stock = $request->stock ?? 0;

        $product->is_featured = $request->is_featured ?? false;
        $product->is_active = $request->is_active ?? false;

        $product->save();

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $product->saveImage($thumbnail);
        }

        // Handle multiple product images
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                $product->saveImage($image);
            }
        }

        // Save variations if they exist
        if ($product->has_variations && isset($request->variations) && is_array($request->variations)) {
            $totalStock = 0;

            foreach ($request->variations as $index => $variationData) {
                if (empty($variationData['attribute_id']) || empty($variationData['attribute_value_id'])) {
                    continue;
                }

                $variation = new SaasProductVariation();
                $variation->product_id = $product->id;
                $variation->attribute_id = $variationData['attribute_id'];
                $variation->attribute_value_id = $variationData['attribute_value_id'];
                $variation->price = $variationData['price'];
                $variation->stock = $variationData['stock'];
                $variation->sku = $variationData['sku'];
                // No discount at variation level - removed
                $variation->save();

                $totalStock += $variation->stock;
            }

            // Update product stock with the sum of variation stocks
            $product->stock = $totalStock;
            $product->save();
        }

        toast('Product created successfully', 'success');
        return redirect()->route('admin.products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasProduct $product)
    {
        $product->load(['category', 'subcategory', 'childCategory', 'brand', 'unit', 'images']);
        return view('saas_admin.saas_product.saas_show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasProduct $product)
    {
        $categories = SaasCategory::all();
        $brands = SaasBrand::all();
        $units = SaasUnit::all();
        $attributes = SaasAttribute::with('values')->get();
        $sellers = User::where('role', 'seller')->orderBy('name')->get();
        $product->load(['images', 'variations.attribute', 'variations.attributeValue']);

        return view('saas_admin.saas_product.saas_edit', compact(
            'product', 'categories', 'brands', 'units', 'attributes', 'sellers'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasProduct $product)
    {
        $request->validate([
            'seller_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:saas_categories,id',
            'subcategory_id' => 'nullable|exists:saas_sub_categories,id',
            'child_category_id' => 'nullable|exists:saas_child_categories,id',
            'brand_id' => 'required|exists:saas_brands,id',
            'unit_id' => 'required|exists:saas_units,id',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:flat,percentage',
            'SKU' => 'required|string|unique:saas_products,SKU,' . $product->id,
            'stock' => 'required|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'has_variations' => 'sometimes|boolean',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deleted_images' => 'nullable|array',
            'deleted_images.*' => 'nullable|exists:saas_product_images,id',
            'variations' => 'required_if:has_variations,1|array',
            'variations.*.attribute_id' => 'required_if:has_variations,1|exists:saas_attributes,id',
            'variations.*.attribute_value_id' => 'required_if:has_variations,1|exists:saas_attribute_values,id',
            'variations.*.price' => 'required_if:has_variations,1|numeric|min:0',
            'variations.*.stock' => 'required_if:has_variations,1|integer|min:0',
            'variations.*.sku' => 'required_if:has_variations,1|string',
            'variations.*.discount' => 'nullable|numeric|min:0',
            'variations.*.discount_type' => 'nullable|in:flat,percentage',
        ]);

        $product->name = $request->name;
        $product->seller_id = $request->seller_id;
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->child_category_id = $request->child_category_id;
        $product->brand_id = $request->brand_id;
        $product->unit_id = $request->unit_id;
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->has_variations = $request->has_variations ?? false;

        // Always set price, discount, and SKU regardless of variations
        $product->price = $request->price;
        $product->discount = $request->discount ?? 0;
        $product->discount_type = $request->discount_type ?? 'flat';
        $product->SKU = $request->SKU;
        $product->stock = $request->stock ?? 0;

        $product->is_featured = $request->is_featured ?? false;
        $product->is_active = $request->is_active ?? false;

        $product->save();

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
                            // If there's already an image, delete it and create a new one
                if ($product->images->count() > 0) {
                    $firstImage = $product->images->first();
                    $product->deleteImage($firstImage->id);
                }

                // Upload new thumbnail
                $thumbnail = $request->file('thumbnail');
                $product->saveImage($thumbnail);
        }

        // Handle multiple product images
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                $product->saveImage($image);
            }
        }

        // Delete images marked for deletion
        if ($request->has('deleted_images') && is_array($request->deleted_images)) {
            foreach ($request->deleted_images as $imageId) {
                $product->deleteImage($imageId);
            }
        }

        // Handle variations
        if ($product->has_variations) {
            // Delete variations that should be removed
            if ($request->has('deleted_variations') && is_array($request->deleted_variations)) {
                SaasProductVariation::whereIn('id', $request->deleted_variations)
                    ->where('product_id', $product->id)
                    ->delete();
            }

            if (isset($request->variations) && is_array($request->variations)) {
                $totalStock = 0;

                foreach ($request->variations as $variationData) {
                    if (empty($variationData['attribute_id']) || empty($variationData['attribute_value_id'])) {
                        continue;
                    }

                    // If variation has an ID, update it, otherwise create new
                    if (isset($variationData['id'])) {
                        $variation = SaasProductVariation::where('id', $variationData['id'])
                            ->where('product_id', $product->id)
                            ->first();

                        if ($variation) {
                            $variation->attribute_id = $variationData['attribute_id'];
                            $variation->attribute_value_id = $variationData['attribute_value_id'];
                            $variation->price = $variationData['price'];
                            $variation->stock = $variationData['stock'];
                            $variation->sku = $variationData['sku'];
                            $variation->save();

                            $totalStock += $variation->stock;
                        }
                    } else {
                        // Create new variation
                        $variation = new SaasProductVariation();
                        $variation->product_id = $product->id;
                        $variation->attribute_id = $variationData['attribute_id'];
                        $variation->attribute_value_id = $variationData['attribute_value_id'];
                        $variation->price = $variationData['price'];
                        $variation->stock = $variationData['stock'];
                        $variation->sku = $variationData['sku'];
                        $variation->save();

                        $totalStock += $variation->stock;
                    }
                }

                // Update product stock with sum of variation stocks
                $product->stock = $totalStock;
                $product->save();
            }
        } else {
            // If product doesn't have variations anymore, delete all variations
            SaasProductVariation::where('product_id', $product->id)->delete();
        }

        toast('Product updated successfully', 'success');
        return redirect()->route('admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasProduct $product)
    {
        // Delete product images
        foreach ($product->images as $image) {
            $product->deleteImage($image->id);
        }

        $product->delete();

        toast('Product deleted successfully', 'success');
        return redirect()->route('admin.products.index');
    }
}
