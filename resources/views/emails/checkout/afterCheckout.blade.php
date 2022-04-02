@component('mail::message')
# Register {{ $checkout->Camp->title }}

Hi {{ $checkout->User->name }}
<br />
Thank you for register on <strong>{{ $checkout->Camp->title }}</strong>, please see payment instruction by click the button below.

@component('mail::button', ['url' => route('dashboard')])
Get Invoice
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
