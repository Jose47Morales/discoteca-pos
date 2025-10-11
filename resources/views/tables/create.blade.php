@extends('layouts.app') 
@section('title', 'Crear Mesa')
@section('content')
<div class="container">
    <h1 class="mb-4">Crear Mesa</h1>
    <form action="{{ route('tables.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nombre de la Mesa</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Estado</label>
            <select class="form-select" id="status" name="status" required>
                <option value="disponible">Disponible</option>
                <option value="ocupado">Ocupado</option>
                <option value="reservado">Reservado</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Crear Mesa</button>
        <a href="{{ route('tables.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection