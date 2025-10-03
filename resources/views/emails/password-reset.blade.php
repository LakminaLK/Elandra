<!DOCTYPE html>
<html         .header {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - Elandra</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
            color: #374151;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        .logo {
            width: 80px;
            height: 80px;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
        }
        .header p {
            margin: 8px 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            margin-bottom: 20px;
            color: #4b5563;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        .cta-button:hover {
            transform: translateY(-2px);
        }
        .security-notice {
            background-color: #fef3cd;
            padding: 20px;
            margin: 30px 0;
            border-radius: 8px;
            border-left: 4px solid #f59e0b;
        }
        .security-notice h3 {
            color: #92400e;
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .security-notice p {
            margin: 0;
            color: #92400e;
            font-size: 14px;
        }
        .footer {
            background-color: #1f2937;
            color: #9ca3af;
            padding: 30px;
            text-align: center;
        }
        .footer h4 {
            color: white;
            margin-top: 0;
            font-family: 'Playfair Display', serif;
        }
        .footer p {
            margin: 8px 0;
            font-size: 14px;
        }
        .expiry-notice {
            background-color: #fee2e2;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
            border: 1px solid #fca5a5;
        }
        .expiry-notice p {
            margin: 0;
            color: #dc2626;
            font-size: 14px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <img src="{{ url('/elandra-logo.webp') }}" alt="Elandra Logo" style="width: 100%; height: 100%; object-fit: contain; border-radius: 50%;" />
            </div>
            <h1>Elandra</h1>
            <p>Luxury Handbags</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">Hello {{ $user->name }}!</div>
            
            <div class="message">
                We received a request to reset your password for your Elandra account. If you made this request, please click the button below to set a new password.
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $resetUrl }}" class="cta-button">Reset Password</a>
            </div>

            <div class="expiry-notice">
                <p><strong>⏰ This link expires in 15 minutes</strong> for your security.</p>
            </div>

            <div class="security-notice">
                <h3>🛡️ Security Information</h3>
                <p>If you didn't request a password reset, please ignore this email. Your account is still secure and no changes have been made.</p>
            </div>

            <div class="message">
                For additional security, you cannot reuse your current password when setting a new one.
            </div>

            <div class="message">
                If you're having trouble clicking the button, copy and paste the following link into your browser:
            </div>
            
            <div style="background-color: #f3f4f6; padding: 15px; border-radius: 6px; margin: 20px 0; word-break: break-all; font-family: monospace; font-size: 14px;">
                {{ $resetUrl }}
            </div>

            <div class="message">
                Need help? Contact our customer support team - we're here to assist you.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <h4>The Elandra Team</h4>
            <p>Crafting luxury, one handbag at a time</p>
            <p>Email: {{ config('mail.from.address') }}</p>
            <p>© {{ date('Y') }} Elandra. All rights reserved.</p>
        </div>
    </div>
</body>
</html>