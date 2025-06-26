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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SaasProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = SaasProduct::with(['category', 'brand'])->where('is_in_house_product', '!=', 1)->latest()->paginate(10);
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
        // Define validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'product_type' => 'required|in:Digital,Physical',
            'is_in_house_product' => 'nullable|boolean',
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
            'variations.*.discount' => 'nullable|numeric|min:0',
            'variations.*.discount_type' => 'nullable|in:flat,percentage',
        ];

        // Make seller_id required only if product is not in-house
        $isInHouse = $request->boolean('is_in_house_product');
        if (!$isInHouse) {
            $rules['seller_id'] = 'required|exists:users,id';
        } else {
            $rules['seller_id'] = 'nullable|exists:users,id';
        }

        $request->validate($rules);

        $product = new SaasProduct();
        $product->name = $request->name;
        $product->seller_id = $isInHouse ? null : $request->seller_id;
        $product->product_type = $request->product_type;
        $product->is_in_house_product = $request->is_in_house_product ?? false;
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
            Log::info('Digital file upload started', [
                'product_id' => $product->id,
                'file_name' => $request->file('file')->getClientOriginalName(),
                'file_size' => $request->file('file')->getSize()
            ]);
            $filename = $product->saveDigitalFile($request->file('file'));
            Log::info('Digital file upload completed', ['filename' => $filename]);
        } elseif ($product->product_type === 'Digital') {
            Log::warning('Digital product created but no file uploaded', [
                'product_id' => $product->id,
                'has_file' => $request->hasFile('file')
            ]);
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
        // Define validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'product_type' => 'required|in:Digital,Physical',
            'is_in_house_product' => 'nullable|boolean',
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
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
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
        ];

        // Make seller_id required only if product is not in-house
        $isInHouse = $request->boolean('is_in_house_product');
        if (!$isInHouse) {
            $rules['seller_id'] = 'required|exists:users,id';
        } else {
            $rules['seller_id'] = 'nullable|exists:users,id';
        }

        $request->validate($rules);

        $product->name = $request->name;
        $product->seller_id = $isInHouse ? null : $request->seller_id;
        $product->product_type = $request->product_type;
        $product->is_in_house_product = $request->is_in_house_product ?? false;
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

    /**
     * Preview digital product file
     */
    public function previewFile(SaasProduct $product)
    {
        // Check if product has a file
        if (!$product->file || !Storage::disk('public')->exists($product->file)) {
            abort(404, 'File not found');
        }

        // Check if it's a digital product
        if ($product->product_type !== 'Digital') {
            abort(403, 'This is not a digital product');
        }

        $filePath = storage_path('app/public/' . $product->file);
        $mimeType = mime_content_type($filePath);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($product->file) . '"'
        ]);
    }

    /**
     * Download digital product file
     */
    public function downloadFile(SaasProduct $product)
    {
        // Check if product has a file
        if (!$product->file || !Storage::disk('public')->exists($product->file)) {
            abort(404, 'File not found');
        }

        // Check if it's a digital product
        if ($product->product_type !== 'Digital') {
            abort(403, 'This is not a digital product');
        }

        $filePath = storage_path('app/public/' . $product->file);
        $originalName = $product->name . '_' . time() . '.' . pathinfo($product->file, PATHINFO_EXTENSION);

        return response()->download($filePath, $originalName);
    }

    /**
     * Approve a product
     */
    public function approve(SaasProduct $product)
    {
        $product->seller_publish_status = SaasProduct::SELLER_PUBLISH_STATUS_APPROVED;
        $product->save();

        // Send approval email to seller
        try {
            $product->load(['seller', 'category', 'brand']);
            if ($product->seller && $product->seller->email) {
                Mail::to($product->seller->email)->send(
                    new \App\Mail\SaasProductApprovalNotification($product, 'approved')
                );

                Log::info('Product approval email sent successfully', [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'seller_email' => $product->seller->email,
                    'seller_name' => $product->seller->name
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send product approval email', [
                'product_id' => $product->id,
                'seller_email' => $product->seller->email ?? 'N/A',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        toast('Product approved successfully', 'success');
        return back();
    }

    /**
     * Deny a product
     */
    public function deny(SaasProduct $product)
    {
        $product->seller_publish_status = SaasProduct::SELLER_PUBLISH_STATUS_DENIED;
        $product->save();

        // Send denial email to seller
        try {
            $product->load(['seller', 'category', 'brand']);
            if ($product->seller && $product->seller->email) {
                Mail::to($product->seller->email)->send(
                    new \App\Mail\SaasProductApprovalNotification($product, 'denied')
                );

                Log::info('Product denial email sent successfully', [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'seller_email' => $product->seller->email,
                    'seller_name' => $product->seller->name
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send product denial email', [
                'product_id' => $product->id,
                'seller_email' => $product->seller->email ?? 'N/A',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        toast('Product denied successfully', 'success');
        return back();
    }

    /**
     * Reset product status to request
     */
    public function resetStatus(SaasProduct $product)
    {
        $product->seller_publish_status = SaasProduct::SELLER_PUBLISH_STATUS_REQUEST;
        $product->save();

        toast('Product status reset to request', 'success');
        return back();
    }
}
