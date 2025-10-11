@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-users"></i> Gestión de Usuarios
                    </h3>
                    <a href="{{ route('users.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Nuevo Usuario
                    </a>
                </div>

                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <form method="get">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Buscar usuarios..."
                                        value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-2">
                            <select name="role" class="form-select" onchange="this.form.submit()">
                                <option value="">Todos los roles</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="vendedor" {{ request('role') == 'vendedor' ? 'selected' : '' }}>Vendedor</option>
                                <option value="cajero" {{ request('role') == 'cajero' ? 'selected' : '' }}>Cajero</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tabla de usuarios -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach($user->roles as $role)
                                        <span class="badge bg-primary">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{ route('users.show', $user) }}" class="btn btn-info btn-sm" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <p class="text-muted">No hay usuarios registrados</p>
                                        <a href="{{ route('users.create') }}" class="btn btn-success">Crear primer usuario</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($users->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $users->links() }}
                    </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Cuando se cambia el filtro de rol
    document.querySelector('select[name="role"]').addEventListener('change', function() {
        this.closest('form').submit();
    });
</script>
@endpush