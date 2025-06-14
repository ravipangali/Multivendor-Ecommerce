<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - AllSewa</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f8f9fa;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #abcf37, #c4e04a);
            padding: 30px 40px;
            text-align: center;
        }

        .header h1 {
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            font-family: 'Playfair Display', serif;
        }

        .header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            margin: 0;
        }

        .content {
            padding: 40px;
        }

        .greeting {
            font-size: 20px;
            font-weight: 600;
            color: #1f4b5f;
            margin-bottom: 20px;
        }

        .message {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .action-container {
            text-align: center;
            margin: 35px 0;
        }

        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #abcf37, #c4e04a);
            color: #ffffff;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(171, 207, 55, 0.3);
        }

        .action-button:hover {
            background: linear-gradient(135deg, #8fb12d, #abcf37);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(171, 207, 55, 0.4);
        }

        .security-notice {
            background-color: #fff5f5;
            border-left: 4px solid #f56565;
            padding: 20px;
            margin: 30px 0;
            border-radius: 0 8px 8px 0;
        }

        .security-notice h3 {
            color: #c53030;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .security-notice p {
            color: #744210;
            font-size: 14px;
            margin: 0;
        }

        .expiry-info {
            background-color: #f0f9ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }

        .expiry-info p {
            color: #1e40af;
            font-size: 14px;
            margin: 0;
            font-weight: 500;
        }

        .alternative-link {
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .alternative-link p {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .alternative-link a {
            color: #abcf37;
            word-break: break-all;
            text-decoration: none;
        }

        .footer {
            background-color: #1f4b5f;
            color: #ffffff;
            padding: 30px 40px;
            text-align: center;
        }

        .footer h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #abcf37;
        }

        .footer p {
            font-size: 14px;
            color: #cbd5e0;
            margin-bottom: 10px;
        }

        .footer .contact-info {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #2d3748;
        }

        .social-links {
            margin-top: 20px;
        }

        .social-links a {
            color: #abcf37;
            text-decoration: none;
            margin: 0 10px;
            font-size: 14px;
        }

        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }

            .header, .content, .footer {
                padding: 25px 20px;
            }

            .header h1 {
                font-size: 24px;
            }

            .action-button {
                padding: 14px 24px;
                font-size: 15px;
            }

            .greeting {
                font-size: 18px;
            }

            .message {
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>AllSewa</h1>
            <p>Your Trusted Marketplace</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hello {{ $user->name ?? 'there' }}!
            </div>

            <div class="message">
                We received a request to reset the password for your AllSewa account. If you didn't make this request, you can safely ignore this email.
            </div>

            <div class="action-container">
                <a href="{{ $url }}" class="action-button">
                    🔐 Reset Your Password
                </a>
            </div>

            <div class="expiry-info">
                <p>⏰ This password reset link will expire in {{ $count }} minutes for security reasons.</p>
            </div>

            <div class="security-notice">
                <h3>🛡️ Security Notice</h3>
                <p>For your security, this link can only be used once. If you need to reset your password again, please request a new link from our website.</p>
            </div>

            <div class="alternative-link">
                <p>If you're having trouble clicking the button above, copy and paste the URL below into your web browser:</p>
                <a href="{{ $url }}">{{ $url }}</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <h3>AllSewa Team</h3>
            <p>Thank you for being part of our community!</p>
            <p>Your trusted platform for buying and selling in Nepal.</p>

            <div class="contact-info">
                <p>Need help? Contact our support team</p>
                <p>Email: support@allsewa.com | Phone: +977-1-XXXXXXX</p>
            </div>

            <div class="social-links">
                <a href="#">Facebook</a> |
                <a href="#">Instagram</a> |
                <a href="#">Twitter</a> |
                <a href="#">LinkedIn</a>
            </div>

            <p style="margin-top: 20px; font-size: 12px; color: #a0aec0;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>
