<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Request Notification</title>
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
            background: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
            margin: -20px -20px 20px -20px;
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
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }
        .button.approve {
            background: #28a745;
        }
        .button.deny {
            background: #dc3545;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛍️ New Product Request</h1>
            <p>A seller has submitted a new product for approval</p>
        </div>

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
                <span>${{ number_format($product->price, 2) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Stock:</span>
                <span>{{ $product->stock }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Product Type:</span>
                <span>{{ $product->product_type }}</span>
            </div>
        </div>

        <div class="product-info">
            <h3>Seller Information</h3>
            <div class="info-row">
                <span class="info-label">Seller Name:</span>
                <span>{{ $seller->name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span>{{ $seller->email ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Store Name:</span>
                <span>{{ $seller->sellerProfile->store_name ?? 'N/A' }}</span>
            </div>
        </div>

        @if($product->description)
        <div class="product-info">
            <h3>Product Description</h3>
            <p>{{ Str::limit($product->description, 200) }}</p>
        </div>
        @endif

        <div style="text-align: center; margin: 30px 0;">
            <p><strong>Please review this product in the admin panel:</strong></p>
            <a href="{{ url('/admin/products/' . $product->id) }}" class="button">View Product Details</a>
        </div>

        <div class="footer">
            <p>This is an automated notification from {{ config('app.name') }}.</p>
            <p>Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
