<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Term Added</title>
</head>
<body style="font-family: Arial, sans-serif;">

    <h2 style="color: #333;">New Term Added</h2>
    <p style="color: #666;">
        Dear {{ $owner->profile->full_name ?? 'School Owner' }},<br><br>
        We are pleased to inform you that a new term (<strong>{{ $term->name }}</strong>) has been added. All your school activities will still be saved in the current term. However, we encourage you to update your school activities to reflect the new term.<br><br>
        To update, please log in to your <a href="{{ route('dashboard') }}" style="color: #007bff; text-decoration: none;">dashboard</a> and click on the 'Update' button.<br><br>
        Thank you for choosing our platform.
    </p>

</body>
</html>