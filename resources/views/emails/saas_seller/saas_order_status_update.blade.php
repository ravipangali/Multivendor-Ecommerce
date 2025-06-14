<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update - Seller Notification</title>
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
        .status-update {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
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
            background-color: #28a745;
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
            <h1>Order Status Updated</h1>
            <p>Order #{{ $order->order_number }}</p>
        </div>

        <p>Dear {{ $seller->name }},</p>

        <p>This is to inform you that the order status has been updated:</p>

        <div class="status-update">
            <h3>Status: {{ ucfirst($currentStatus) }}</h3>
            @if($previousStatus !== $currentStatus)
                <p><small>Previous status: {{ ucfirst($previousStatus) }}</small></p>
            @endif
        </div>

        <div class="order-details">
            <h3>Order Information</h3>
            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
            <p><strong>Customer:</strong> {{ $customer->name }} ({{ $customer->email }})</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y g:i A') }}</p>
            <p><strong>Order Total:</strong> Rs. {{ number_format($order->total_amount, 2) }}</p>
        </div>

        @if($currentStatus === 'delivered')
            <div class="order-details">
                <h3>Congratulations!</h3>
                <p>Your order has been successfully delivered to the customer. Well done on completing this transaction!</p>
            </div>
        @endif

        @if($currentStatus === 'cancelled' || $currentStatus === 'refunded')
            <div class="order-details">
                <h3>Order {{ ucfirst($currentStatus) }}</h3>
                <p>Please note that this order has been {{ $currentStatus }}. If you have any inventory to restore, please update your stock accordingly.</p>
            </div>
        @endif

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('seller.orders.show', $order->id) }}" class="btn">View Order Details</a>
        </div>

        <div class="footer">
            <p>Thank you for being a valued seller!</p>
            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>
