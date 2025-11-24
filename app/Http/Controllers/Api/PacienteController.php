<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use App\Http\Resources\PacienteResource; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Necesario para la validación

class PacienteController extends Controller
{
    /**
     * Devuelve una lista de pacientes.
     */
    public function index()
    {
        return PacienteResource::collection(Paciente::all());
    }

    /**
     * Muestra un paciente específico.
     */
    public function show(Paciente $paciente)
    {
        return new PacienteResource($paciente);
    }

    /**
     * Almacena un nuevo paciente.
     */
    public function store(Request $request)
    {
        // 1. Definir reglas de validación
        $validator = Validator::make($request->all(), [
            'nombre_completo' => 'required|string|max:255',
            'email' => 'required|email|unique:pacientes,email',
            'telefono' => 'nullable|string|max:20',
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'nullable|string',
        ]);

        // 2. Verificar validación
        if ($validator->fails()) {
            // Devuelve errores de validación con código 422
            return response()->json($validator->errors(), 422);
        }

        // 3. Crear el recurso
        $paciente = Paciente::create($request->all());

        // 4. Devolver la respuesta con el Resource y código 201 (Created)
        return new PacienteResource($paciente);
    }

    /**
     * Actualiza un paciente existente.
     */
    public function update(Request $request, Paciente $paciente)
    {
        // 1. Reglas de validación (ignorar el email actual del paciente)
        $validator = Validator::make($request->all(), [
            'nombre_completo' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:pacientes,email,' . $paciente->id,
            'telefono' => 'nullable|string|max:20',
            'fecha_nacimiento' => 'sometimes|required|date',
            'direccion' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 2. Actualizar y guardar
        $paciente->update($request->all());

        // 3. Devolver la versión actualizada
        return new PacienteResource($paciente);
    }

    /**
     * Elimina un paciente.
     */
    public function destroy(Paciente $paciente)
    {
        $paciente->delete();

        // Devuelve una respuesta vacía con código 204 (No Content)
        return response()->json(null, 204);
    }
}