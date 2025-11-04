@extends("layout")

@section("title", "Gastos")

@section("contenido")
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Gastos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1, h3 {
            color: #333;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .success {
            color: green;
            font-weight: bold;
        }
        .filter-box {
            margin-top: 20px;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ccc;
        }
        .filter-box input, .filter-box select {
            margin-right: 10px;
            padding: 5px;
        }
        a.btn {
            display: inline-block;
            padding: 4px 8px;
            background: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 5px;
        }
        a.btn:hover {
            background: #0b7dda;
        }
    </style>
</head>

<body>
    <h1>Listado de Gastos</h1>

    {{-- Mensaje de éxito --}}
    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    {{-- Botón para crear nuevo gasto --}}
    <a href="{{ route('gastos.create') }}">Agregar gasto</a>
    <hr>

    {{-- Filtro (CU9) --}}
    <div class="filter-box">
        <h3>Filtrar gastos</h3>
        <form method="GET" action="{{ route('gastos.filtrar') }}">
            <label>Desde:</label>
            <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}">

            <label>Hasta:</label>
            <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}">

            <label>Forma de pago:</label>
            <select name="formaPago">
                <option value="">-- Todas --</option>
                <option value="efectivo" {{ request('formaPago') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                <option value="tarjeta" {{ request('formaPago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                <option value="transferencia" {{ request('formaPago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
            </select>

            <button type="submit">Aplicar filtro</button>
            <a href="{{ route('gastos.index') }}">Limpiar</a>
        </form>
    </div>

    {{-- Tabla de gastos --}}
    @if(count($gastos) > 0)
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>Forma de Pago</th>
                    <th>Descripción</th> 
                    <th>Categoría</th>
                    <th>Transferencia</th> 
                    <th>Acciones</th> 
                </tr>
            </thead>
            <tbody>
                @foreach($gastos as $gasto)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($gasto->fecha)->format('d/m/Y') }}</td>
                        <td>${{ number_format($gasto->monto, 2, ',', '.') }}</td>
                        <td>{{ ucfirst($gasto->formaPago) }}</td>
                        <td>{{ $gasto->descripcion ?? '—' }}</td> 
                        <td>{{ $gasto->categoria->nombre ?? '-' }}</td> 

                        <td>
                            @if($gasto->transferencia)
                                <strong>Alias:</strong> {{ $gasto->transferencia->alias }} <br>
                                <strong>Destinatario:</strong> {{ $gasto->transferencia->nombreDestinatario }}
                            @else
                                —
                            @endif
                        </td>

                        <td>
                            {{-- Nuevo botón Ver detalle --}}
                            <a class="btn" href="{{ route('gastos.show', $gasto->idGasto) }}">Ver</a>

                            {{-- Editar --}}
                            <a class="btn" href="{{ route('gastos.edit', $gasto->idGasto) }}">Editar</a>

                            {{-- Eliminar --}}
                            <form method="POST" action="{{ route('gastos.destroy', $gasto->idGasto) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Deseas eliminar este gasto?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr>
        <h3>Total general: ${{ number_format($gastos->sum('monto'), 2, ',', '.') }}</h3>

        @if(isset($subtotales) && count($subtotales) > 0)
    <h3>Subtotales por categoría:</h3>
    <ul>
        {{-- Aquí, $nombreCategoria es la clave del array (el nombre) --}}
        @foreach($subtotales as $nombreCategoria => $monto)
            <li><strong>{{ $nombreCategoria }}:</strong> ${{ number_format($monto, 2, ',', '.') }}</li>
        @endforeach
    </ul>
@endif
    @else
        <p>No hay gastos registrados aún.</p>
    @endif
</body>
</html>
@endsection
