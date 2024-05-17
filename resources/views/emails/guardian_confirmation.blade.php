<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guardian Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 0;">

    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">

        <p style="font-size: 16px;">Dear {{ $guardian->profile->full_name }},</p>
        
        <p style="font-size: 16px;">We are pleased to inform you that {{ $student->profile->full_name }} has confirmed you as their guardian. You can now view the progress of  {{ $student->profile->full_name }} .</p>
        
        <p style="font-size: 16px;">To view {{ $student->profile->name }}'s progress, click the button below:</p>
        
        <table cellpadding="0" cellspacing="0" border="0">
            <tbody>
                <tr>
                    <td>
                        <a href="{{ route('student.progress', ['student_id' => $student->id]) }}" style="display: inline-block; background-color: #007bff; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 5px;" target="_blank">View Progress</a>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <p style="font-size: 16px;">If you have any questions or concerns, feel free to contact us.</p>
        
        <p style="font-size: 16px;">Thank you,<br> Central School System</p>

    </div>

</body>
</html>
