<!DOCTYPE html>
<html>
<head>
    <title>Free Trial Activated</title>
</head>
<body>
    <h2>Hello {{ $name }},</h2>
    <p>Congratulations! Your startup has been approved.</p>
    <p>You now have a <strong>14-day free trial</strong>.</p>
    <p>Your trial will end on: <strong>{{ $trialEnd }}</strong>.</p>
    <p>You can now <a href="{{ $loginUrl }}">login to your dashboard</a> and start using the platform.</p>
    <p>Thank you for joining us!</p>
</body>
</html>
