<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Citas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        /* Estilos base consistentes con tu plantilla principal */
        :root {
            --primary-color: #667eea;
        }

        body {
            /* Fondo de degradado suave */
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .login-card {
            max-width: 420px;
            width: 90%;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15); /* Sombra más pronunciada */
            padding: 2rem;
        }

        .btn-primary {
            /* Mismo degradado que la barra lateral */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            transition: all 0.3s;
            padding: 0.75rem 1.5rem;
        }

        .btn-primary:hover {
            box-shadow: 0 4px 10px rgba(118, 75, 162, 0.4);
            transform: translateY(-1px);
        }

        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
        }

        .form-label {
            font-weight: 500;
            color: #495057;
        }
    </style>
</head>
<body>

    <div class="card login-card">
        <div class="text-center mb-4">
            <img src="{{ asset('img/logo3.png') }}" 
                 alt="Logo de la Clínica" 
                 style="width: 60px; height: auto; margin-bottom: 10px;">
            <h4 class="mt-2 text-primary fw-bold">Iniciar Sesión</h4>
            <p class="text-muted">Accede a tu panel de administración.</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success mb-4">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       autocomplete="username"
                       class="form-control @error('email') is-invalid @enderror">
                
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Contraseña</label>
                <input id="password"
                       type="password"
                       name="password"
                       required 
                       autocomplete="current-password"
                       class="form-control @error('password') is-invalid @enderror">
                       
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-check mb-4">
                <input id="remember_me" 
                       type="checkbox" 
                       name="remember"
                       class="form-check-input">
                <label class="form-check-label text-muted" for="remember_me">
                    Recordarme
                </label>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Acceder
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>