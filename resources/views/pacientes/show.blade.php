@extends('layouts.app')

@section('title', 'Detalle del Paciente')

@section('content')
<div class="container-fluid">
    <!-- Botón de volver -->
    <div class="mb-3">
        <a href="{{ route('pacientes.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver a Pacientes
        </a>
    </div>

    <div class="row">
        <!-- Información del Paciente -->
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-fill me-2"></i>Información del Paciente</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center" 
                             style="width: 120px; height: 120px;">
                            <i class="bi bi-person-fill text-danger" style="font-size: 60px;"></i>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted small">Nombre Completo</label>
                        <h5 class="mb-0">{{ $paciente->nombre_completo }}</h5>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="text-muted small"><i class="bi bi-card-text me-1"></i>DNI</label>
                            <div class="fw-bold">{{ $paciente->dni }}</div>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small"><i class="bi bi-calendar-event me-1"></i>Edad</label>
                            <div>
                                <span class="badge bg-info fs-6">{{ $paciente->edad }} años</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small"><i class="bi bi-calendar3 me-1"></i>Fecha de Nacimiento</label>
                        <div>{{ $paciente->fecha_nacimiento->format('d/m/Y') }}</div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="text-muted small"><i class="bi bi-envelope me-2"></i>Email</label>
                        <div class="text-break">{{ $paciente->email }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small"><i class="bi bi-telephone me-2"></i>Teléfono</label>
                        <div>{{ $paciente->telefono }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small"><i class="bi bi-geo-alt me-2"></i>Dirección</label>
                        <div>{{ $paciente->direccion ?? 'No especificada' }}</div>
                    </div>

                    @if($paciente->observaciones)
                        <hr>
                        <div class="alert alert-info mb-3">
                            <label class="text-muted small fw-bold"><i class="bi bi-info-circle me-2"></i>Observaciones Médicas</label>
                            <p class="mb-0 mt-2">{{ $paciente->observaciones }}</p>
                        </div>
                    @endif

                    <hr>

                    <div class="d-grid gap-2">
                        <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Editar Paciente
                        </a>
                        <a href="{{ route('citas.create') }}?paciente_id={{ $paciente->id }}" class="btn btn-success">
                            <i class="bi bi-calendar-plus me-2"></i>Nueva Cita
                        </a>
                        <form action="{{ route('pacientes.destroy', $paciente) }}" 
                              method="POST" 
                              onsubmit="return confirm('¿Está seguro de eliminar este paciente?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash me-2"></i>Eliminar Paciente
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de Citas -->
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Historial de Citas</h5>
                    <span class="badge bg-primary">{{ $paciente->citas->count() }} citas</span>
                </div>
                <div class="card-body">
                    @if($paciente->citas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Fecha y Hora</th>
                                        <th>Médico</th>
                                        <th>Motivo</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($paciente->citas as $cita)
                                        <tr>
                                            <td>
                                                <strong>{{ $cita->fecha_hora->format('d/m/Y') }}</strong><br>
                                                <small class="text-muted">{{ $cita->fecha_hora->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <i class="bi bi-person-badge me-1"></i>
                                                Dr. {{ $cita->medico->nombre_completo }}<br>
                                                <small class="text-muted">{{ $cita->medico->especialidad }}</small>
                                            </td>
                                            <td>{{ Str::limit($cita->motivo, 30) }}</td>
                                            <td>
                                                <span class="badge bg-{{ 
                                                    $cita->estado == 'completada' ? 'success' : 
                                                    ($cita->estado == 'cancelada' ? 'danger' : 
                                                    ($cita->estado == 'confirmada' ? 'info' : 'warning')) 
                                                }}">
                                                    {{ ucfirst($cita->estado) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('citas.show', $cita) }}" 
                                                   class="btn btn-sm btn-info"
                                                   title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Estadísticas de citas -->
                        <div class="row mt-4">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                                    <h3 class="mb-0 text-warning">{{ $paciente->citas->where('estado', 'pendiente')->count() }}</h3>
                                    <small class="text-muted">Pendientes</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                                    <h3 class="mb-0 text-info">{{ $paciente->citas->where('estado', 'confirmada')->count() }}</h3>
                                    <small class="text-muted">Confirmadas</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                    <h3 class="mb-0 text-success">{{ $paciente->citas->where('estado', 'completada')->count() }}</h3>
                                    <small class="text-muted">Completadas</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="text-center p-3 bg-danger bg-opacity-10 rounded">
                                    <h3 class="mb-0 text-danger">{{ $paciente->citas->where('estado', 'cancelada')->count() }}</h3>
                                    <small class="text-muted">Canceladas</small>
                                </div>
                            </div>
                        </div>

                        <!-- Línea de tiempo de citas recientes -->
                        @if($paciente->citas->where('diagnostico', '!=', null)->count() > 0)
                            <hr class="my-4">
                            <h6 class="mb-3"><i class="bi bi-clock-history me-2"></i>Historial Médico</h6>
                            @foreach($paciente->citas->where('diagnostico', '!=', null)->take(3) as $cita)
                                <div class="border-start border-primary border-3 ps-3 mb-3">
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">{{ $cita->fecha_hora->format('d/m/Y') }}</small>
                                        <small class="text-muted">Dr. {{ $cita->medico->nombre_completo }}</small>
                                    </div>
                                    <strong>{{ $cita->motivo }}</strong>
                                    @if($cita->diagnostico)
                                        <p class="mb-1 mt-2"><strong>Diagnóstico:</strong> {{ Str::limit($cita->diagnostico, 100) }}</p>
                                    @endif
                                    @if($cita->receta)
                                        <p class="mb-0"><strong>Receta:</strong> {{ Str::limit($cita->receta, 100) }}</p>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x text-muted" style="font-size: 64px;"></i>
                            <p class="text-muted mt-3">Este paciente no tiene citas registradas</p>
                            <a href="{{ route('citas.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Crear Primera Cita
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection