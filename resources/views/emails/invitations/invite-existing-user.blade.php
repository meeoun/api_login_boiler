@component('mail::message')
    # Hi

    You have been invited to join the team
    **{{$invitation->team->name}}**.
    @component('mail::button', ['url' => $url])
        View Invitation
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
