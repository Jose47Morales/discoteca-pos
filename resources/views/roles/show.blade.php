@extends('layouts.app')

@section('title', 'Detalle de Rol')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0"><i class="fas fa-user-shield me-2"></i> Detalle de Rol</h1>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <h3><span class="text-muted">Nombre:</span> <strong>{{ $role->name }}</strong></h3>
            <hr>
            <h5 class="mb-3">Permisos Asignados</h5>
            <div>
                @if($role->permissions->isNotEmpty())
                <div class="row">
                    @foreach(config('custom_permissions') as $groupKey => $group)
                    @php
                    $groupPermissions = collect($group['permissions'])->keys();
                    $assignedPermissions = $role->permissions->pluck('name');
                    $intersected = $groupPermissions->intersect($assignedPermissions);
                    @endphp

                    @if($intersected->isNotEmpty())
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <strong>{{ $group['label'] }}</strong>
                            </div>
                            <div class="card-body">
                                @foreach($intersected as $permKey)
                                <span class="badge bg-primary me-1 mb-1">{{ $group['permissions'][$permKey] }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
                @else
                <span class="text-muted">No hay permisos asignados a este rol.</span>
                @endif
            </div>
        </div>
        <div class="card-footer d-flex gap-2">
            <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este rol?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash-alt"></i> Eliminar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection