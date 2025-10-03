<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Elandra</title>
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
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
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
        .features {
            background-color: #f9fafb;
            padding: 30px;
            margin: 30px 0;
            border-radius: 8px;
            border-left: 4px solid #4F46E5;
        }
        .features h3 {
            color: #1f2937;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .features ul {
            margin: 0;
            padding-left: 20px;
            color: #4b5563;
        }
        .features li {
            margin-bottom: 8px;
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
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #9ca3af;
            text-decoration: none;
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
            <div class="greeting">Hello {{ $user->name }}!</div>
            
            <div class="message">
                Welcome to Elandra, where luxury meets elegance and every handbag tells a unique story.
            </div>

            <div class="message">
                Your account has been successfully created and verified. We're thrilled to have you join our exclusive community of fashion enthusiasts who appreciate the finest craftsmanship and timeless design.
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/') }}" class="cta-button">Explore Our Collection</a>
            </div>

            <div class="features">
                <h3>What awaits you at Elandra:</h3>
                <ul>
                    <li><strong>Handcrafted Excellence:</strong> Premium materials and artisanal craftsmanship</li>
                    <li><strong>Exclusive Designs:</strong> Limited edition collections and unique pieces</li>
                    <li><strong>Personalized Service:</strong> Dedicated customer support for your luxury journey</li>
                    <li><strong>Worldwide Delivery:</strong> Secure shipping to your doorstep</li>
                    <li><strong>Lifetime Care:</strong> Professional maintenance and care services</li>
                </ul>
            </div>

            <div class="message">
                Need assistance? Our customer support team is here to help you discover the perfect handbag that reflects your personal style and elegance.
            </div>

            <div class="message">
                Thank you for choosing Elandra. Let your luxury journey begin!
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <h4>The Elandra Team</h4>
            <p>Crafting luxury, one handbag at a time</p>
            <p>Email: {{ config('mail.from.address') }}</p>
            <p>© {{ date('Y') }} Elandra. All rights reserved.</p>
            
            <div class="social-links">
                <a href="#">Instagram</a> |
                <a href="#">Facebook</a> |
                <a href="#">Pinterest</a>
            </div>
        </div>
    </div>
</body>
</html>