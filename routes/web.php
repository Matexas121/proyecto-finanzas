<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GastoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TransferenciaController;
use Barryvdh\DomPDF\Facade\Pdf;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Este archivo define todas las rutas web del sistema de control de finanzas.
| Incluye autenticación, gestión de gastos y transferencias, reportes, 
| exportaciones y copia de seguridad.
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD (solo usuarios verificados)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS - Accesibles solo con sesión iniciada
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PERFIL DE USUARIO
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | MÓDULO DE GASTOS Y TRANSFERENCIAS (CU5–CU9)
    |--------------------------------------------------------------------------
    | ✅ Importante: la ruta de filtro debe ir antes del resource
    */
    Route::get('/gastos/filtrar', [GastoController::class, 'filtrar'])->name('gastos.filtrar');
    Route::resource('gastos', GastoController::class);

    Route::resource('transferencias', TransferenciaController::class);


    /*
    |--------------------------------------------------------------------------
    | MÓDULO DE REPORTES Y ANÁLISIS (CU11–CU14)
    |--------------------------------------------------------------------------
    */
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/exportar/{formato}', [ReporteController::class, 'exportar'])->name('reportes.exportar');
    Route::get('/reportes/backup', [ReporteController::class, 'backup'])->name('reportes.backup');
});

/*
|--------------------------------------------------------------------------
| AUTENTICACIÓN (login, registro, recuperación de contraseña)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
