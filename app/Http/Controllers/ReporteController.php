<?php

namespace App\Http\Controllers;

use App\Service\ReporteFinancieroService; // ¡CORREGIDO! De App\Services a App\Service
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReporteController extends Controller
{
    protected ReporteFinancieroService $reporteService;

    /**
     * Inyección de dependencia del servicio
     */
    public function __construct(ReporteFinancieroService $reporteService)
    {
        $this->reporteService = $reporteService;
    }

    /**
     * Muestra el resumen de reportes financieros.
     */
    public function index(): View
    {
        // El servicio retorna un array con los datos calculados.
        $reporte = $this->reporteService->generarReporte(auth()->id());

        // Retorna la vista y le pasa el array de datos
        return view('reportes.index', [
            'reporte' => $reporte,
        ]);
    }
}

