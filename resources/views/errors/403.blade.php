{{-- resources/views/errors/403.blade.php --}}
@extends('layouts.app')

@section('title', 'Acceso denegado')

@section('content')
<div class="container text-center mt-5">
    <h1 class="display-4 text-danger">403 - Acceso denegado</h1>
    <p class="lead">No tienes permisos para acceder a esta página.</p>

    <a href="{{ url()->previous() }}" class="btn btn-primary mt-3">
        <i class="fas fa-arrow-left"></i> Volver atrás
    </a>
</div>
@endsection
