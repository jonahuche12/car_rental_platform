<!DOCTYPE html>
<html>
<head>
    <title>Scholarship Enrollment Confirmation</title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f6f6;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333333;
        }
        .content {
            padding: 20px;
            font-size: 16px;
            line-height: 1.6;
            color: #333333;
        }
        .content p {
            margin: 0 0 10px;
        }
        .button-container {
            text-align: center;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #ffffff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #777777;
            padding: 10px 0;
            border-top: 1px solid #eeeeee;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Scholarship Enrollment Confirmation</h1>
        </div>
        <div class="content">
            <p>Dear {{ $student->profile->full_name }},</p>
            <p>Congratulations! You have successfully enrolled in the <b>{{ $category->name }} </b> scholarship category under the <b>{{ $category->scholarship->title }} </b></p>
            <p>A dedicated link will be sent to you one week before the test, which you can use to take the test.</p>
            <div class="button-container">
                <a href="{{ url('/dashboard') }}" class="button">Go to Dashboard</a>
            </div>
            <p>Best regards,<br>Scholarship Team</p><br><p>Central School System</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Scholarship Program. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
