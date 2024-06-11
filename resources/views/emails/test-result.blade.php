<!DOCTYPE html>
<html>
<head>
    <title>Your Test Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin: 20px auto;
            overflow: hidden;
        }
        .header {
            background-color: #007bff;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
            line-height: 1.6;
        }
        .content h2 {
            margin-top: 0;
            color: #333;
        }
        .content p {
            margin: 10px 0;
            color: #555;
        }
        .content strong {
            color: #007bff;
        }
        .footer {
            padding: 10px 20px;
            background-color: #f1f1f1;
            text-align: center;
            font-size: 12px;
            color: #555555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Test Results</h1>
        </div>
        <div class="content">
            <h2>Dear {{ $user->profile->full_name }},</h2>
            <p>Thank you for completing the <strong>{{ $test->title }}</strong> test.</p>
            <p>We are pleased to inform you that your score is: <strong>{{ $score }}</strong>.</p>
            <p>Your test status is: <strong style="color: {{ $passed ? '#007bff' : 'red' }};">{{ $passed ? 'Passed' : 'Failed' }}</strong></p>
            <p>We appreciate your hard work and dedication. Keep striving for excellence.</p>
            <p>Best regards,<br>The Central School System Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Central School System. All rights reserved.
        </div>
    </div>
</body>
</html>
