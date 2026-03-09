@component('mail::message')
# Wachtwoord resetten

We hebben een verzoek ontvangen om je wachtwoord te resetten voor **DigiDate**.

Klik op de knop hieronder om een nieuw wachtwoord in te stellen:

@component('mail::button', ['url' => $url])
Wachtwoord resetten
@endcomponent

Als je dit verzoek niet hebt gedaan, hoef je niets te doen.

Met vriendelijke groet,<br>
**DigiDate**
@endcomponent

