@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">Dashboard de la Clínica</h1>

        {{-- FILA 1: TARJETAS DE ESTADÍSTICAS --}}
        <div class="row">

            {{-- Tarjeta 1: Total Médicos (Gradiente Púrpura/Azul) --}}
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Médicos Activos</h5>
                            <h2 class="display-4 fw-bold">{{ $totalMedicos ?? 0 }}</h2> 
                        </div>
                        <i class="bi bi-person-badge fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>

            {{-- Tarjeta 2: Total Pacientes (Gradiente Rosa/Naranja) --}}
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Total Pacientes</h5>
                            <h2 class="display-4 fw-bold">{{ $totalPacientes ?? 0 }}</h2> 
                        </div>
                        <i class="bi bi-people fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>

            {{-- Tarjeta 3: Citas Pendientes (Gradiente Azul/Cian) --}}
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card text-white" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Citas Pendientes</h5>
                            <h2 class="display-4 fw-bold">{{ $totalCitasPendientes ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-calendar-minus fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>

            {{-- Tarjeta 4: Tasa Completadas (Gradiente Verde Brillante) --}}
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card text-white" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Tasa Completadas</h5>
                            <h2 class="display-4 fw-bold">{{ $tasaCompletadas ?? 0 }}%</h2>
                        </div>
                        <i class="bi bi-check-circle fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>

        </div> {{-- Fin Fila 1: Totales --}}

        {{-- FILA 2: TABLA DE CITAS RECIENTES --}}
        <div class="row">
            
            {{-- Columna 1: Citas Recientes (Se mantiene el tamaño grande) --}}
            <div class="col-lg-7 mb-4">
                <div class="card">
                    <div class="card-header">
                        Citas Recientes
                        <a href="{{ route('citas.index') }}" class="btn btn-sm btn-primary float-end">Ver Todas</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Fecha y Hora</th>
                                        <th>Paciente</th>
                                        <th>Médico</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($citasRecientes as $cita)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y H:i A') }}</td>
                                            <td>{{ $cita->paciente->nombre }}</td>
                                            <td>{{ $cita->medico->nombre }} ({{ $cita->medico->especialidad }})</td>
                                            <td>
                                                @php
                                                    $badgeClass = match ($cita->estado) {
                                                        'pendiente' => 'bg-warning text-dark',
                                                        'completada' => 'bg-success',
                                                        'cancelada' => 'bg-danger',
                                                        default => 'bg-secondary'
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ ucfirst($cita->estado) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No hay citas registradas recientemente.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Columna 2: CONTENIDO NUEVO (Pacientes y Médicos) --}}
            <div class="col-lg-5 mb-4">
                <div class="row">
                    
                    {{-- Tarjeta A: Pacientes Recientes --}}
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-person-plus me-2"></i>Nuevos Pacientes</h5>
                                <a href="{{ route('pacientes.index') }}" class="btn btn-sm btn-outline-primary float-end">Ver todos</a>
                            </div>
                            <ul class="list-group list-group-flush">
                                @forelse($pacientesRecientes as $paciente)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="bi bi-person-fill me-2 text-primary"></i>
                                            {{ $paciente->nombre }}
                                        </div>
                                        <small class="text-muted">
                                            Hace {{ $paciente->created_at->diffForHumans(null, true) }}
                                        </small>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted">No hay nuevos pacientes.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    {{-- Tarjeta B: Top Médicos --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="bi bi-heart-pulse me-2"></i>Top 5 Médicos (Citas)</h5>
                            </div>
                            <ul class="list-group list-group-flush">
                                @forelse($medicosTopCitas as $medico)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="bi bi-person-circle me-2 text-success"></i>
                                            Dr. {{ $medico->nombre }}
                                        </div>
                                        <span class="badge bg-primary rounded-pill">{{ $medico->citas_count }} Citas</span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted">No hay datos de médicos.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div> {{-- Fin Fila 2 --}}

    </div>
@endsection

{{-- Eliminamos la sección @section('scripts') que contenía el código de Chart.js para el gráfico semanal --}}