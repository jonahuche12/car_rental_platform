<!DOCTYPE html>
<html>
<head>
    <title>Payment Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f5f5; text-align: center; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; border: 1px solid #e0e0e0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
        <div style="text-align: center;">
            <img src="{{ asset('assets/img/slogo.png') }}" alt="Skillzart Logo" style="max-width: 100px; margin-bottom: 20px;">
        </div>
        @if($transfer->status == "payment complete")
        <h1>Payment Confirmation</h1>
        <p>Hello,</p>
        <p>We are pleased to inform you that the payment made by <b>{{ $transfer->email }}</b> has been received successfully.</p>
        <p>The Payment Session ID is: <br> <a href="#">{{ $transfer->payment_session_id }}</a></p>
        @else
        <h1>Payment Notification</h1>
        <p>Hello,</p>
        <p>We regret to inform you that the payment made by <b>{{ $transfer->email }}</b> was not successful.</p>
        <p>Your payment appears to be incomplete. If you require assistance, please contact our support team at <a href="mailto:support@skillzart.com">support@skillzart.com</a>.</p>
        <p>The Payment Session ID is: <br> <a href="#">{{ $transfer->payment_session_id }}</a></p>
        @endif
        <a href="{{ route('home') }}" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #007bff; color: #ffffff; text-decoration: none; border-radius: 5px;">Return to Home</a>
    </div>
</body>
</html>