<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\User;
use App\Models\SaleDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index(){

        if(Auth::user()->role !== 'admin'){
            abort(403, 'Acceso denegado');
        }

        // Fechas para comparar
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endLastMonth = Carbon::now()->subMonth()->endOfMonth();

        // Ventas día actual
        $todaySales = 0;
        $yesterdaySales = 0;
        $salesGrowth = 0;

        if(Schema::hasTable('sales')){
            $todaySales = Sale::whereDate('created_at', $today)
                                ->where('status', 'pagado')
                                ->sum('total');

            $yesterdaySales = Sale::whereDate('created_at', $yesterday)
                                ->where('status', 'pagado')
                                ->sum('total');

            $salesGrowth = $yesterdaySales > 0 ? (($todaySales - $yesterdaySales) / $yesterdaySales) * 100 : 0;
        }

        // Ordenes día actual
        $todayOrders = 0;
        $lastHourOrders = 0;

        if(Schema::hasTable('sales')){
            $todayOrders = Sale::whereDate('created_at', $today)->count();
            $lastHourOrders = Sale::where('created_at', '>=', Carbon::now()->subHour())->count();
        }
        
        // Clientes activos
        $activeCustomers = 0;

        if(Schema::hasTable('sales')){
            $activeCustomers = Sale::whereDate('created_at', $today)
                            ->where('status', '!=', 'cancelado')
                            ->distinct('table_id')
                            ->count();
        }

        // Producto más vendido día actual
        $topProduct = null;

        if(Schema::hasTable('sale_details') && Schema::hasTable('products') && Schema::hasTable('sales')){
            $topProduct = DB::table('sale_details')
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->whereDate('sales.created_at', $today)
            ->where('sales.status', 'pagado')
            ->select('products.name', DB::raw('SUM(sale_details.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->first();
        }
        

        // Transacciones recientes
        $recentTransactions = collect([]);
        if(Schema::hasTable('sales')){
            $recentTransactions = Sale::with(['table', 'items'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($sale) {
                return [
                    'id' => $sale->id,
                    'table_name' => $sale->table ? $sale->table->name : 'Invitado',
                    'status' => $sale->status,
                    'total' => $sale->total,
                    'items_count' => $sale->items->count(),
                    'created_at' => $sale->created_at,
                    'time_ago' => $sale->created_at->diffForHumans()
                ];
            });
        }
        

        // Productos mas vendidos día actual
        $topProductsToday = collect([]);
        if(Schema::hasTable('sale_details') && Schema::hasTable('products') && Schema::hasTable('sales')){
            $topProductsToday = DB::table('sale_details')
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->whereDate('sales.created_at', $today)
            ->where('sales.status', 'pagado')
            ->select(
                'products.name',
                DB::raw('SUM(sale_details.quantity) as total_sold'),
                DB::raw('SUM(sale_details.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get();
        }
        
        
        // Resumen mensual
        $monthlyRevenue = 0;
        $lastMonthRevenue = 0;
        $monthlyGrowth = 0;
        $monthlyOrdersCount = 0;
        $averageOrderValue = 0;
        $lastMonthOrdersCount = 0;
        $lastMonthAOV = 0;
        $aovGrowth = 0;

        if(Schema::hasTable('sales')){
            $monthlyRevenue = Sale::whereBetween('created_at', [$startOfMonth, Carbon::now()])
                            ->where('status', 'pagado')
                            ->sum('total');

            $lastMonthRevenue = Sale::whereBetween('created_at', [$lastMonth, $endLastMonth])
                            ->where('status', 'pagado')
                            ->sum('total');

            $monthlyGrowth = $lastMonthRevenue > 0 ? (($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;

            // Promedio de ventas mes actual
            $monthlyOrdersCount = Sale::whereBetween('created_at', [$startOfMonth, Carbon::now()])
                            ->where('status', 'pagado')
                            ->count();
        
            $averageOrderValue = $monthlyOrdersCount > 0 ? $monthlyRevenue / $monthlyOrdersCount : 0;

            // Promedio de ventas mes pasado
            $lastMonthOrdersCount = Sale::whereBetween('created_at', [$lastMonth, $endLastMonth])
                            ->where('status', 'pagado')
                            ->count();

            $lastMonthAOV = $lastMonthOrdersCount > 0 ? $lastMonthRevenue / $lastMonthOrdersCount : 0;
            $aovGrowth = $lastMonthAOV > 0 ? (($averageOrderValue - $lastMonthAOV) / $lastMonthAOV) * 100 : 0;
        }
        

        return view('dashboard', compact(
            'todaySales',
            'salesGrowth',
            'todayOrders',
            'lastHourOrders',
            'activeCustomers',
            'topProduct',
            'recentTransactions',
            'topProductsToday',
            'monthlyRevenue',
            'monthlyGrowth',
            'averageOrderValue',
            'aovGrowth',
        ));
    }

    public function refreshData()
    {
        $today = Carbon::today();

        $data = [
            'todaySales' => Sale::whereDate('crated_at', $today)
                                ->where('status', 'completed')
                                ->sum('total'),
            'todayOrders' => Sale::whereDate('created_at', $today)->count(),
            'actuveCustomers' => Sale::whereDate('created_at', $today)
                                ->where('status', '!=', 'cancelado')
                                ->distinct('table_id')
                                ->count(),
            'lastUpdate' => now()->format('H:i:s')
        ];

        return response()->json($data);
    }
}