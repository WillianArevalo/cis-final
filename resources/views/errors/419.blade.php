@extends('errors.layout')

@section('code', '419')
@section('title', 'Sesion expirada')
@section('message', 'Tu sesion ha expirado por inactividad. Vuelve a intentarlo.')

@section('actions')
    <flux:button variant="primary" as="link" href="{{ route('login') }}" icon="arrow-right-on-rectangle">
        Iniciar sesion
    </flux:button>
    <flux:button variant="ghost" type="button" onclick="location.reload()" icon="arrow-path">
        Recargar
    </flux:button>
@endsection
