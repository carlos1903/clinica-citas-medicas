@extends('layouts.app')

@section('title', 'Detalle de la Cita')

@section('content')
<div class="container-fluid">
    <!-- Botón de volver -->
    <div class="mb-3">
        <a href="{{ route('citas.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver a Citas
        </a>
    </div>

    <div class="row">
        <!-- Información de la Cita -->
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Información de la Cita</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center" 
                             style="width: 100px; height: 100px;">
                            <i class="bi bi-calendar-check text-primary" style="font-size: 50px;"></i>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">ID de Cita</label>
                        <h5 class="mb-0">#{{ str_pad($cita->id, 5, '0', STR_PAD_LEFT) }}</h5>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="text-muted small"><i class="bi bi-calendar3 me-1"></i>Fecha</label>
                            <div class="fw-bold">{{ $cita->fecha_hora->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small"><i class="bi bi-clock me-1"></i>Hora</label>
                            <div class="fw-bold">{{ $cita->fecha_hora->format('H:i') }}</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small"><i class="bi bi-circle-fill me-1"></i>Estado</label>
                        <div>
                            <span class="badge bg-{{ 
                                $cita->estado == 'completada' ? 'success' : 
                                ($cita->estado == 'cancelada' ? 'danger' : 
                                ($cita->estado == 'confirmada' ? 'info' : 'warning')) 
                            }} fs-6">
                                {{ ucfirst($cita->estado) }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small"><i class="bi bi-file-text me-1"></i>Motivo</label>
                        <p class="mb-0">{{ $cita->motivo }}</p>
                    </div>

                    <hr>

                    <div class="d-grid gap-2">
                        <a href="{{ route('citas.edit', $cita) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Editar Cita
                        </a>
                        <form action="{{ route('citas.destroy', $cita) }}" 
                              method="POST" 
                              onsubmit="return confirm('¿Está seguro de eliminar esta cita?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash me-2"></i>Eliminar Cita
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detalles Adicionales -->
        <div class="col-lg-8 col-md-12">
            <!-- Información del Paciente -->
            <div class="card mb-3">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-person-fill me-2"></i>Paciente</h5>
                    <a href="{{ route('pacientes.show', $cita->paciente) }}" class="btn btn-sm btn-light">
                        <i class="bi bi-eye me-1"></i>Ver Perfil
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Nombre Completo</label>
                            <p class="mb-0 fw-bold">{{ $cita->paciente->nombre_completo }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">DNI</label>
                            <p class="mb-0">{{ $cita->paciente->dni }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Edad</label>
                            <p class="mb-0">{{ $cita->paciente->edad }} años</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Email</label>
                            <p class="mb-0 text-break">{{ $cita->paciente->email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Teléfono</label>
                            <p class="mb-0">{{ $cita->paciente->telefono }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Médico -->
            <div class="card mb-3">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Médico</h5>
                    <a href="{{ route('medicos.show', $cita->medico) }}" class="btn btn-sm btn-light">
                        <i class="bi bi-eye me-1"></i>Ver Perfil
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Nombre Completo</label>
                            <p class="mb-0 fw-bold">Dr. {{ $cita->medico->nombre_completo }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Especialidad</label>
                            <p class="mb-0">
                                <span class="badge bg-info">{{ $cita->medico->especialidad }}</span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Email</label>
                            <p class="mb-0 text-break">{{ $cita->medico->email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Consultorio</label>
                            <p class="mb-0">{{ $cita->medico->consultorio ?? 'No asignado' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Médica -->
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-clipboard-pulse me-2"></i>Información Médica</h5>
                </div>
                <div class="card-body">
                    @if($cita->diagnostico || $cita->receta || $cita->notas)
                        @if($cita->diagnostico)
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-file-medical text-primary me-2" style="font-size: 24px;"></i>
                                    <h6 class="mb-0">Diagnóstico</h6>
                                </div>
                                <div class="border-start border-primary border-3 ps-3 py-2 bg-light rounded">
                                    {{ $cita->diagnostico }}
                                </div>
                            </div>
                        @endif

                        @if($cita->receta)
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-capsule text-success me-2" style="font-size: 24px;"></i>
                                    <h6 class="mb-0">Receta / Medicamentos</h6>
                                </div>
                                <div class="border-start border-success border-3 ps-3 py-2 bg-light rounded">
                                    {{ $cita->receta }}
                                </div>
                            </div>
                        @endif

                        @if($cita->notas)
                            <div class="mb-0">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-journal-text text-info me-2" style="font-size: 24px;"></i>
                                    <h6 class="mb-0">Notas Adicionales</h6>
                                </div>
                                <div class="border-start border-info border-3 ps-3 py-2 bg-light rounded">
                                    {{ $cita->notas }}
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-clipboard2-x text-muted" style="font-size: 48px;"></i>
                            <p class="text-muted mt-3 mb-0">No hay información médica registrada para esta cita</p>
                            <small class="text-muted">Edite la cita para agregar diagnóstico, receta o notas</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection