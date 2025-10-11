<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Caja</title>
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
        <div class="title">Reporte de Cajas</div>
        <small>{{ now()->format('d/m/Y H:i') }}</small>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Apertura</th>
                <th>Cierre</th>
                <th>Monto Inicial</th>
                <th>Monto Final</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $cash)
                <tr>
                    <td>{{ $cash->id }}</td>
                    <td>{{ $cash->user->name ?? 'N/A' }}</td>
                    <td>{{ $cash->opened_at ? \Carbon\Carbon::parse($cash->opened_at)->format('d/m/Y H:i') : 'N/A' }}</td>
                    <td>{{ $cash->closed_at ? \Carbon\Carbon::parse($cash->closed_at)->format('d/m/Y H:i') : 'N/A' }}</td>
                    <td>${{ number_format($cash->opening_amount ?? 0, 2) }}</td>
                    <td>${{ number_format($cash->closing_amount ?? 0, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generado automáticamente por el sistma - {{ config('app.name') }}
    </div>
</body>
</html>
