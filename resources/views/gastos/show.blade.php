@extends('layout')

@section('title', 'Detalle del gasto')

@section('contenido')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Gasto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 700px;
            margin: 40px auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #222;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border-bottom: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background: #f4f4f4;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            color: #2196F3;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Detalle del Gasto #{{ $gasto->idGasto }}</h1>

        <table>
            <tr>
                <th>Fecha</th>
                <td>{{ \Carbon\Carbon::parse($gasto->fecha)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Monto</th>
                <td>${{ number_format($gasto->monto, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Forma de pago</th>
                <td>{{ ucfirst($gasto->formaPago) }}</td>
            </tr>
            <tr>
                <th>Descripción</th>
                <td>{{ $gasto->descripcion ?? '—' }}</td>
            </tr>
            <tr>
                <th>Categoría</th>
                <td>{{ $gasto->categoria->nombre ?? 'Sin categoría' }}</td>
            </tr>
            @if($gasto->transferencia)
            <tr>
                <th>Alias</th>
                <td>{{ $gasto->transferencia->alias }}</td>
            </tr>
            <tr>
                <th>Nombre del destinatario</th>
                <td>{{ $gasto->transferencia->nombreDestinatario }}</td>
            </tr>
            @endif
        </table>

        <a href="{{ route('gastos.index') }}">   Volver al listado</a>
    </div>
</body>
</html>
@endsection
