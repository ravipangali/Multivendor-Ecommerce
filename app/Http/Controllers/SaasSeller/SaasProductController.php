<?php

namespace App\Http\Controllers\SaasSeller;

use App\Http\Controllers\Controller;
use App\Models\SaasProduct;
use App\Models\SaasCategory;
use App\Models\SaasSubCategory;
use App\Models\SaasChildCategory;
use App\Models\SaasBrand;
use App\Models\SaasUnit;
use App\Models\SaasAttribute;
use App\Models\SaasProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SaasProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sellerId = Auth::id();
        $products = SaasProduct::with(['category', 'brand'])
            ->where('seller_id', $sellerId)
            ->latest()
            ->paginate(10);

        return view('saas_seller.saas_product.saas_index', compact('products'));
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

        return view('saas_seller.saas_product.saas_create', compact(
            'categories', 'brands', 'units', 'attributes'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $sellerId = Auth::id();

        $request->validate([
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
            'is_featured' => 'required|boolean',
            'is_active' => 'required|boolean',
            'has_variations' => 'sometimes|boolean',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
        $product->seller_id = $sellerId;
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

        // For products without variations, use the stock value from the form
        if (!$product->has_variations) {
            $product->stock = $request->stock ?? 0;
        } else {
            // Initialize to zero for products with variations
            // Will be updated below after the variations are saved
            $product->stock = 0;
        }

        $product->is_featured = $request->is_featured ?? false;
        $product->is_active = $request->is_active ?? false;

        $product->save();

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $product->saveImage($thumbnail);
        }

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
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
        return redirect()->route('seller.products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasProduct $product)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            toast('You are not authorized to view this product.', 'error');
            return redirect()->route('seller.products.index');
        }

        $product->load(['category', 'subcategory', 'childCategory', 'brand', 'unit', 'images']);
        return view('saas_seller.saas_product.saas_show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasProduct $product)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            toast('You are not authorized to edit this product.', 'error');
            return redirect()->route('seller.products.index');
        }

        $categories = SaasCategory::all();
        $subcategories = SaasSubCategory::where('category_id', $product->category_id)->get();
        $childcategories = SaasChildCategory::where('sub_category_id', $product->subcategory_id)->get();
        $brands = SaasBrand::all();
        $units = SaasUnit::all();
        $attributes = SaasAttribute::with('values')->get();
        $product->load('images');

        return view('saas_seller.saas_product.saas_edit', compact(
            'product', 'categories', 'subcategories', 'childcategories', 'brands', 'units', 'attributes'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasProduct $product)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            toast('You are not authorized to update this product.', 'error');
            return redirect()->route('seller.products.index');
        }

        $request->validate([
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
            'is_featured' => 'required|boolean',
            'is_active' => 'required|boolean',
            'has_variations' => 'sometimes|boolean',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

        // For products without variations, use the stock value from the form
        if (!$product->has_variations) {
            $product->stock = $request->stock ?? 0;
        }

        $product->is_featured = $request->is_featured ?? false;
        $product->is_active = $request->is_active ?? false;

        $product->save();

        // Handle thumbnail update (which is the first image)
        if ($request->hasFile('thumbnail')) {
            // Delete the first image (if exists) and upload the new one
            $firstImage = $product->images()->first();
            if ($firstImage) {
                $product->deleteImage($firstImage->id);
            }

            // Upload new thumbnail
            $thumbnail = $request->file('thumbnail');
            $product->saveImage($thumbnail);
        }

        // Handle multiple images update
        if ($request->hasFile('images')) {
            // We'll keep the thumbnail (first image) and replace the rest
            $images = $product->images()->skip(1)->get();
            foreach ($images as $image) {
                $product->deleteImage($image->id);
            }

            // Upload new images
            foreach ($request->file('images') as $image) {
                $product->saveImage($image);
            }
        }

        // Handle variations
        if ($product->has_variations) {
            // Delete variations that were marked for deletion
            if ($request->has('deleted_variations') && is_array($request->deleted_variations)) {
                SaasProductVariation::whereIn('id', $request->deleted_variations)
                    ->where('product_id', $product->id)
                    ->delete();
            }

            // Update or create variations
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
                            // No discount at variation level - removed
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
                        // No discount at variation level - removed
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
        return redirect()->route('seller.products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasProduct $product)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            toast('You are not authorized to delete this product.', 'error');
            return redirect()->route('seller.products.index');
        }

        // Check if the product has orders
        if ($product->orderItems()->count() > 0) {
            toast('Cannot delete product because it has associated orders', 'error');
            return redirect()->route('seller.products.index');
        }

        // Delete product images
        foreach ($product->images as $image) {
            $product->deleteImage($image->id);
        }

        $product->delete();

        toast('Product deleted successfully', 'success');
        return redirect()->route('seller.products.index');
    }

    /**
     * Get subcategories for a category (AJAX).
     */
    public function getSubcategories($categoryId)
    {
        $subcategories = SaasSubCategory::where('category_id', $categoryId)->get();
        return response()->json($subcategories);
    }

    /**
     * Get child categories for a subcategory (AJAX).
     */
    public function getChildCategories($subcategoryId)
    {
        $childCategories = SaasChildCategory::where('sub_category_id', $subcategoryId)->get();
        return response()->json($childCategories);
    }
}
