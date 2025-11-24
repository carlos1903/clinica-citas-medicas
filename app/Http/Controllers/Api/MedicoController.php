<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medico;
use App\Http\Resources\MedicoResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MedicoController extends Controller
{
    /**
     * Muestra una lista de todos los médicos.
     * GET /api/v1/medicos
     */
    public function index()
    {
        // Devuelve todos los médicos en formato JSON usando el Resource
        return MedicoResource::collection(Medico::all());
    }

    /**
     * Muestra un médico específico.
     * GET /api/v1/medicos/{medico}
     */
    public function show(Medico $medico)
    {
        // Devuelve el médico individual en formato JSON
        return new MedicoResource($medico);
    }

    /**
     * Almacena un nuevo médico.
     * POST /api/v1/medicos
     */
    public function store(Request $request)
    {
        // 1. Definir reglas de validación
        $validator = Validator::make($request->all(), [
            'nombre_completo' => 'required|string|max:255',
            'especialidad' => 'required|string|max:100',
            'email' => 'required|email|unique:medicos,email', // Email debe ser único en la tabla 'medicos'
            'telefono' => 'nullable|string|max:20',
            'consultorio' => 'nullable|string|max:50',
            'activo' => 'boolean',
        ]);

        // 2. Verificar validación
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422); // 422 Unprocessable Entity
        }

        // 3. Crear el recurso
        $medico = Medico::create($request->all());

        // 4. Devolver la respuesta con el Resource y código 201 (Created)
        return new MedicoResource($medico);
    }

    /**
     * Actualiza un médico existente.
     * PUT/PATCH /api/v1/medicos/{medico}
     */
    public function update(Request $request, Medico $medico)
    {
        // 1. Reglas de validación
        $validator = Validator::make($request->all(), [
            'nombre_completo' => 'sometimes|required|string|max:255',
            'especialidad' => 'sometimes|required|string|max:100',
            // El email debe ser único, PERO ignora el email actual del médico que estamos editando
            'email' => ['sometimes', 'required', 'email', Rule::unique('medicos', 'email')->ignore($medico->id)],
            'telefono' => 'nullable|string|max:20',
            'consultorio' => 'nullable|string|max:50',
            'activo' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 2. Actualizar y guardar
        $medico->update($request->all());

        // 3. Devolver la versión actualizada
        return new MedicoResource($medico);
    }

    /**
     * Elimina un médico.
     * DELETE /api/v1/medicos/{medico}
     */
    public function destroy(Medico $medico)
    {
        $medico->delete();

        // 204 No Content - La eliminación fue exitosa
        return response()->json(null, 204);
    }
}