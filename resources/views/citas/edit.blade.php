@extends('layouts.app')

@section('title', 'Editar Cita')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Editar Cita</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('citas.update', $cita) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="paciente_id" class="form-label">Paciente *</label>
                                <select name="paciente_id" 
                                        id="paciente_id" 
                                        class="form-select @error('paciente_id') is-invalid @enderror" 
                                        required>
                                    <option value="">Seleccione un paciente</option>
                                    @foreach($pacientes as $paciente)
                                        <option value="{{ $paciente->id }}" 
                                                {{ old('paciente_id', $cita->paciente_id) == $paciente->id ? 'selected' : '' }}>
                                            {{ $paciente->nombre_completo }} - {{ $paciente->dni }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('paciente_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="medico_id" class="form-label">Médico *</label>
                                <select name="medico_id" 
                                        id="medico_id" 
                                        class="form-select @error('medico_id') is-invalid @enderror" 
                                        required>
                                    <option value="">Seleccione un médico</option>
                                    @foreach($medicos as $medico)
                                        <option value="{{ $medico->id }}" 
                                                {{ old('medico_id', $cita->medico_id) == $medico->id ? 'selected' : '' }}>
                                            Dr. {{ $medico->nombre_completo }} - {{ $medico->especialidad }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('medico_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_hora" class="form-label">Fecha y Hora *</label>
                                <input type="datetime-local" 
                                       class="form-control @error('fecha_hora') is-invalid @enderror" 
                                       id="fecha_hora" 
                                       name="fecha_hora" 
                                       value="{{ old('fecha_hora', $cita->fecha_hora->format('Y-m-d\TH:i')) }}" 
                                       required>
                                @error('fecha_hora')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="estado" class="form-label">Estado *</label>
                                <select name="estado" 
                                        id="estado" 
                                        class="form-select @error('estado') is-invalid @enderror" 
                                        required>
                                    <option value="pendiente" {{ old('estado', $cita->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="confirmada" {{ old('estado', $cita->estado) == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                    <option value="completada" {{ old('estado', $cita->estado) == 'completada' ? 'selected' : '' }}>Completada</option>
                                    <option value="cancelada" {{ old('estado', $cita->estado) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                </select>
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="motivo" class="form-label">Motivo de la Consulta *</label>
                            <input type="text" 
                                   class="form-control @error('motivo') is-invalid @enderror" 
                                   id="motivo" 
                                   name="motivo" 
                                   value="{{ old('motivo', $cita->motivo) }}" 
                                   required>
                            @error('motivo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Información Médica</h5>

                        <div class="mb-3">
                            <label for="diagnostico" class="form-label">Diagnóstico</label>
                            <textarea class="form-control @error('diagnostico') is-invalid @enderror" 
                                      id="diagnostico" 
                                      name="diagnostico" 
                                      rows="3">{{ old('diagnostico', $cita->diagnostico) }}</textarea>
                            @error('diagnostico')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="receta" class="form-label">Receta / Medicamentos</label>
                            <textarea class="form-control @error('receta') is-invalid @enderror" 
                                      id="receta" 
                                      name="receta" 
                                      rows="3">{{ old('receta', $cita->receta) }}</textarea>
                            @error('receta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notas" class="form-label">Notas Adicionales</label>
                            <textarea class="form-control @error('notas') is-invalid @enderror" 
                                      id="notas" 
                                      name="notas" 
                                      rows="2">{{ old('notas', $cita->notas) }}</textarea>
                            @error('notas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('citas.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Actualizar Cita
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection