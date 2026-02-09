@extends('mail.layouts.app')

@section('content')
    <h1 class="mail-title">Cambiar contraseña</h1>
    <p class="mail-text">Hola {{ $user->name ?? 'hola' }},</p>
    <p class="mail-text">
        Recibimos una solicitud para cambiar tu contraseña. Usa el siguiente boton para continuar.
    </p>
    <p class="mail-text">
        <a href="{{ $resetUrl }}" class="mail-button" target="_blank" rel="noreferrer">
            Cambiar contraseña
        </a>
    </p>
    <div class="mail-divider"></div>
    <p class="mail-text">
        Si el boton no funciona, copia y pega este enlace en tu navegador:
    </p>
    <p class="mail-text">
        <a href="{{ $resetUrl }}" class="mail-link" target="_blank" rel="noreferrer">{{ $resetUrl }}</a>
    </p>
@endsection
