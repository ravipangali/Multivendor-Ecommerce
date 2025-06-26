<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Account {{ ucfirst($status) }}</title>
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
        .seller-info {
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
        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header {{ $isApproved ? 'approved' : 'denied' }}">
            <h1>{{ $isApproved ? '🎉' : '⚠️' }} Seller Account {{ ucfirst($status) }}</h1>
            <p>{{ $isApproved ? 'Welcome to our marketplace!' : 'Account status update' }}</p>
        </div>

        <p>Hello {{ $seller->name }},</p>

        @if($isApproved)
            <div class="welcome-section">
                <h2>🎊 Congratulations! 🎊</h2>
                <p>Your seller account has been <strong>APPROVED</strong> and you're now part of our marketplace family!</p>
            </div>

            <div class="alert success">
                <strong>🚀 You're ready to start selling!</strong> Your seller account is now active and you have full access to all seller features.
            </div>
        @else
            <div class="alert danger">
                <strong>Account Under Review:</strong> Your seller account has been temporarily suspended and needs attention.
            </div>
        @endif

        <div class="seller-info">
            <h3>Account Details</h3>
            <div class="info-row">
                <span class="info-label">Seller Name:</span>
                <span>{{ $seller->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span>{{ $seller->email }}</span>
            </div>
            @if($seller->sellerProfile)
                <div class="info-row">
                    <span class="info-label">Store Name:</span>
                    <span>{{ $seller->sellerProfile->store_name }}</span>
                </div>
            @endif
            <div class="info-row">
                <span class="info-label">Account Status:</span>
                <span class="status-badge status-{{ $isApproved ? 'approved' : 'denied' }}">
                    {{ $isApproved ? 'APPROVED' : 'SUSPENDED' }}
                </span>
            </div>
        </div>

        @if($isApproved)
            <div style="text-align: center; margin: 30px 0;">
                <p><strong>🛍️ Start your selling journey today!</strong></p>
                <a href="{{ url('/seller/dashboard') }}" class="button success">Go to Seller Dashboard</a>
                <a href="{{ url('/seller/products/create') }}" class="button info">Add Your First Product</a>
            </div>

            <div class="alert success">
                <h4>🎯 What you can do now:</h4>
                <ul>
                    <li>✅ Add products to your store</li>
                    <li>✅ Manage your inventory and pricing</li>
                    <li>✅ Process customer orders</li>
                    <li>✅ Track your sales and earnings</li>
                    <li>✅ Customize your store profile</li>
                    <li>✅ Access seller analytics and reports</li>
                </ul>
            </div>

            <div class="alert success">
                <h4>📋 Getting Started Tips:</h4>
                <ul>
                    <li>Complete your seller profile with store information</li>
                    <li>Upload high-quality product images</li>
                    <li>Write detailed and accurate product descriptions</li>
                    <li>Set competitive pricing</li>
                    <li>Respond promptly to customer inquiries</li>
                    <li>Maintain good seller ratings</li>
                </ul>
            </div>
        @else
            <div style="text-align: center; margin: 30px 0;">
                <p><strong>Please review your account information</strong></p>
                <a href="{{ url('/seller/profile') }}" class="button">Update Profile</a>
                <a href="{{ url('/contact') }}" class="button info">Contact Support</a>
            </div>

            <div class="alert danger">
                <h4>⚠️ Common reasons for account suspension:</h4>
                <ul>
                    <li>Incomplete seller profile information</li>
                    <li>Invalid or missing business documentation</li>
                    <li>Suspicious or fraudulent activity</li>
                    <li>Violation of marketplace policies</li>
                    <li>Poor customer reviews or ratings</li>
                    <li>Unresolved customer complaints</li>
                </ul>
                <p><strong>📞 Need help?</strong> Contact our seller support team for assistance in resolving any issues.</p>
            </div>
        @endif

        <div class="footer">
            <p>This is an automated notification from {{ config('app.name') }}.</p>
            @if($isApproved)
                <p>We're excited to have you as part of our seller community!</p>
                <p>For any questions or support, please contact our seller support team.</p>
            @else
                <p>If you believe this is an error or need assistance, please contact our support team immediately.</p>
            @endif
            <p>Thank you for choosing our marketplace!</p>
        </div>
    </div>
</body>
</html>
