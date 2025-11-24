<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function index()
    {
        $pacientes = Paciente::orderBy('apellido')->paginate(10);
        return view('pacientes.index', compact('pacientes'));
    }

    public function create()
    {
        return view('pacientes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'required|string|unique:pacientes,dni',
            'fecha_nacimiento' => 'required|date',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:pacientes,email',
            'direccion' => 'nullable|string',
            'observaciones' => 'nullable|string'
        ]);

        Paciente::create($validated);

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente registrado exitosamente');
    }

    public function show(Paciente $paciente)
    {
        $paciente->load(['citas' => function($query) {
            $query->with('medico')->orderBy('fecha_hora', 'desc');
        }]);
        
        return view('pacientes.show', compact('paciente'));
    }

    public function edit(Paciente $paciente)
    {
        return view('pacientes.edit', compact('paciente'));
    }

    public function update(Request $request, Paciente $paciente)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'required|string|unique:pacientes,dni,' . $paciente->id,
            'fecha_nacimiento' => 'required|date',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:pacientes,email,' . $paciente->id,
            'direccion' => 'nullable|string',
            'observaciones' => 'nullable|string'
        ]);

        $paciente->update($validated);

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente actualizado exitosamente');
    }

    public function destroy(Paciente $paciente)
    {
        try {
            $paciente->delete();
            return redirect()->route('pacientes.index')
                ->with('success', 'Paciente eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('pacientes.index')
                ->with('error', 'No se puede eliminar el paciente porque tiene citas asociadas');
        }
    }
}