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
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SaasInHouseProductController extends Controller
{
    /**
     * Display a listing of in-house products
     */
    public function index(Request $request)
    {
        $query = SaasProduct::with(['category', 'brand', 'images'])
            ->where('is_in_house_product', true)
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('SKU', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by brand
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $products = $query->paginate(20);

        // Get filter options
        $categories = SaasCategory::all();
        $brands = SaasBrand::all();

        // Calculate statistics
        $totalProducts = SaasProduct::where('is_in_house_product', true)->count();
        $activeProducts = SaasProduct::where('is_in_house_product', true)->where('is_active', true)->count();
        $lowStockProducts = SaasProduct::where('is_in_house_product', true)->where('stock', '<=', 10)->count();

        return view('saas_admin.saas_in_house_products.saas_index', compact(
            'products', 'categories', 'brands', 'totalProducts', 'activeProducts', 'lowStockProducts'
        ));
    }

    /**
     * Show the form for creating a new in-house product
     */
    public function create()
    {
        $categories = SaasCategory::all();
        $brands = SaasBrand::all();
        $units = SaasUnit::all();
        $attributes = SaasAttribute::with('values')->get();

        return view('saas_admin.saas_in_house_products.saas_create', compact(
            'categories', 'brands', 'units', 'attributes'
        ));
    }

    /**
     * Store a newly created in-house product
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'product_type' => 'required|in:Digital,Physical',
            'file' => 'required_if:product_type,Digital|nullable|file|mimes:pdf,doc,docx,zip,rar,txt,mp3,mp4,avi,mov|max:51200',
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
            'stock' => 'required_if:product_type,Physical|nullable|integer|min:0',
            'is_featured' => 'nullable',
            'is_active' => 'nullable',
            'has_variations' => 'sometimes|boolean',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'variations' => 'required_if:has_variations,1|array',
            'variations.*.attribute_id' => 'required_if:has_variations,1|exists:saas_attributes,id',
            'variations.*.attribute_value_id' => 'required_if:has_variations,1|exists:saas_attribute_values,id',
            'variations.*.price' => 'required_if:has_variations,1|numeric|min:0',
            'variations.*.stock' => 'required_if:has_variations,1|integer|min:0',
            'variations.*.sku' => 'required_if:has_variations,1|string',
        ]);

        $product = new SaasProduct();
        $product->name = $request->name;
        $product->seller_id = null; // Always null for in-house products
        $product->product_type = $request->product_type;
        $product->is_in_house_product = true; // Always true for this controller
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->child_category_id = $request->child_category_id;
        $product->brand_id = $request->brand_id;
        $product->unit_id = $request->unit_id;
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->has_variations = $request->has_variations ?? false;
        $product->price = $request->price;
        $product->discount = $request->discount ?? 0;
        $product->discount_type = $request->discount_type ?? 'flat';
        $product->SKU = $request->SKU;

        // Set stock based on product type
        if ($product->product_type === 'Physical') {
            $product->stock = $request->stock ?? 0;
        } else {
            $product->stock = 999999; // Digital products have unlimited stock
        }

        $product->is_featured = $request->has('is_featured') ? (bool) $request->is_featured : false;
        $product->is_active = $request->has('is_active') ? (bool) $request->is_active : false;

        $product->save();

        // Handle digital product file upload
        if ($product->product_type === 'Digital' && $request->hasFile('file')) {
            $filename = $product->saveDigitalFile($request->file('file'));
        }

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

            foreach ($request->variations as $variationData) {
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
                $variation->save();

                $totalStock += $variation->stock;
            }

            // Update product stock with the sum of variation stocks
            $product->stock = $totalStock;
            $product->save();
        }

        toast('In-house product created successfully!', 'success');
        return redirect()->route('admin.in-house-products.index');
    }

    /**
     * Display the specified in-house product
     */
    public function show($in_house_product)
    {
        $product = SaasProduct::where('id', $in_house_product)
            ->where('is_in_house_product', true)
            ->with(['category', 'subcategory', 'childCategory', 'brand', 'unit', 'images', 'variations'])
            ->firstOrFail();

        return view('saas_admin.saas_in_house_products.saas_show', compact('product'));
    }

    /**
     * Show the form for editing the specified in-house product
     */
    public function edit($in_house_product)
    {
        $product = SaasProduct::where('id', $in_house_product)
            ->where('is_in_house_product', true)
            ->with(['images', 'variations.attribute', 'variations.attributeValue'])
            ->firstOrFail();

        $categories = SaasCategory::all();
        $brands = SaasBrand::all();
        $units = SaasUnit::all();
        $attributes = SaasAttribute::with('values')->get();

        return view('saas_admin.saas_in_house_products.saas_edit', compact(
            'product', 'categories', 'brands', 'units', 'attributes'
        ));
    }

    /**
     * Update the specified in-house product
     */
    public function update(Request $request, $in_house_product)
    {
        $product = SaasProduct::where('id', $in_house_product)
            ->where('is_in_house_product', true)
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'product_type' => 'required|in:Digital,Physical',
            'file' => 'nullable|file|mimes:pdf,doc,docx,zip,rar,txt,mp3,mp4,avi,mov|max:51200',
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
            'stock' => 'required_if:product_type,Physical|nullable|integer|min:0',
            'is_featured' => 'nullable',
            'is_active' => 'nullable',
            'has_variations' => 'sometimes|boolean',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'deleted_images' => 'nullable|array',
            'deleted_images.*' => 'nullable|exists:saas_product_images,id',
        ]);

        $product->name = $request->name;
        $product->seller_id = null; // Always null for in-house products
        $product->product_type = $request->product_type;
        $product->is_in_house_product = true; // Always true
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->child_category_id = $request->child_category_id;
        $product->brand_id = $request->brand_id;
        $product->unit_id = $request->unit_id;
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->has_variations = $request->has_variations ?? false;
        $product->price = $request->price;
        $product->discount = $request->discount ?? 0;
        $product->discount_type = $request->discount_type ?? 'flat';
        $product->SKU = $request->SKU;

        // Set stock based on product type
        if ($product->product_type === 'Physical') {
            $product->stock = $request->stock ?? 0;
        } else {
            $product->stock = 999999; // Digital products have unlimited stock
        }

        $product->is_featured = $request->has('is_featured') ? (bool) $request->is_featured : false;
        $product->is_active = $request->has('is_active') ? (bool) $request->is_active : false;

        $product->save();

        // Handle digital product file upload
        if ($product->product_type === 'Digital' && $request->hasFile('file')) {
            $product->saveDigitalFile($request->file('file'));
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // If there's already an image, delete it and create a new one
            if ($product->images->count() > 0) {
                $firstImage = $product->images->first();
                $product->deleteImage($firstImage->id);
            }
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

        toast('In-house product updated successfully!', 'success');
        return redirect()->route('admin.in-house-products.index');
    }

    /**
     * Remove the specified in-house product
     */
    public function destroy($in_house_product)
    {
        $product = SaasProduct::where('id', $in_house_product)
            ->where('is_in_house_product', true)
            ->firstOrFail();

        // Delete product images
        foreach ($product->images as $image) {
            $product->deleteImage($image->id);
        }

        // Delete variations
        $product->variations()->delete();

        // Delete the product
        $product->delete();

        toast('In-house product deleted successfully!', 'success');
        return redirect()->route('admin.in-house-products.index');
    }
}
