<!DOCTYPE html>
<html>
<head>
    <title>Reset Password Notification</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #333333; text-align: center;">Reset Password Notification</h2>
        <p style="color: #666666; font-size: 16px; line-height: 1.5;">
            Hello,
        </p>
        <p style="color: #666666; font-size: 16px; line-height: 1.5;">
            You are receiving this email because we received a password reset request for your account.
        </p>
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('customer.reset-password', ['token' => $token, 'email' => $email]) }}" style="background-color: #d97706; color: white; padding: 14px 28px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 16px;">Reset Password</a>
        </div>
        <p style="color: #666666; font-size: 16px; line-height: 1.5;">
            This password reset link will expire in 60 minutes.
        </p>
        <p style="color: #666666; font-size: 16px; line-height: 1.5;">
            If you did not request a password reset, no further action is required.
        </p>
        <hr style="border: none; border-top: 1px solid #eeeeee; margin: 30px 0;">
        <p style="color: #999999; font-size: 14px; text-align: center;">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>
    </div>
</body>
</html>
