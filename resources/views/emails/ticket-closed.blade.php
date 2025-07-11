<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Ticket Closed</title>
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
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .success-icon {
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
        
        .email-body {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 20px;
        }
        
        .ticket-info {
            background: #f8fafc;
            border-radius: 8px;
            padding: 24px;
            margin: 20px 0;
            border-left: 4px solid #22c55e;
        }
        
        .ticket-info h3 {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 16px;
        }
        
        .info-row {
            background: white;
            padding: 16px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 14px;
        }
        
        .info-value {
            color: #1f2937;
            font-size: 14px;
            text-align: right;
        }
        
        .priority-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .priority-low { background: #dcfce7; color: #166534; }
        .priority-medium { background: #fef3c7; color: #92400e; }
        .priority-high { background: #fed7d7; color: #c53030; }
        .priority-critical { background: #fee2e2; color: #dc2626; }
        
        .message-section {
            margin: 24px 0;
        }
        
        .message-section h4 {
            font-size: 14px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }
        
        .original-message {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 16px;
            font-style: italic;
            color: #4b5563;
            line-height: 1.5;
        }
        
        .resolution-notes {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            padding: 16px;
            color: #1e40af;
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
        
        .footer-text {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.5;
            margin-top: 24px;
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
            
            .info-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }
            
            .info-value {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="success-icon">✓</div>
            <h1>Ticket Resolved Successfully</h1>
            <p>Your support request has been completed</p>
        </div>
        
        <!-- Body -->
        <div class="email-body">
            <p>We've successfully resolved your support ticket. Our team has carefully reviewed and addressed your request.</p>
            
            <!-- Ticket Information -->
            <div class="ticket-info">
                <h3>📋 Ticket Details</h3>
                <div class="info-row">
                    <span class="info-label">Ticket ID</span>
                    <span class="info-value">#{{ $ticket->id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Type</span>
                    <span class="info-value">{{ $ticket->type }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Priority</span>
                    <span class="info-value">
                        <span class="priority-badge priority-{{ strtolower($ticket->priority) }}">
                            {{ $ticket->priority }}
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Module</span>
                    <span class="info-value">{{ $ticket->module }}</span>
                </div>
                @if($ticket->tags)
                <div class="info-row">
                    <span class="info-label">Tags</span>
                    <span class="info-value">{{ $ticket->tags }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Resolved Date</span>
                    <span class="info-value">{{ $ticket->updated_at->format('M d, Y \a\t h:i A') }}</span>
                </div>
            </div>
            
            <!-- Original Message -->
            <div class="message-section">
                <h4>🔍 Your Original Request</h4>
                <div class="original-message">
                    "{{ Str::limit($ticket->message ?? $ticket->description, 300) }}"
                </div>
            </div>
            
            <!-- Resolution Notes -->
            @if($ticket->note)
            <div class="message-section">
                <h4>💡 Resolution Summary</h4>
                <div class="resolution-notes">
                    {{ $ticket->note }}
                </div>
            </div>
            @endif
                        
            <div class="footer-text">
                <p><strong>Need more help?</strong> If you have any questions about this resolution or need further assistance, feel free to create a new support ticket. We're always here to help!</p>
                
                <p style="margin-top: 16px;">Thank you for your patience while we worked on your request. We truly value your business and appreciate the opportunity to serve you.</p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div class="signature">Best regards,<br>The Support Team</div>
            <div class="company-info">
                {{ config('app.name') }} • Committed to Excellence
            </div>
        </div>
    </div>
</body>
</html>