@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Reporte de Caja #{{ $cashRegister->id }}</h3>
    <p><strong>Usuario:</strong> {{ $cashRegister->user->name }}</p>
    <p><strong>Apertura:</strong> {{ $cashRegister->opened_at }}</p>
    <p><strong>Cierre:</strong> {{ $cashRegister->closed_at ?? 'Pendiente' }}</p>
    <p><strong>Monto inicial:</strong> {{ number_format($cashRegister->opening_amount, 2) }}</p>
    <p><strong>Monto final:</strong> {{ number_format($cashRegister->closing_amount, 2) }}</p>

    <h4>Resumen por método</h4>
    <ul>
        @foreach($resumenPorMetodo as $metodo => $monto)
            <li>{{ ucfirst($metodo) }}: ${{ number_format($monto, 2) }}</li>
        @endforeach
    </ul>

    <h4>Pagos detallados</h4>
    <div class="d-flex justify-content-end mb-3">
    <a href="{{ route('cash-registers.index') }}" class="btn btn-sm btn-secondary m-2">
            <i class="fas fa-arrow-left"></i> Volver
    </a>
    <a href="{{ route('cash-registers.export', $cashRegister->id) }}" class="btn btn-sm btn-primary m-2" target="_blank">
        <i class="fas fa-file-pdf"></i> Exportar PDF
    </a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Venta</th>
                <th>Monto</th>
                <th>Método</th>
                <th>Usuario</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $pago)
                <tr>
                    <td>#{{ $pago->sale_id }}</td>
                    <td>${{ number_format($pago->amount, 2) }}</td>
                    <td>{{ ucfirst($pago->payment_method) }}</td>
                    <td>{{ $pago->user->name }}</td>
                    <td>{{ $pago->paid_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection