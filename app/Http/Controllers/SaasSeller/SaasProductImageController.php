<?php

namespace App\Http\Controllers\SaasSeller;

use App\Http\Controllers\Controller;
use App\Models\SaasProduct;
use App\Models\SaasProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SaasProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SaasProduct $product)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('seller.products.index')
                ->with('error', 'You are not authorized to view images for this product.');
        }

        $images = $product->images;
        return view('saas_seller.saas_product_image.saas_index', compact('product', 'images'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(SaasProduct $product)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('seller.products.index')
                ->with('error', 'You are not authorized to add images to this product.');
        }

        return view('saas_seller.saas_product_image.saas_create', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, SaasProduct $product)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('seller.products.index')
                ->with('error', 'You are not authorized to add images to this product.');
        }

        $request->validate([
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $savedImages = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $savedImages[] = $product->saveImage($image);
            }
        }

        return redirect()->route('seller.products.images.index', $product->id)
            ->with('success', count($savedImages) . ' images uploaded successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasProduct $product, SaasProductImage $image)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('seller.products.index')
                ->with('error', 'You are not authorized to view this product image.');
        }

        // Check if the image belongs to this product
        if ($image->product_id !== $product->id) {
            return redirect()->route('seller.products.images.index', $product->id)
                ->with('error', 'This image does not belong to the specified product.');
        }

        return view('saas_seller.saas_product_image.saas_show', compact('product', 'image'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasProduct $product, SaasProductImage $image)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('seller.products.index')
                ->with('error', 'You are not authorized to edit this product image.');
        }

        // Check if the image belongs to this product
        if ($image->product_id !== $product->id) {
            return redirect()->route('seller.products.images.index', $product->id)
                ->with('error', 'This image does not belong to the specified product.');
        }

        return view('saas_seller.saas_product_image.saas_edit', compact('product', 'image'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasProduct $product, SaasProductImage $image)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('seller.products.index')
                ->with('error', 'You are not authorized to update this product image.');
        }

        // Check if the image belongs to this product
        if ($image->product_id !== $product->id) {
            return redirect()->route('seller.products.images.index', $product->id)
                ->with('error', 'This image does not belong to the specified product.');
        }

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Delete the old image file
        $oldImagePath = str_replace('/storage/', '', $image->image_url);
        Storage::disk('public')->delete($oldImagePath);

        // Upload the new image file
        $uploadedImage = $request->file('image');
        $filename = 'product_images/' . uniqid() . '.' . $uploadedImage->getClientOriginalExtension();
        $uploadedImage->storeAs('public', $filename);

        // Update the image record
        $image->fill(['image_url' => $filename])->save();

        return redirect()->route('seller.products.images.index', $product->id)
            ->with('success', 'Product image updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasProduct $product, SaasProductImage $image)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('seller.products.index')
                ->with('error', 'You are not authorized to delete this product image.');
        }

        // Check if the image belongs to this product
        if ($image->product_id !== $product->id) {
            return redirect()->route('seller.products.images.index', $product->id)
                ->with('error', 'This image does not belong to the specified product.');
        }

        // Don't allow deleting the only image
        if ($product->images()->count() <= 1) {
            return redirect()->route('seller.products.images.index', $product->id)
                ->with('error', 'Cannot delete the only image of the product. Please add another image first.');
        }

        // Delete the image
        $product->deleteImage($image->id);

        return redirect()->route('seller.products.images.index', $product->id)
            ->with('success', 'Product image deleted successfully');
    }

    /**
     * Set image as primary (thumbnail).
     */
    public function setAsPrimary(SaasProduct $product, SaasProductImage $image)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('seller.products.index')
                ->with('error', 'You are not authorized to manage this product image.');
        }

        // Check if the image belongs to this product
        if ($image->product_id !== $product->id) {
            return redirect()->route('seller.products.images.index', $product->id)
                ->with('error', 'This image does not belong to the specified product.');
        }

        // Get all images of the product
        $images = $product->images;

        // Move the selected image to be the first one
        // In relational database, we don't have direct "position" field,
        // so we need to recreate the order by deleting and re-adding images
        $imageUrls = [];

        // First, collect the image URLs in the desired order
        foreach ($images as $img) {
            if ($img->id === $image->id) {
                // Skip the selected image as we'll add it first
                continue;
            }
            // Get the raw image path without the storage URL prefix
            $imageUrls[] = str_replace('/storage/', '', $img->image_url);
        }

        // Delete all existing images
        foreach ($images as $img) {
            // Only delete the database record, not the actual file
            $img->delete();
        }

        // Get the raw image path without the storage URL prefix
        $primaryImagePath = str_replace('/storage/', '', $image->image_url);

        // Re-add the selected image as the first one
        $primaryImage = new SaasProductImage();
        $primaryImage->product_id = $product->id;
        $primaryImage->fill(['image_url' => $primaryImagePath])->save();

        // Add the rest of the images
        foreach ($imageUrls as $url) {
            $newImage = new SaasProductImage();
            $newImage->product_id = $product->id;
            $newImage->fill(['image_url' => $url])->save();
        }

        return redirect()->route('seller.products.images.index', $product->id)
            ->with('success', 'Image set as primary (thumbnail) successfully');
    }
}
