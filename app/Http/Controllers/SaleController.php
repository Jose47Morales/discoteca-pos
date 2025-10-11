<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Models\Payment;
use App\Models\Product;
use App\Models\SaleDetail;
use App\Models\Sale;
use App\Models\Table;
use App\Models\CashRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Listado de ventas.
     */
    public function index(Request $request)
    {

        $query = Sale::with('user', 'table', 'items.product')
                    ->orderBy('created_at', 'desc');

        // Filtros en vista admin
        if(Auth::user()->role === 'admin' || Auth::user()->role === 'caja') {
            
            if($request->filled('from')) {
                $query->where('created_at', '>=', $request->from);
            }

            if($request->filled('to')) {
                $query->where('created_at', '<=', $request->to);
            }

            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            if($request->filled('table_id')) {
                $query->where('table_id', $request->table_id);
            }
        } else{
            // Solo mostrar ventas del usuario no admin
            $query->where('user_id', Auth::id());
        }

        $sales = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('sales.index', compact('sales'));
    }

    /**
     * Pantalla para tomar pedidos.
     */
    public function create()
    {
        // Verifica permisos
        if (!in_array(Auth::user()->role, ['admin', 'vendedor', 'caja'])) {
            abort(403, 'No tienes permisos para aceder al sistema POS');
        }

        $user = Auth::user();

        if ($user->role === 'caja') {
            $cashRegisters = CashRegister::where('user_id', $user->id)
                ->where('status', 'abierta')
                ->get();
        } else {
            $cashRegisters = CashRegister::where('status', 'abierta')
                 ->orderBy('opened_at', 'desc')
                 ->get();
        }

        if ($cashRegisters->isEmpty()) {
            return redirect()->route('cash-registers.open')
                        ->with('error', 'No hay ninguna caja abierta para registrar ventas.');
        }

        $products = Product::where('stock', '>', 0)
                        ->orderBy('category')
                        ->orderBy('name')
                        ->get();
        
        $productsByCategory = $products->groupBy('category');

        $tables = Table::orderBy('name')->get();

        $occupiedTables = Sale::where('status', 'pendiente')
                        ->pluck('table_id')
                        ->toArray();

        return view('sales.create', compact('products', 'productsByCategory', 'tables', 'occupiedTables', 'cashRegisters'));
    }

    /**
     * Guardar los pedidos.
     */
    public function store(StoreSaleRequest $request)
    {
        $user = Auth::user();

        if ($user->role === 'caja' && $request->has('cash_register_id')) {
            throw new \Exception("No puedes seleccionar manualmente la caja si eres cajero.");
        }

        DB::beginTransaction();

        try {
            if ($user->role === 'caja') {
                $cashRegister = CashRegister::where('user_id', $user->id)
                    ->where('status', 'abierta')
                    ->first();

                if (!$cashRegister) {
                    throw new \Exception("No hay ninguna caja abierta para registrar ventas.");
                }

                $cashRegisterId = $cashRegister->id;

            } else {
                $cashRegisterId = $request->cash_register_id;
            }

            // Valida stock de producto
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                if(!$product){
                    throw new \Exception("Producto no encontrado (ID {$item['product_id']}).");
                }
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuficiente para {$product->name}. Disponible: {$product->stock}");
                }
            }

            $sale = Sale::create([
                'user_id' => $user->id,
                'table_id' => $request->table_id,
                'cash_register_id' => $cashRegisterId,
                'total' => 0,
                'status' => 'pendiente',
                'payment_method' => $request->payment_method ?? null,
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                $subtotal = $item['price'] * $item['quantity'];

                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;

                $product = Product::find($item['product_id']);

                if ($product){
                    Product::where('id', $item['product_id'])
                            ->update(['stock' => DB::raw('stock - ' . $item['quantity'])]);
                } 
            }

            $sale->update([
                'total' => $total
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venta procesada exitosamente',
                'sale_id' => $sale->id,
                'sale_number' => str_pad($sale->id, 6, '0', STR_PAD_LEFT)
            ]);
        } catch (\Exception $e){
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    } 

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $sale->load(['user', 'table', 'items.product']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        if ($sale->status === 'pagado') {
            return redirect()->route('sales.show', $sale)
                            ->with('error', 'No se puede editar una venta pagada');
        }

        $sale->load(['user', 'table', 'items.product']);
        $tables = Table::orderBy('name')->get();

        $products = Product::where('stock', '>', 0)
                        ->orderBy('category')
                        ->orderBy('name')
                        ->get();
        
        $productsByCategory = $products->groupBy('category');

        $user = Auth::user();

        if ($user->role === 'caja') {
            $cashRegisters = CashRegister::where('user_id', $user->id)
                ->where('status', 'abierta')
                ->get();
        } else {
            $cashRegisters = CashRegister::where('status', 'abierta')
                 ->orderBy('opened_at', 'desc')
                 ->get();
        }

        if ($cashRegisters->isEmpty()) {
            return redirect()->route('cash-registers.open')
                        ->with('error', 'No hay ninguna caja abierta para registrar ventas.');
        }

        $occupiedTables = Sale::where('status', 'pendiente')
                        ->pluck('table_id')
                        ->toArray();

        return view('sales.edit', compact('sale', 'products', 'tables', 'productsByCategory', 'cashRegisters', 'occupiedTables'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'table_id'           => 'nullable|exists:tables,id',
            'total'              => 'required|numeric|min:0',
            'cart'               => 'required|json',
        ]);

        $cart = json_decode($request->cart, true);

        foreach ($sale->items as $oldItem) {
            $product = $oldItem->product;
            if ($product) {
                $product->stock += $oldItem->quantity;
                $product->save();
            }
        }

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                if ($product->stock < $item['quantity']) {
                    return redirect()
                        ->back()
                        ->with('error', "Stock insuficiente para {$product->name}. Disponible: {$product->stock}");
                }
            } 
        }

        $sale->update([
            'table_id' => $request->table_id,
            'total' => $request->total,
        ]);

        $existingItems = $sale->items()->pluck('product_id')->toArray();

        $newItems = array_keys($cart);

        $itemsToDelete = array_diff($existingItems, $newItems);
        if (!empty($itemsToDelete)) {
            $sale->items()->whereIn('product_id', $itemsToDelete)->delete();
        }

        foreach ($cart as $productId => $item) {
            $sale->items()->updateOrCreate(
                ['product_id' => $productId],
                [
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]
            );

            $product = Product::find($productId);
            if ($product) {
                $product->stock -= $item['quantity'];
                $product->save();
            }
        }

        return redirect()
            ->route('sales.index')
            ->with('success', 'Venta actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        if(Auth::user()->role !== 'admin') {
            abort(403, 'No autorizado para eliminar esta venta.');
        }

        if ($sale->status === 'pagado'){
            return redirect()->route('sales.index')
                            ->with('error', 'No se puede eliminar una venta pagada');
        }

        DB::beginTransaction();

        try {
            foreach ($sale->items as $item) {
                if ($item->product->stock) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            $sale->delete();

            DB::commit();

            return redirect()->route('sales.index')
                            ->with('success', 'Venta eliminada exitosamente');
        } catch(\Exception $e) {
            DB::rollBack();

            return redirect()->route('sales.index')
                            ->with('error', 'Error al eliminar la venta: ' . $e->getMessage());
        }
    }

    private function authorizeAccess(Sale $sale)
    {
        if (Auth::user()->role !== 'admin' && $sale->user_id !== Auth::id()) {
            abort(403, 'No autorizado para ver esta venta.');
        }
    }

    public function pay(Request $request, Sale $sale)
    {
        if ($sale->status !== 'pendiente') {
            return redirect()->route('sales.index', $sale)
                            ->with('error', 'Solo se pueden pagar ventas pendientes.');
        }

        $request->validate([
            'payment_method' => 'required|string|in:efectivo,transferencia',
        ]);

        $openCashRegister = CashRegister::where('id', $sale->cash_register_id)
                                ->where('status', 'abierta')
                                ->first();

        if (!$openCashRegister) {
            return redirect()->route('sales.show', $sale)
                            ->with('error', 'No hay una caja abierta para registrar el pago.');
        }

        DB::beginTransaction();

        try {
            Payment::create([
                'sale_id' => $sale->id,
                'user_id' => Auth::id(),
                'amount' => $sale->total,
                'payment_method' => $request->payment_method,
                'paid_at' => now(),
                'paid_by' => Auth::id(),
                'cash_register_id' => $openCashRegister->id,
            ]);

            $totalPagado = $sale->payments()->sum('amount');

            if ($totalPagado >= $sale->total) {
                $sale->update([
                    'status' => 'pagado',
                    'payment_method' => $request->payment_method,
                    'paid_at' => now(),
                    'paid_by' => Auth::id(),
                ]);
            }

            DB::commit();

            return redirect()->route('sales.show', $sale)
                            ->with('success', 'Venta pagada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('sales.show', $sale)
                            ->with('error', 'Error al registrar pago: ' . $e->getMessage());
        }
    }

    public function getProductsByCategory(Request $request)
    {
        $category = $request->query('category');

        $query = Product::where('stock', '>', '0');

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        $products = $query->select('id', 'name', 'category', 'price', 'stock')
                        ->orderBy('name')
                        ->get();

        return response()->json($products);
    }

    public function getTablesStatus()
    {
        $tables = Table::orderBy('name')->get();
        $occupiedTables = Sale::where('status', 'pendiente')->pluck('table_id')->toArray();

        $tablesStatus = $tables->map(function($table) use ($occupiedTables) {
            return [
                'id' => $table->id,
                'name' => $table->name,
                'is_occupied' => in_array($table->id, $occupiedTables)
            ];
        });

        return response()->json($tablesStatus);
    }
}
