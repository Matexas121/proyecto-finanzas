@extends("layout")

@section("title", "Reportes")

@section("contenido")
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen Mensual</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fafafa;
            margin: 20px;
        }
        h1, h2 {
            color: #333;
        }
        .card {
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .chart-container {
            width: 380px;
            height: 380px;
            margin: 0 auto;
            position: relative;
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
        .positive { color: green; font-weight: bold; }
        .negative { color: red; font-weight: bold; }
    </style>
</head>

<body>
    <h1>📅 Resumen Mensual</h1>
    <a href="{{ route('gastos.index') }}">⬅️ Volver a gastos</a>
    <hr>

    {{-- FILTRO DE MES Y AÑO --}}
    <form method="GET" action="{{ route('reportes.index') }}">
        <label for="mes">Mes:</label>
        <select name="mes" id="mes">
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $m == $mesSeleccionado ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                </option>
            @endfor
        </select>

        <label for="anio">Año:</label>
        <select name="anio" id="anio">
            @for ($a = now()->year; $a >= now()->year - 3; $a--)
                <option value="{{ $a }}" {{ $a == $anioSeleccionado ? 'selected' : '' }}>
                    {{ $a }}
                </option>
            @endfor
        </select>

        <button type="submit">📊 Ver resumen</button>
    </form>

    {{-- Totales principales --}}
    <div class="card">
        <h2>💵 Totales del mes</h2>
        <p><strong>Total de gastos:</strong> ${{ number_format($totalGastos, 2, ',', '.') }}</p>
        <p><strong>Total de transferencias:</strong> {{ $totalTransferencias }}</p>

        @if($totalMesAnterior > 0)
            <p>
                <strong>Comparado con el mes anterior:</strong>
                @if($variacion > 0)
                    <span class="negative">+{{ number_format($variacion, 2) }}%</span> más gasto
                @elseif($variacion < 0)
                    <span class="positive">{{ number_format($variacion, 2) }}%</span> menos gasto
                @else
                    Sin variación
                @endif
            </p>
        @else
            <p><em>No hay datos del mes anterior para comparar.</em></p>
        @endif
    </div>

    {{-- Gráfico de distribución --}}
    <div class="card">
        <h2>📊 Distribución de gastos por categoría</h2>
        <div class="chart-container">
            <canvas id="graficoGastos"></canvas>
        </div>
    </div>

    {{-- Tabla de detalle --}}
    <div class="card">
        <h2>📋 Detalle de gastos del mes</h2>
        @if($gastos->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Categoría</th>
                        <th>Forma de pago</th>
                        <th>Monto</th>
                        <th>Destinatario</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gastos as $g)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($g->fecha)->format('d/m/Y') }}</td>
                            <td>{{ $g->categoria->nombre ?? 'Sin categoría' }}</td>
                            <td>{{ ucfirst($g->formaPago) }}</td>
                            <td>${{ number_format($g->monto, 2, ',', '.') }}</td>
                            <td>{{ $g->transferencia->nombreDestinatario ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No hay gastos registrados en este mes.</p>
        @endif
    </div>

    <hr>
    <h2>📤 Exportar o Descargar</h2>
    <a href="{{ url('/reportes/exportar/pdf') }}">📄 Exportar a PDF</a> |
    <a href="{{ url('/reportes/exportar/csv') }}">📊 Exportar a CSV</a> |
    <a href="{{ url('/reportes/backup') }}">💾 Descargar copia de seguridad</a>

    <script>
        const ctx = document.getElementById('graficoGastos');
        const chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Gastos por categoría',
                    data: @json($data),
                    backgroundColor: [
                        '#4CAF50', '#2196F3', '#FF9800', '#E91E63', '#9C27B0',
                        '#03A9F4', '#FFC107', '#8BC34A', '#FF5722', '#607D8B'
                    ],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 20,
                            color: '#333'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
@endsection
