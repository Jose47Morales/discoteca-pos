<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Caja #{{ $cashRegister->id }}</title>
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
        <div class="title">Reporte de Caja #{{ $cashRegister->id }}</div>
        <small>Generado: {{ now()->format('d/m/Y H:i') }}</small>
    </div>

    <p><strong>Usuario:</strong> {{ $cashRegister->user->name }}</p>
    <p><strong>Apertura:</strong> {{ $cashRegister->opened_at }}</p>
    <p><strong>Cierre:</strong> {{ $cashRegister->closed_at ?? 'Pendiente' }}</p>
    <p><strong>Monto inicial:</strong> ${{ number_format($cashRegister->opening_amount, 2) }}</p>
    <p><strong>Monto final:</strong> ${{ number_format($cashRegister->closing_amount, 2) }}</p>

    <h4>Resumen por Método de Pago</h4>
    <table>
        <thead>
            <tr>
                <th>Método</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resumenPorMetodo as $metodo => $monto)
                <tr>
                    <td>{{ ucfirst($metodo) }}</td>
                    <td>${{ number_format($monto, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4 style="margin-top: 20px;">Pagos detallados</h4>
    <table>
        <thead>
            <tr>
                <th>Venta</th>
                <th>Monto</th>
                <th>Método</th>
                <th>Usuario</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $pago)
                <tr>
                    <td>#{{ $pago->sale_id }}</td>
                    <td>${{ number_format($pago->amount, 2) }}</td>
                    <td>{{ ucfirst($pago->payment_method) }}</td>
                    <td>{{ $pago->user->name }}</td>
                    <td>{{ $pago->paid_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generado automáticamente por el sistema - {{ config('app.name') }}
    </div>

</body>
</html>
