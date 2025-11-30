<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TAS')</title>

    {{-- Bootstrap y FontAwesome --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Navbar --}}
    <link rel="stylesheet" href="{{ asset('css/navbar-styles.css') }}">

    {{-- Estilos propios de cada página --}}
    @stack('styles')
</head>

<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-custom navbar-dark fixed-top">
        <div class="container-fluid px-4 px-lg-5">

            {{-- LOGO --}}
            <a class="navbar-brand fw-bold text-white d-flex align-items-center"
               href="{{ route('tas_inicioView') }}">
                <img src="/images/logo.png" alt="TAS" class="logo-img me-2">
                TAS
            </a>

            {{-- Botón responsive --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu"
                aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Contenido del menú --}}
            <div class="collapse navbar-collapse" id="navbarMenu">

                <ul class="navbar-nav mx-auto justify-content-center mb-2 mb-lg-0">

                    @php
                        $usuario = session('usuario');
                        $rol = $usuario['rol'] ?? 'paciente';
                    @endphp

                    {{-- ================================
                        MENÚ DE EMPLEADO
                    ================================= --}}
                    @if ($rol === 'empleado')
                        <li class="nav-item">
                            <a class="nav-link text-white fs-5 {{ request()->routeIs('empleado_recetas') ? 'active' : '' }}"
                                href="{{ route('empleado_recetas') }}">
                                Recetas
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white fs-5 {{ request()->routeIs('empleado_recetas_expiradas') ? 'active' : '' }}"
                                href="{{ route('empleado_recetas_expiradas') }}">
                                Recetas Expiradas
                            </a>
                        </li>

                    {{-- ================================
                        MENÚ DE PACIENTE NORMAL
                    ================================= --}}
                    @else
                        <li class="nav-item">
                            <a class="nav-link text-white fs-5 {{ request()->routeIs('tas_inicioView') ? 'active' : '' }}"
                                href="{{ route('tas_inicioView') }}">
                                Inicio
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white fs-5 {{ request()->routeIs('tas_subirRecetaView') ? 'active' : '' }}"
                                href="{{ route('tas_subirRecetaView') }}">
                                Subir Receta
                            </a>
                        </li>
                    @endif
                </ul>

                {{-- PERFIL / LOGIN --}}
                <div class="d-flex align-items-center">
                    @if ($usuario)
                        {{-- MENÚ DE USUARIO LOGUEADO --}}
                        <div class="dropdown">
                            <button class="btn btn-accent dropdown-toggle d-flex align-items-center" type="button"
                                id="userMenu" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-2"></i>
                                {{ $usuario['nombre'] ?? 'Usuario' }}
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                {{-- Si es paciente, mostrar método de pago --}}
                                @if ($rol !== 'empleado')
                                    <li>
                                        <a class="dropdown-item" href="{{ route('tas_metodoPagoView') }}">
                                            Método de pago
                                        </a>
                                    </li>
                                @endif

                                {{-- Cerrar sesión --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Cerrar sesión
                                    </a>
                                </li>
                            </ul>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>

                    @else
                        {{-- BOTÓN LOGIN --}}
                        <a class="btn btn-accent" href="{{ route('tas_loginView') }}">Iniciar Sesión</a>
                    @endif
                </div>

            </div>
        </div>
    </nav>

    {{-- CONTENIDO DE LA PÁGINA --}}
    <div class="container mt-4">
        @yield('content')
    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Scripts específicos de cada vista --}}
    @stack('scripts')

</body>
</html>
