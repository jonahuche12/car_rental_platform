<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promotion Notification</title>
</head>
<body>
    <p>
        Dear <strong>{{ $student->profile->full_name }}</strong>,
    </p>
    
    <p>
        This is to inform you that you 
        <span style="color: {{ $isPromoted ? 'green' : 'red' }}; font-weight: bold;">
            {{ $isPromoted ? ' have been promoted' : 'have not been promoted' }}
        </span>
        to the next class.
    </p>
    
    <p>
        Your current class is: <strong>{{ $student->schoolClass->name }}</strong>
    </p>
    
    <p>
        If you have any questions, please feel free to contact Your School Administrator.
    </p>
    
    <p>
       Best Regards,
        <br>
        Central School System
    </p>
</body>
</html>
