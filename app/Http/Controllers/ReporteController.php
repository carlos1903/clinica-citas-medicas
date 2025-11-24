<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $totalMedicos = Medico::where('activo', true)->count();
        $totalPacientes = Paciente::count();
        $totalCitas = Cita::count();
        
        // Citas por estado
        $citasPorEstado = Cita::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();
        
        // Citas por mes (últimos 6 meses)
        $citasPorMes = Cita::select(
                DB::raw('MONTH(fecha_hora) as mes'),
                DB::raw('YEAR(fecha_hora) as año'),
                DB::raw('count(*) as total')
            )
            ->where('fecha_hora', '>=', Carbon::now()->subMonths(6))
            ->groupBy('mes', 'año')
            ->orderBy('año', 'asc')
            ->orderBy('mes', 'asc')
            ->get();
        
        // Médicos con más citas
        $medicosMasCitas = Medico::withCount('citas')
            ->orderBy('citas_count', 'desc')
            ->take(5)
            ->get();
        
        // Citas por especialidad
        $citasPorEspecialidad = Medico::select('especialidad', DB::raw('count(citas.id) as total'))
            ->join('citas', 'medicos.id', '=', 'citas.medico_id')
            ->groupBy('especialidad')
            ->orderBy('total', 'desc')
            ->get();
        
        // Tasa de cancelación
        $totalCitasCompletadas = Cita::where('estado', 'completada')->count();
        $totalCitasCanceladas = Cita::where('estado', 'cancelada')->count();
        $tasaCancelacion = $totalCitas > 0 ? round(($totalCitasCanceladas / $totalCitas) * 100, 2) : 0;
        $tasaCompletadas = $totalCitas > 0 ? round(($totalCitasCompletadas / $totalCitas) * 100, 2) : 0;

        return view('reportes.index', compact(
            'totalMedicos',
            'totalPacientes',
            'totalCitas',
            'citasPorEstado',
            'citasPorMes',
            'medicosMasCitas',
            'citasPorEspecialidad',
            'tasaCancelacion',
            'tasaCompletadas'
        ));
    }
}