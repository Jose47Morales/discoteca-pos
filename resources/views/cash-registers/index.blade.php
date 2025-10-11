@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="mb-0">
            <i class="fas fa-cash-register"></i> Cajas
        </h3>
        <a href="{{ route('cash-registers.open') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Nueva Caja
        </a>
        <a href="{{ route('reports.pdf', ['type' => 'cash']) }}?{{ request()->getQueryString() }}" class="btn btn-danger btn-sm">
            <i class="fas fa-file-pdf"></i> Exportar PDF
        </a>
        <a href="{{ route('reports.excel', ['type' => 'cash']) }}?{{ request()->getQueryString() }}" class="btn btn-success btn-sm">
            <i class="fas fa-file-excel"></i> Exportar Xlsx
        </a>
    </div>
    <form method="GET" action="{{ route('cash-registers.index') }}" class="row my-3 g-2">
        <div class="col-md-2">
            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
        </div>
        <div class="col-md-2">
            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-control">
                <option value="">-- Estado --</option>
                <option value="abierta" {{ request('status') == 'abierta' ? 'selected' : '' }}>Abierta</option>
                <option value="cerrada" {{ request('status') == 'cerrada' ? 'selected' : '' }}>Cerrada</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="user_id" class="form-control">
                <option value="">-- Cajero --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex">
            <button class="btn btn-primary me-2">Filtrar</button>
            <a href="{{ route('cash-registers.index') }}" class="btn btn-secondary">Limpiar</a>
        </div>
    </form>

    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cajero</th>
                <th>Fecha Apertura</th>
                <th>Fecha Cierre</th>
                <th>Estado</th>
                <th>Total Ventas</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cashRegisters as $cashRegister)
                <tr>
                    <td>{{ $cashRegister->id }}</td>
                    <td>{{ $cashRegister->user->name }}</td>
                    <td>{{ $cashRegister->opened_at->format('d/m/Y H:i') }}</td>
                    <td>
                        {{ $cashRegister->closed_at ? $cashRegister->closed_at->format('d/m/Y H:i') : 'Aún abierta' }}
                    </td>
                    <td>
                        <span class="badge {{ $cashRegister->status === 'abierta' ? 'bg-success' : 'bg-secondary' }}"> 
                            {{ ucfirst($cashRegister->status) }} 
                        </span>
                    </td>
                    <td>{{ number_format($cashRegister->sales->sum('total'), 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('cash-registers.show', $cashRegister) }}" 
                            class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($cashRegister && $cashRegister->status === 'abierta')
                            <a href="{{ route('cash-registers.close', $cashRegister) }}" 
                                class="btn btn-danger btn-sm">
                                <i class="fas fa-close"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No hay cajas registradas</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection