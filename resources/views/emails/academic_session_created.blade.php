<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Academic Session Created</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h2 {
            color: #333;
        }
        p {
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 8px 16px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <h2>New Academic Session Created</h2>
    <p>
        Dear {{ $owner->profile->full_name ?? 'School Owner' }},<br><br>
        We are pleased to inform you that a new academic session (<strong>{{ $academicSession->name }}</strong>) has been created. All your school activities will still be saved in the current academic session. However, we encourage you to update your school activities to reflect the new session.<br><br>
        To update, please log in to your dashboard and click on the <a href="{{ route('dashboard') }}" class="button">Dashboard</a> button.<br><br>
        Thank you for choosing our platform.
    </p>

</body>
</html>
