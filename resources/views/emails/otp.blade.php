<!DOCTYPE html>
<html>
<head>
    <title>{{ $purpose }}</title>
</head>
<body>
<p>Hello {{ $user->name ?? 'User' }},</p>
<p>Your OTP for {{ strtolower($purpose) }} is: <strong>{{ $otp }}</strong></p>
<p>This code will expire in 10 minutes.</p>
<p>Thanks,</p>
<p>{{ config('app.name') }}</p>
</body>
</html>
