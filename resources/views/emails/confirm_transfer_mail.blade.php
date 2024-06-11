<!DOCTYPE html>
<html>
<head>
    <title>Payment Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .logo {
            max-width: 100px;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
        .content {
            text-align: left;
        }
        .content h1 {
            color: #333333;
        }
        .content p {
            color: #666666;
            line-height: 1.6;
        }
        .content a {
            color: #007bff;
            text-decoration: none;
        }
        .content a:hover {
            text-decoration: underline;
        }
        .content b {
            color: #333333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div style="text-align: center;">
            <img src="{{ asset('assets/img/slogo.png') }}" alt="Skillzart Logo" class="logo">
        </div>
        <div class="content">
            @if($transfer->status == "payment complete")
                <h1>Payment Confirmation</h1>
                <p>Hello,</p>
                <p>We are pleased to inform you that the payment made by <b>{{ $transfer->email }}</b> has been received successfully.</p>
                <p>Amount Paid: <b>&#8358;{{ number_format($transfer->price, 2) }}</b></p>
                
                <p>The Payment Session ID is: <br> <a href="#">{{ $transfer->payment_session_id }}</a></p>
            @else
                <h1>Payment Notification</h1>
                <p>Hello,</p>
                <p>We regret to inform you that the payment made by <b>{{ $transfer->email }}</b> was not successful.</p>
                <p>Your payment appears to be incomplete. If you require assistance, please contact our support team at <a href="mailto:support@skillzart.com">support@skillzart.com</a>.</p>
                <p>The Payment Session ID is: <br> <a href="#">{{ $transfer->payment_session_id }}</a></p>
            @endif
            <a href="{{ route('home') }}" class="button">Return to Home</a>
        </div>
    </div>
</body>
</html>
