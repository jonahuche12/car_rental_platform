<!DOCTYPE html>
<html>
<head>
    <title>Account Activation</title>
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
            text-align: center;
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
            <h1>Welcome to Central School System!</h1>
            <p>Hello {{ $user->name }},</p>
            <p>We are pleased to inform you that your package has been successfully activated. We are excited to have you as a part of our community.</p>
            <p>As a welcome gift, you have been credited with 200 School Connects. We hope this helps you make the most out of our platform.</p>
            <p>Feel free to explore and take advantage of the numerous resources available to you.</p>
            <a href="{{ route('home') }}" class="button">Go to Home</a>
        </div>
    </div>
</body>
</html>
