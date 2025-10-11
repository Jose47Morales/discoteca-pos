@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')
<div class="container">
    <div class="row jusitfy-content-center">
        <div class="col-md-8">

            <div class="card shaow-sm">
                <div class="card-header bg-warning text-dark">
                    <i class="fas fa-edit"></i> Editar Producto
                </div>
                <div class="card-body">

                    <form action="{{ route('products.update', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre del producto</label>
                            <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Categoría</label>
                            <select name="category" id="category"
                                    class="form-select @error('category') is-invalid @enderror" required>
                                @foreach(\App\Models\Product::CATEGORIES as $value => $label)
                                    <option value="{{ $value }}" 
                                        {{ old('category', $product->category) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Precio</label>
                            <input type="number" name="price" id="price" step="0.01"
                                    class="form-control @error('price') is-invalid @enderror"
                                    value="{{ old('price', $product->price) }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" name="stock" id="stock"
                                    class="form-control @error('stock') is-invalid @enderror"
                                    value="{{ old('stock', $product->stock) }}" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-warning text-dark">
                            <i class="fas fa-save"></i> Actualizar
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection