<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Gastos</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #999; padding: 5px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h1>Reporte de Gastos del Usuario</h1>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Monto</th>
                <th>Forma de Pago</th>
                <th>Descripción</th>
                <th>Alias</th>
                <th>Destinatario</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gastos as $gasto)
                <tr>
                    <td>{{ $gasto->fecha }}</td>
                    <td>${{ number_format($gasto->monto, 2, ',', '.') }}</td>
                    <td>{{ ucfirst($gasto->formaPago) }}</td>
                    <td>{{ $gasto->descripcion ?? '—' }}</td>
                    <td>{{ $gasto->transferencia->alias ?? '—' }}</td>
                    <td>{{ $gasto->transferencia->nombreDestinatario ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
