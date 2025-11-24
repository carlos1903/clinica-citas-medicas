@extends('layouts.app')

@section('title', 'Pacientes')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-people me-2"></i>Gestión de Pacientes</h1>
        <a href="{{ route('pacientes.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Nuevo Paciente
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
                            <th>DNI</th>
                            <th>Edad</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pacientes as $paciente)
                            <tr>
                                <td>{{ $paciente->id }}</td>
                                <td>
                                    <strong>{{ $paciente->nombre_completo }}</strong>
                                </td>
                                <td>{{ $paciente->dni }}</td>
                                <td>{{ $paciente->edad }} años</td>
                                <td>{{ $paciente->email }}</td>
                                <td>{{ $paciente->telefono }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('pacientes.show', $paciente) }}" 
                                           class="btn btn-sm btn-info" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('pacientes.edit', $paciente) }}" 
                                           class="btn btn-sm btn-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('pacientes.destroy', $paciente) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('¿Está seguro de eliminar este paciente?')"
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
                                    No hay pacientes registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $pacientes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection