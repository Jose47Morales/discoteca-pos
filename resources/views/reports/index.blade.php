@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="container">
    <h1 class="mb-4"><i class="fas fa-chart-line"></i> Reportes</h1>

    <form method="GET" class="row mb-4">
        <div class="col-md-4">
            <label for="from" class="form-label">Desde</label>
            <input type="date" id="from" name="from" value="{{ $from ?? '' }}" class="form-control">
        </div>
        <div class="col-md-4">
            <label for="to" class="form-label">Hasta</label>
            <input type="date" id="to" name="to" value="{{ $to ?? '' }}" class="form-control">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Filtrar
            </button>
        </div>
    </form>

    <div class="row text-center mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Total Ventas</h5>
                    <h3>{{ number_format($metrics['ventas_total'], 0, ',', '.') ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>N° Ventas</h5>
                    <h3>{{ number_format($metrics['ventas_count'], 0, ',', '.') ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Promedio Venta</h5>
                    <h3>{{ number_format($metrics['promedio_venta'], 0, ',', '.') ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Cajas</h5>
                    <p>
                        <span class="badge bg-success">Abiertas: {{ $metrics['cajas_abiertas'] }}</span>
                        <span class="badge bg-secondary">Cerradas: {{ $metrics['cajas_cerradas'] }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-6 text-center">
            <h5><i class="fas fa-shopping-cart"></i> Ventas</h5>
            <a href="{{ route('reports.pdf', ['type' => 'sales']) }}?{{ request()->getQueryString() }}"
                class="btn btn-danger me-2">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
            <a href="{{ route('reports.excel', ['type' => 'sales']) }}?{{ request()->getQueryString() }}"
                class="btn btn-success">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </a>
        </div>

        <div class="col-md-6 text-center">
            <h5><i class="fas fa-cash-register"></i> Cajas</h5>
            <a href="{{ route('reports.pdf', ['type' => 'cash']) }}?{{ request()->getQueryString() }}"
                class="btn btn-danger me-2">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
            <a href="{{ route('reports.excel', ['type' => 'cash']) }}?{{ request()->getQueryString() }}"
                class="btn btn-success">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </a>
        </div>
    </div>

</div>
</div>

</div>
</div>
</div>
@endsection