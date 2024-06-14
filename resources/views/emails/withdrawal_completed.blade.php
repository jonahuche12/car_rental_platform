@component('mail::message')
# Withdrawal Completed

Dear {{ $user->name }},

We are pleased to inform you that your withdrawal request has been successfully processed. The details of the transaction are as follows:

- **Account Name:** **{{ $accountName }}**
- **Bank Name:** **{{ $bankName }}**
- **Account Number:** **{{ $accountNumber }}**
- **Amount:** **₦{{ $amount }}**
- **Processed At:** **{{ $processedAt }}**

An amount of **₦{{ $amount }}** has been transferred to your specified account.

Thank you for choosing our service. If you have any questions or need further assistance, please do not hesitate to contact us.

Best Regards,  
**{{ config('app.name') }} Team**
@endcomponent
