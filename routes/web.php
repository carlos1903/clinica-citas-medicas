<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\ReporteController; // <--- ¡Esta es la línea clave agregada!

// Ruta de login (pública)
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
});

// Rutas protegidas (requieren autenticación)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('medicos', MedicoController::class);
    Route::resource('pacientes', PacienteController::class);
    Route::resource('citas', CitaController::class);

    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
});

require __DIR__.'/auth.php';