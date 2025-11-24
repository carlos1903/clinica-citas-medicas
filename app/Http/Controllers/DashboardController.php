<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. OBTENER TOTALES CLAVE
        $totalMedicos = Medico::where('activo', true)->count();
        $totalPacientes = Paciente::count();
        
        $totalCitas = Cita::count();
        $totalCitasPendientes = Cita::where('estado', 'pendiente')->count();
        $totalCitasCompletadas = Cita::where('estado', 'completada')->count();
        
        // 2. CALCULAR TASA DE COMPLETADAS
        $tasaCompletadas = 0;
        if ($totalCitas > 0) {
            $tasaCompletadas = round(($totalCitasCompletadas / $totalCitas) * 100, 2);
        }

        // 3. LISTADO DE CITAS RECIENTES
        $citasRecientes = Cita::with(['medico', 'paciente'])
            ->orderBy('fecha_hora', 'desc')
            ->take(8) // Mostrar las últimas 8 citas
            ->get();

        // 4. NUEVOS DATOS SOLICITADOS (Pacientes y Médicos)
        
        // Pacientes más recientes (los últimos 5 en registrarse)
        $pacientesRecientes = Paciente::orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Top 5 Médicos con más citas (similar a tu reporte)
        $medicosTopCitas = Medico::select('id', 'nombre', 'apellido', 'especialidad')
            ->withCount('citas') // Asume que tienes la relación 'citas' en el modelo Medico
            ->orderBy('citas_count', 'desc')
            ->take(5)
            ->get();


        // 5. PASAR DATOS A LA VISTA
        return view('dashboard', [
            // Totales para las tarjetas de la fila 1
            'totalMedicos' => $totalMedicos,
            'totalPacientes' => $totalPacientes,
            'totalCitasPendientes' => $totalCitasPendientes,
            'tasaCompletadas' => $tasaCompletadas,
            
            // Listados y tablas
            'citasRecientes' => $citasRecientes,
            'pacientesRecientes' => $pacientesRecientes, // NUEVO
            'medicosTopCitas' => $medicosTopCitas,       // NUEVO
        ]);
    }
}