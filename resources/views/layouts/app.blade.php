<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema de Administración')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.2rem;
        }

        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            width: 220px;
            background: #343a40;
            color: white;
            padding-top: 60px;
        }

        .sidebar a {
            color: #adb5bd;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
        }

        .sidebar a:hover, .sidebar a.active {
            background: #495057;
            color: white;
        }

        .content {
            margin-left: 220px;
            padding: 20px;
        }
    </style>

    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="fas fa-music"></i> Discoteca POS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <span class="nav-link text-light">
                                <i class="fas fa-user"></i> {{ Auth::user()->name }}    
                            </span>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link" style="display: inline; padding: 0; border: none; background: none;">
                                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    @endauth
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                            </a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="sidebar">
        <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard*') ? 'active' : '' }}">
            <i class="fas fa-grip"></i> Dashboard
        </a>
        <a href="{{ route('products.index') }}" class="{{ request()->is('products*') ? 'active' : '' }}">
            <i class="fas fa-boxes"></i> Productos
        </a>    
        <a href="{{ route('sales.index') }}" class="{{ request()->is('sales*') ? 'active' : '' }}">
            <i class="fas fa-cash-register"></i> Ventas
        </a>
        <a href="{{ route('cash-registers.index') }}" class="{{ request()->is('cash-registers*') ? 'active' : '' }}">
            <i class="fas fa-cash-register"></i> Cuadre de Caja
        </a>
        <a href="#" class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#configMenu" aria-expanded="false">
            <i class="fas fa-cogs"></i> Configuración
        </a>
        <div class="collapse" id="configMenu">
            <ul class="btn-toggle-nav list-unstyled fw-normal small">
                <li>
                    <a href="{{ route('tables.index') }}" class="{{ request()->is('tables*') ? 'active' : '' }} nav-link">
                        <i class="fas fa-chair"></i> Mesas
                    </a>
                </li>
                <li>
                    <a href="{{ route('users.index') }}" class="{{ request()->is('users*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> Usuarios
                    </a>
                </li>
                <li>
                    <a href="{{ route('roles.index') }}" class="{{ request()->is('roles*') ? 'active' : '' }}">
                        <i class="fas fa-user-shield"></i> Roles
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <main class="flex-fill py-4 container-fluid" style="padding-top: 80px !important; padding-left: 240px;">
        @if(session('error'))
            <div class="alert alert-danger mt-3" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @yield('content')
    </main>

    <footer class="text-center py-4 bg-dark text-light">
        <small>&copy; {{ date('Y') }} Sistema Discoteca. Todos los derechos reservados.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>