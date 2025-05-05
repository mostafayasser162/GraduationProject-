<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Startup Rejected</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 30px;">
    <div style="background-color: #fff; padding: 20px; border-radius: 10px; max-width: 600px; margin: auto;">
        <h2 style="color: #d9534f;">Hello {{ $startup->user->name }},</h2>

        <p>We regret to inform you that your startup application "<strong>{{ $startup->name }}</strong>" has been <strong style="color: #d9534f;">rejected</strong>.</p>

        <p>If you believe this decision was made in error, feel free to contact our support team for further assistance.</p>

        <hr style="margin: 20px 0;">

        <p style="color: #888;">Thank you for your interest in our platform.</p>
        <p style="color: #888;">– The Team</p>
    </div>
</body>
</html>
