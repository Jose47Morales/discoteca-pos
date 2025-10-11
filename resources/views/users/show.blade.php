@extends('layouts.app')

@section('title', 'Detalle de Usuario')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0"><i class="fas fa-user me-2"></i> Detalle de Usuario</h1>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <h3><span class="text-muted">Nombre:</span><strong> {{ $user->name }} </strong></h3>
            <h5 class="mt-2 mb-3"><span class="text-muted">Email:</span> {{ $user->email }} </h5>
            <hr>
            <h5 class="mb-2">Roles Asignados:</h5>
            <div class="mb-3">
                @forelse($user->roles as $role)
                    <span class="badge bg-primary mb-1">{{ $role->name }}</span>
                @empty
                    <span class="text-muted">Sin rol asignado</span>
                @endforelse
            </div>

            <h5 class="mb-2">Permisos efectivos:</h5>
            <div>
                @if($user->getAllPermissions()->isNotEmpty())
                <div class="row">
                    @foreach(config('custom_permissions') as $groupKey => $group)
                    @php
                    $groupPermissions = collect($group['permissions'])->keys();
                    $assignedPermissions = $user->getAllPermissions()->pluck('name');
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
                <span class="text-muted">No hay permisos directos asignados a este usuario.</span>
                @endif
            </div>
            <div class="card-footer d-flex gap-2">
                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection