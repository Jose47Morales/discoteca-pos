@extends('layouts.app')

@section('title', 'Gestión de Ventas')

@push('styles')
<style>
    .filter-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .stats-card h4 {
        font-size: 2rem;
        font-weight: bold;
        margin: 0;
    }

    .stats-card p {
        margin: 0;
        opacity: 0.9;
    }

    .table-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }

    .badge-status {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .export-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .pagination {
        margin: 0;
        gap: 0.25rem;
    }

    .pagination .page-item {
        margin: 0;
    }

    .pagination .page-link {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        color: #667eea;
        padding: 0.5rem 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .pagination .page-link:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
        transform: translateY(-2px);
    }
    
    .pagination .page-item.active .page-link {
        background: #667eea;
        border-color: #667eea;
        color: white;
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
    }
    
    .pagination .page-item.disabled .page-link {
        background: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
        cursor: not-allowed;
    }
    
    .pagination .page-link svg {
        width: 20px;
        height: 20px;
        vertical-align: middle;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="fas fa-cash-register text-primary"></i> Gestión de Ventas
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('sales.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i> Nueva Venta
            </a>
        </div>
    </div>

    @if(auth()->user()->role === 'admin')
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <p class="mb-1">Total Ventas</p>
                <h4>${{ number_format($sales->sum('total'), 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <p class="mb-1">Ventas Hoy</p>
                <h4>{{ $sales->where('created_at', '>=', today())->count() }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <p class="mb-1">Pendientes</p>
                <h4>{{ $sales->where('status', 'pendiente')->count() }}</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <p class="mb-1">Completadas</p>
                <h4>{{ $sales->where('status', 'pagado')->count() }}</h4>
            </div>
        </div>
    </div>
    @endif

    @if(auth()->user()->role === 'admin')
    <div class="filter-card">
        <form method="GET" class="row mb-3">
            <div class="col-md-3">
                <label for="from" class="form-label">
                    <i class="fas fa-calendar me-1"></i> Desde
                </label>
                <input type="date" name="from" id="from" class="form-control" value="{{ request('from') }}">
            </div>
            <div class="col-md-3">
                <label for="to" class="form-label">
                    <i class="fas fa-calendar me-1"></i> Hasta
                </label>
                <input type="date" name="to" id="to" class="form-control" value="{{ request('to') }}">
            </div>
            <div class="col-md-2">
                <label for="user_id" class="form-label">
                    <i class="fas fa-user me-1"></i> Vendedor
                </label>
                <select name="user_id" id="user_id" class="form-select">
                    <option value="">Todos</option>
                    @foreach(\App\Models\User::all() as $v)
                    <option value="{{ $v->id }}" {{ request('user_id') == $v->id ? 'selected' : '' }}>
                        {{ $v->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="table_id" class="form-label">
                    <i class="fas fa-chair me-1"></i> Mesa
                </label>
                <select name="table_id" id="table_id" class="form-select">
                    <option value="">Todas las mesas</option>
                    @foreach(\App\Models\Table::all() as $t)
                    <option value="{{ $t->id }}" {{ request('table_id') == $t->id ? 'selected' : '' }}>
                        {{ $t->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label d-block">&nbsp;</label>
                <button class="btn btn-primary w-100" type="submit">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
            </div>
        </form>
        <div class="mt-3 d-flex justify-content-between align-items-center">
            <a href="{{ route('sales.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-redo me-1"></i> Limpiar Filtros
            </a>
            <div class="export-buttons">
                <a href="{{ route('reports.pdf', ['type' => 'sales']) }}?{{ request()->getQueryString() }}"
                    class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf me-1"></i> PDF
                </a>
                <a href="{{ route('reports.excel', ['type' => 'sales']) }}?{{ request()->getQueryString() }}"
                    class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel me-1"></i> Excel
                </a>
            </div>
        </div>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th style="width: 120px;">Estado</th>
                            <th>Mesa</th>
                            <th>Vendedor</th>
                            <th style="width: 130px;">Total</th>
                            <th style="width: 160px;">Fecha</th>
                            <th style="width: 180px;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                        <tr>
                            <td><strong>{{ $sale->id }}</strong></td>
                            <td>
                                @if($sale->status === 'pendiente')
                                <span class="badge badge-status bg-warning text-dark">
                                    <i class="fas fa-clock me-1"></i> Pendiente
                                </span>
                                @elseif($sale->status === 'pagado')
                                <span class="badge badge-status bg-success">
                                    <i class="fas fa-check-circle me-1"></i> Pagado
                                </span>
                                @else
                                <span class="badge badge-status bg-danger">
                                    <i class="fas fa-times-circle me-1"></i> Cancelado
                                </span>
                                @endif
                            </td>
                            <td>
                                <i class="fas fa-chair text-muted me-1"></i>
                                {{ $sale->table ? $sale->table->name : 'Sin mesa' }}
                            </td>
                            <td>
                                <i class="fas fa-user text-muted me-1"></i>
                                {{ $sale->user ? $sale->user->name : 'N/A' }}
                            </td>
                            <td>
                                <strong class="text-success">
                                    {{ number_format($sale->total, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ $sale->created_at->format('d/m/Y') }}
                                    <br>
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $sale->created_at->format('H:i') }}
                                </small>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('sales.show', $sale->id) }}"
                                        class="btn btn-info btn-sm"
                                        title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($sale->status === 'pendiente')
                                    <a href="{{ route('sales.edit', $sale->id) }}"
                                        class="btn btn-warning btn-sm"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif

                                    @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('sales.destroy', $sale) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('¿Seguro que deseas eliminar esta venta?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-danger btn-sm"
                                            title="Eliminar">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-3">No hay ventas registradas</p>
                                <a href="{{ route('sales.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i> Crear Primera Venta
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($sales->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    {{ $sales->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection