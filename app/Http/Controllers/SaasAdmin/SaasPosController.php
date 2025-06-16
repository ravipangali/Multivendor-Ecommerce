<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasProduct;
use App\Models\SaasOrder;
use App\Models\SaasOrderItem;
use App\Models\SaasInHouseSale;
use App\Models\SaasInHouseSaleItem;
use App\Models\User;
use App\Models\SaasCategory;
use App\Models\SaasBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaasPosController extends Controller
{
    /**
     * Display the POS interface.
     */
    public function index()
    {
        $categories = SaasCategory::all();
        $brands = SaasBrand::all();
        $products = SaasProduct::with(['images', 'category', 'brand'])
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->where('is_in_house_product', true)
            ->paginate(20);

        return view('saas_admin.saas_pos.saas_index', compact('categories', 'brands', 'products'));
    }

    /**
     * Search products for POS.
     */
    public function searchProducts(Request $request)
    {
        $query = SaasProduct::with(['images', 'category', 'brand'])
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->where('is_in_house_product', true);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('SKU', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        $products = $query->paginate(20);

        return response()->json([
            'products' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
            ]
        ]);
    }

    /**
     * Get product details for POS.
     */
    public function getProduct($id)
    {
        $product = SaasProduct::with(['images', 'category', 'brand', 'variations'])
            ->where('is_active', true)
            ->findOrFail($id);

        return response()->json($product);
    }

    /**
     * Process POS sale.
     */
    public function processSale(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'customer_address' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:saas_products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,card,bank_transfer,mobile_payment',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:flat,percentage',
            'tax_amount' => 'nullable|numeric|min:0',
            'shipping_amount' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Calculate subtotal from items
            $subtotal = 0;
            foreach ($request->items as $item) {
                $itemTotal = $item['price'] * $item['quantity'];
                if (isset($item['discount']) && $item['discount'] > 0) {
                    $itemTotal -= $item['discount'];
                }
                $subtotal += $itemTotal;
            }

            // Calculate totals
            $discountAmount = 0;
            $discountType = 'flat';

            // Handle discount from POS (comes as 'discount' not 'discount_amount')
            $discountValue = $request->discount ?? $request->discount_amount ?? 0;
            if ($discountValue > 0) {
                $discountType = $request->discount_type ?? 'flat';
                if ($discountType === 'percentage') {
                    $discountAmount = ($subtotal * $discountValue) / 100;
                } else {
                    $discountAmount = $discountValue;
                }
            }

            $taxAmount = $request->tax ?? $request->tax_amount ?? 0;
            $shippingAmount = $request->shipping_amount ?? 0;
            $totalAmount = $subtotal - $discountAmount + $taxAmount + $shippingAmount;
            $paidAmount = $request->paid_amount;
            $dueAmount = $totalAmount - $paidAmount;

            // Determine payment status
            $paymentStatus = 'paid';
            if ($paidAmount < $totalAmount) {
                $paymentStatus = $paidAmount > 0 ? 'partial' : 'pending';
            }

            // Create in-house sale
            $sale = new SaasInHouseSale();
            $sale->sale_number = SaasInHouseSale::generateSaleNumber();
            $sale->customer_name = $request->customer_name;
            $sale->customer_phone = $request->customer_phone;
            $sale->customer_email = $request->customer_email;
            $sale->customer_address = $request->customer_address;
            $sale->subtotal = $subtotal;
            $sale->discount_amount = $discountAmount;
            $sale->discount_type = $discountType;
            $sale->tax_amount = $taxAmount;
            $sale->shipping_amount = $shippingAmount;
            $sale->total_amount = $totalAmount;
            $sale->payment_method = $request->payment_method;
            $sale->payment_status = $paymentStatus;
            $sale->paid_amount = $paidAmount;
            $sale->due_amount = $dueAmount;
            $sale->notes = $request->notes;
            $sale->cashier_id = Auth::id();
            $sale->sale_date = now();
            $sale->save();

            // Create sale items and update stock
            foreach ($request->items as $item) {
                $product = SaasProduct::findOrFail($item['product_id']);

                // Check stock availability
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                // Calculate item total price
                $itemSubtotal = $item['price'] * $item['quantity'];
                $itemDiscount = $item['discount'] ?? 0;
                $itemTotalPrice = $itemSubtotal - $itemDiscount;

                // Create sale item
                $saleItem = new SaasInHouseSaleItem();
                $saleItem->sale_id = $sale->id;
                $saleItem->product_id = $product->id;
                $saleItem->variation_id = $item['variation_id'] ?? null;
                $saleItem->product_name = $product->name;
                $saleItem->product_sku = $product->SKU;
                $saleItem->unit_price = $item['price'];
                $saleItem->quantity = $item['quantity'];
                $saleItem->discount_amount = $itemDiscount;
                $saleItem->discount_type = 'flat'; // Item-level discounts are always flat
                $saleItem->total_price = $itemTotalPrice;
                $saleItem->save();

                // Update product stock
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sale processed successfully',
                'sale' => $sale,
                'sale_number' => $sale->sale_number,
                'order_number' => $sale->sale_number // For compatibility with frontend
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process sale: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Print receipt for POS sale.
     */
    public function printReceipt($saleNumber)
    {
        $sale = SaasInHouseSale::with(['saleItems.product', 'cashier'])
            ->where('sale_number', $saleNumber)
            ->firstOrFail();

        return view('saas_admin.saas_pos.saas_receipt', compact('sale'));
    }
}
