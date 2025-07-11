<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Support Ticket Created</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
        }
        
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .email-header {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .ticket-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px;
        }
        
        .email-header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .email-header p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .ticket-number {
            background: rgba(255, 255, 255, 0.15);
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 18px;
            margin-top: 12px;
            display: inline-block;
        }
        
        .email-body {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 20px;
        }
        
        .confirmation-message {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #3b82f6;
        }
        
        .confirmation-message .icon {
            font-size: 20px;
            margin-right: 8px;
        }
        
        .ticket-info {
            background: #f8fafc;
            border-radius: 8px;
            padding: 24px;
            margin: 24px 0;
            border: 1px solid #e2e8f0;
        }
        
        .ticket-info h3 {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
        }
        
        .ticket-info h3 .icon {
            margin-right: 8px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        
        .info-item {
            background: white;
            padding: 16px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }
        
        .info-label {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        
        .info-value {
            color: #1f2937;
            font-size: 14px;
            font-weight: 500;
        }
        
        .priority-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .priority-low { background: #dcfce7; color: #166534; }
        .priority-medium { background: #fef3c7; color: #92400e; }
        .priority-high { background: #fed7d7; color: #c53030; }
        .priority-critical { background: #fee2e2; color: #dc2626; }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            background: #dbeafe;
            color: #1e40af;
        }
        
        .description-section {
            margin: 24px 0;
        }
        
        .description-section h4 {
            font-size: 14px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }
        
        .description-section h4 .icon {
            margin-right: 8px;
        }
        
        .description-content {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            color: #4b5563;
            line-height: 1.6;
            font-size: 15px;
        }
        
        .next-steps {
            background: #fefce8;
            border: 1px solid #fde047;
            border-radius: 8px;
            padding: 20px;
            margin: 24px 0;
            border-left: 4px solid #eab308;
        }
        
        .next-steps h4 {
            color: #a16207;
            font-weight: 600;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }
        
        .next-steps h4 .icon {
            margin-right: 8px;
        }
        
        .next-steps p {
            color: #92400e;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .cta-section {
            text-align: center;
            margin: 32px 0;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        }
        
        .response-time {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
            text-align: center;
        }
        
        .response-time .time {
            font-size: 24px;
            font-weight: 700;
            color: #166534;
            display: block;
        }
        
        .response-time .label {
            font-size: 14px;
            color: #166534;
            margin-top: 4px;
        }
        
        .email-footer {
            background: #f8fafc;
            padding: 24px 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        
        .signature {
            color: #374151;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .company-info {
            color: #6b7280;
            font-size: 13px;
        }
        
        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 8px;
            }
            
            .email-header, .email-body, .email-footer {
                padding: 24px 20px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .ticket-number {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="ticket-icon">🎫</div>
            <h1>New Support Ticket Created</h1>
            <p>We've received your request and are here to help</p>
            <div class="ticket-number">#{{ $ticket->id }}</div>
        </div>
        
        <!-- Body -->
        <div class="email-body">
            <div class="greeting">Hello {{ $user->name }}!</div>
            
            <div class="confirmation-message">
                <p><span class="icon">✅</span><strong>Your support ticket has been successfully created!</strong> Our team has been notified and will begin working on your request shortly.</p>
            </div>
            
            <!-- Ticket Information -->
            <div class="ticket-info">
                <h3><span class="icon">📋</span>Ticket Details</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Ticket ID</div>
                        <div class="info-value">#{{ $ticket->id }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Created By</div>
                        <div class="info-value">{{ $user->name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Organization</div>
                        <div class="info-value">{{ $user->Organization->name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Type</div>
                        <div class="info-value">{{ $ticket->type ?? 'General' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Priority</div>
                        <div class="info-value">
                            <span class="priority-badge priority-{{ strtolower($ticket->priority) }}">
                                {{ $ticket->priority }}
                            </span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <span class="status-badge">{{ $ticket->status }}</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Created Date</div>
                        <div class="info-value">{{ $ticket->created_at->format('M d, Y \a\t h:i A') }}</div>
                    </div>
                    @if($ticket->module)
                    <div class="info-item">
                        <div class="info-label">Module</div>
                        <div class="info-value">{{ $ticket->module }}</div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Description -->
            @if($ticket->description)
            <div class="description-section">
                <h4><span class="icon">📝</span>Request Description</h4>
                <div class="description-content">
                    {{ $ticket->description }}
                </div>
            </div>
            @endif
            
            <!-- Expected Response Time -->
            <div class="response-time">
                <span class="time">
                    @if($ticket->priority === 'Critical')
                        4-8 Hours
                    @elseif($ticket->priority === 'High')
                        8-12 Hours
                    @elseif($ticket->priority === 'Medium')
                        12-24 Hours 
                    @else
                        24-48 Hours
                    @endif
                </span>
                <div class="label">Expected Response Time</div>
            </div>
            
            <!-- Next Steps -->
            <div class="next-steps">
                <h4><span class="icon">⏭️</span>What Happens Next?</h4>
                <p>Our support team will review your request and get back to you within the expected timeframe above. You'll receive email updates on any progress or when your ticket is resolved.</p>
            </div>
            
            
            <p style="color: #6b7280; font-size: 14px; text-align: center; margin-top: 24px;">
                <strong>Need to add more information?</strong><br>
                You can reply to this email or visit your ticket dashboard to provide additional details.
            </p>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div class="signature">Best regards,<br>The Support Team</div>
            <div class="company-info">
                {{ config('app.name') }} • Always Here to Help
            </div>
        </div>
    </div>
</body>
</html>