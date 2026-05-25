<x-mail::message>
# ✉️ New Contact Message: {{ ucfirst($data['department']) }}

A new customer inquiry has been submitted through the Farmora contact form.

<x-mail::panel>
**Sender Details**
- **Name:** {{ $data['first_name'] }} {{ $data['last_name'] }}
- **Email:** [{{ $data['email'] }}](mailto:{{ $data['email'] }})
- **Department:** {{ ucfirst($data['department']) }}
</x-mail::panel>

### Message Content
<x-mail::panel>
{{ $data['message'] }}
</x-mail::panel>


*This is an automated notification sent from the Farmora website.*
</x-mail::message>
