@extends('layouts.app')

@section('title', 'Reportes y Estadísticas')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-graph-up me-2"></i>Reportes y Estadísticas</h1>
    </div>

    <!-- Tarjetas de Estadísticas Principales -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-2">Total Médicos</h6>
                        <h2 class="mb-0">{{ $totalMedicos }}</h2>
                    </div>
                    <i class="bi bi-person-badge" style="font-size: 48px; opacity: 0.3;"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-2">Total Pacientes</h6>
                        <h2 class="mb-0">{{ $totalPacientes }}</h2>
                    </div>
                    <i class="bi bi-people" style="font-size: 48px; opacity: 0.3;"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-2">Total Citas</h6>
                        <h2 class="mb-0">{{ $totalCitas }}</h2>
                    </div>
                    <i class="bi bi-calendar-check" style="font-size: 48px; opacity: 0.3;"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-2">Tasa Completadas</h6>
                        <h2 class="mb-0">{{ $tasaCompletadas }}%</h2>
                    </div>
                    <i class="bi bi-check-circle" style="font-size: 48px; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row">
        <!-- Gráfico de Citas por Estado -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Citas por Estado</h5>
                </div>
                <div class="card-body">
                    <canvas id="citasPorEstadoChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfico de Citas por Mes -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Citas por Mes (Últimos 6 meses)</h5>
                </div>
                <div class="card-body">
                    <canvas id="citasPorMesChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfico de Médicos con Más Citas -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Top 5 Médicos con Más Citas</h5>
                </div>
                <div class="card-body">
                    <canvas id="medicosMasCitasChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfico de Citas por Especialidad -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-hospital me-2"></i>Citas por Especialidad</h5>
                </div>
                <div class="card-body">
                    <canvas id="citasPorEspecialidadChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Estadísticas Detalladas -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-table me-2"></i>Resumen Detallado</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Métrica</th>
                                    <th class="text-end">Valor</th>
                                    <th>Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($citasPorEstado as $estado)
                                    <tr>
                                        <td><strong>Citas {{ ucfirst($estado->estado) }}</strong></td>
                                        <td class="text-end">{{ $estado->total }}</td>
                                        <td>
                                            <div class="progress" style="height: 25px;">
                                                <div class="progress-bar bg-{{ 
                                                    $estado->estado == 'completada' ? 'success' : 
                                                    ($estado->estado == 'cancelada' ? 'danger' : 
                                                    ($estado->estado == 'confirmada' ? 'info' : 'warning')) 
                                                }}" 
                                                     role="progressbar" 
                                                     style="width: {{ $totalCitas > 0 ? round(($estado->total / $totalCitas) * 100, 2) : 0 }}%"
                                                     aria-valuenow="{{ $estado->total }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="{{ $totalCitas }}">
                                                    {{ $totalCitas > 0 ? round(($estado->total / $totalCitas) * 100, 2) : 0 }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Colores personalizados
    const colors = {
        primary: 'rgba(102, 126, 234, 0.8)',
        success: 'rgba(67, 233, 123, 0.8)',
        danger: 'rgba(245, 87, 108, 0.8)',
        warning: 'rgba(255, 193, 7, 0.8)',
        info: 'rgba(79, 172, 254, 0.8)',
    };

    // 1. Doughnut: Citas por Estado
    const citasPorEstadoData = {!! json_encode($citasPorEstado) !!};
    const ctxEstado = document.getElementById('citasPorEstadoChart').getContext('2d');
    new Chart(ctxEstado, {
        type: 'doughnut',
        data: {
            labels: citasPorEstadoData.map(item => item.estado.charAt(0).toUpperCase() + item.estado.slice(1)),
            datasets: [{
                data: citasPorEstadoData.map(item => item.total),
                backgroundColor: [colors.warning, colors.info, colors.success, colors.danger],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // 2. Line: Citas por Mes
    const citasPorMesData = {!! json_encode($citasPorMes) !!};
    const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    const ctxMes = document.getElementById('citasPorMesChart').getContext('2d');
    new Chart(ctxMes, {
        type: 'line',
        data: {
            labels: citasPorMesData.map(item => meses[item.mes - 1] + ' ' + item.año),
            datasets: [{
                label: 'Citas',
                data: citasPorMesData.map(item => item.total),
                borderColor: colors.primary,
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // 3. Médicos con Más Citas (Bar Horizontal)
    const medicosMasCitasData = {!! json_encode($medicosMasCitas) !!};
    const ctxMedicos = document.getElementById('medicosMasCitasChart').getContext('2d');
    new Chart(ctxMedicos, {
        type: 'bar',
        data: {
            labels: medicosMasCitasData.map(item => 'Dr. ' + item.nombre + ' ' + item.apellido),
            datasets: [{
                label: 'Número de Citas',
                data: medicosMasCitasData.map(item => item.citas_count),
                backgroundColor: colors.success,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // 4. Citas por Especialidad (Bar)
    const citasPorEspecialidadData = {!! json_encode($citasPorEspecialidad) !!};
    const ctxEspecialidad = document.getElementById('citasPorEspecialidadChart').getContext('2d');
    new Chart(ctxEspecialidad, {
        type: 'bar',
        data: {
            labels: citasPorEspecialidadData.map(item => item.especialidad),
            datasets: [{
                label: 'Citas',
                data: citasPorEspecialidadData.map(item => item.total),
                backgroundColor: colors.info,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
</script>
@endsection
