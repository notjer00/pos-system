<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OTP Verification Code</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #f8f9fa; border-radius: 8px; padding: 30px;">
        <h1 style="color: #4f46e5; margin-bottom: 20px;">Email Verification</h1>
        
        <p>Hi {{ $userName }},</p>
        
        <p>Thank you for registering! Please use the following One-Time Password (OTP) to verify your email address:</p>
        
        <div style="background: #fff; border: 2px solid #4f46e5; border-radius: 8px; padding: 20px; text-align: center; margin: 24px 0;">
            <span style="font-size: 32px; font-weight: bold; letter-spacing: 4px; color: #4f46e5; font-family: monospace;">{{ $otpCode }}</span>
        </div>
        
        <p><strong>This code will expire in 10 minutes.</strong></p>
        
        <p>If you didn't create an account, you can safely ignore this email.</p>
        
        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 24px 0;">
        
        <p style="font-size: 12px; color: #6b7280;">This is an automated message from {{ config('app.name') }}. Please do not reply to this email.</p>
    </div>
</body>
</html>