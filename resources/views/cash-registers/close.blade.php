@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Cerrar Caja #{{ $cashRegister->id }}</h2>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Cajero:</strong> {{ $cashRegister->user->name }}</p>
            <p><strong>Apertura:</strong> {{ $cashRegister->opened_at->format('d/m/Y H:i') }}</p>
            <p><strong>Monto Apertura:</strong> {{ number_format($cashRegister->opening_amount, 2) }}</p>
        </div>
    </div>
    
    @if($salesByUser && $salesByUser->count())
        <h4 class="mb-3">Resumen de Ventas</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Cantidad Ventas</th>
                    <th>Total Vendido</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salesByUser as $data)
                    <tr>
                        <td>{{ $data['user']->name }}</td>
                        <td>{{ $data['count'] }}</td>
                        <td>{{ number_format($data['total'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-dark">
                    <th>Total Caja</th>
                    <th>{{ $cashRegister->sales()->where('status', 'pagado')->count() }}</th>
                    <th>
                        ${{ number_format($cashRegister->sales()->where('status', 'pagado')->sum('total'), 2) }}
                    </th>
                </tr>
            </tfoot>
        </table>
    @endif
    <form action="{{ route('cash-registers.close.store', $cashRegister->id) }}" method="post">
        @csrf
        @method('PUT')

        <div class="form-group mt-3">
            <label for="closing_amount">Monto contado</label>
            <input type="number" step="0.01" name="closing_amount" id="closing_amount" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-danger mt-3">Cerrar Caja</button>
        <a href="{{ route('cash-registers.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
</div>
@endsection