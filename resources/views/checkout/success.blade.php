<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment Successful - Elandra</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #ecfdf5 0%, #dbeafe 100%);
            min-height: 100vh;
        }
        
        .container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 16px;
        }
        
        .success-card {
            max-width: 768px;
            width: 100%;
            text-align: center;
        }
        
        .success-icon {
            width: 128px;
            height: 128px;
            margin: 0 auto 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #34d399, #059669);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: pulse 2s infinite;
        }
        
        .success-icon i {
            font-size: 48px;
            color: white;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .title {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 16px;
            background: linear-gradient(135deg, #059669, #2563eb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .subtitle {
            font-size: 20px;
            color: #374151;
            margin-bottom: 32px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .order-card {
            background: white;
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid #f3f4f6;
        }
        
        .order-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            text-align: left;
        }
        
        @media (max-width: 768px) {
            .order-details {
                grid-template-columns: 1fr;
            }
            .title {
                font-size: 36px;
            }
        }
        
        .detail-label {
            font-size: 14px;
            font-weight: 500;
            color: #6b7280;
            margin-bottom: 8px;
        }
        
        .detail-value {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 16px;
        }
        
        .order-number {
            background: #f3f4f6;
            padding: 12px 16px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 500;
            background: #dbeafe;
            color: #1e40af;
        }
        
        .total-amount {
            font-size: 36px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 16px;
        }
        
        .breakdown {
            font-size: 14px;
            color: #6b7280;
        }
        
        .breakdown-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        
        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 32px;
        }
        
        @media (max-width: 640px) {
            .action-buttons {
                grid-template-columns: 1fr;
            }
        }
        
        .btn {
            padding: 16px 24px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: white;
        }
        
        .btn-secondary {
            background: white;
            color: #374151;
            border: 2px solid #e5e7eb;
        }
        
        .btn i {
            margin-right: 12px;
            font-size: 16px;
        }
        
        .info-section {
            background: #f0f9ff;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #e0f2fe;
        }
        
        .info-title {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0284c7;
            font-weight: 600;
            margin-bottom: 16px;
        }
        
        .info-list {
            font-size: 14px;
            color: #0369a1;
            text-align: center;
        }
        
        .info-list p {
            margin-bottom: 8px;
        }
        
        .info-list i {
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-card">
            <!-- Success Animation -->
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            
            <!-- Success Message -->
            <h1 class="title">Payment Successful!</h1>
            <p class="subtitle">
                🎉 Thank you for your purchase! Your order has been confirmed and will be processed shortly.
            </p>
            
            <!-- Order Summary Card -->
            <div class="order-card">
                <div class="order-details">
                    <!-- Order Details -->
                    <div>
                        <div class="detail-label">Order Number</div>
                        <div class="detail-value order-number">{{ $order->order_number }}</div>
                        
                        <div class="detail-label">Order Date</div>
                        <div class="detail-value">{{ $order->created_at->format('F j, Y \a\t g:i A') }}</div>
                        
                        <div class="detail-label">Status</div>
                        <span class="status-badge">
                            <i class="fas fa-clock" style="margin-right: 8px;"></i>
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    
                    <!-- Order Total -->
                    <div style="text-align: right;">
                        <div class="detail-label">Total Amount</div>
                        <div class="total-amount">${{ number_format($order->total_amount, 2) }}</div>
                        
                        <!-- Breakdown -->
                        <div class="breakdown">
                            @php
                                $subtotal = $order->total_amount - $order->tax_amount - $order->shipping_amount;
                            @endphp
                            <div class="breakdown-row">
                                <span>Subtotal:</span>
                                <span>${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="breakdown-row">
                                <span>Tax:</span>
                                <span>${{ number_format($order->tax_amount, 2) }}</span>
                            </div>
                            <div class="breakdown-row">
                                <span>Shipping:</span>
                                <span>
                                    @if($order->shipping_amount == 0)
                                        FREE
                                    @else
                                        ${{ number_format($order->shipping_amount, 2) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                
            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('orders.show', $order) }}" class="btn btn-primary">
                    <i class="fas fa-receipt"></i>
                    View Order Details
                </a>
                
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-shopping-bag"></i>
                    Continue Shopping
                </a>
            </div>
            
            <!-- Additional Info -->
            <div class="info-section">
                <div class="info-title">
                    <i class="fas fa-info-circle" style="margin-right: 8px;"></i>
                    <span>What happens next?</span>
                </div>
                <div class="info-list">
                    <p><i class="fas fa-envelope"></i>A confirmation email has been sent to your email address</p>
                    <p><i class="fas fa-truck"></i>We'll notify you when your order ships</p>
                    <p><i class="fas fa-headset"></i>Need help? Contact our support team</p>
                </div>
            </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes confetti-fall {
            from {
                top: -10px;
                transform: rotate(0deg);
            }
            to {
                top: 100vh;
                transform: rotate(360deg);
            }
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    </style>

    <script>
        // Celebrate with confetti on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Launch confetti
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 }
            });
            
            // Additional confetti burst after delay
            setTimeout(() => {
                confetti({
                    particleCount: 50,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0 }
                });
                confetti({
                    particleCount: 50,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1 }
                });
            }, 1000);
        });
    </script>
    
    <style>
        @keyframes confetti-fall {
            to {
                transform: translateY(100vh) rotate(360deg);
            }
        }
    </style>
</body>
</html>