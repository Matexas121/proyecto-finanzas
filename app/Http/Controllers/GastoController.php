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
        $this->middleware('auth');     //Este constructor aplica el middleware auth a todo el controlador. Significa que solo los usuarios logueados pueden acceder a sus métodos
    }

    
     //CU8 - Visualización de lista de gastos del usuario autenticado.
    public function index()
    {
        $usuarioId = Auth::id();

        $gastos = Gasto::where('idUsuario', $usuarioId)  //busca los gastos del usuario log
            ->with('transferencia', 'categoria')  //accede a las relaciones gracias a las funciones del modelo
            ->orderBy('fecha', 'desc') //ordena por fecha de manera descendente 
            ->get();
    $categorias = Categoria::all(); // Esta línea está bien si la usas para otras cosas.
    $totalGeneral = $gastos->sum('monto');
       // 💡 CAMBIO CLAVE: Agrupar por el nombre de la categoría y mapear para sumar
    $subtotales = $gastos
        // 1. Agrupa la colección por el nombre del modelo relacionado 'categoria'
        ->groupBy(fn($gasto) => $gasto->categoria->nombre ?? 'Sin Categoría')
        // 2. Mapea el grupo para sumar el 'monto' de los gastos en ese grupo
        ->map(fn($grupo) => $grupo->sum('monto'));

    return view('gastos.index', compact('gastos', 'totalGeneral', 'subtotales', 'categorias'));
    }

    
     //CU9 - Filtrar gastos por fecha, categoría o forma de pago.
    public function filtrar(Request $request)
{
    $query = Gasto::where('idUsuario', Auth::id());  //query representa una consulta. Trae los gastos del usuario autenticado

    if ($request->filled('fecha_desde') && $request->filled('fecha_hasta')) {            //pregunta a traves filled, si los campos fecha_desde y fecha hasta estan cargados
        $query->whereBetween('fecha', [$request->fecha_desde, $request->fecha_hasta]);    //si estan cargados, trae los gastos de esas fechas
    }

    if ($request->filled('formaPago')) {    //pregunta a traves filled, si el campo formaPago esta cargado
        $query->where('formaPago', $request->formaPago);
    }

    if ($request->filled('idCategoria')) {
        $query->where('idCategoria', $request->idCategoria);
    }

    $gastos = $query->with('transferencia', 'categoria')->orderBy('fecha', 'desc')->get();  //guardo los gastos una vez aplicado los filtros

    $totalGeneral = $gastos->sum('monto'); //se suman todos los valores la columna monto de gastos
    $subtotales = $gastos->groupBy('idCategoria')->map(fn($g) => $g->sum('monto'));  //agrupa los gastos por categoria, y despues suma el monto de cada grupo, es decir de cada categoria

    return view('gastos.index', compact('gastos', 'totalGeneral', 'subtotales'));
}


    
    //CU5 - Mostrar formulario para crear un nuevo gasto.
     
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
        ]; //son las reglas a complir de los datos ingresado por el usuario

        if ($request->input('formaPago') === 'transferencia') {
            $rules['alias'] = 'required|string|max:255';
            $rules['nombreDestinatario'] = 'required|string|max:255';
        } //si se selecciona la forma de pago transferencia, se agregan las relgas a alias y nombredestinatario

        $validated = $request->validate($rules); //le decimos a laravel que valide lo que mando el usuario(request) a traves de las reglas (rules)
        //validate es un arreglo libre de errores 
        $gasto = Gasto::create([
            'monto' => $validated['monto'],
            'fecha' => $validated['fecha'],
            'descripcion' => $validated['descripcion'] ?? null,
            'formaPago' => $validated['formaPago'],
            'dniUsuario' => Auth::user()->dniUsuario ?? null,
            'idUsuario' => Auth::id(),
            'idCategoria' => $validated['idCategoria'] ?? null,
        ]);// se crea un nuevo gasto utilizando el arreglo validated, es decir, con los datos ya validados

        if ($validated['formaPago'] === 'transferencia') {
            Transferencia::create([
                'alias' => $validated['alias'],
                'nombreDestinatario' => $validated['nombreDestinatario'],
                'gasto_id' => $gasto->idGasto,
            ]);
        }// Si la forma de pago es transferencia, se crea también un registro en la tabla de transferencias, relacionándolo con el gasto mediante gasto_id

        return redirect()->route('gastos.index')->with('success', 'Gasto registrado correctamente.');
    }

    /**
     * Mostrar un gasto específico (CU6 opcional).
     */
    public function show(Gasto $gasto)
    {
        if ($gasto->idUsuario !== Auth::id()) {
            abort(403);
        }//verificacion de que el gasto le pertenece al usuario

        $gasto->load('transferencia', 'categoria'); //carga las relaciones asociadas al gasto

        return view('gastos.show', compact('gasto'));
    }

    /**
     * CU6 - Mostrar formulario de edición de gasto.
     */
    public function edit(Gasto $gasto)
    {
        if ($gasto->idUsuario !== Auth::id()) {
            abort(403);
        }// verificacion de que el gasto le pertenece al usuario

        $gasto->load('transferencia', 'categoria'); //carga a gasto sus relaciones
        $categorias = Categoria::all(); //guarda todas las categorias

        return view('gastos.edit', compact('gasto', 'categorias')); 
    }

    /**
     * CU6 - Actualizar un gasto existente.
     */
    public function update(Request $request, Gasto $gasto)
    {
        if ($gasto->idUsuario !== Auth::id()) {
            abort(403);
        }//verificacion de que el gasto le pretenece al usuario

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
