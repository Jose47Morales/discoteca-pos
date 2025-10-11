@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalles de la Caja #{{ $cashRegister->id }}</h1>
    <a href="{{ route('cash-registers.export', $cashRegister->id) }}" 
        class="btn btn-sm btn-primary mb-4" target="_blank">
        <i class="fas fa-file-pdf"></i> Exportar PDF
    </a>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Cajero:</strong> {{ $cashRegister->user->name }}</p>
            <p><strong>Fecha Apertura:</strong> {{ $cashRegister->opened_at->format('d/m/Y H:i') }}</p>
            <p><strong>Fecha Cierre:</strong> 
                {{ $cashRegister->closed_at ? $cashRegister->closed_at->format('d/m/Y H:I') : 'Aún abierta' }}
            </p>
            <p><strong>Estado:</strong> 
                <span class="badge {{ $cashRegister->status === 'abierta' ? 'bg-success' : 'bg-secondary' }}"> {{ ucfirst($cashRegister->status) }} </span>
            </p>
            <p><strong>Total ventas:</strong> {{ number_format($cashRegister->sales->sum('total'), 0, ',', '.') }}</p>
        </div>
    </div>

    <h3>Ventas asociadas</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Venta</th>
                <th>Fecha</th>
                <th>Vendedor</th>
                <th>Método de Pago</th>
                <th>Estado</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cashRegister->sales as $sale)
                <tr>
                    <td>{{ $sale->id }}</td>
                    <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $sale->user->name ?? 'Vendedor anónimo' }}</td>
                    <td>
                        @if($sale->status === 'pendiente')
                            Sin cancelar
                        @else    
                            {{ ucfirst($sale->payment_method) }}
                        @endif
                    </td>
                    <td>
                        <span class="badge
                            @if($sale->status === 'pagado') bg-success
                            @elseif($sale->status === 'pendiente') bg-warning
                            @else bg-danger
                            @endif">
                            {{ $sale->status }}
                        </span>
                    </td>
                    <td>{{ number_format($sale->total, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No hay ventas registradas en esta caja.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('cash-registers.index') }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection