<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Your Admin Password</title>
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .email-header {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 50%, #3730a3 100%);
            padding: 50px 30px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .email-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="30" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="70" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="80" r="2.5" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="60" r="1.5" fill="rgba(255,255,255,0.1)"/></svg>');
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(1deg); }
        }
        
        .logo {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.25), rgba(255, 255, 255, 0.15));
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }
        
        .email-content {
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
            color: #4b5563;
            margin-bottom: 30px;
            line-height: 1.7;
        }
        
        .security-notice {
            background: linear-gradient(135deg, #fef3cd 0%, #fde68a 100%);
            border: 2px solid #f59e0b;
            padding: 25px;
            margin: 30px 0;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .security-notice::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: linear-gradient(to bottom, #f59e0b, #d97706);
        }
        
        .security-notice h4 {
            color: #92400e;
            margin: 0 0 12px 0;
            font-size: 18px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .security-notice p {
            color: #b45309;
            margin: 0;
            font-size: 15px;
            line-height: 1.6;
            font-weight: 500;
        }
        
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            color: white !important;
            padding: 18px 40px;
            text-decoration: none !important;
            border-radius: 12px;
            font-weight: 700;
            font-size: 18px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
            transition: all 0.3s ease;
            border: 2px solid transparent;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            position: relative;
            overflow: hidden;
        }
        
        .reset-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .reset-button:hover::before {
            left: 100%;
        }
        
        .reset-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(16, 185, 129, 0.6);
            background: linear-gradient(135deg, #059669 0%, #065f46 100%);
        }
        
        .button-container {
            text-align: center;
            margin: 40px 0;
            padding: 20px;
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border-radius: 16px;
            border: 1px solid #d1fae5;
        }
        
        .button-description {
            font-size: 14px;
            color: #065f46;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .alternative-link {
            background: linear-gradient(135deg, #fef7ff 0%, #faf5ff 100%);
            border: 2px solid #e9d5ff;
            padding: 25px;
            border-radius: 12px;
            margin: 30px 0;
            text-align: center;
        }
        
        .alternative-link p {
            margin: 0 0 15px 0;
            font-size: 15px;
            color: #7c3aed;
            font-weight: 600;
        }
        
        .alternative-link code {
            display: block;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #f1f5f9;
            padding: 18px 20px;
            border-radius: 10px;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 14px;
            word-break: break-all;
            margin-top: 15px;
            border: 1px solid #334155;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.3);
        }
        
        .copy-notice {
            font-size: 12px;
            color: #8b5cf6;
            margin-top: 10px;
            font-style: italic;
        }
        
        .email-footer {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 40px 30px;
            text-align: center;
            border-top: 3px solid #e2e8f0;
            position: relative;
        }
        
        .email-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #6366f1, #8b5cf6, #ec4899, #f59e0b);
        }
        
        .footer-text {
            color: #475569;
            font-size: 16px;
            margin: 0;
            font-weight: 600;
        }
        
        .footer-links {
            margin-top: 25px;
        }
        
        .footer-links a {
            color: #6366f1;
            text-decoration: none;
            margin: 0 15px;
            font-size: 14px;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .footer-links a:hover {
            background: #6366f1;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }
        
        .divider {
            height: 2px;
            background: linear-gradient(to right, transparent, #cbd5e1, #6366f1, #cbd5e1, transparent);
            margin: 40px 0;
            border-radius: 1px;
        }
        
        .request-details {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 20px;
            margin-top: 30px;
            text-align: left;
        }
        
        .request-details strong {
            color: #1e293b;
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .request-details br + strong {
            margin-top: 8px;
        }
        
        @media (max-width: 600px) {
            .email-container {
                margin: 20px;
                border-radius: 12px;
                overflow: hidden;
            }
            
            .email-header, .email-content, .email-footer {
                padding: 30px 20px;
            }
            
            .greeting {
                font-size: 20px;
            }
            
            .reset-button {
                display: block;
                width: 100%;
                box-sizing: border-box;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            
            <h1 style="margin: 0; font-size: 32px; font-weight: 800; position: relative; z-index: 1;">Elandra Admin</h1>
            <p style="margin: 15px 0 0 0; opacity: 0.95; font-size: 18px; font-weight: 500; position: relative; z-index: 1;">🔐 Password Reset Request</p>
        </div>
        
        <!-- Content -->
        <div class="email-content">
            <h2 class="greeting">Hello Administrator,</h2>
            
            <p class="message">
                We received a request to reset your admin password for your Elandra account. If you made this request, 
                click the button below to set a new password. This link will expire in 24 hours for security reasons.
            </p>
            
            <div class="button-container">
                <p class="button-description">🔐 Click the button below to securely reset your password</p>
                <a href="{{ $resetUrl }}" class="reset-button">Reset Your Password</a>
            </div>
            
            <div class="security-notice">
                <h4>🔒 Security Notice</h4>
                <p>
                    If you did not request this password reset, please ignore this email and contact your system administrator immediately. 
                    Your password will remain unchanged.
                </p>
            </div>
            
            <div class="divider"></div>
            
            <div class="alternative-link">
                <p>🔗 <strong>Having trouble with the button?</strong><br>Copy and paste this URL into your browser:</p>
                <code>{{ $resetUrl }}</code>
                <p class="copy-notice">💡 Select all text above and copy (Ctrl+C / Cmd+C)</p>
            </div>
            
            <div class="request-details">
                <strong>📋 Request Details:</strong>
                <span style="color: #64748b; font-size: 14px;">
                    <strong>Email:</strong> {{ $email }}<br>
                    <strong>Time:</strong> {{ now()->format('F j, Y \a\t g:i A T') }}<br>
                    <strong>IP Address:</strong> {{ request()->ip() ?? 'N/A' }}
                </span>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <p class="footer-text">
                This email was sent by <strong>Elandra</strong> - E-commerce Management System
            </p>
            
            <div class="footer-links">
                <a href="#">Help Center</a>
                <a href="#">Contact Support</a>
                <a href="#">Privacy Policy</a>
            </div>
            
            <p style="color: #9ca3af; font-size: 12px; margin-top: 20px;">
                © {{ date('Y') }} Elandra. All rights reserved.<br>
                This is an automated message, please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>