<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Http\Resources\CitaResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CitaController extends Controller
{
    /**
     * Muestra una lista de todas las citas.
     * GET /api/v1/citas
     */
    public function index()
    {
        // Carga las relaciones paciente y medico para que el Resource funcione correctamente
        $citas = Cita::with(['paciente', 'medico'])->get();
        return CitaResource::collection($citas);
    }

    /**
     * Muestra una cita específica.
     * GET /api/v1/citas/{cita}
     */
    public function show(Cita $cita)
    {
        // Carga las relaciones al mostrar el detalle
        return new CitaResource($cita->load(['paciente', 'medico']));
    }

    /**
     * Almacena una nueva cita (con validación de disponibilidad).
     * POST /api/v1/citas
     */
    public function store(Request $request)
    {
        // 1. Definir reglas de validación
        $validator = Validator::make($request->all(), [
            'medico_id' => 'required|exists:medicos,id',
            'paciente_id' => 'required|exists:pacientes,id',
            'fecha_hora' => 'required|date|after:now', // Debe ser una fecha futura
            'motivo' => 'required|string|max:255',
            'estado' => 'required|in:pendiente,confirmada,cancelada,finalizada',
        ]);

        // 2. Verificar validación
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 3. Lógica de Negocio CLAVE: Verificar disponibilidad
        $fechaHoraSolicitada = Carbon::parse($request->fecha_hora);

        $slot_ocupado = Cita::where('medico_id', $request->medico_id)
                            ->where('fecha_hora', $fechaHoraSolicitada)
                            // Evitar contar citas que ya están canceladas o finalizadas
                            ->whereNotIn('estado', ['cancelada', 'finalizada'])
                            ->exists();

        if ($slot_ocupado) {
            // Error personalizado para conflicto de agenda
            return response()->json(
                ['error' => 'Conflicto de agenda. El médico ya tiene una cita no cancelada a esta hora.'], 
                409 // 409 Conflict
            );
        }

        // 4. Creación de la cita
        $cita = Cita::create($request->all());

        // 5. Devolver la respuesta (código 201 Created)
        return new CitaResource($cita->load(['paciente', 'medico']));
    }

    /**
     * Actualiza una cita existente.
     * PUT/PATCH /api/v1/citas/{cita}
     */
    public function update(Request $request, Cita $cita)
    {
        // Las reglas de validación deben ser similares a 'store'
        $validator = Validator::make($request->all(), [
            'medico_id' => 'sometimes|required|exists:medicos,id',
            'paciente_id' => 'sometimes|required|exists:pacientes,id',
            'fecha_hora' => 'sometimes|required|date|after:now',
            'motivo' => 'sometimes|required|string|max:255',
            'estado' => 'sometimes|required|in:pendiente,confirmada,cancelada,finalizada',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 2. Lógica de Negocio: Revalidar disponibilidad solo si se cambia la fecha/hora/médico
        if ($request->hasAny(['medico_id', 'fecha_hora'])) {
            $medicoId = $request->input('medico_id', $cita->medico_id);
            $fechaHora = Carbon::parse($request->input('fecha_hora', $cita->fecha_hora));

            $slot_ocupado = Cita::where('medico_id', $medicoId)
                                ->where('fecha_hora', $fechaHora)
                                ->where('id', '!=', $cita->id) // Ignorar la cita actual
                                ->whereNotIn('estado', ['cancelada', 'finalizada'])
                                ->exists();

            if ($slot_ocupado) {
                return response()->json(
                    ['error' => 'Conflicto de agenda. El nuevo horario está ocupado para ese médico.'], 
                    409
                );
            }
        }
        
        // 3. Actualizar y devolver
        $cita->update($request->all());
        return new CitaResource($cita->load(['paciente', 'medico']));
    }

    /**
     * Elimina una cita.
     * DELETE /api/v1/citas/{cita}
     */
    public function destroy(Cita $cita)
    {
        $cita->delete();
        return response()->json(null, 204);
    }
}