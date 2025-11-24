@extends('layouts.app')

@section('title', 'Detalle del Médico')

@section('styles')
<style>
    /* ------------------------------------------------ */
    /* ** CORRECCIÓN GENERAL PARA EL ANCHO DE LA TABLA ** */
    /* Estas reglas se aplican en todas las resoluciones, incluyendo Desktop.
       Fuerzan a las columnas a ser lo más compactas posible. */
    .table-citas th,
    .table-citas td {
        white-space: nowrap; /* Impide que el contenido se envuelva a la siguiente línea */
        padding: 0.75rem;
    }

    /* Asignar anchos fijos a las columnas de menor contenido */
    .table-citas .col-estado {
        width: 100px; 
    }

    .table-citas .col-acciones {
        width: 50px; /* Para que solo quepa el botón de icono */
        text-align: center;
    }

    /* Ajustar el tamaño del paciente y el motivo */
    .table-citas .col-paciente, 
    .table-citas .col-motivo {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis; /* Añadir puntos suspensivos si se desborda */
    }

    /* El resto de estilos para móvil que ya teníamos se mantienen: */
    @media (max-width: 768px) {
        .table-responsive table {
            font-size: 12px; 
        }
        .table-responsive table th,
        .table-responsive table td {
            padding: 0.5rem 0.3rem; 
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <a href="{{ route('medicos.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver a Médicos
        </a>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Información del Médico</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center" 
                             style="width: 120px; height: 120px;">
                            <i class="bi bi-person-fill text-primary" style="font-size: 60px;"></i>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted small">Nombre Completo</label>
                        <h5 class="mb-0">{{ $medico->nombre_completo }}</h5>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Especialidad</label>
                        <div>
                            <span class="badge bg-info fs-6">{{ $medico->especialidad }}</span>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="text-muted small"><i class="bi bi-envelope me-2"></i>Email</label>
                        <div>{{ $medico->email }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small"><i class="bi bi-telephone me-2"></i>Teléfono</label>
                        <div>{{ $medico->telefono ?? 'No especificado' }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small"><i class="bi bi-door-open me-2"></i>Consultorio</label>
                        <div>{{ $medico->consultorio ?? 'No asignado' }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small"><i class="bi bi-circle-fill me-2"></i>Estado</label>
                        <div>
                            @if($medico->activo)
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Activo</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Inactivo</span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="d-grid gap-2">
                        <a href="{{ route('medicos.edit', $medico) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Editar Médico
                        </a>
                        <form action="{{ route('medicos.destroy', $medico) }}" 
                              method="POST" 
                              onsubmit="return confirm('¿Está seguro de eliminar este médico?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash me-2"></i>Eliminar Médico
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Historial de Citas</h5>
                    <span class="badge bg-primary">{{ $medico->citas->count() }} citas</span>
                </div>
                <div class="card-body">
                    @if($medico->citas->count() > 0)
                        <div class="table-responsive">
                            {{-- Agregamos la clase 'table-citas' para aplicar nuestros estilos de ancho fijo --}}
                            <table class="table table-hover table-citas"> 
                                <thead>
                                    <tr>
                                        <th>Fecha y Hora</th>
                                        <th class="col-paciente">Paciente</th>
                                        <th class="col-motivo">Motivo</th>
                                        <th class="col-estado">Estado</th>
                                        <th class="text-center col-acciones">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($medico->citas as $cita)
                                        <tr>
                                            <td>
                                                <strong>{{ $cita->fecha_hora->format('d/m/Y') }}</strong><br>
                                                <small class="text-muted">{{ $cita->fecha_hora->format('H:i') }}</small>
                                            </td>
                                            <td class="col-paciente">
                                                <i class="bi bi-person-circle me-1"></i>
                                                {{ $cita->paciente->nombre_completo }}
                                            </td>
                                            <td class="col-motivo">{{ Str::limit($cita->motivo, 30) }}</td>
                                            <td class="col-estado">
                                                <span class="badge bg-{{ 
                                                    $cita->estado == 'completada' ? 'success' : 
                                                    ($cita->estado == 'cancelada' ? 'danger' : 
                                                    ($cita->estado == 'confirmada' ? 'info' : 'warning')) 
                                                }}">
                                                    {{ ucfirst($cita->estado) }}
                                                </span>
                                            </td>
                                            <td class="col-acciones">
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

                        <div class="row mt-4">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                                    <h3 class="mb-0 text-warning">{{ $medico->citas->where('estado', 'pendiente')->count() }}</h3>
                                    <small class="text-muted">Pendientes</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                                    <h3 class="mb-0 text-info">{{ $medico->citas->where('estado', 'confirmada')->count() }}</h3>
                                    <small class="text-muted">Confirmadas</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                    <h3 class="mb-0 text-success">{{ $medico->citas->where('estado', 'completada')->count() }}</h3>
                                    <small class="text-muted">Completadas</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="text-center p-3 bg-danger bg-opacity-10 rounded">
                                    <h3 class="mb-0 text-danger">{{ $medico->citas->where('estado', 'cancelada')->count() }}</h3>
                                    <small class="text-muted">Canceladas</small>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x text-muted" style="font-size: 64px;"></i>
                            <p class="text-muted mt-3">Este médico no tiene citas registradas</p>
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