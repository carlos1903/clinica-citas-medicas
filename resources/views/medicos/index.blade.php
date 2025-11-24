@extends('layouts.app')

@section('title', 'Médicos')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-person-badge me-2"></i>Gestión de Médicos</h1>
        <a href="{{ route('medicos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Nuevo Médico
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Especialidad</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Consultorio</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicos as $medico)
                            <tr>
                                <td>{{ $medico->id }}</td>
                                <td>
                                    <strong>{{ $medico->nombre_completo }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $medico->especialidad }}</span>
                                </td>
                                <td>{{ $medico->email }}</td>
                                <td>{{ $medico->telefono ?? 'N/A' }}</td>
                                <td>{{ $medico->consultorio ?? 'N/A' }}</td>
                                <td>
                                    @if($medico->activo)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('medicos.show', $medico) }}" 
                                           class="btn btn-sm btn-info" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('medicos.edit', $medico) }}" 
                                           class="btn btn-sm btn-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('medicos.destroy', $medico) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('¿Está seguro de eliminar este médico?')"
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
                                <td colspan="8" class="text-center text-muted">
                                    No hay médicos registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $medicos->links() }}
            </div>
        </div>
    </div>
</div>
@endsection