@extends('layouts.app')

@section('title', 'Detalle de la venta')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-file-invoice"></i> Detalle de la venta #{{ $sale->id }}</h4>
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                <div class="card-body">
                    <p>
                        <strong>Estado:</strong>
                        @if($sale->status === 'pendiente')
                            <span class="badge bg-warning text-dark">Pendiente</span>
                        @elseif($sale->status === 'pagado')
                            <span class="badge bg-success">Pagado</span>
                        @else
                            <span class="badge bg-danger">Cancelado</span>
                        @endif
                    </p>

                    <p><strong>Mesa:</strong> {{ $sale->table ? $sale->table->name : 'Sin mesa' }}</p>
                    <p><strong>Vendedor:</strong> {{ $sale->user ? $sale->user->name : 'N/A' }}</p>
                    <p><strong>Total:</strong> {{ number_format($sale->total, 0, ',', '.') }}</p>
                    <p><strong>Fecha:</strong> {{ $sale->created_at->format('d/m/Y H:i') }}</p>
                    @if($sale->status === 'pagado')
                        <p><strong>Fecha de pago:</strong> {{ $sale->paid_at->format('d/m/Y H:i') }}</p>
                    @endif

                    <hr>

                    <h5><i class="fas fa-box"></i> Productos</h5>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered">    
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->items as $detail)
                                    <tr>
                                        <td>{{ $detail->product->name }}</td>
                                        <td>{{ $detail->quantity }}</td>
                                        <td>{{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                                        <td>{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($sale->status === 'pendiente')  
                        <h5><i class="fas fa-credit-card"></i> Registrar Pago</h5>
                        <form action="{{ route('sales.pay', $sale->id) }}" method="POST" class="mt-3">
                            @csrf
                            @method('PATCH')

                            <div class="d-flex gap-3">
                                <button type="submit" name="payment_method" value="efectivo" class="btn btn-success">
                                    <i class="fas fa-money-bill-wave"></i> Efectivo
                                </button>

                                <button type="submit" name="payment_method" value="transferencia" class="btn btn-primary">
                                    <i class="fas fa-university"></i> Transferencia
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
