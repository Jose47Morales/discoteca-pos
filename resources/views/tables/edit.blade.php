@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Mesa</h1>

    <form action="{{ route('tables.update', $table) }}" method="post">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nombre de la Mesa</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $table->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Estado</label>
            <select class="form-select" id="status" name="status" required>
                <option value="disponible" {{ old('status', $table->status) === 'disponible' ? 'selected' : '' }}>Disponible</option>
                <option value="ocupado" {{ old('status', $table->status) === 'ocupado' ? 'selected' : '' }}>Ocupado</option>
                <option value="reservado" {{ old('status', $table->status) === 'reservado' ? 'selected' : '' }}>Reservado</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Mesa</button>
        <a href="{{ route('tables.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection