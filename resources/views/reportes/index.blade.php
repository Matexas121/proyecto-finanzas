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

        /* 🔹 Contenedor del gráfico centrado y con tamaño fijo */
        .chart-container {
            width: 380px;
            height: 380px;
            margin: 0 auto; /* centra horizontalmente */
            position: relative;
        }

        /* 🔹 Ajuste responsivo para pantallas pequeñas */
        @media (max-width: 600px) {
            .chart-container {
                width: 300px;
                height: 300px;
            }
        }
    </style>
</head>

<body>
    <h1>📅 Resumen Mensual</h1>
    <a href="{{ route('gastos.index') }}">⬅️ Volver a gastos</a>
    <hr>

    {{-- Totales principales --}}
    <div class="card">
        <h2>💵 Totales del mes</h2>
        <p><strong>Total de gastos:</strong> ${{ number_format($totalGastos, 2, ',', '.') }}</p>
        <p><strong>Total de transferencias:</strong> {{ $totalTransferencias }}</p>
        <p><strong>Saldo disponible:</strong> ${{ number_format($saldo, 2, ',', '.') }}</p>
    </div>

    {{-- Gráfico de distribución (CU12) --}}
    <div class="card">
        <h2>📊 Distribución de gastos por categoría</h2>
        <div class="chart-container">
            <canvas id="graficoGastos"></canvas>
        </div>
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
                maintainAspectRatio: false, // 🔹 esto hace que respete el tamaño del contenedor
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
