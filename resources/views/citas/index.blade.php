@extends('layouts.app')

@section('title', 'Citas')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-calendar-check me-2"></i>Gestión de Citas</h1>
        <a href="{{ route('citas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Nueva Cita
        </a>
    </div>

    <!-- Filtros -->
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('citas.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="confirmada" {{ request('estado') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                        <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                        <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" name="fecha" id="fecha" class="form-control" value="{{ request('fecha') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-funnel me-2"></i>Filtrar
                    </button>
                    <a href="{{ route('citas.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha y Hora</th>
                            <th>Paciente</th>
                            <th>Médico</th>
                            <th>Motivo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($citas as $cita)
                            <tr>
                                <td>{{ $cita->id }}</td>
                                <td>
                                    <strong>{{ $cita->fecha_hora->format('d/m/Y') }}</strong><br>
                                    <small class="text-muted">{{ $cita->fecha_hora->format('H:i') }}</small>
                                </td>
                                <td>{{ $cita->paciente->nombre_completo }}</td>
                                <td>Dr. {{ $cita->medico->nombre_completo }}</td>
                                <td>{{ $cita->motivo }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $cita->estado == 'completada' ? 'success' : 
                                        ($cita->estado == 'cancelada' ? 'danger' : 
                                        ($cita->estado == 'confirmada' ? 'info' : 'warning')) 
                                    }}">
                                        {{ ucfirst($cita->estado) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('citas.show', $cita) }}" 
                                           class="btn btn-sm btn-info" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('citas.edit', $cita) }}" 
                                           class="btn btn-sm btn-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('citas.destroy', $cita) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('¿Está seguro de eliminar esta cita?')"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No hay citas registradas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $citas->links() }}
            </div>
        </div>
    </div>
</div>
@endsection