<?php

namespace App\Http\Controllers;

// Importaciones necesarias
use App\Models\Gasto;
use App\Models\Transferencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GastoController extends Controller
{
    public function __construct()
    {
        // Este middleware asegura que solo usuarios autenticados accedan a las rutas
        $this->middleware('auth');
    }

    /**
     * CU8 - Visualización de lista de gastos del mes en curso.
     * Muestra todos los gastos del usuario logueado, ordenados por fecha descendente.
     */
    public function index()
    {
        // Filtramos los gastos para que solo se muestren los del usuario autenticado.
        // Se usa "with('transferencia')" para traer los datos de la relación si existen.
        $gastos = Gasto::where('idUsuario', Auth::id())
                        ->with('transferencia', 'categoria')
                        ->orderBy('fecha', 'desc')
                        ->get();

        // Retorna la vista con la lista de gastos.
        return view('gastos.index', compact('gastos'));
    }

    /**
 * CU9 - Filtrar gastos por fecha, categoría o forma de pago.
 * También permite calcular totales y subtotales (CU10).
 */
public function filtrar(Request $request)
{
    $query = Gasto::where('idUsuario', Auth::id());

    // Filtrado por rango de fechas
    if ($request->filled('fecha_desde') && $request->filled('fecha_hasta')) {
        $query->whereBetween('fecha', [$request->fecha_desde, $request->fecha_hasta]);
    }

    // Filtrado por forma de pago
    if ($request->filled('formaPago')) {
        $query->where('formaPago', $request->formaPago);
    }

    // Filtrado por categoría (opcional)
    if ($request->filled('idCategoria')) {
        $query->where('idCategoria', $request->idCategoria); 
        
    }

    // Obtener resultados
    $gastos = $query->with('transferencia', 'categoria')->orderBy('fecha', 'desc')->get();

    // Totales y subtotales (CU10)
    $totalGeneral = $gastos->sum('monto');
    $subtotales = $gastos->groupBy('idCategoria')->map(function ($grupo) {
        return $grupo->sum('monto');
    });

    return view('gastos.index', compact('gastos', 'totalGeneral', 'subtotales'));
}


    /**
     * CU5 - Registración de Gasto.
     * Muestra el formulario de creación de un nuevo gasto.
     */
    public function create()
    {
        // Retorna la vista del formulario (resources/views/gastos/create.blade.php)
        return view('gastos.create');
    }

    /**
     * CU5 - Guardar un nuevo gasto o transferencia.
     * Implementa validaciones, lógica de guardado y registro de transferencias.
     */
    public function store(Request $request)
    {
        // ------------------------------------------------------------------
        // VALIDACIÓN DE DATOS (Consigna: Seguridad y Validación)
        // ------------------------------------------------------------------
        $rules = [
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
            'formaPago' => 'required|string|in:efectivo,tarjeta,transferencia',
            'descripcion' => 'nullable|string|max:255',
            'idCategoria' => 'nullable|integer',
        ];

        // Si la forma de pago es transferencia, se requieren campos extra.
        if ($request->input('formaPago') === 'transferencia') {
            $rules['alias'] = 'required|string|max:255';
            $rules['nombreDestinatario'] = 'required|string|max:255';
        }

        // Se ejecuta la validación según las reglas.
        $validated = $request->validate($rules);

        // ------------------------------------------------------------------
        // CREACIÓN DEL GASTO (Consigna: CRUD)
        // ------------------------------------------------------------------
        $gasto = Gasto::create([
            'monto' => $validated['monto'],
            'fecha' => $validated['fecha'],
            'descripcion' => $validated['descripcion'] ?? null,
            'formaPago' => $validated['formaPago'],
            'dniUsuario' => Auth::user()->dniUsuario, // Usar el campo DNI del usuario autenticado
            'idUsuario' => Auth::id(), // Si la tabla NECESITA AMBOS, envíale ambos
            'idCategoria' => $validated['idCategoria'] ?? null,
        ]);

        // ------------------------------------------------------------------
        // LÓGICA DE TRANSFERENCIAS (Consigna: CU5)
        // Si el gasto fue hecho con transferencia, se guarda la información adicional.
        // ------------------------------------------------------------------
        if ($validated['formaPago'] === 'transferencia') {
            Transferencia::create([
                'alias' => $validated['alias'],
                'nombreDestinatario' => $validated['nombreDestinatario'],
                'gasto_id' => $gasto->idGasto, // Clave primaria correcta del modelo
            ]);
        }

        // Redirige al listado con un mensaje de éxito.
        return redirect()->route('gastos.index')
                         ->with('success', 'Gasto registrado correctamente.');
    }

    /**
     * Mostrar un gasto específico (opcional).
     * Verifica que el gasto pertenezca al usuario actual antes de mostrarlo.
     */
    public function show(Gasto $gasto)
    {
        // Seguridad: el usuario solo puede ver sus propios gastos.
        if ($gasto->idUsuario !== Auth::id()) {
            abort(403); // Acceso prohibido.
        }

        return view('gastos.show', ['gasto' => $gasto->load('transferencia')]);
    }

    /**
     * CU6 - Edición de Gasto.
     * Muestra el formulario de edición con los datos actuales del gasto.
     */
    public function edit(Gasto $gasto)
    {
        // Seguridad: evitar que un usuario edite gastos de otro.
        if ($gasto->idUsuario !== Auth::id()) {
            abort(403);
        }

        // Cargar la relación de transferencia si existe.
        $gasto->load('transferencia');

        // Retorna la vista de edición.
        return view('gastos.edit', compact('gasto'));
    }

    /**
     * CU6 - Actualización del Gasto.
     * Implementa el CRUD y la lógica condicional para transferencias.
     */
    public function update(Request $request, Gasto $gasto)
    {
        // Seguridad: el usuario solo puede actualizar sus propios registros.
        if ($gasto->idUsuario !== Auth::id()) {
            abort(403);
        }

        // Reglas de validación (idénticas al store).
        $rules = [
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
            'formaPago' => 'required|string|in:efectivo,tarjeta,transferencia',
            'descripcion' => 'nullable|string|max:255',
            'idCategoria' => 'nullable|integer',
        ];

        // Validaciones adicionales si es transferencia.
        if ($request->input('formaPago') === 'transferencia') {
            $rules['alias'] = 'required|string|max:255';
            $rules['nombreDestinatario'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);

        // ------------------------------------------------------------------
        // ACTUALIZAR GASTO (Consigna: CRUD)
        // ------------------------------------------------------------------
        $gasto->update([
            'monto' => $validated['monto'],
            'fecha' => $validated['fecha'],
            'descripcion' => $validated['descripcion'] ?? null,
            'formaPago' => $validated['formaPago'], 
            'dniUsuario' => $validated['dniUsuario'],
            'idCategoria' => $validated['idCategoria'] ?? null,
        ]);

        // ------------------------------------------------------------------
        // ACTUALIZAR O CREAR TRANSFERENCIA (Consigna: CU6)
        // ------------------------------------------------------------------
        if ($validated['formaPago'] === 'transferencia') {
            // Si es transferencia, se crea o actualiza el registro asociado.
            Transferencia::updateOrCreate(
                ['idGasto' => $gasto->gasto_id],
                [
                    'alias' => $validated['alias'],
                    'nombreDestinatario' => $validated['nombreDestinatario'],
                ]
            );
        } else {
            // Si ya tenía una transferencia y ahora no corresponde, se elimina.
            if ($gasto->transferencia) {
                $gasto->transferencia->delete();
            }
        }

        // Redirige con mensaje de confirmación.
        return redirect()->route('gastos.index')
                         ->with('success', 'Gasto actualizado correctamente.');
    }

    /**
     * CU7 - Eliminación de gasto.
     * Elimina un gasto y su transferencia asociada si existe.
     */
    public function destroy(Gasto $gasto)
    {
        // Seguridad: solo el dueño puede eliminarlo.
        if ($gasto->idUsuario !== Auth::id()) {
            abort(403);
        }

        // Si hay una transferencia asociada, eliminarla primero.
        if ($gasto->transferencia) {
            $gasto->transferencia->delete();
        }

        // Eliminar el gasto.
        $gasto->delete();

        // Redirigir al listado con mensaje.
        return redirect()->route('gastos.index')
                         ->with('success', 'Gasto eliminado correctamente.');
    }
}
