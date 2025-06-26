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
use App\Mail\SaasProductRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $sellerId = Auth::id();
        $products = SaasProduct::with(['category', 'brand', 'images', 'variations'])
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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
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

        // Handle stock based on product type and variations
        if ($product->product_type === 'Digital') {
            $product->stock = 999999; // Digital products have unlimited stock
        } else {
            // For physical products without variations, use the stock value from the form
            if (!$product->has_variations) {
                $product->stock = $request->stock ?? 0;
            } else {
                // Initialize to zero for products with variations
                // Will be updated below after the variations are saved
                $product->stock = 0;
            }
        }

        $product->is_featured = $request->has('is_featured') ? (bool) $request->is_featured : false;
        $product->is_active = $request->has('is_active') ? (bool) $request->is_active : false;
        $product->seller_publish_status = SaasProduct::SELLER_PUBLISH_STATUS_REQUEST;

        $product->save();

                // Send email notification to admin about new product request
        try {
            $adminEmail = config('app.admin_email');

            if (empty($adminEmail)) {
                Log::warning('Admin email not configured, skipping product request notification', [
                    'product_id' => $product->id,
                    'seller_id' => $sellerId
                ]);
            } else {
                // Load relationships before sending email
                $productWithRelations = $product->load(['seller', 'category', 'brand', 'images']);

                // Verify seller exists
                if (!$productWithRelations->seller) {
                    Log::error('Product has no seller relationship', [
                        'product_id' => $product->id,
                        'seller_id' => $sellerId
                    ]);
                } else {
                    // Send the email
                    Mail::to($adminEmail)->send(new SaasProductRequestNotification($productWithRelations));

                    Log::info('Product request notification sent successfully', [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'seller_id' => $sellerId,
                        'seller_name' => $productWithRelations->seller->name,
                        'admin_email' => $adminEmail,
                        'mail_driver' => config('mail.default')
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to send product request notification', [
                'product_id' => $product->id,
                'seller_id' => $sellerId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'mail_config' => [
                    'driver' => config('mail.default'),
                    'admin_email' => config('app.admin_email'),
                    'from_address' => config('mail.from.address')
                ]
            ]);

            // Still continue with success message since product was created
        }

        // Handle digital product file upload
        if ($product->product_type === 'Digital' && $request->hasFile('file')) {
            Log::info('Digital file upload started (Seller)', [
                'product_id' => $product->id,
                'seller_id' => $sellerId,
                'file_name' => $request->file('file')->getClientOriginalName(),
                'file_size' => $request->file('file')->getSize()
            ]);
            $filename = $product->saveDigitalFile($request->file('file'));
            Log::info('Digital file upload completed (Seller)', ['filename' => $filename]);
        } elseif ($product->product_type === 'Digital') {
            Log::warning('Digital product created but no file uploaded (Seller)', [
                'product_id' => $product->id,
                'seller_id' => $sellerId,
                'has_file' => $request->hasFile('file')
            ]);
        }

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

        return redirect()->route('seller.products.index')->with('success', 'Product created successfully and sent for approval');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasProduct $product)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('seller.products.index')->with('error', 'You are not authorized to view this product.');
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
            return redirect()->route('seller.products.index')->with('error', 'You are not authorized to edit this product.');
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
            return redirect()->route('seller.products.index')->with('error', 'You are not authorized to update this product.');
        }

        $request->validate([
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
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
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

        // Handle stock based on product type and variations
        if ($product->product_type === 'Digital') {
            $product->stock = 999999; // Digital products have unlimited stock
        } else {
            // For physical products without variations, use the stock value from the form
            if (!$product->has_variations) {
                $product->stock = $request->stock ?? 0;
            }
        }

        $product->is_featured = $request->has('is_featured') ? (bool) $request->is_featured : false;
        $product->is_active = $request->has('is_active') ? (bool) $request->is_active : false;

        $product->save();

        // Handle digital product file upload
        if ($product->product_type === 'Digital' && $request->hasFile('file')) {
            $product->saveDigitalFile($request->file('file'));
        }

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

        return redirect()->route('seller.products.index')->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasProduct $product)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('seller.products.index')->with('error', 'You are not authorized to delete this product.');
        }

        // Check if the product has orders
        if ($product->orderItems()->count() > 0) {
            return redirect()->route('seller.products.index')->with('error', 'Cannot delete product because it has associated orders');
        }

        // Delete product images
        foreach ($product->images as $image) {
            $product->deleteImage($image->id);
        }

        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Product deleted successfully');
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

    /**
     * Preview digital product file
     */
    public function previewFile(SaasProduct $product)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            abort(403, 'You are not authorized to access this file');
        }

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
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            abort(403, 'You are not authorized to access this file');
        }

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
}
