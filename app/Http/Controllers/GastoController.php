<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\Transferencia;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GastoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * CU8 - Visualización de lista de gastos del usuario autenticado.
     */
    public function index()
    {
        $usuarioId = Auth::id();

        $gastos = Gasto::where('idUsuario', $usuarioId)
            ->with('transferencia', 'categoria')
            ->orderBy('fecha', 'desc')
            ->get();

        $categorias = Categoria::all();
        $totalGeneral = $gastos->sum('monto');
        $subtotales = $gastos->groupBy('idCategoria')->map(fn($grupo) => $grupo->sum('monto'));

        return view('gastos.index', compact('gastos', 'totalGeneral', 'subtotales', 'categorias'));
    }

    /**
     * CU9 - Filtrar gastos por fecha, categoría o forma de pago.
     */
    public function filtrar(Request $request)
{
    $query = Gasto::where('idUsuario', Auth::id());

    if ($request->filled('fecha_desde') && $request->filled('fecha_hasta')) {
        $query->whereBetween('fecha', [$request->fecha_desde, $request->fecha_hasta]);
    }

    if ($request->filled('formaPago')) {
        $query->where('formaPago', $request->formaPago);
    }

    if ($request->filled('idCategoria')) {
        $query->where('idCategoria', $request->idCategoria);
    }

    $gastos = $query->with('transferencia', 'categoria')->orderBy('fecha', 'desc')->get();

    $totalGeneral = $gastos->sum('monto');
    $subtotales = $gastos->groupBy('idCategoria')->map(fn($g) => $g->sum('monto'));

    return view('gastos.index', compact('gastos', 'totalGeneral', 'subtotales'));
}


    /**
     * CU5 - Mostrar formulario para crear un nuevo gasto.
     */
    public function create()
    {
        $categorias = Categoria::all();
        return view('gastos.create', compact('categorias'));
    }

    /**
     * CU5 - Guardar un nuevo gasto o transferencia.
     */
    public function store(Request $request)
    {
        $rules = [
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
            'formaPago' => 'required|string|in:efectivo,tarjeta,transferencia',
            'descripcion' => 'nullable|string|max:255',
            'idCategoria' => 'nullable|integer',
        ];

        if ($request->input('formaPago') === 'transferencia') {
            $rules['alias'] = 'required|string|max:255';
            $rules['nombreDestinatario'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);

        $gasto = Gasto::create([
            'monto' => $validated['monto'],
            'fecha' => $validated['fecha'],
            'descripcion' => $validated['descripcion'] ?? null,
            'formaPago' => $validated['formaPago'],
            'dniUsuario' => Auth::user()->dniUsuario ?? null,
            'idUsuario' => Auth::id(),
            'idCategoria' => $validated['idCategoria'] ?? null,
        ]);

        if ($validated['formaPago'] === 'transferencia') {
            Transferencia::create([
                'alias' => $validated['alias'],
                'nombreDestinatario' => $validated['nombreDestinatario'],
                'gasto_id' => $gasto->idGasto,
            ]);
        }

        return redirect()->route('gastos.index')->with('success', 'Gasto registrado correctamente.');
    }

    /**
     * Mostrar un gasto específico (CU6 opcional).
     */
    public function show(Gasto $gasto)
    {
        if ($gasto->idUsuario !== Auth::id()) {
            abort(403);
        }

        $gasto->load('transferencia', 'categoria');

        return view('gastos.show', compact('gasto'));
    }

    /**
     * CU6 - Mostrar formulario de edición de gasto.
     */
    public function edit(Gasto $gasto)
    {
        if ($gasto->idUsuario !== Auth::id()) {
            abort(403);
        }

        $gasto->load('transferencia', 'categoria');
        $categorias = Categoria::all();

        return view('gastos.edit', compact('gasto', 'categorias'));
    }

    /**
     * CU6 - Actualizar un gasto existente.
     */
    public function update(Request $request, Gasto $gasto)
    {
        if ($gasto->idUsuario !== Auth::id()) {
            abort(403);
        }

        $rules = [
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
            'formaPago' => 'required|string|in:efectivo,tarjeta,transferencia',
            'descripcion' => 'nullable|string|max:255',
            'idCategoria' => 'nullable|integer',
        ];

        if ($request->input('formaPago') === 'transferencia') {
            $rules['alias'] = 'required|string|max:255';
            $rules['nombreDestinatario'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);

        $gasto->update([
            'monto' => $validated['monto'],
            'fecha' => $validated['fecha'],
            'descripcion' => $validated['descripcion'] ?? null,
            'formaPago' => $validated['formaPago'],
            'idCategoria' => $validated['idCategoria'] ?? null,
        ]);

        if ($validated['formaPago'] === 'transferencia') {
            Transferencia::updateOrCreate(
                ['gasto_id' => $gasto->idGasto],
                [
                    'alias' => $validated['alias'],
                    'nombreDestinatario' => $validated['nombreDestinatario'],
                ]
            );
        } else {
            if ($gasto->transferencia) {
                $gasto->transferencia->delete();
            }
        }

        return redirect()->route('gastos.index')->with('success', 'Gasto actualizado correctamente.');
    }

    /**
     * CU7 - Eliminar gasto y su transferencia asociada.
     */
    public function destroy(Gasto $gasto)
    {
        if ($gasto->idUsuario !== Auth::id()) {
            abort(403);
        }

        if ($gasto->transferencia) {
            $gasto->transferencia->delete();
        }

        $gasto->delete();

        return redirect()->route('gastos.index')->with('success', 'Gasto eliminado correctamente.');
    }
}
