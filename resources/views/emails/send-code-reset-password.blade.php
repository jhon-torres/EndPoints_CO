@component('mail::message')
<h1>Hemos recibido tu solicitud para restablecer la contraseña de tu cuenta.</h1>
<p>Puedes utilizar el siguiente código para recuperar tu cuenta:</p>

@component('mail::panel')
{{ $code }}
@endcomponent

<p>El código tiene una validez de una hora a partir del momento en que se envió el mensaje.</p>
@endcomponent
