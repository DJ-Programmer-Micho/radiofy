<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Your Password</title>
</head>
<body>
    <h2>Password Reset Request</h2>
    <p>You are receiving this email because we received a password reset request for your account.</p>
    <p>
        Click the link below to reset your password:
        <a href="{{ route('lis.password', ['token' => $token, 'email' => urlencode($email)]) }}">
            Reset Password
        </a>
    </p>
    <p>If you did not request a password reset, no further action is required.</p>
    <p>Regards,<br>The Mradiofy Team</p>
</body>
</html>
