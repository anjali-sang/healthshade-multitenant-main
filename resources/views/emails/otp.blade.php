<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your OTP Code</title>
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

        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }

        .otp-container {
            background-color: #f8fafc;
            border: 2px dashed #e2e8f0;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }

        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #2563eb;
            letter-spacing: 8px;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
        }

        .otp-label {
            font-size: 14px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .expiry-info {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .expiry-info strong {
            color: #d97706;
        }

        .instructions {
            margin: 20px 0;
            padding: 0 20px;
        }

        .instructions li {
            margin: 10px 0;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 14px;
        }

        .security-note {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .security-note strong {
            color: #dc2626;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="Healthshade"
                        style="max-width: 200px; height: auto; margin-bottom: 10px;">
                @else
                    <div style="font-size: 24px; font-weight: bold; color: #2563eb;">Healthshade</div>
                @endif
            </div>
            <h1 style="color: #1f2937; margin: 0;">Email Verification</h1>
        </div>

        <p>Hello,</p>
        <p>Thank you for starting your registration with us. To verify your email address and complete your
            registration, please use the One-Time Password (OTP) below:</p>

        <div class="otp-container">
            <div class="otp-label">Your OTP Code</div>
            <div class="otp-code">{{ $otp }}</div>
        </div>

        <div class="expiry-info">
            <strong>⏰ Important:</strong> This OTP will expire in <strong>3 minutes</strong>. Please use it promptly to
            complete your registration.
        </div>

        <div class="instructions">
            <h3 style="color: #374151;">How to use this OTP:</h3>
            <ol>
                <li>Return to the registration page</li>
                <li>Enter this 6-digit code in the OTP field</li>
                <li>Click "Verify OTP" to proceed</li>
                <li>Complete your registration details</li>
            </ol>
        </div>

        <div class="security-note">
            <strong>🔒 Security Notice:</strong> This OTP is confidential and should not be shared with anyone. If you
            didn't request this verification, please ignore this email or contact our support team.
        </div>

        <p>If you're having trouble with the registration process, please don't hesitate to contact our support team.
        </p>

        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; {{ date('Y') }} Healthshade. All rights reserved.</p>
        </div>
    </div>
</body>

</html>