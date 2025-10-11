@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalles del Producto</h1>

    <div class="card">
        <div class="card-header">
            {{ $product->name }}
        </div>
        <div class="card-body">
            <p><strong>Categoría:</strong> {{ ucfirst($product->category) }} </p>
            <p><strong>Precio:</strong> {{ number_format($product->price, 2, ',', '.') }} </p>
            <p><strong>Stock:</strong> {{ $product->stock }} </p>
            <p><strong>Creado:</strong> {{ $product->created_at->format('d/m/Y H:i') }} </p>
            <p><strong>Actualizado:</strong> {{ $product->updated_at->format('d/m/Y H:i') }} </p>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <div>
                <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                    <div class="fas fa-edit"></div> Editar
                </a>
                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('¿Seguro que deseas eliminar este producto?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection