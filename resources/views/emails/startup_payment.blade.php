<!DOCTYPE html>
<html>
<head>
    <title>Payment Required</title>
</head>
<body>
    <h2>Hello {{ $name }},</h2>
    <p>Your startup has been approved.</p>
    <p>To activate your account, please complete your payment for Package {{ $packageId }}.</p>
    <p><a href="{{ $paymentUrl }}">Click here to complete payment</a></p>
    <p>Once payment is completed, you can access your dashboard and start using our services.</p>
    <p>Thank you for joining our platform!</p>
</body>
</html>
