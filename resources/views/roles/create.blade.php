@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0"><i class="fas fa-user-shield me-2"></i> Crear Nuevo Rol</h1>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre del Rol</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ej: admin, vendedor" required maxlength="255">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="permissions" class="form-label">Permisos</label>
                    <div class="row">
                        @foreach(config('custom_permissions') as $groupKey => $group)
                            <div class="card mb-3">
                                <div class="card-header">
                                    <strong>{{ $group['label'] }}</strong>
                                </div>
                                <div class="card-body">
                                    @foreach($group['permissions'] as $permKey => $permLabel)
                                        <div class="form-check">
                                            <input 
                                                type="checkbox" 
                                                name="permissions[]" 
                                                class="form-check-input"
                                                value="{{ $permKey }}"
                                                id="perm_{{ $permKey }}"
                                                @if(isset($role) && $role->hasPermissionTo($permKey)) checked @endif
                                            >
                                            <label for="perm_{{ $permKey }}" class="form-check-label">
                                                {{ $permLabel }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('permissions')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> Guardar Rol
                </button>
            </form>
        </div>
    </div>
</div>
@endsection