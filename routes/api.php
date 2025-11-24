<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Importar los controladores de la carpeta Api
use App\Http\Controllers\Api\MedicoController;
use App\Http\Controllers\Api\PacienteController;
use App\Http\Controllers\Api\CitaController;

/*
|--------------------------------------------------------------------------
| Rutas de API
|--------------------------------------------------------------------------
|
| Estas rutas son cargadas por el RouteServiceProvider y asignadas al
| grupo de middleware "api". Utilizan tokens de Sanctum para autenticación.
|
| NOTA: Se eliminó .prefix('v1') para resolver el error 404 y conflictos. 
| Las URL finales serán /api/user, /api/medicos, etc.
|
*/

// 🔒 Todas las rutas de la API deben estar protegidas por tokens de Sanctum
// Se eliminó .prefix('v1') para evitar el conflicto de rutas.
Route::middleware('auth:sanctum')->group(function () {
    
    // Ruta para obtener el usuario autenticado (útil para verificar el token)
    // URL Final: http://127.0.0.1:8000/api/user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rutas RESTful para Médicos: GET, POST, PUT, DELETE
    // URL Final: http://127.0.0.1:8000/api/medicos
    Route::apiResource('medicos', MedicoController::class);

    // Rutas RESTful para Pacientes
    // URL Final: http://127.0.0.1:8000/api/pacientes
    Route::apiResource('pacientes', PacienteController::class);

    // Rutas RESTful para Citas
    // URL Final: http://127.0.0.1:8000/api/citas
    Route::apiResource('citas', CitaController::class);
});