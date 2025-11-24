<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CitaController extends Controller
{
    public function index(Request $request)
    {
        $query = Cita::with(['paciente', 'medico']);
        
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        if ($request->filled('fecha')) {
            $query->whereDate('fecha_hora', $request->fecha);
        }
        
        $citas = $query->orderBy('fecha_hora', 'desc')->paginate(15);
        
        return view('citas.index', compact('citas'));
    }

    public function create()
    {
        $medicos = Medico::where('activo', true)->orderBy('nombre')->get();
        $pacientes = Paciente::orderBy('apellido')->get();
        
        return view('citas.create', compact('medicos', 'pacientes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'fecha_hora' => 'required|date|after:now',
            'motivo' => 'required|string|max:255',
            'estado' => 'required|in:pendiente,confirmada,cancelada,completada'
        ]);

        // Verificar disponibilidad del médico
        $citaExistente = Cita::where('medico_id', $validated['medico_id'])
            ->where('fecha_hora', $validated['fecha_hora'])
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->first();

        if ($citaExistente) {
            return back()->withInput()
                ->with('error', 'El médico ya tiene una cita programada en ese horario');
        }

        Cita::create($validated);

        return redirect()->route('citas.index')
            ->with('success', 'Cita registrada exitosamente');
    }

    public function show(Cita $cita)
    {
        $cita->load(['paciente', 'medico']);
        return view('citas.show', compact('cita'));
    }

    public function edit(Cita $cita)
    {
        $medicos = Medico::where('activo', true)->orderBy('nombre')->get();
        $pacientes = Paciente::orderBy('apellido')->get();
        
        return view('citas.edit', compact('cita', 'medicos', 'pacientes'));
    }

    public function update(Request $request, Cita $cita)
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'fecha_hora' => 'required|date',
            'motivo' => 'required|string|max:255',
            'estado' => 'required|in:pendiente,confirmada,cancelada,completada',
            'diagnostico' => 'nullable|string',
            'receta' => 'nullable|string',
            'notas' => 'nullable|string'
        ]);

        // Verificar disponibilidad si cambió fecha/hora o médico
        if ($cita->fecha_hora != $validated['fecha_hora'] || $cita->medico_id != $validated['medico_id']) {
            $citaExistente = Cita::where('medico_id', $validated['medico_id'])
                ->where('fecha_hora', $validated['fecha_hora'])
                ->where('id', '!=', $cita->id)
                ->whereIn('estado', ['pendiente', 'confirmada'])
                ->first();

            if ($citaExistente) {
                return back()->withInput()
                    ->with('error', 'El médico ya tiene una cita programada en ese horario');
            }
        }

        $cita->update($validated);

        return redirect()->route('citas.index')
            ->with('success', 'Cita actualizada exitosamente');
    }

    public function destroy(Cita $cita)
    {
        $cita->delete();
        
        return redirect()->route('citas.index')
            ->with('success', 'Cita eliminada exitosamente');
    }
}