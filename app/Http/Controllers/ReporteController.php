<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Gasto;
use App\Models\Transferencia;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ReporteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * CU11 y CU12 - Resumen mensual y gráfico
     */
    public function index()
    {
        $usuarioId = Auth::id();

        // Obtener los gastos del mes actual del usuario autenticado
        $gastos = Gasto::where('idUsuario', $usuarioId)
            ->whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->with('transferencia', 'categoria')
            ->get();

        // Total de gastos del mes
        $totalGastos = $gastos->sum('monto');
<<<<<<< HEAD
        $totalTransferencias = Transferencia::whereIn('gasto_id', $gastos->pluck('idGasto'))->count();
=======

        // Total de transferencias registradas (corrige la columna a gasto_id)
        $totalTransferencias = Transferencia::whereIn('gasto_id', $gastos->pluck('idGasto'))->count();

        // Saldo simulado (en este ejemplo fijo en 100000)
>>>>>>> 1d697772ca9df3e021b054a2708a9c32075a7c38
        $saldo = 100000 - $totalGastos;

        // Agrupar por categoría y calcular subtotales
        $porCategoria = $gastos->groupBy('idCategoria')->map(fn($grupo) => $grupo->sum('monto'));

        // Preparar datos para el gráfico
        $labels = [];
        $data = [];

        foreach ($porCategoria as $categoria => $monto) {
            $labels[] = $categoria ? "Categoría $categoria" : "Sin categoría";
            $data[] = $monto;
        }

        return view('reportes.index', compact(
            'totalGastos',
            'totalTransferencias',
            'saldo',
            'labels',
            'data',
            'gastos'
        ));
    }

    /**
     * CU13 - Exportar a PDF o CSV
     */
    public function exportar($formato)
    {
        $usuarioId = Auth::id();
        $gastos = Gasto::where('idUsuario', $usuarioId)->with('transferencia')->get();

        if ($formato === 'csv') {
            // Generar CSV
            $csv = "Fecha,Monto,Forma de Pago,Descripción,Alias,Destinatario\n";
            foreach ($gastos as $g) {
                $csv .= "{$g->fecha},{$g->monto},{$g->formaPago},{$g->descripcion},";
                $csv .= $g->transferencia
                    ? "{$g->transferencia->alias},{$g->transferencia->nombreDestinatario}\n"
                    : ",\n";
            }
            $filename = "reporte_gastos.csv";
            return Response::make($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=$filename",
            ]);
        }

        if ($formato === 'pdf') {
            $pdf = Pdf::loadView('reportes.pdf', compact('gastos'));
            return $pdf->download('reporte_gastos.pdf');
        }

        return back()->with('error', 'Formato no válido.');
    }

    /**
     * CU14 - Descargar copia de seguridad
     */
    public function backup()
    {
        $usuarioId = Auth::id();
        $gastos = Gasto::where('idUsuario', $usuarioId)
            ->with('transferencia')
            ->get();

        $json = json_encode($gastos, JSON_PRETTY_PRINT);
        $filename = "backup_usuario_{$usuarioId}.json";

        Storage::disk('local')->put($filename, $json);

        return response()->download(storage_path("app/$filename"));
    }
}
