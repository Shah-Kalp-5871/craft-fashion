<!DOCTYPE html>
<html>
<head>
    <title>Verify Your Email</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #333333; text-align: center;">Email Verification</h2>
        <p style="color: #666666; font-size: 16px; line-height: 1.5;">
            Hello,
        </p>
        <p style="color: #666666; font-size: 16px; line-height: 1.5;">
            Thank you for registering with {{ config('app.name') }}. Please use the following One-Time Password (OTP) to verify your email address:
        </p>
        <div style="text-align: center; margin: 30px 0;">
            <span style="font-size: 32px; font-weight: bold; color: #d97706; letter-spacing: 5px;">{{ $otp }}</span>
        </div>
        <p style="color: #666666; font-size: 16px; line-height: 1.5;">
            This OTP is valid for 5 minutes. If you did not request this, please ignore this email.
        </p>
        <hr style="border: none; border-top: 1px solid #eeeeee; margin: 30px 0;">
        <p style="color: #999999; font-size: 14px; text-align: center;">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>
    </div>
</body>
</html>
