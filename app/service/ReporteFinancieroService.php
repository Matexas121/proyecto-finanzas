<?php

// CAMBIO CRÍTICO: Se cambia 'Services' a 'Service' (singular)
namespace App\Service;

use App\Models\Gasto;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View; // Agregado por si es necesario para el Canvas (aunque no se usa aquí)

class ReporteFinancieroService
{
    /**
     * Genera un array con el resumen financiero para un usuario dado.
     *
     * @param int $userId ID del usuario autenticado.
     * @return array
     */
    public function generarReporte(int $userId): array
    {
        // 1. Obtener todos los gastos/ingresos del usuario
        // Nota: Asumimos que si el 'monto' es positivo es un ingreso, y si es negativo es un gasto.
        $gastos = Gasto::where('idUsuario', $userId)->get();

        // 2. Calcular totales
        $totalIngresos = $gastos->where('monto', '>', 0)->sum('monto');
        $totalGastos = $gastos->where('monto', '<', 0)->sum('monto');
        $saldoActual = $totalIngresos + $totalGastos; // TotalGastos ya es negativo

        // 3. Desglose por Categoría
        // Agrupamos los gastos por la clave 'idCategoria'
        $gastosPorCategoria = $gastos->groupBy('idCategoria')->map(function ($items) {
            return $items->sum('monto');
        });

        // 4. Obtener nombres de las categorías
        $categorias = Categoria::all()->keyBy('id');

        $desglose = $gastosPorCategoria->map(function ($monto, $idCategoria) use ($categorias) {
            
            // CORRECCIÓN: Usar una comprobación más segura para obtener el nombre de la categoría.
            // Si la categoría no existe en la colección, usamos 'Sin Categoría'.
            $categoria = $categorias->get($idCategoria);
            $nombreCategoria = $categoria ? $categoria->nombre : 'Sin Categoría';
            
            $tipo = ($monto > 0) ? 'Ingreso' : 'Gasto';

            return [
                'categoria' => $nombreCategoria,
                'monto' => $monto,
                'tipo' => $tipo,
            ];
        })->sortByDesc('monto')->values()->all();

        // 5. Devolver el reporte final
        return [
            'totales' => [
                'ingresos' => abs($totalIngresos),
                'gastos' => abs($totalGastos), // Usamos abs() para mostrar el gasto como valor positivo
                'saldo' => $saldoActual,
            ],
            'desglose_categorias' => $desglose,
        ];
    }
}

