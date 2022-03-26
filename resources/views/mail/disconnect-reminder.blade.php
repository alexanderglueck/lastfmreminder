@component('mail::message')
# last.fm disconnected

It appears last.fm did not receive any scrobbles within the last 24 hours.
If you didn't listen to any songs you can disregard this message.
If you did listen to some songs, check if last.fm is still connected with Spotify.

@component('mail::button', ['url' => 'https://www.last.fm/settings/applications'])
Check connection
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
