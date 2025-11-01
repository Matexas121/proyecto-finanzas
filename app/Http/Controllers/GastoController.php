<?php

namespace App\Http\Controllers;

// Importaciones necesarias (asegúrate de que tus modelos Gasto y Transferencia existan en App\Models\)
use App\Models\Gasto; // <-- Agregado
use App\Models\Transferencia; // <-- Agregado
use App\Http\Controllers\Controller; // Ya estaba
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Se recomienda usar el modelo de User correcto de Laravel si necesitas la clase:
// use App\Models\User; // (Si el modelo User está aquí)


class GastoController extends Controller
{
    /**
     * Display a listing of the resource.
     */ 

    public function __construct()
{
    $this->middleware('auth');
}

    public function index()
    {
        // Se puede simplificar usando el helper Auth::user()
        $usuarios = Auth::user();

        // Se usa Gasto::where('dniUsuario', Auth::id()) en lugar de $usuarios->gasto()
        // por buenas prácticas, asegurando que solo se traigan los gastos del usuario actual.
        $gastos = Gasto::where('dniUsuario', Auth::id())
                        ->with('transferencia')
                        ->orderBy('fecha', 'desc')
                        ->get();

        return view('gastos.index', ['gastos' => $gastos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Esto asume que tienes la vista en resources/views/gastos/create.blade.php
        return view('gastos.create');
    }

    /**
     * Store a newly created resource in storage.
     * Implementa la Validación de Datos (Consigna 4) y el CRUD (Consigna 2)
     */
    public function store(Request $request)
    {
        // ------------------------------------------------------------------
        // CONSIGNAS 4 & 5: VALIDACIÓN ESTRICTA Y LÓGICA DE TRANSFERENCIAS
        // ------------------------------------------------------------------
        $rules = [
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date', // Asumo que tienes un campo fecha
            'formaPago' => 'required|string|in:efectivo,tarjeta,transferencia',
            // Otras validaciones (categoría, descripción, etc.)
        ];

        // Validaciones condicionales para TRANSFERENCIA
        if ($request->input('formaPago') === 'transferencia') {
            $rules['alias'] = 'required|string|max:255';
            $rules['nombreDestinatario'] = 'required|string|max:255';
        }

        $validatedData = $request->validate($rules);
        // ------------------------------------------------------------------


        // 1. Crear el Gasto
        $gasto = Gasto::create([
            'monto' => $validatedData['monto'],
            'fecha' => $validatedData['fecha'] ?? now(), // Usar la fecha validada
            'formaPago' => $validatedData['formaPago'],
            'dniUsuario' => Auth::id(),
            // ... otros campos del gasto
        ]);


        // 2. Crear la Transferencia si aplica (Consigna 3)
        if ($validatedData['formaPago'] === 'transferencia') {
            Transferencia::create([
                'alias' => $validatedData['alias'],
                'nombreDestinatario' => $validatedData['nombreDestinatario'],
                'idGasto' => $gasto->id, // Usar el ID del gasto recién creado (asumo que se llama 'id')
            ]);
        }
        
        return redirect()->route('gastos.index')->with('success', 'Gasto/Transferencia registrada exitosamente.');
    }

    /**
     * Display the specified resource. (Generalmente no se usa para Gastos)
     */
    public function show(Gasto $gasto)
    {
        // Si necesitas mostrar un solo gasto, asegúrate de que sea del usuario actual
        if ($gasto->dniUsuario !== Auth::id()) {
            abort(403); // Prohibido
        }
        return view('gastos.show', ['gasto' => $gasto->load('transferencia')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gasto $gasto) // Usamos Type Hinting de Gasto para la inyección de modelos
    {
        // 1. Asegurar la propiedad:
        if ($gasto->dniUsuario !== Auth::id()) {
            abort(403);
        }

        // 2. Cargar la relación de transferencia (si existe)
        $gasto->load('transferencia');
        
        return view('gastos.edit', ['gasto' => $gasto]);
    }

    /**
     * Update the specified resource in storage.
     * Implementa el CRUD (Consigna 2) y la Lógica de Transferencias (Consigna 3)
     */
    public function update(Request $request, Gasto $gasto) // Usamos Type Hinting
    {
        // 1. Asegurar la propiedad
        if ($gasto->dniUsuario !== Auth::id()) {
            abort(403);
        }

        // 2. Validación (similar al 'store')
        $rules = [
            'monto' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
            'formaPago' => 'required|string|in:efectivo,tarjeta,transferencia',
        ];

        if ($request->input('formaPago') === 'transferencia') {
            $rules['alias'] = 'required|string|max:255';
            $rules['nombreDestinatario'] = 'required|string|max:255';
        }

        $validatedData = $request->validate($rules);

        // 3. Actualizar el Gasto
        $gasto->update($validatedData);

        // 4. Lógica de Transferencia
        if ($validatedData['formaPago'] === 'transferencia') {
            // Si es transferencia: Crear o Actualizar la transferencia relacionada
            Transferencia::updateOrCreate(
                ['idGasto' => $gasto->id], // Clave para encontrar
                [
                    'alias' => $validatedData['alias'],
                    'nombreDestinatario' => $validatedData['nombreDestinatario'],
                ]
            );
        } else {
            // Si NO es transferencia: Eliminar la transferencia relacionada si existía
            if ($gasto->transferencia) {
                $gasto->transferencia->delete();
            }
        }

        return redirect()->route('gastos.index')->with('success', 'Gasto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     * Implementa el CRUD (Consigna 2) y la Lógica de Transferencias (Consigna 3)
     */
    public function destroy(Gasto $gasto) // Usamos Type Hinting
    {
        // 1. Asegurar la propiedad
        if ($gasto->dniUsuario !== Auth::id()) {
            abort(403);
        }

        // 2. Si hay transferencia asociada, eliminarla primero
        // (Aunque es mejor configurar la clave foránea en la DB para 'ON DELETE CASCADE')
        if ($gasto->transferencia) {
            $gasto->transferencia->delete();
        }

        // 3. Eliminar el Gasto
        $gasto->delete();

        return redirect()->route('gastos.index')->with('success', 'Gasto/Transferencia eliminada.');
    }
}