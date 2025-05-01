@component('mail::message')
# Invitación para unirse a {{ config('app.name') }}

Has sido invitado a unirte a nuestra plataforma.

@component('mail::button', ['url' => url('/register?token=' . $invitation->token)])
Registrarse
@endcomponent

Este enlace de invitación expirará el {{ $invitation->expires_at->format('d/m/Y') }}.

Gracias,<br>
{{ config('app.name') }}
@endcomponent
