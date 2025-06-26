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
use Illuminate\Support\Facades\Log;

use App\Models\SaasProduct;
use App\Models\SaasSetting;
use App\Models\SaasTransaction;
use App\Mail\SaasOrderStatusChanged;

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

        // Initial validation for shipping and payment
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_state' => 'required|string|max:255',
            'shipping_postal_code' => 'required|string|max:20',
            'payment_method' => 'required|string|in:cash_on_delivery,esewa,khalti,bank_transfer',
        ]);

        // Billing validation - if same_as_shipping is not checked, validate billing fields
        if (!$request->has('same_as_shipping') || !$request->boolean('same_as_shipping')) {
            $request->validate([
                'billing_name' => 'required|string|max:255',
                'billing_email' => 'required|email|max:255',
                'billing_phone' => 'required|string|max:20',
                'billing_country' => 'required|string|max:255',
                'billing_address' => 'required|string|max:255',
                'billing_city' => 'required|string|max:255',
                'billing_state' => 'required|string|max:255',
                'billing_postal_code' => 'required|string|max:20',
            ]);
        }

        $cartTotals = $this->cartCalculationService->getCartTotals(auth()->id(), session()->getId());
        $cartItems = $this->cartCalculationService->getCartItems(auth()->id(), session()->getId());

        if ($cartItems->isEmpty()) {
            return redirect()->route('saas.customer.cart.index')
                ->with('warning', 'Your cart is empty!');
        }

        // Check for insufficient stock before processing
        foreach ($cartItems as $item) {
            if ($item->hasInsufficientStock()) {
                return redirect()->route('saas.customer.cart.index')
                    ->with('error', 'Not enough stock for ' . $item->product->name . '. Available: ' . $item->getAvailableStock());
            }
        }

        // Handle coupon
        $coupon = null;
        if ($request->filled('coupon_code')) {
            $coupon = SaasCoupon::where('code', $request->coupon_code)->first();
            if (!$coupon || !$coupon->isValid()) {
                return back()->withInput()
                    ->with('error', 'Invalid or expired coupon code!');
            }
        }

        // Determine seller_id for the main order
        $orderSellerId = $this->determineOrderSellerId($cartItems);

        // Prepare order data with corrected field names
        $orderData = [
            'customer_id' => auth()->id(),
            'order_number' => SaasOrder::generateOrderNumber(),
            'subtotal' => $cartTotals['subtotal'] ?? 0,
            'shipping_fee' => $cartTotals['shipping_fee'] ?? 0,
            'tax' => $cartTotals['tax'] ?? 0,
            'discount' => $cartTotals['discount'] ?? 0,
            'total' => $cartTotals['total'] ?? 0,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'placed_at' => now(),
            'order_notes' => $request->order_notes,

            // Shipping information - use the correct field names
            'shipping_name' => $request->shipping_name,
            'shipping_email' => $request->shipping_email,
            'shipping_phone' => $request->shipping_phone,
            'shipping_country' => $request->shipping_country,
            'shipping_street_address' => $request->shipping_address,
            'shipping_city' => $request->shipping_city,
            'shipping_state' => $request->shipping_state,
            'shipping_postal_code' => $request->shipping_postal_code,

            // Billing information - if same_as_shipping is checked, use shipping data, else use provided billing data
            'billing_name' => $request->boolean('same_as_shipping') ? $request->shipping_name : $request->billing_name,
            'billing_email' => $request->boolean('same_as_shipping') ? $request->shipping_email : $request->billing_email,
            'billing_phone' => $request->boolean('same_as_shipping') ? $request->shipping_phone : $request->billing_phone,
            'billing_country' => $request->boolean('same_as_shipping') ? $request->shipping_country : $request->billing_country,
            'billing_street_address' => $request->boolean('same_as_shipping') ? $request->shipping_address : $request->billing_address,
            'billing_city' => $request->boolean('same_as_shipping') ? $request->shipping_city : $request->billing_city,
            'billing_state' => $request->boolean('same_as_shipping') ? $request->shipping_state : $request->billing_state,
            'billing_postal_code' => $request->boolean('same_as_shipping') ? $request->shipping_postal_code : $request->billing_postal_code,

            // Coupon information - ensure nulls instead of empty strings
            'coupon_code' => isset($cartTotals['coupon_code']) && !empty($cartTotals['coupon_code']) ? $cartTotals['coupon_code'] : null,
            'coupon_discount_amount' => $cartTotals['coupon_discount_amount'] ?? 0,
            'coupon_discount_type' => isset($cartTotals['coupon_discount_type']) && !empty($cartTotals['coupon_discount_type']) ? $cartTotals['coupon_discount_type'] : null,
        ];

        // Only add seller_id if it's not null
        if ($orderSellerId !== null) {
            $orderData['seller_id'] = $orderSellerId;
        }

        try {
            DB::beginTransaction();
            Log::info('Starting order creation process');

            $order = SaasOrder::create($orderData);
            Log::info('Order created successfully', ['order_id' => $order->id]);

            // Create order items
            Log::info('Starting order items creation', ['cart_items_count' => $cartItems->count()]);
            foreach ($cartItems as $item) {
                // Deduct stock
                $product = SaasProduct::find($item->product_id);

                if ($product->has_variations && $item->variation_id) {
                    $variation = $product->variations()->find($item->variation_id);
                    if ($variation) {
                        $variation->decrement('stock', $item->quantity);
                    }
                } else {
                    $product->decrement('stock', $item->quantity);
                }

                // Calculate item price - prioritize variation price, then product price, then cart price
                $itemPrice = $item->price;
                if (!$itemPrice) {
                    if ($item->productVariation) {
                        $itemPrice = $item->productVariation->final_price;
                    } elseif ($item->product) {
                        $itemPrice = $item->product->final_price;
                    } else {
                        $itemPrice = 0;
                    }
                }

                Log::info('Creating order item', ['product_id' => $item->product_id, 'price' => $itemPrice]);
                SaasOrderItem::create([
                    'order_id' => $order->id,
                    'seller_id' => $item->product->seller_id,
                    'product_id' => $item->product_id,
                    'variation_id' => $item->variation_id,
                    'quantity' => $item->quantity,
                    'price' => $itemPrice,
                    'discount' => $item->product->discount ?? 0,
                    'tax' => $this->taxService->calculateProductTax($itemPrice, $item->quantity),
                    'status' => 'pending',
                ]);
                Log::info('Order item created successfully');
            }

            // If a coupon was used, increment its used count
            if ($coupon) {
                Log::info('Incrementing coupon usage', ['coupon_code' => $coupon->code]);
                $coupon->incrementUsedCount();
                Log::info('Coupon usage incremented successfully');
            }

            // Clear the cart
            Log::info('Clearing cart');
            $this->cartCalculationService->clearCart(auth()->id(), session()->getId());
            Log::info('Cart cleared successfully');

            DB::commit();
            Log::info('Transaction committed successfully');

            // Send order confirmation email after commit (so order is saved even if email fails)
            // Temporarily commented out to isolate checkout issues
            // try {
            //     $this->sendOrderConfirmationEmails($order);
            // } catch (\Exception $emailException) {
            //     Log::warning('Order confirmation email failed: ' . $emailException->getMessage());
            // }

            return redirect()->route('customer.checkout.success')
                ->with('success', 'Your order has been placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order processing failed: ' . $e->getMessage(), [
                'exception' => $e,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'orderData' => $orderData,
                'cartItems' => $cartItems->toArray()
            ]);
            return back()->withInput()
                ->with('error', 'An error occurred while processing your order. Please try again. Error: ' . $e->getMessage());
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
        return view('saas_customer.saas_checkout_cancel')
            ->with('error', 'Your order was canceled.');
    }

    /**
     * Send order confirmation emails to customers and vendors
     */
    private function sendOrderConfirmationEmails($order)
    {
        try {
            // Load necessary relationships
            $order->load(['customer', 'seller', 'items.product']);

            // Send confirmation email to customer
            if ($order->customer && $order->customer->email) {
                Mail::to($order->customer->email)->send(new SaasOrderConfirmation($order));
            }

            // Send notification email to vendor/seller
            if ($order->seller && $order->seller->email) {
                Mail::to($order->seller->email)->send(new SaasOrderStatusChanged($order, $order->seller, 'New Order Placed'));
            }
        } catch (\Exception $e) {
            // Log the error but don't fail the order process
            Log::error('Failed to send order confirmation emails: ' . $e->getMessage());
        }
    }

    /**
     * Determine the seller_id for the main order based on cart items
     */
    private function determineOrderSellerId($cartItems)
    {
        $sellerIds = [];
        $hasInHouseProducts = false;

        foreach ($cartItems as $item) {
            if ($item->product->is_in_house_product || $item->product->seller_id === null) {
                $hasInHouseProducts = true;
            } else {
                $sellerIds[] = $item->product->seller_id;
            }
        }

        // Remove duplicates
        $uniqueSellerIds = array_unique($sellerIds);

        // If there are in-house products or multiple sellers, return null
        if ($hasInHouseProducts || count($uniqueSellerIds) !== 1) {
            return null;
        }

        // If all products are from a single seller, return that seller_id
        return count($uniqueSellerIds) === 1 ? $uniqueSellerIds[0] : null;
    }
}
