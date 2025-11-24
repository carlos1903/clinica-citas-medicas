<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use Illuminate\Http\Request;

class MedicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medicos = Medico::orderBy('nombre')->paginate(10);
        return view('medicos.index', compact('medicos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('medicos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'especialidad' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'required|email|unique:medicos,email',
            'consultorio' => 'nullable|string|max:255',
        ]);

        // Agregar el estado activo manualmente
        $validated['activo'] = $request->has('activo') ? true : false;

        Medico::create($validated);

        return redirect()->route('medicos.index')
            ->with('success', 'Médico registrado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Medico $medico)
    {
        $medico->load(['citas' => function($query) {
            $query->with('paciente')->orderBy('fecha_hora', 'desc');
        }]);
        
        return view('medicos.show', compact('medico'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Medico $medico)
    {
        return view('medicos.edit', compact('medico'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Medico $medico)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'especialidad' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'required|email|unique:medicos,email,' . $medico->id,
            'consultorio' => 'nullable|string|max:255',
        ]);

        // Agregar el estado activo manualmente
        $validated['activo'] = $request->has('activo') ? true : false;

        $medico->update($validated);

        return redirect()->route('medicos.index')
            ->with('success', 'Médico actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medico $medico)
    {
        try {
            $medico->delete();
            return redirect()->route('medicos.index')
                ->with('success', 'Médico eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('medicos.index')
                ->with('error', 'No se puede eliminar el médico porque tiene citas asociadas');
        }
    }
}