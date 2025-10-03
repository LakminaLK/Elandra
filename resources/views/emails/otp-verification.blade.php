<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        
        .email-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .logo {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .otp-code {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border: 2px dashed #4F46E5;
            padding: 30px;
            text-align: center;
            border-radius: 12px;
            margin: 30px 0;
        }
        
        .otp-number {
            font-size: 36px;
            font-weight: bold;
            color: #4F46E5;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
        }
        
        .footer {
            background: #f8fafc;
            padding: 30px;
            text-align: center;
            font-size: 14px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        
        .luxury-accent {
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #4F46E5, #7C3AED, #EC4899);
        }
        
        .warning {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }
        
        .brand-text {
            color: #4F46E5;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="luxury-accent"></div>
        
        <div class="header">
            <div class="logo">E</div>
            <h1 style="margin: 0; font-size: 28px; font-weight: 300;">Elandra</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9; font-size: 16px;">Luxury Handbags</p>
        </div>
        
        <div class="content">
            <h2 style="color: #1e293b; margin-bottom: 20px; font-size: 24px;">Email Verification Required</h2>
            
            <p>Hello!</p>
            
            <p>Welcome to <span class="brand-text">Elandra</span>! To complete your registration and start exploring our exquisite collection of luxury handbags, please verify your email address.</p>
            
            <div class="otp-code">
                <p style="margin: 0 0 10px 0; color: #64748b; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Verification Code</p>
                <div class="otp-number">{{ $otpCode }}</div>
                <p style="margin: 10px 0 0 0; color: #64748b; font-size: 12px;">Enter this code in the verification form</p>
            </div>
            
            <div class="warning">
                <strong>⏱️ Important:</strong> This verification code will expire in <strong>10 minutes</strong> for your security.
            </div>
            
            <p>If you didn't create an account with Elandra, please ignore this email or contact our support team.</p>
            
            <p style="margin-bottom: 0;">Thank you for choosing Elandra!</p>
        </div>
        
        <div class="footer">
            <p><strong>Elandra</strong> - Where Luxury Meets Style</p>
            <p>This is an automated email. Please do not reply to this message.</p>
            <p style="margin-top: 20px; font-size: 12px;">
                © {{ date('Y') }} Elandra. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>