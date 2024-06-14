@component('mail::message')
# Withdrawal Failed

Unfortunately, your withdrawal request could not be processed. The details of the request are as follows:

- **Account Name:** **{{ $accountName }}**
- **Amount:** **₦{{ $amount }}**

The amount of **₦{{ $amount }}** has been returned to your wallet.

We apologize for any inconvenience this may have caused. If you have any questions or need further assistance, please do not hesitate to contact us.

Best Regards,  
**{{ config('app.name') }}
@endcomponent
