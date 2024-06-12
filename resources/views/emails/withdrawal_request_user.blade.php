<!DOCTYPE html>
<html>
<head>
    <title>Withdrawal Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #007bff;
        }
        .content {
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .content p {
            margin: 0 0 10px;
        }
        .content a {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Withdrawal Request</h1>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>We have received a withdrawal request for <strong>{{ $user->profile->full_name }}</strong>.</p>
            <p>You have requested to withdraw an amount of <strong>₦{{ number_format($amount, 2) }}</strong>.</p>
            <p>To complete this process, please update your account number by clicking the link below:</p>
            <p><a href="{{ route('withdrawal.updateUserAccount', ['token' => $token]) }}">Update Account Number</a></p>
            <p>Thank you,</p>
            <p><strong>The Central School System Team</strong></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Central School System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
