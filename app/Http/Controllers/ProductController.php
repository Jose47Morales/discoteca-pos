<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Listar productos.
     */
    public function index()
    {
        if(Auth::user()->role === 'vendedor'){
            return redirect()->route('sales.index');
        }

        if(Auth::user()->role !== 'admin'){
            abort(403, 'Acceso denegado');
        }

        $products = Product::orderBy('name')->paginate(10);
        return view('products.index', compact('products'));
    }

    /**
     * Crear un nuevo producto.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Almacenar un nuevo producto.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products',
            'category' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Detalle de un producto.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Editar un producto.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Actualizar un producto.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'category' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Eliminar un producto.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Producto eliminado exitosamente.');
    }
}
