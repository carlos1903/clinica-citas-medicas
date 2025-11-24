<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Citas Médicas')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #667eea;
            --sidebar-width: 260px;
        }
        
        body {
            min-height: 100vh;
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed; 
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 0;
            z-index: 1000;
            transform: translateX(0); 
            transition: transform 0.3s ease;
            /* Si la barra lateral es más larga que la pantalla, permite el scroll DENTRO de ella */
            overflow-y: auto; 
        }
        
        /* Ocultar en móvil por defecto si no se aplica la clase 'mobile-show' */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
        }

        .sidebar.mobile-show {
            transform: translateX(0);
        }
        
        .sidebar .logo {
            color: white;
            text-align: center;
            padding: 25px 20px;
            font-size: 24px;
            font-weight: bold;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex; 
            align-items: center;
            justify-content: center;
        }
        
        .sidebar .logo img {
            margin-right: 0; 
        }

        .sidebar .nav {
            padding: 20px 0;
            /* Añadimos un padding inferior para compensar la altura del user-section 
               y evitar que los últimos enlaces queden cubiertos si el scroll es necesario */
            padding-bottom: 120px; 
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 14px 25px;
            margin: 5px 15px;
            border-radius: 10px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            font-weight: 500;
            text-decoration: none; 
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
            transform: translateX(5px);
        }
        
        .sidebar .nav-link i {
            margin-right: 12px;
            font-size: 20px;
        }
        
        .sidebar .user-section {
            /* Vuelve a absolute para que se fije al fondo de la barra lateral */
            position: absolute; 
            bottom: 0;
            width: 100%;
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.1);
        }
        
        .sidebar .user-info {
            color: white;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .sidebar .user-info i {
            font-size: 32px;
            margin-right: 10px;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
            transition: margin-left 0.3s ease;
        }
        
        /* Mobile Toggle */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: var(--primary-color);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            cursor: pointer; 
        }
        
        /* Overlay para mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        
        .sidebar-overlay.active {
            display: block;
        }
        
        /* Cards y otros estilos (sin cambios) */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 25px rgba(0,0,0,0.15);
        }
        
        .card-header {
            background: white;
            border-bottom: 2px solid #f0f0f0;
            padding: 20px;
            border-radius: 15px 15px 0 0 !important;
            font-weight: 600;
        }
        
        .stat-card {
            padding: 25px;
            border-radius: 15px;
            color: white;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .btn {
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 500;
            transition: all 0.3s;
            text-decoration: none; 
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        
        .btn-group .btn {
            padding: 8px 12px;
        }
        
        .table {
            background: white;
        }
        
        .table thead th {
            border-bottom: 2px solid #667eea;
            color: #667eea;
            font-weight: 600;
        }
        
        .badge {
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: 500;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            
            .main-content {
                margin-left: 0;
                padding: 20px 15px;
                padding-top: 80px;
            }
            
            .mobile-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .stat-card {
                margin-bottom: 15px;
            }
            
            .table-responsive {
                font-size: 14px;
            }
            
            .card-header h1,
            .card-header h4,
            .card-header h5 {
                font-size: 18px;
            }
        }
        
        @media (max-width: 576px) {
            .btn-group {
                display: flex;
                flex-direction: column;
                width: 100%;
            }
            
            .btn-group .btn {
                width: 100%;
                margin-bottom: 5px;
            }
        }

        .alert {
            border-radius: 10px;
            border: none;
        }
    </style>
    @yield('styles')
</head>
<body>
    <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle navigation">
        <i class="bi bi-list fs-4"></i>
    </button>

    <div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>

    <div class="sidebar" id="sidebar">
        <div class="logo">
            <img src="{{ asset('img/logo3.png') }}" 
                 alt="Logo de la Clínica" 
                 style="width: 50px; height: auto;">
        </div>

        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('medicos.*') ? 'active' : '' }}" href="{{ route('medicos.index') }}">
                <i class="bi bi-person-badge"></i> Médicos
            </a>
            <a class="nav-link {{ request()->routeIs('pacientes.*') ? 'active' : '' }}" href="{{ route('pacientes.index') }}">
                <i class="bi bi-people"></i> Pacientes
            </a>
            <a class="nav-link {{ request()->routeIs('citas.*') ? 'active' : '' }}" href="{{ route('citas.index') }}">
                <i class="bi bi-calendar-check"></i> Citas
            </a>
            <a class="nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}" href="{{ route('reportes.index') }}">
                <i class="bi bi-bar-chart"></i> Reportes
            </a>

        </nav>
        
        <div class="user-section">
            <div class="user-info">
                <i class="bi bi-person-circle"></i>
                <div>
                    <div class="fw-bold">{{ Auth::user()->name ?? 'Usuario Desconocido' }}</div>
                    <small style="opacity: 0.8;">Administrador</small>
                </div>
            </div >
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-light btn-sm w-100">
                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                </button>
            </form>
        </div>
    </div>

    <div class="main-content" id="mainContent">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile menu toggle
        const mobileToggle = document.getElementById('mobileToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        mobileToggle.addEventListener('click', () => {
            sidebar.classList.toggle('mobile-show');
            sidebarOverlay.classList.toggle('active');
        });

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('mobile-show');
            sidebarOverlay.classList.remove('active');
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = bootstrap.Alert.getInstance(alert) || new bootstrap.Alert(alert);
                const closeButton = alert.querySelector('.btn-close');
                if (closeButton) {
                    closeButton.click();
                } else {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 150); 
                }
            });
        }, 5000);
    </script>
    @yield('scripts')
</body>
</html>