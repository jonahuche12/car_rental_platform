<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promotion Notification for Your Child</title>
</head>
<body>
    <p>
        Dear Guardian,
    </p>
    
    <p>
        This is to inform you that your child, {{ $student->profile->full_name }}, from {{ $student->school->name }}, has been 
        <span style="color: {{ $isPromoted ? 'green' : 'red' }}; font-weight: bold;">
            {{ $isPromoted ? 'promoted' : 'not promoted' }}
        </span>
        to the next class, {{ $student->schoolClass()->name }}.
    </p>
    
    <p>
        You can view your child's progress by clicking the following link:
        <a href="{{ route('student.progress', ['student_id' => $student->id]) }}" style="display: inline-block; background-color: #007bff; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 5px;" target="_blank">View Progress</a>
    </p>

    <p>
        If you have any questions or concerns, please feel free to contact your Ward/Child's school Administrator.
    </p>
    
    <p>
        Best Regards,
        <br>
        Central School System.
    </p>
</body>
</html>
