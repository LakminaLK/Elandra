<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Changed - Elandra</title>
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
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
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
        .success-icon {
            width: 60px;
            height: 60px;
            background-color: #d1fae5;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 20px;
            text-align: center;
        }
        .message {
            font-size: 16px;
            margin-bottom: 20px;
            color: #4b5563;
        }
        .security-notice {
            background-color: #f0f9ff;
            padding: 20px;
            margin: 30px 0;
            border-radius: 8px;
            border-left: 4px solid #0ea5e9;
        }
        .security-notice h3 {
            color: #0c4a6e;
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .security-notice p {
            margin: 0;
            color: #0c4a6e;
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
        .timestamp {
            background-color: #f9fafb;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
            text-align: center;
        }
        .timestamp p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
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
            <div class="success-icon">
                <svg width="32" height="32" fill="#059669" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </div>

            <div class="greeting">Password Changed Successfully!</div>
            
            <div class="message" style="text-align: center;">
                Hello {{ $user->name }}, your Elandra account password has been successfully updated.
            </div>

            <div class="timestamp">
                <p><strong>Changed on:</strong> {{ now()->format('F j, Y \\a\\t g:i A T') }}</p>
            </div>

            <div class="security-notice">
                <h3>🛡️ Account Security</h3>
                <p>If you didn't make this change, please contact our support team immediately. Your account security is our top priority.</p>
            </div>

            <div class="message">
                Your account is now secured with your new password. You can continue enjoying our luxury handbag collection with confidence.
            </div>

            <div class="message">
                <strong>Security Tips:</strong>
            </div>
            <ul style="color: #4b5563; margin-left: 20px;">
                <li>Keep your password private and secure</li>
                <li>Use a unique password for your Elandra account</li>
                <li>Contact us if you notice any suspicious activity</li>
            </ul>

            <div class="message">
                Thank you for choosing Elandra for your luxury handbag needs.
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