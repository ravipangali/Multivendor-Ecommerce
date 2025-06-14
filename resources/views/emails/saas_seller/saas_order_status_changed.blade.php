<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update - Seller Notification</title>
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
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
            margin-bottom: 5px;
        }
        .item-details {
            font-size: 14px;
            color: #6c757d;
        }
        .item-price {
            font-weight: 600;
            color: #495057;
        }
        .total-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .total-row.final {
            font-weight: 600;
            font-size: 18px;
            color: #495057;
            border-top: 2px solid #dee2e6;
            padding-top: 10px;
            margin-top: 15px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }
        .footer a {
            color: #28a745;
            text-decoration: none;
        }
        .seller-alert {
            background-color: #e8f5e8;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
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
            <h1>Order Status Update - Seller Portal</h1>
        </div>

        <div class="content">
            <p>Hello {{ $order->seller->name }},</p>

            <div class="seller-alert">
                <strong>📦 Order Status Change Notification</strong><br>
                One of your orders has been updated by {{ $recipient === 'admin' ? 'an administrator' : 'the customer' }}.
            </div>

            <p>Order <strong>#{{ $order->order_number }}</strong> status has been updated.</p>

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
                <h3>Order Information</h3>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>
                <p><strong>Customer:</strong> {{ $order->customer->name }} ({{ $order->customer->email }})</p>
                <p><strong>Order Total:</strong> Rs. {{ number_format($order->total, 2) }}</p>
                @if($order->customer->phone)
                    <p><strong>Customer Phone:</strong> {{ $order->customer->phone }}</p>
                @endif
            </div>

            <div class="order-details">
                <h3>Items Sold</h3>
                @foreach($order->items as $item)
                    <div class="order-item">
                        <div class="item-info">
                            <div class="item-name">{{ $item->product->name }}</div>
                            <div class="item-details">
                                Quantity: {{ $item->quantity }} | Unit Price: Rs. {{ number_format($item->price, 2) }}
                                @if($item->productVariation)
                                    <br>Variation: {{ $item->productVariation->attribute->name }}: {{ $item->productVariation->attributeValue->value }}
                                @endif
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
                    <span>Total Revenue:</span>
                    <span>Rs. {{ number_format($order->total, 2) }}</span>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('seller.orders.show', $order->id) }}" class="button">Manage Order</a>
            </div>

            @if($order->order_status === 'cancelled')
                <div class="seller-alert">
                    <strong>⚠️ Action Required:</strong><br>
                    This order has been cancelled. Please ensure any stock adjustments have been properly handled and review your inventory levels.
                </div>
            @elseif($order->order_status === 'delivered')
                <div class="seller-alert">
                    <strong>✅ Congratulations!</strong><br>
                    This order has been marked as delivered. Payment processing will begin shortly.
                </div>
            @endif

            <p>Thank you for being a valued seller on our platform!</p>
        </div>

        <div class="footer">
            <p>Questions about this order? Contact our seller support team.</p>
            <p>
                <a href="mailto:seller-support@example.com">seller-support@example.com</a> |
                <a href="tel:+1234567890">+1 (234) 567-890</a>
            </p>
        </div>
    </div>
</body>
</html>
