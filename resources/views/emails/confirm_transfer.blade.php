<!DOCTYPE html>
<html>
<head>
    <title>Confirm Transfer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .logo {
            max-width: 100px;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div>
            <img src="{{ asset('assets/img/slogo.png') }}" alt="CSS Logo" class="logo">
        </div>
        <h1>Congratulations!</h1>
        <p>Hello Admin,</p>
        <p>Payment has been made by <b>{{ $transfer->email }}</b>.</p>
        <p>The confirmation link for this payment is:</p>
        <p><a href="{{ $transfer->confirmation_link }}">{{ $transfer->confirmation_link }}</a></p>
        <a href="{{ route('home') }}" class="btn">Go to Home</a>
    </div>
</body>
</html>
