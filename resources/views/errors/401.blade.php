@extends('errors.layout')

@section('code', '401')
@section('title', 'No autorizado')
@section('message', 'Necesitas iniciar sesion para acceder a este recurso.')

@section('actions')
    <flux:button variant="primary" as="link" href="{{ route('login') }}" icon="arrow-right-on-rectangle">
        Iniciar sesion
    </flux:button>
    <flux:button variant="ghost" type="button" onclick="history.back()" icon="arrow-left">
        Volver
    </flux:button>
@endsection
