@component('mail::message')
# Guardian Added You as Ward

Hello {{ $student->profile->full_name }},

We wanted to let you know that {{ $guardian->profile->full_name }} has added you as their ward. 
Please click on the button below to confirm that {{ $guardian->profile->full_name }} is now your guardian.

@component('mail::button', ['url' => route('ward.confirm', $confirmationToken)])
Confirm Guardian
@endcomponent

Thank you,
Central School System.
@endcomponent
