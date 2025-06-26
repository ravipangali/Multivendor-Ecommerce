<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product {{ ucfirst($status) }} Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background: {{ $isApproved ? '#28a745' : '#dc3545' }};
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
            margin: -20px -20px 20px -20px;
        }
        .header.approved {
            background: #28a745;
        }
        .header.denied {
            background: #dc3545;
        }
        .product-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            width: 120px;
        }
        .button {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
            font-weight: bold;
        }
        .button.success {
            background: #28a745;
        }
        .button.info {
            background: #17a2b8;
        }
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            color: white;
            display: inline-block;
            margin: 10px 0;
        }
        .status-approved {
            background: #28a745;
        }
        .status-denied {
            background: #dc3545;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #666;
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .alert.success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        .alert.danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header {{ $isApproved ? 'approved' : 'denied' }}">
            <h1>{{ $isApproved ? '✅' : '❌' }} Product {{ ucfirst($status) }}</h1>
            <p>Your product submission has been {{ $status }}</p>
        </div>

        <p>Hello {{ $seller->name }},</p>

        @if($isApproved)
            <div class="alert success">
                <strong>Great news!</strong> Your product has been approved and is now live on our marketplace.
            </div>
        @else
            <div class="alert danger">
                <strong>Update Required:</strong> Your product submission needs some adjustments before it can go live.
            </div>
        @endif

        <div class="product-info">
            <h3>Product Details</h3>
            <div class="info-row">
                <span class="info-label">Product Name:</span>
                <span>{{ $product->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">SKU:</span>
                <span>{{ $product->SKU }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Category:</span>
                <span>{{ $product->category->name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Price:</span>
                <span>Rs. {{ number_format($product->price, 2) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="status-badge status-{{ $isApproved ? 'approved' : 'denied' }}">
                    {{ $isApproved ? 'APPROVED' : 'DENIED' }}
                </span>
            </div>
        </div>

        @if($isApproved)
            <div style="text-align: center; margin: 30px 0;">
                <p><strong>Your product is now live and customers can purchase it!</strong></p>
                <a href="{{ url('/seller/products') }}" class="button success">View Your Products</a>
                <a href="{{ url('/seller/dashboard') }}" class="button info">Go to Dashboard</a>
            </div>

            <div class="alert success">
                <h4>What's Next?</h4>
                <ul>
                    <li>Your product is now visible to customers</li>
                    <li>Monitor your sales and inventory in the seller dashboard</li>
                    <li>Respond promptly to customer inquiries</li>
                    <li>Consider promoting your product for better visibility</li>
                </ul>
            </div>
        @else
            <div style="text-align: center; margin: 30px 0;">
                <p><strong>Please review and update your product details</strong></p>
                <a href="{{ url('/seller/products/' . $product->id . '/edit') }}" class="button">Edit Product</a>
                <a href="{{ url('/seller/products') }}" class="button info">View All Products</a>
            </div>

            <div class="alert danger">
                <h4>Common Reasons for Denial:</h4>
                <ul>
                    <li>Incomplete or inaccurate product information</li>
                    <li>Poor quality or missing product images</li>
                    <li>Inappropriate product category selection</li>
                    <li>Pricing issues or unrealistic pricing</li>
                    <li>Product doesn't meet marketplace guidelines</li>
                </ul>
                <p><strong>Tip:</strong> Please review our <a href="{{ url('/seller-guidelines') }}">Seller Guidelines</a> and make necessary corrections before resubmitting.</p>
            </div>
        @endif

        <div class="footer">
            <p>This is an automated notification from {{ config('app.name') }}.</p>
            <p>If you have any questions, please contact our seller support team.</p>
            <p>Thank you for being part of our marketplace!</p>
        </div>
    </div>
</body>
</html>
