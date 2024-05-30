<!DOCTYPE html>
<html>
<head>
    <title>Scholarship Category Updated</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f7f7f7;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 24px;
            color: #007bff;
        }
        p {
            margin: 0 0 10px;
        }
        .highlight {
            background-color: #007bff;
            color: #ffffff;
            padding: 4px 8px;
            border-radius: 4px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            font-size: 16px;
            color: #ffffff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Scholarship Category Information</h1>
        <p>Dear {{ $student->profile->full_name }},</p>
        <p>We are pleased to inform you that the scholarship category <strong>{{ $category->name }}</strong> has been updated with the following details:</p>
        <p><strong>Start Date:</strong> <span class="highlight">{{ $category->start_date ? \Carbon\Carbon::parse($category->start_date)->format('l, F jS, Y \a\t g:i A') : 'N/A' }}</span></p>
        <p><strong>End Date:</strong> <span class="highlight">{{ $category->end_date ? \Carbon\Carbon::parse($category->end_date)->format('l, F jS, Y \a\t g:i A') : 'N/A' }}</span></p>
        <p>Click on the button below to start the test</p>
        <p><a href="{{ $link }}" class="btn">Start Test</a></p>
        <p>Best regards,</p>
        <p>Scholarship Team</p>
        <p>The Central School System.</p>
    </div>
</body>
</html>
