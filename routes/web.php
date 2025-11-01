<<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GastoController; 
use App\Http\Controllers\ReporteController; 
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Rutas de Perfil (Generadas por Breeze/Jetstream)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // ----------------------------------------------------------------------------------
    // CONSIGNAS DEL PROYECTO: GASTOS Y REPORTES
    // ----------------------------------------------------------------------------------

    // 1. CRUD de Gastos/Transferencias
    // Esto genera 7 rutas automáticamente (index, create, store, show, edit, update, destroy)
    Route::resource('gastos', GastoController::class);

    // 2. Ruta de Reportes Financieros
    // Accede a la lógica del ReporteController
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
});

require __DIR__.'/auth.php';
