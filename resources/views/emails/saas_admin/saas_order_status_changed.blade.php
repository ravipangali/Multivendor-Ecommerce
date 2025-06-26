<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update - Admin Notification</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .alert-box {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            margin: 10px 0;
        }
        .status-pending { background-color: #fef3cd; color: #856404; }
        .status-processing { background-color: #cce5ff; color: #004085; }
        .status-shipped { background-color: #d4edda; color: #155724; }
        .status-delivered { background-color: #d1ecf1; color: #0c5460; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        .status-refunded { background-color: #e2e3e5; color: #383d41; }
        .order-details {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .order-details h3 {
            margin-top: 0;
            color: #495057;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .item-info {
            flex: 1;
        }
        .item-name {
            font-weight: 600;
            color: #495057;
        }
        .item-details {
            font-size: 14px;
            color: #6c757d;
        }
        .item-price {
            font-weight: 600;
            color: #28a745;
        }
        .total-section {
            background-color: #e9ecef;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .total-row.final {
            font-weight: 600;
            font-size: 18px;
            color: #495057;
            border-top: 2px solid #dee2e6;
            padding-top: 10px;
            margin-top: 10px;
        }
        .button {
            display: inline-block;
            background-color: #dc3545;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .footer a {
            color: #dc3545;
            text-decoration: none;
        }
        @media (max-width: 600px) {
            body { padding: 10px; }
            .header, .content { padding: 20px; }
            .order-item { flex-direction: column; align-items: flex-start; }
            .item-price { margin-top: 10px; }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🔔 Order Status Update - Admin Alert</h1>
        </div>

        <div class="content">
            <div class="alert-box">
                <strong>📦 Order Status Change Alert</strong><br>
                Order <strong>#{{ $order->order_number }}</strong> status has been updated and requires your attention.
            </div>

            <div style="text-align: center; margin: 20px 0;">
                <div>
                    <strong>Previous Status:</strong>
                    <span class="status-badge status-{{ $previousStatus }}">{{ ucfirst($previousStatus) }}</span>
                </div>
                <div style="margin: 10px 0; font-size: 18px;">↓</div>
                <div>
                    <strong>Current Status:</strong>
                    <span class="status-badge status-{{ $order->order_status }}">{{ ucfirst($order->order_status) }}</span>
                </div>
            </div>

            @if($order->order_status === 'cancelled' && $order->cancellation_reason)
                <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 6px; margin: 20px 0;">
                    <strong>Cancellation Reason:</strong> {{ $order->cancellation_reason }}
                </div>
            @endif

            @if($order->admin_note)
                <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin: 20px 0;">
                    <strong>Admin Note:</strong> {{ $order->admin_note }}
                </div>
            @endif

            <div class="order-details">
                <h3>Order Overview</h3>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y g:i A') }}</p>
                <p><strong>Customer:</strong> {{ $order->customer->name }} ({{ $order->customer->email }})</p>
                @if($order->customer->phone)
                    <p><strong>Customer Phone:</strong> {{ $order->customer->phone }}</p>
                @endif
                <p><strong>Seller:</strong> {{ $order->seller->name ?? 'N/A' }} ({{ $order->seller->email ?? 'N/A' }})</p>
                <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
                <p><strong>Payment Method:</strong> {{ $order->payment_method ?? 'N/A' }}</p>
            </div>

            <div class="order-details">
                <h3>Shipping Information</h3>
                <p><strong>{{ $order->shipping_name }}</strong></p>
                @if($order->shipping_phone)
                    <p>📞 {{ $order->shipping_phone }}</p>
                @endif
                @if($order->shipping_email)
                    <p>📧 {{ $order->shipping_email }}</p>
                @endif
                <p>
                    📍 {{ $order->shipping_address }}<br>
                    {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}<br>
                    {{ $order->shipping_country }}
                </p>
            </div>

            <div class="order-details">
                <h3>Order Items</h3>
                @foreach($order->items as $item)
                    <div class="order-item">
                        <div class="item-info">
                            <div class="item-name">{{ $item->product->name }}</div>
                            <div class="item-details">
                                SKU: {{ $item->product->SKU }} | Quantity: {{ $item->quantity }} | Unit Price: Rs. {{ number_format($item->price, 2) }}
                                @if($item->productVariation)
                                    <br>Variation: {{ $item->productVariation->attribute->name }}: {{ $item->productVariation->attributeValue->value }}
                                @endif
                                <br>Seller: {{ $item->seller->name ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="item-price">Rs. {{ number_format($item->price * $item->quantity, 2) }}</div>
                    </div>
                @endforeach
            </div>

            <div class="total-section">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>Rs. {{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="total-row">
                        <span>Discount:</span>
                        <span>-Rs. {{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                @endif
                @if($order->coupon_code)
                    <div class="total-row">
                        <span>Coupon ({{ $order->coupon_code }}):</span>
                        <span>Applied</span>
                    </div>
                @endif
                @if($order->shipping_fee > 0)
                    <div class="total-row">
                        <span>Shipping Fee:</span>
                        <span>Rs. {{ number_format($order->shipping_fee, 2) }}</span>
                    </div>
                @endif
                @if($order->tax_amount > 0)
                    <div class="total-row">
                        <span>Tax:</span>
                        <span>Rs. {{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                @endif
                <div class="total-row final">
                    <span>Total Order Value:</span>
                    <span>Rs. {{ number_format($order->total, 2) }}</span>
                </div>
            </div>

            @if($order->order_status === 'cancelled')
                <div class="alert-box">
                    <strong>⚠️ Order Cancelled</strong><br>
                    This order has been cancelled. Please review if any refunds need to be processed and monitor seller inventory adjustments.
                </div>
            @elseif($order->order_status === 'delivered')
                <div class="alert-box">
                    <strong>✅ Order Delivered</strong><br>
                    This order has been successfully delivered. Payment processing and commission calculations should be reviewed.
                </div>
            @elseif($order->order_status === 'refunded')
                <div class="alert-box">
                    <strong>💰 Order Refunded</strong><br>
                    This order has been refunded. Please ensure the refund process is completed and monitor the financial impact.
                </div>
            @endif

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('admin.orders.show', $order->id) }}" class="button">Review Order in Admin Panel</a>
            </div>

            <p>This notification was sent because an order status has changed in your e-commerce platform. Please review the order and take any necessary actions.</p>
        </div>

        <div class="footer">
            <p>Admin Dashboard | Order Management System</p>
            <p>
                <a href="{{ route('admin.orders.index') }}">View All Orders</a> |
                <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
            </p>
            <p>This is an automated notification from your e-commerce platform.</p>
        </div>
    </div>
</body>
</html>
