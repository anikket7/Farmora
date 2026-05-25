<x-mail::message>
# 🔐 Verification Code Request: {{ $name }}

Welcome to the Farmora Ecosystem! Use the verification code below to verify your email address. This code is valid for **15 minutes**.

<x-mail::panel>
<h1 style="text-align: center; font-size: 32px; letter-spacing: 5px; color: #cb9f58; margin: 10px 0;">{{ $otp }}</h1>
</x-mail::panel>

If you did not initiate this request, you can safely ignore this email.

*This is an automated transmission from the Farmora Ecosystem.*
</x-mail::message>
