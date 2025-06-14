<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Notification</title>
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
            background-color: #28a745;
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
        .customer-info {
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
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .action-buttons {
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 New Order Received!</h1>
            <p>You have a new order to fulfill</p>
        </div>

        <p>Dear {{ $seller->name }},</p>

        <p>Congratulations! You have received a new order. Please review the details below and prepare the items for shipment:</p>

        <div class="order-details">
            <h3>Order Information</h3>
            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y g:i A') }}</p>
            <p><strong>Order Status:</strong> {{ ucfirst($order->order_status) }}</p>
            <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
        </div>

        <div class="customer-info">
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> {{ $customer->name }}</p>
            <p><strong>Email:</strong> {{ $customer->email }}</p>
            <p><strong>Phone:</strong> {{ $customer->phone ?? 'Not provided' }}</p>
        </div>

        <div class="customer-info">
            <h3>Shipping Address</h3>
            <p><strong>{{ $order->shipping_name }}</strong></p>
            <p>{{ $order->shipping_phone }}</p>
            <p>{{ $order->shipping_email }}</p>
            <p>
                {{ $order->shipping_address }}<br>
                {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}<br>
                {{ $order->shipping_country }}
            </p>
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
                    <td>Rs. {{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="border: none; text-align: right; padding: 5px 0;">
                        <strong>Items Subtotal:</strong>
                    </td>
                    <td style="border: none; text-align: right; padding: 5px 0;">
                        Rs. {{ number_format($items->sum(function($item) { return $item->price * $item->quantity; }), 2) }}
                    </td>
                </tr>
                <tr style="border-top: 2px solid #28a745;">
                    <td style="border: none; text-align: right; padding: 10px 0; font-size: 18px;">
                        <strong>Your Earnings:</strong>
                    </td>
                    <td style="border: none; text-align: right; padding: 10px 0; font-size: 18px;">
                        <strong>Rs. {{ number_format($items->sum(function($item) { return $item->price * $item->quantity; }), 2) }}</strong>
                    </td>
                </tr>
            </table>
        </div>

        @if($order->order_notes)
        <div class="customer-info">
            <h3>Customer Notes</h3>
            <p>{{ $order->order_notes }}</p>
        </div>
        @endif

        <div class="action-buttons">
            <a href="{{ route('seller.orders.show', $order->id) }}" class="btn">View Full Order Details</a>
        </div>

        <h3>Next Steps:</h3>
        <ol>
            <li>Log in to your seller dashboard to review the complete order</li>
            <li>Prepare the items for shipment</li>
            <li>Update the order status once items are ready</li>
            <li>Mark as shipped once the package is dispatched</li>
        </ol>

        <p><strong>Important:</strong> Please ensure timely processing of this order to maintain good customer relationships.</p>

        <div class="footer">
            <p>Thank you for being a valued seller!</p>
            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>
