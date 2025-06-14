<?php

namespace App\Http\Controllers\SaasCustomer;

use App\Http\Controllers\Controller;
use App\Mail\SaasOrderConfirmation;
use App\Mail\SaasVendorOrderNotification;
use App\Models\SaasCart;
use App\Models\SaasCoupon;
use App\Models\SaasOrder;
use App\Models\SaasOrderItem;
use App\Models\SaasPaymentMethod;
use App\Services\SaasCartCalculationService;
use App\Services\SaasShippingService;
use App\Services\SaasTaxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class SaasCustomerCheckoutController extends Controller
{
    protected $cartCalculationService;
    protected $shippingService;
    protected $taxService;

    public function __construct(SaasCartCalculationService $cartCalculationService, SaasShippingService $shippingService, SaasTaxService $taxService)
    {
        $this->cartCalculationService = $cartCalculationService;
        $this->shippingService = $shippingService;
        $this->taxService = $taxService;
    }

    public function saasIndex()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to checkout.');
        }

        $cartItems = SaasCart::where('user_id', Auth::id())
            ->with(['product.images', 'product.brand', 'productVariation'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart')->with('error', 'Your cart is empty.');
        }

        // Check for applied coupon in session
        $appliedCoupon = null;
        if (session('applied_coupon_code')) {
            $appliedCoupon = SaasCoupon::where('code', session('applied_coupon_code'))->first();

            // If coupon doesn't exist or is invalid, remove from session
            if (!$appliedCoupon || !$appliedCoupon->isValid()) {
                session()->forget('applied_coupon_code');
                $appliedCoupon = null;
            }
        }

        // Calculate totals using the service
        $totals = $this->cartCalculationService->calculateCartTotals($cartItems, $appliedCoupon);

        // Extract individual values for backward compatibility
        $cartSubtotal = $totals['subtotal'];
        $shippingFee = $totals['shipping_cost'];
        $tax = $totals['tax_amount'];
        $grandTotal = $totals['grand_total'];

        // Get customer profile for shipping address
        $customer = Auth::user();
        $customerProfile = $customer->customerProfile;

        // Get available payment methods
        $paymentMethods = SaasPaymentMethod::where('is_active', true)->get();

        return view('saas_customer.saas_checkout', compact(
            'cartItems',
            'cartSubtotal',
            'shippingFee',
            'tax',
            'grandTotal',
            'customer',
            'customerProfile',
            'paymentMethods'
        ));
    }

    public function saasProcess(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to checkout.');
        }

        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:100',
            'payment_method' => 'required|in:cash_on_delivery,bank_transfer,esewa,khalti',
            'order_notes' => 'nullable|string|max:1000'
        ]);

        $cartItems = SaasCart::where('user_id', Auth::id())
            ->with(['product', 'productVariation'])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart')->with('error', 'Your cart is empty.');
        }

        // Validate stock availability
        foreach ($cartItems as $item) {
            $availableStock = $item->productVariation ? $item->productVariation->stock : $item->product->stock;
            if ($item->quantity > $availableStock) {
                return back()->with('error', "Insufficient stock for {$item->product->name}. Available: {$availableStock}");
            }
        }

        // Calculate totals using services and settings - use final price (includes product discounts)
        $cartSubtotal = $cartItems->sum(function($item) {
            $price = $item->productVariation ? $item->productVariation->final_price : $item->product->final_price;
            return $price * $item->quantity;
        });

        // Use services for proper tax and shipping calculation based on settings
        $shippingFee = $this->shippingService->calculateShippingCost($cartItems, $cartSubtotal, $request->all());
        $tax = $this->taxService->calculateTax($cartItems, $cartSubtotal, $request->all(), $shippingFee);

        // Calculate coupon discount if coupon is applied
        $couponCode = null;
        $couponDiscountAmount = 0;
        $couponDiscountType = null;

        // Check for coupon in request or session
        $appliedCouponCode = $request->coupon_code ?? session('applied_coupon_code');

        if (!empty($appliedCouponCode)) {
            $coupon = SaasCoupon::where('code', $appliedCouponCode)->first();

            if ($coupon && $coupon->isValid()) {
                $couponCode = $coupon->code;
                $couponDiscountType = $coupon->discount_type;

                if ($coupon->discount_type === 'percentage') {
                    $couponDiscountAmount = round(($cartSubtotal * $coupon->discount_value) / 100, 2);
                    // Cap at maximum discount if set
                    if (isset($coupon->max_discount_amount) && $couponDiscountAmount > $coupon->max_discount_amount) {
                        $couponDiscountAmount = $coupon->max_discount_amount;
                    }
                } else {
                    $couponDiscountAmount = min($coupon->discount_value, $cartSubtotal);
                }

                // Apply minimum order amount check
                if (isset($coupon->min_order_amount) && $cartSubtotal < $coupon->min_order_amount) {
                    $couponDiscountAmount = 0;
                    $couponCode = null;
                    $couponDiscountType = null;
                }
            }
        }

        $discount = $couponDiscountAmount;

        $grandTotal = $cartSubtotal + $shippingFee + $tax - $discount;

        DB::beginTransaction();

        try {
            // Log general cart information
            \Illuminate\Support\Facades\Log::info('Starting checkout process', [
                'customer_id' => Auth::id(),
                'cart_items_count' => $cartItems->count(),
                'cart_subtotal' => $cartSubtotal,
                'shipping_fee' => $shippingFee,
                'tax' => $tax,
                'discount' => $discount,
                'grand_total' => $grandTotal
            ]);

            // Debug the schema
            $dbColumns = Schema::getColumnListing('saas_orders');
            \Illuminate\Support\Facades\Log::info('Available columns in saas_orders table:', $dbColumns);

            // Group cart items by seller
            $sellerCartItems = $cartItems->groupBy('product.seller_id');
            $createdOrders = [];
            $isFirstSeller = true;

            foreach ($sellerCartItems as $sellerId => $sellerItems) {
                // Log detailed information to help diagnose issues
                \Illuminate\Support\Facades\Log::info('Processing order for seller: ' . $sellerId);
                \Illuminate\Support\Facades\Log::info('Items count: ' . count($sellerItems));

                $sellerSubtotal = $sellerItems->sum(function($item) {
                    $price = $item->productVariation ? $item->productVariation->final_price : $item->product->final_price;
                    return $price * $item->quantity;
                });

                // Calculate seller's proportion of total (avoid division by zero)
                $sellerProportion = $cartSubtotal > 0 ? $sellerSubtotal / $cartSubtotal : 0;

                // Calculate seller's share of shipping, tax, discount
                $sellerShipping = round($shippingFee * $sellerProportion, 2);
                $sellerTax = round($tax * $sellerProportion, 2);
                $sellerDiscount = round($discount * $sellerProportion, 2);
                $sellerTotal = round($sellerSubtotal + $sellerShipping + $sellerTax - $sellerDiscount, 2);

                \Illuminate\Support\Facades\Log::info('Creating order with values:', [
                    'customer_id' => Auth::id(),
                    'seller_id' => $sellerId,
                    'subtotal' => $sellerSubtotal,
                    'shipping_fee' => $sellerShipping,
                    'tax' => $sellerTax,
                    'discount' => $sellerDiscount,
                    'total' => $sellerTotal,
                ]);

                // Format the shipping address to include all shipping details
                // Create separate order for each seller
                $order = SaasOrder::create([
                    'customer_id' => Auth::id(),
                    'seller_id' => $sellerId,
                    'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                    'subtotal' => $sellerSubtotal,
                    'shipping_fee' => $sellerShipping,
                    'tax' => $sellerTax,
                    'discount' => $sellerDiscount,
                    'total' => $sellerTotal,
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'pending',
                    'order_status' => 'pending',
                    'placed_at' => now(),
                    'order_notes' => $request->order_notes,
                    // Individual shipping address fields
                    'shipping_name' => $request->shipping_name,
                    'shipping_email' => $request->shipping_email,
                    'shipping_phone' => $request->shipping_phone,
                    'shipping_country' => $request->shipping_country,
                    'shipping_street_address' => $request->shipping_address,
                    'shipping_city' => $request->shipping_city,
                    'shipping_state' => $request->shipping_state,
                    'shipping_postal_code' => $request->shipping_postal_code,
                    // Individual billing address fields (same as shipping for now)
                    'billing_name' => $request->shipping_name,
                    'billing_email' => $request->shipping_email,
                    'billing_phone' => $request->shipping_phone,
                    'billing_country' => $request->shipping_country,
                    'billing_street_address' => $request->shipping_address,
                    'billing_city' => $request->shipping_city,
                    'billing_state' => $request->shipping_state,
                    'billing_postal_code' => $request->shipping_postal_code,
                    // Coupon information - only apply to first seller to avoid duplication
                    'coupon_code' => $isFirstSeller ? $couponCode : null,
                    'coupon_discount_amount' => $isFirstSeller ? $couponDiscountAmount : 0,
                    'coupon_discount_type' => $isFirstSeller ? $couponDiscountType : null,
                ]);

                // Create order items for this seller
                foreach ($sellerItems as $cartItem) {
                    $price = $cartItem->productVariation ? $cartItem->productVariation->final_price : $cartItem->product->final_price;

                    // Calculate item-level tax and discount
                    $itemSubtotal = $price * $cartItem->quantity;
                    $itemTaxRate = $sellerSubtotal > 0 ? $sellerTax / $sellerSubtotal : 0;
                    $itemDiscountRate = $sellerSubtotal > 0 ? $sellerDiscount / $sellerSubtotal : 0;

                    $itemTax = round($itemSubtotal * $itemTaxRate, 2);
                    $itemDiscount = round($itemSubtotal * $itemDiscountRate, 2);

                    // Make sure we get the right variation ID from the cart item
                    $variationId = null;
                    if (isset($cartItem->variation_id)) {
                        $variationId = $cartItem->variation_id;
                    } elseif (isset($cartItem->product_variation_id)) {
                        $variationId = $cartItem->product_variation_id;
                    }

                    SaasOrderItem::create([
                        'order_id' => $order->id,
                        'seller_id' => $sellerId,
                        'product_id' => $cartItem->product_id,
                        'variation_id' => $variationId,
                        'quantity' => $cartItem->quantity,
                        'price' => $price,
                        'discount' => $itemDiscount,
                        'tax' => $itemTax,
                        'status' => 'pending',
                    ]);

                    // Update stock
                    if ($cartItem->productVariation) {
                        $cartItem->productVariation->decrement('stock', $cartItem->quantity);
                    } else {
                        $cartItem->product->decrement('stock', $cartItem->quantity);
                    }
                }

                $createdOrders[] = $order;
                $isFirstSeller = false; // Only first seller gets coupon tracking
            }

            // Clear cart
            SaasCart::where('user_id', Auth::id())->delete();

            // Increment coupon usage if coupon was applied
            if ($couponCode) {
                $coupon = SaasCoupon::where('code', $couponCode)->first();
                if ($coupon) {
                    $coupon->increment('used_count');
                    \Illuminate\Support\Facades\Log::info('Coupon usage incremented', [
                        'coupon_code' => $coupon->code,
                        'new_used_count' => $coupon->used_count
                    ]);
                }

                // Clear coupon from session after successful order
                session()->forget('applied_coupon_code');
            }

            DB::commit();

            // Send confirmation emails
            $this->sendOrderConfirmationEmails($createdOrders);

            // Store created orders in session for success page
            session(['created_orders' => collect($createdOrders)->pluck('id')->toArray()]);

            // Redirect to payment gateway or success page
            if ($request->payment_method === 'cash_on_delivery') {
                return redirect()->route('customer.checkout.success')
                    ->with('success', 'Orders placed successfully! You will pay on delivery.');
            }

            // For other payment methods, you would redirect to payment gateway
            // For now, we'll just redirect to success page
            return redirect()->route('customer.checkout.success')
                ->with('success', 'Orders placed successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            \Illuminate\Support\Facades\Log::error('Checkout Error: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error('Stack trace: ' . $e->getTraceAsString());

            // Log the SQL that might have caused the error
            if ($e instanceof \Illuminate\Database\QueryException) {
                \Illuminate\Support\Facades\Log::error('SQL Error: ' . $e->getSql());
                \Illuminate\Support\Facades\Log::error('SQL Bindings: ' . json_encode($e->getBindings()));
            }

            // Provide more friendly error message but log the details
            $errorMsg = 'An error occurred while processing your order.';
            if (app()->environment('local', 'development', 'staging')) {
                $errorMsg .= ' Error: ' . $e->getMessage();
            }

            // Also log form data for debugging (removing sensitive info)
            $requestData = $request->except(['password', 'card_number', 'cvv']);
            \Illuminate\Support\Facades\Log::error('Form data: ' . json_encode($requestData));

            return back()->withInput()->with('error', $errorMsg);
        }
    }

    public function saasSuccess()
    {
        $orderIds = session('created_orders', []);
        session()->forget('created_orders');

        if (empty($orderIds)) {
            return redirect()->route('customer.cart')->with('error', 'No orders found.');
        }

        $orders = SaasOrder::whereIn('id', $orderIds)
            ->with(['customer', 'seller', 'items.product', 'items.productVariation.attributeValues.attribute'])
            ->get();

        return view('saas_customer.saas_checkout_success', compact('orders'));
    }

    public function saasCancel()
    {
        return view('saas_customer.saas_checkout_cancel');
    }

    /**
     * Send order confirmation emails to customers and vendors
     */
    private function sendOrderConfirmationEmails($orders)
    {
        try {
            foreach ($orders as $order) {
                // Load necessary relationships
                $order->load(['customer', 'seller', 'items.product']);

                // Send confirmation email to customer
                if ($order->customer && $order->customer->email) {
                    Mail::to($order->customer->email)->send(new SaasOrderConfirmation($order));
                }

                // Send notification email to vendor/seller
                if ($order->seller && $order->seller->email) {
                    Mail::to($order->seller->email)->send(new SaasVendorOrderNotification($order));
                }
            }
        } catch (\Exception $e) {
            // Log the error but don't fail the order process
            \Illuminate\Support\Facades\Log::error('Failed to send order confirmation emails: ' . $e->getMessage());
        }
    }
}
