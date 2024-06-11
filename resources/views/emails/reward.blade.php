<!DOCTYPE html>
<html>
<head>
    <title>Reward Notification</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 0;">
    <div style="background-color: #f7f7f7; padding: 20px;">
        <div style="background-color: #ffffff; border-radius: 8px; max-width: 600px; margin: 0 auto; padding: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
            <h2 style="color: #007bff; text-align: center;">Reward Notification</h2>
            <p style="color: #333333;">Dear {{ $studentName }},</p>
            <p style="color: #333333;">Congratulations! We are pleased to inform you that you are among the top-performing students in your scholarship category <b>({{ $category_name }})</b>. Your outstanding efforts and dedication have earned you a well-deserved reward.</p>
            <p style="color: #333333;">To facilitate the processing of your reward, we kindly request that you update your account details by clicking the link below:</p>
            <p style="text-align: center;">
                <a href="{{ $updateLink }}" style="background-color: #007bff; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 4px; display: inline-block;">Update Account Details</a>
            </p>
            <p style="color: #333333;">We commend you for your hard work and wish you continued success in your academic endeavors.</p>
            <p style="color: #333333;">Best regards,</p>
            <p style="color: #333333;"><strong>The Central School System - Scholarship Team</strong></p>
        </div>
    </div>
</body>
</html>
