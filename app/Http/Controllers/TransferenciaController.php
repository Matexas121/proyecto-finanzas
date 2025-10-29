<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransferenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transferencias = \App\Models\Transferencia::all();
        return view('transferencias.index', ['transferencias' => $transferencias]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('transferencias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \App\Models\Transferencia::create($request->all());
        return redirect() -> route('transferencias.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $idTransferencia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $idTransferencia)
    {
        $transferencia = \App\Models\Transferencia::findOrFail($idTransferencia);
        return view('transferencias.edit', ['transferencia' => $transferencia]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $idTransferencia)
    {
        $transferencia = \App\Models\Transferencia::findOrFail($idTransferencia);
        $transferencia->update($request->all());
        return redirect()->route('transferencias.index');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $idTransferencia)
    {
        $transferencia = \App\Models\Transferencia::findOrFail($idTransferencia);
        $transferencia->delete();
        return redirect() -> route('transferencias.index');
    }
}
