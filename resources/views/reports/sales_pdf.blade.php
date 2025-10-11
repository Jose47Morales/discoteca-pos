<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de ventas</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 20px; font-weight: bold;}
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background: #444; color: #fff;}
        .total { font-weight: bold; background: #eee; }
        .footer { position: fixed; bottom: -30px; font-size: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <i class="fas fa-music me-3"></i> Discoteca POS
        <div class="title">Reporte de Ventas</div>
        <small>{{ now()->format('d/m/Y H:i') }}</small>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Vendedor</th>
                <th>Mesa</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Método de Pago</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $sale)
                <tr>
                    <td>{{ $sale->id }}</td>
                    <td>{{ $sale->user->name ?? 'N/A' }}</td>
                    <td>{{ $sale->table->name ?? 'N/A' }}</td>
                    <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                    <td>${{ number_format($sale->total, 2) }}</td>
                    <td>{{ $sale->payment_method ?? 'N/A' }}</td>
                    <td>{{ ucfirst($sale->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generado automáticamente por el sistma - {{ config('app.name') }}
    </div>
</body>
</html>