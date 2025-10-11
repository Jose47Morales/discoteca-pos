@extends('layouts.app')

@section('title', 'Dashboard - Sistema POS Discoteca')

@push('styles')
<style>
    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-card {
        border-radius: 15px;
        border: none;
        color: white;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
        pointer-events: none;
    }

    .bg-sales { background: linear-gradient(135deg, #43cea2 0%, #185a9d 100%); }
    .bg-orders { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .bg-customers { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .bg-products { background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); }

    .transaction-card, .products-card {
        border-radius: 15px;
        background: #2c3e50;
        color: white;
        border: none;
    }

    .status-badge {
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-completed { background: #27ae60; }
    .status-pending { background: #f39c12; }

    .product-rank {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        font-size: 0.8rem;
    }

    .rank-1 { background: #e91e63; }
    .rank-2 { background: #9c27b0; }
    .rank-3 { background: #673ab7; }
    .rank-4 { background: #3f51b5; }
    .rank-5 { background: #2196f3; }

    .monthly-overview {
        background: #34495e;
        padding: 1rem;
        border-radius: 15px;
        color: white;
    }

    .metric-value {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .metric-change {
        font-size: 0.9rem;
        opacity: .8;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .quick-action-btn{
        border-radius: 15px;
        padding: 12px 24px;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
    }

    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-4">
    <div class="dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="mb-2">
                    <i class="fas fa-music me-3"></i> Discoteca POS Dashboard
                </h1>
                <p class="mb-0 fs-5">
                    Bienvenido de nuevo, <strong>{{ Auth::user()->name }}</strong>
                </p>
                <small class="opacity-75">{{ now()->format('l, F j, Y') }}</small>
            </div>
            <div class="col-md-4 text-end">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('sales.create') }}" class="btn btn-light quick-action-btn">
                        <i class="fas fa-shopping-cart me-2"></i> Nueva Venta
                    </a>
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-light quick-action-btn">
                        <i class="fas fa-chart-line me-2"></i> Reportes
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-light quick-action-btn">
                        <i class="fas fa-boxes me-2"></i> Productos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6">
            <div class="card stat-card bg-sales h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="mb-2 text-white-50">
                                <i class="fas fa-dollar-sign me-2"></i> Ventas de Hoy
                            </p>
                            <h3 class="mb-1">${{ number_format($todaySales, 2) }}</h3>
                            <small class="{{ $salesGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                                <i class="fas fa-arrow-{{ $salesGrowth >= 0 ? 'up' : 'down' }} me-1"></i>
                                {{ $salesGrowth >= 0 ? '+' : '' }}{{ number_format($salesGrowth, 1) }}% que ayer
                            </small>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-chart-line fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card stat-card bg-orders h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="mb 2 text-white-50">
                                <i class="fas fa-shopping-cart me-2"></i> Ordenes de Hoy
                            </p>
                            <h3 class="mb-1">{{ number_format($todayOrders) }}</h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i> + {{ $lastHourOrders }} en la Última Hora
                            </small>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-shopping-cart fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card stat-card bg-customers h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="mb-2 text-white-50">
                                <i class="fas fa-users me-2"></i> Clientes de Hoy
                            </p>
                            <h3 class="mb-1">{{ $activeCustomers }}</h3>
                            <small class="text-white-50">
                                En el lugar
                            </small>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-users fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card stat-card bg-products h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="mb-2" style="color: #8b4513;">
                                <i class="fas fa-star me-2"></i> Mejor Producto
                            </p>
                            <h5 class="mb-1 text-dark">{{ $topProduct->name ?? 'No hay ventas' }}</h5>
                            <small class="text-muted">
                                {{ $topProduct ? $topProduct->total_sold . ' vendido hoy' : '¡Empieza a vender!' }}
                            </small>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-cocktail fa-2x" style="color: #8b4513; opacity: 0.5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card transaction-card h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="text-white mb-0">
                        <i class="fas fa-receipt me-2"></i> Transacciones recientes
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($recentTransactions as $transaction)
                    <div class="d-flex justify-content-between align-items-center py-3 {{ !$loop->last ? 'border-bottom border-secondary' : '' }}">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <strong>{{ $transaction['table_name'] }}</strong>
                                <span class="status-badge status-{{ $transaction['status'] === 'pagado' ? 'completed' : 'pending' }} ms-2">
                                    {{ $transaction['status'] }}
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="text-{{ $transaction['status'] === 'pagado' ? 'success' : 'warning' }} fw-bold">
                                ${{ number_format($transaction['total'], 2) }}
                            </div>
                            <small class="text-white-50">
                                {{ $transaction['items_count'] }} items • {{ $transaction['time_ago'] }}
                            </small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-receipt fa-2x text-white-50 mb-2"></i>
                        <p class="text-white-50 mb-0">No hay Transacciones</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card products-card h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="text-white mb-0">
                        <i class="fas fa-chart-line me-2"></i> Productos mas Vendidos
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($topProductsToday as $index => $product)
                    <div class="d-flex justify-content-between align-items-center py-3 {{ !$loop->last ? 'border-bottom border-secondary' : '' }}">
                        <div class="d-flex align-items-center">
                            <div class="product-rank rank-{{ $index + 1 }} me-3">{{ $index + 1 }}</div>
                            <div>
                                <strong>{{ $product->name }}</strong>
                                <br><small class="text-white-50">{{ $product->total_sold }} vendido</small>
                            </div>
                        </div>
                        <div class="text-success fw-bold">${{ number_format($product->total_revenue, 2) }}</div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line fa-2x text-white-50 mb-2"></i>
                        <p class="text-white-50 mb-0">No hay productos vendidos</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card monthly-overview">
                <div class="vard-header bg-transparent border-0 pb-0">
                    <h5 class="text-white mb-0">
                        <i class="fas fa-calendar-alt me-2"></i> Resumen Mensual
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-6">
                            <div class="border-end border-secondary pe-4">
                                <p class="text-white-50 mb-2">Crecimiento mensual</p>
                                <div class="metric-value text-success">${{ number_format($monthlyRevenue, 2) }}</div>
                                <div class="metric-change {{ $monthlyGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="fas fa-arrow-{{ $monthlyGrowth >=0 ? 'up' : 'down' }} me-1"></i>
                                    {{ $monthlyGrowth >= 0 ? '+' : '' }}{{ number_format($monthlyGrowth, 1) }}% vs Mes Anterior
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-secondary pe-4">
                                <p class="text-white-50 mb-2">Promedio de Ventas</p>
                                <div class="metric-value text-info">${{ number_format($averageOrderValue, 2) }}</div>
                                <div class="metric-change {{ $aovGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="fas fa-arrow-{{ $aovGrowth >= 0 ? 'up' : 'down' }} me-1"></i>
                                    {{ $aovGrowth >= 0 ? '+' : '' }}{{ number_format($aovGrowth, 1) }}% de Mejora
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection