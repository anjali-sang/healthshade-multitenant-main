<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order Placement Failed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e74c3c;
        }
        .logo {
            max-width: 200px;
            height: auto;
        }
        .alert-badge {
            background-color: #e74c3c;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 30px;
        }
        .error-box {
            background-color: #fdf2f2;
            border: 1px solid #e74c3c;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .error-title {
            color: #e74c3c;
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .error-message {
            color: #666;
            font-family: monospace;
            background-color: #f8f8f8;
            padding: 10px;
            border-radius: 4px;
            word-break: break-word;
        }
        .order-details {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .order-number {
            font-weight: bold;
            color: #007bff;
        }
        .supplier-info {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        .info-row {
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            color: #856404;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .timestamp {
            color: #999;
            font-size: 12px;
            margin-top: 10px;
        }
        .action-required {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        .action-title {
            font-weight: bold;
            color: #856404;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="alert-badge">⚠️ FAILURE ALERT</div>
            <h1 style="color: #e74c3c; margin: 0;">Purchase Order Placement Failed</h1>
        </div>

        <div class="content">
            <p><strong>Organization:</strong> {{ $organization ? $organization->name : 'N/A' }}</p>
            <p>We encountered an error while attempting to place the following purchase order(s) with suppliers. Immediate attention is required.</p>

            <div class="error-box">
                <div class="error-title">Error Details:</div>
                <div class="error-message">{{ $errorMessage }}</div>
                <div class="timestamp">
                    <strong>Failure Time:</strong> {{ $failureTimestamp->format('Y-m-d H:i:s T') }}
                </div>
            </div>

            <div class="order-details">
                <h3 style="margin-top: 0; color: #333;">Failed Purchase Orders ({{ $totalFailedOrders }})</h3>
                
                @foreach($failedOrders as $order)
                <div class="order-item">
                    <div>
                        <span class="order-number">PO #{{ $order->purchase_oder_number ?? $order->id }}</span>
                        @if($order->merge_id)
                            <br><small>Merge ID: {{ $order->merge_id }}</small>
                        @endif
                    </div>
                    <div style="text-align: right;">
                        <div><strong>${{ number_format($order->total_amount ?? 0, 2) }}</strong></div>
                        <div><small>{{ $order->created_at->format('M d, Y') }}</small></div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($failedOrders->first() && $failedOrders->first()->purchaseSupplier)
            <div class="supplier-info">
                <h4 style="margin-top: 0; color: #856404;">Supplier Information:</h4>
                <div class="info-row">
                    <span class="info-label">Name:</span> {{ $failedOrders->first()->purchaseSupplier->supplier_name ?? 'N/A' }}
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span> {{ $failedOrders->first()->purchaseSupplier->supplier_email ?? 'N/A' }}
                </div>
                <div class="info-row">
                    <span class="info-label">Contact:</span> {{ $failedOrders->first()->purchaseSupplier->supplier_phone ?? 'N/A' }}
                </div>
            </div>
            @endif

            <div class="action-required">
                <div class="action-title">Action Required:</div>
                <ul style="margin: 8px 0; padding-left: 20px;">
                    <li>Review the error message and affected purchase orders</li>
                    <li>Check supplier contact information and email validity</li>
                    <li>Verify network connectivity and email service status</li>
                    <li>Manually retry the purchase order placement if needed</li>
                    <li>Contact the supplier directly if email delivery continues to fail</li>
                </ul>
            </div>

            <p>This is an automated notification. The affected purchase orders remain in "ordered" status and will need manual intervention.</p>
        </div>

        <div class="footer">
            <p><strong>Inventra Support Team</strong></p>
            <p>Email: support@healthshade.com</p>
            <p style="font-size: 12px; color: #999;">
                This email was sent automatically by the Purchase Order Management System.<br>
                Please do not reply to this email address.
            </p>
        </div>
    </div>
</body>
</html>