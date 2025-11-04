<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Gasto;
use App\Models\Transferencia;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * CU11 y CU12 - Resumen mensual con comparación entre meses seleccionados
     */
    public function index()
    {
        $usuarioId = Auth::id();

        // 🔹 Mes/Año principal (actual o seleccionado)
        $mesSeleccionado = request('mes') ?? now()->month;
        $anioSeleccionado = request('anio') ?? now()->year;

        // 🔹 Mes/Año de comparación (opcional, elegidos por el usuario)
        $mesComparar = request('mes_comparar');
        $anioComparar = request('anio_comparar');

        // 🔹 Gastos del mes principal
        $gastos = Gasto::where('idUsuario', $usuarioId)
            ->whereMonth('fecha', $mesSeleccionado)
            ->whereYear('fecha', $anioSeleccionado)
            ->with('transferencia', 'categoria')
            ->get();

        $totalGastos = $gastos->sum('monto');
        $totalTransferencias = Transferencia::whereIn('gasto_id', $gastos->pluck('idGasto'))->count();

        // 🔹 Comparación con otro mes (si fue seleccionado)
        $totalMesComparado = null;
        $variacion = null;

        if ($mesComparar && $anioComparar) {
            $gastosComparar = Gasto::where('idUsuario', $usuarioId)
                ->whereMonth('fecha', $mesComparar)
                ->whereYear('fecha', $anioComparar)
                ->get();

            $totalMesComparado = $gastosComparar->sum('monto');

            // Si hay datos del mes comparado, calculamos la variación
            if ($totalMesComparado > 0) {
                $variacion = (($totalGastos - $totalMesComparado) / $totalMesComparado) * 100;
            }
        }

        // 🔹 Gráfico de distribución por categoría
        $porCategoria = $gastos->groupBy('idCategoria')->map(fn($grupo) => $grupo->sum('monto'));

        $labels = [];
        $data = [];

        foreach ($porCategoria as $categoriaId => $monto) {
            $categoria = $gastos->firstWhere('idCategoria', $categoriaId)?->categoria?->nombre;
            $labels[] = $categoria ?? 'Sin categoría';
            $data[] = $monto;
        }

        return view('reportes.index', compact(
            'totalGastos',
            'totalTransferencias',
            'labels',
            'data',
            'gastos',
            'mesSeleccionado',
            'anioSeleccionado',
            'mesComparar',
            'anioComparar',
            'totalMesComparado',
            'variacion'
        ));
    }

    /**
     * CU13 - Exportar a PDF o CSV
     */
    public function exportar($formato)
    {
        $usuarioId = Auth::id();
        $gastos = Gasto::where('idUsuario', $usuarioId)->with('transferencia', 'categoria')->get();

        if ($formato === 'csv') {
            $csv = "Fecha,Monto,Forma de Pago,Categoría,Descripción,Alias,Destinatario\n";
            foreach ($gastos as $g) {
                $categoria = $g->categoria->nombre ?? 'Sin categoría';
                $csv .= "{$g->fecha},{$g->monto},{$g->formaPago},{$categoria},{$g->descripcion},";
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
            ->with('transferencia', 'categoria')
            ->get();

        $json = json_encode($gastos, JSON_PRETTY_PRINT);
        $filename = "backup_usuario_{$usuarioId}.json";

        Storage::disk('local')->put($filename, $json);

        return response()->download(storage_path("app/$filename"));
    }
}

