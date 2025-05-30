<!DOCTYPE html>
<html>
<head>
    <title>Final Payment Due</title>
</head>
<body>
    <p>Dear Startup,</p>

    <p>This is a kind reminder that the final payment for your order is now due.</p>

    <ul>
        <li><strong>Factory:</strong> {{ $factoryName }}</li>
        <li><strong>Request Description:</strong> {{ $requestDescription }}</li>
        <li><strong>Amount Due:</strong> ${{ number_format($amountDue, 2) }}</li>
    </ul>

    <p>Please proceed with the payment at your earliest convenience to complete the deal.</p>

    <p>Thanks,<br>
    {{ config('app.name') }}</p>
</body>
</html>
