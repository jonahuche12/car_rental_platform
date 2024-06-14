@component('mail::message')
# Update Account Details Required

Hello,

We noticed that your withdrawal request is missing some account details. To complete the process, please update your account information.

- **Account Name:** {{ $accountName ?? 'Not Provided' }}
- **Bank Name:** {{ $bankName ?? 'Not Provided' }}
- **Account Number:** {{ $accountNumber ?? 'Not Provided' }}
- **Amount:** **₦{{ number_format($amount, 2) }}**

Please click the link below to update your account details:

@component('mail::button', ['url' => $updateLink])
Update Account Details
@endcomponent

Thank you for your prompt attention to this matter.

Best Regards,  
**{{ config('app.name') }} Team**
@endcomponent
