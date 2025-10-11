@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Abrir Caja</h2>

    <form action="{{ route('cash-registers.open.store') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="opening-amount">Monto de Apertura</label>
            <input type="number" step="0.01" name="opening_amount" id="opening_amount"
                    class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success mt-3">Abrir Caja</button>
        <a href="{{ route('sales.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
</div>
@endsection