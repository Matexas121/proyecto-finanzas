<?php

namespace App\Http\Controllers;
use App\Http\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $usuarios = Auth::user();
        
        $gastos = $usuarios->gasto()->with('transferencia') ->orderBy('fecha', 'desc')->get();  //$usuarios->gastp() llama a la relacion hasMany que esta en el modelo de User, ->with('transferencia') trae ademas la relacion de transferencias con gasttos, si es que hay. orderBy ordena por fecha. El get() es la funcion que ejecuta la consulta
        return view('gastos.index', ['gastos' => $gastos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gastos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $gasto = \App\Models\Gasto::create([
           'monto'=>$request->monto,
            'formaPago'=>$request->formaPago,
            'dniUsuario'=> Auth::id(),
        ]);
        
        
        if ($request->input('formaPago') === 'transferencia') {
            \App\Models\Transferencia::create([
                'alias' => $request->alias,
                'nombreDestinatario'=> $request->nombreDestinatario,
                'idGasto'=>$gasto->idGasto,
                    
            ]);
        }
        return redirect() -> route('gastos.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($idGasto)
    {
        $gasto = \App\Models\Gasto::findOrFail($idGasto);
        return view('gastos.edit', ['gasto'=>$gasto]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $idGasto)
    {
        $gasto = \App\Models\Gasto::findOrFail($idGasto);
        $gasto->update($request->all());
        return redirect()-> route('gastos.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idGasto)
    {
        $gasto = \App\Models\Gasto::findOrFail($idGasto);
        $gasto->delete();
        return redirect() -> route('gastos.index');
    }
}
