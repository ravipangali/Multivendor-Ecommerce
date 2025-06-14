<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .order-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .total-section {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .shipping-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Confirmation</h1>
            <p>Thank you for your order!</p>
        </div>

        <p>Dear {{ $customer->name }},</p>

        <p>We have received your order and it is being processed. Here are the details:</p>

        <div class="order-details">
            <h3>Order Information</h3>
            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y g:i A') }}</p>
            <p><strong>Order Status:</strong> {{ ucfirst($order->order_status) }}</p>
            <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
        </div>

        <h3>Order Items</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Variation</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>
                        @if($item->productVariation)
                            {{ $item->productVariation->attribute->name ?? '' }}:
                            {{ $item->productVariation->attributeValue->value ?? '' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rs. {{ number_format($item->price, 2) }}</td>
                    <td>Rs. {{ number_format(($item->price * $item->quantity) + $item->tax - $item->discount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="border: none; text-align: right; padding: 5px 0;">
                        <strong>Subtotal:</strong>
                    </td>
                    <td style="border: none; text-align: right; padding: 5px 0;">
                        Rs. {{ number_format($order->subtotal, 2) }}
                    </td>
                </tr>
                @if($order->discount > 0)
                <tr>
                    <td style="border: none; text-align: right; padding: 5px 0;">
                        <strong>Discount:</strong>
                    </td>
                    <td style="border: none; text-align: right; padding: 5px 0;">
                        - Rs. {{ number_format($order->discount, 2) }}
                    </td>
                </tr>
                @endif
                <tr>
                    <td style="border: none; text-align: right; padding: 5px 0;">
                        <strong>Shipping:</strong>
                    </td>
                    <td style="border: none; text-align: right; padding: 5px 0;">
                        Rs. {{ number_format($order->shipping_fee, 2) }}
                    </td>
                </tr>
                <tr>
                    <td style="border: none; text-align: right; padding: 5px 0;">
                        <strong>Tax:</strong>
                    </td>
                    <td style="border: none; text-align: right; padding: 5px 0;">
                        Rs. {{ number_format($order->tax, 2) }}
                    </td>
                </tr>
                <tr style="border-top: 2px solid #007bff;">
                    <td style="border: none; text-align: right; padding: 10px 0; font-size: 18px;">
                        <strong>Total:</strong>
                    </td>
                    <td style="border: none; text-align: right; padding: 10px 0; font-size: 18px;">
                        <strong>Rs. {{ number_format($order->total, 2) }}</strong>
                    </td>
                </tr>
            </table>
        </div>

        <div class="shipping-info">
            <h3>Shipping Information</h3>
            @if($order->shipping_name || $order->shipping_email || $order->shipping_phone)
                <p>
                    @if($order->shipping_name)<strong>Name:</strong> {{ $order->shipping_name }}<br>@endif
                    @if($order->shipping_email)<strong>Email:</strong> {{ $order->shipping_email }}<br>@endif
                    @if($order->shipping_phone)<strong>Phone:</strong> {{ $order->shipping_phone }}<br>@endif
                </p>
            @endif

            @if($order->shipping_street_address || $order->shipping_city || $order->shipping_state || $order->shipping_postal_code || $order->shipping_country)
                <p>
                    <strong>Address:</strong><br>
                    @if($order->shipping_street_address){{ $order->shipping_street_address }}<br>@endif
                    @if($order->shipping_city || $order->shipping_state || $order->shipping_postal_code)
                        {{ $order->shipping_city }}@if($order->shipping_city && $order->shipping_state), @endif{{ $order->shipping_state }} {{ $order->shipping_postal_code }}<br>
                    @endif
                    @if($order->shipping_country){{ $order->shipping_country }}@endif
                </p>
            @endif
        </div>

        @if($order->coupon_code)
        <div class="shipping-info">
            <h3>Coupon Applied</h3>
            <p>
                <strong>Coupon Code:</strong> {{ $order->coupon_code }}<br>
                <strong>Discount:</strong>
                @if($order->coupon_discount_type === 'percentage')
                    {{ round(($order->coupon_discount_amount / $order->subtotal) * 100, 2) }}%
                @else
                    Rs. {{ number_format($order->coupon_discount_amount, 2) }}
                @endif
                <br>
                <strong>You Saved:</strong> Rs. {{ number_format($order->coupon_discount_amount, 2) }}
            </p>
        </div>
        @endif

        @if($order->order_notes)
        <div class="shipping-info">
            <h3>Order Notes</h3>
            <p>{{ $order->order_notes }}</p>
        </div>
        @endif

        <p>You will receive another email when your order has been shipped.</p>

        <p>If you have any questions about your order, please don't hesitate to contact us.</p>

        <div class="footer">
            <p>Thank you for shopping with us!</p>
            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>
