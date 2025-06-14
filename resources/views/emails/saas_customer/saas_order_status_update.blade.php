<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update</title>
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
        .status-update {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .status-delivered {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .status-cancelled {
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .status-shipped {
            background-color: #fff3cd;
            border-color: #ffeaa7;
        }
        .order-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
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
        .status-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Status Update</h1>
            <p>Order #{{ $order->order_number }}</p>
        </div>

        <p>Dear {{ $customer->name }},</p>

        <p>We wanted to update you on the status of your order. Here's what's happening:</p>

        @php
            $statusClass = 'status-update';
            $statusIcon = '📦';
            $statusMessage = '';

            switch($currentStatus) {
                case 'pending':
                    $statusIcon = '⏳';
                    $statusMessage = 'Your order is pending and will be processed soon.';
                    break;
                case 'processing':
                    $statusIcon = '⚙️';
                    $statusMessage = 'Your order is being processed and prepared for shipment.';
                    break;
                case 'shipped':
                    $statusClass .= ' status-shipped';
                    $statusIcon = '🚚';
                    $statusMessage = 'Great news! Your order has been shipped and is on its way to you.';
                    break;
                case 'delivered':
                    $statusClass .= ' status-delivered';
                    $statusIcon = '✅';
                    $statusMessage = 'Excellent! Your order has been delivered successfully.';
                    break;
                case 'cancelled':
                    $statusClass .= ' status-cancelled';
                    $statusIcon = '❌';
                    $statusMessage = 'Your order has been cancelled. If you have any questions, please contact us.';
                    break;
                case 'refunded':
                    $statusClass .= ' status-cancelled';
                    $statusIcon = '💰';
                    $statusMessage = 'Your order has been refunded. The refund will be processed according to our refund policy.';
                    break;
                default:
                    $statusMessage = 'Your order status has been updated.';
            }
        @endphp

        <div class="{{ $statusClass }}">
            <div class="status-icon">{{ $statusIcon }}</div>
            <h3>Status: {{ ucfirst($currentStatus) }}</h3>
            <p><strong>{{ $statusMessage }}</strong></p>
            @if($previousStatus !== $currentStatus)
                <p><small>Previous status: {{ ucfirst($previousStatus) }}</small></p>
            @endif
        </div>

        <div class="order-details">
            <h3>Order Information</h3>
            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y g:i A') }}</p>
            <p><strong>Order Total:</strong> Rs. {{ number_format($order->total_amount, 2) }}</p>
            <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
        </div>

        @if($currentStatus === 'shipped')
            <div class="order-details">
                <h3>Shipping Information</h3>
                <p>Your package is on its way to:</p>
                <p>
                    <strong>{{ $order->shipping_name }}</strong><br>
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}<br>
                    {{ $order->shipping_country }}
                </p>
                <p><strong>Expected Delivery:</strong> 3-5 business days</p>
            </div>
        @endif

        @if($currentStatus === 'delivered')
            <div class="order-details">
                <h3>Thank You!</h3>
                <p>We hope you're satisfied with your purchase. If you have a moment, we'd love to hear your feedback about the products you received.</p>
                <p>If there are any issues with your order, please don't hesitate to contact us.</p>
            </div>
        @endif

        @if($currentStatus === 'cancelled' || $currentStatus === 'refunded')
            <div class="order-details">
                <h3>What's Next?</h3>
                @if($currentStatus === 'cancelled')
                    <p>Your order has been cancelled and any charges will be refunded within 3-5 business days.</p>
                @else
                    <p>Your refund is being processed and will be credited to your original payment method within 3-5 business days.</p>
                @endif
                <p>If you have any questions or need assistance, please contact our customer support team.</p>
            </div>
        @endif

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('customer.orders.detail', $order->id) }}" class="btn">View Order Details</a>
        </div>

        <p>If you have any questions about your order, please don't hesitate to contact us. We're here to help!</p>

        <div class="footer">
            <p>Thank you for shopping with us!</p>
            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>
