<x-mail::message>
# 🌍 Incoming Transmission: {{ ucfirst($data['department']) }}

A new user has initialized a transmission via the Farmora Contact Protocol.

<x-mail::panel>
**Sender Identification**
- **Name:** {{ $data['first_name'] }} {{ $data['last_name'] }}
- **Return Address:** [{{ $data['email'] }}](mailto:{{ $data['email'] }})
- **Routing Dept:** {{ ucfirst($data['department']) }}
</x-mail::panel>

### Message Payload
<x-mail::panel>
{{ $data['message'] }}
</x-mail::panel>

<x-mail::button :url="config('app.url') . '/admin'" color="success">
Access Admin Dashboard
</x-mail::button>

*This is an automated transmission from the Farmora Ecosystem.*
</x-mail::message>
