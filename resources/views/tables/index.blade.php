@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Gestión de Mesas</h1>
    <a href="{{ route('tables.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Nueva Mesa
    </a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tables as $table)
            <tr>
                <td>{{ $table->name }}</td>
                <td>
                    @if($table->status === 'disponible')
                        <span class="badge bg-success">Disponible</span>
                    @elseif($table->status === 'ocupado')
                        <span class="badge bg-danger">Ocupado</span>
                    @else
                        <span class="badge bg-warning text-dark">Reservado</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('tables.edit', $table) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <form action="{{ route('tables.destroy', $table) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta mesa?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">No hay mesas disponibles</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $tables->links() }}
</div>
@endsection