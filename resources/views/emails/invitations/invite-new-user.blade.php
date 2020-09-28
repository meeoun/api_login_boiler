@component('mail::message')
# Hi

You have been invited to join the team
**{{$invitation->team->name}}**.
Because you are not signed up.  Please
[Register for free]({{ $url  }}), then you can accept or reject!
@component('mail::button', ['url' => $url])
Register For Free
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
