<?php

namespace App\Http\Controllers\SaasCustomer;

use App\Http\Controllers\Controller;
use App\Models\SaasOrder;
use App\Models\SaasProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class SaasDigitalProductController extends Controller
{
    /**
     * Download a digital product file.
     */
    public function download(Request $request, $orderId, $productId)
    {
        $user = Auth::user();

        // Find the order
        $order = SaasOrder::where('id', $orderId)
            ->where('customer_id', $user->id)
            ->first();

        if (!$order) {
            abort(404, 'Order not found.');
        }

        // Check if order can download digital products
        if (!$order->canDownloadDigitalProducts()) {
            return back()->with('error', 'Digital products can only be downloaded when order is delivered and payment is confirmed.');
        }

        // Find the product
        $product = SaasProduct::where('id', $productId)
            ->where('product_type', 'Digital')
            ->whereNotNull('file')
            ->first();

        if (!$product) {
            abort(404, 'Digital product not found.');
        }

        // Check if product is in the order
        $orderItem = $order->items()->where('product_id', $productId)->first();
        if (!$orderItem) {
            abort(403, 'Product not found in your order.');
        }

        // Check if file exists
        if (!Storage::disk('public')->exists($product->file)) {
            return back()->with('error', 'Digital product file not found.');
        }

        // Log the download (optional)
        Log::info('Digital product downloaded', [
            'user_id' => $user->id,
            'order_id' => $orderId,
            'product_id' => $productId,
            'product_name' => $product->name,
        ]);

        // Return file download response
        $filePath = storage_path('app/public/' . $product->file);
        $fileName = $product->name . '.' . pathinfo($product->file, PATHINFO_EXTENSION);

        return response()->download($filePath, $fileName);
    }

    /**
     * Get list of downloadable digital products for an order.
     */
    public function getDownloadableProducts($orderId)
    {
        $user = Auth::user();

        $order = SaasOrder::where('id', $orderId)
            ->where('customer_id', $user->id)
            ->first();

        if (!$order) {
            abort(404, 'Order not found.');
        }

        $downloadableProducts = $order->getDownloadableDigitalProducts();

        return response()->json([
            'can_download' => $order->canDownloadDigitalProducts(),
            'products' => $downloadableProducts->map(function ($product) use ($orderId) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'download_url' => route('customer.digital-product.download', [
                        'orderId' => $orderId,
                        'productId' => $product->id
                    ])
                ];
            })
        ]);
    }

    /**
     * Stream/preview a digital product file (for certain file types).
     */
    public function preview(Request $request, $orderId, $productId)
    {
        $user = Auth::user();

        // Find the order
        $order = SaasOrder::where('id', $orderId)
            ->where('customer_id', $user->id)
            ->first();

        if (!$order) {
            abort(404, 'Order not found.');
        }

        // Check if order can download digital products
        if (!$order->canDownloadDigitalProducts()) {
            abort(403, 'Digital products can only be accessed when order is delivered and payment is confirmed.');
        }

        // Find the product
        $product = SaasProduct::where('id', $productId)
            ->where('product_type', 'Digital')
            ->whereNotNull('file')
            ->first();

        if (!$product) {
            abort(404, 'Digital product not found.');
        }

        // Check if product is in the order
        $orderItem = $order->items()->where('product_id', $productId)->first();
        if (!$orderItem) {
            abort(403, 'Product not found in your order.');
        }

        // Check if file exists
        if (!Storage::disk('public')->exists($product->file)) {
            abort(404, 'Digital product file not found.');
        }

        $filePath = storage_path('app/public/' . $product->file);
        $mimeType = mime_content_type($filePath);

        // Only allow preview for certain file types
        $previewableTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif', 'text/plain'];

        if (!in_array($mimeType, $previewableTypes)) {
            return back()->with('error', 'This file type cannot be previewed. Please download it instead.');
        }

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($product->file) . '"'
        ]);
    }
}
