<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TAS')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/navbar-styles.css') }}">
    @stack('styles')
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-custom navbar-dark">
        <div class="container-fluid px-4 px-lg-5">
            <a class="navbar-brand fw-bold text-white d-flex align-items-center" href="{{ route('tas_inicioView') }}">
                <img src="/images/logo.png" alt="TAS" class="logo-img">
                TAS
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu"
                aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav mx-auto justify-content-center mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link text-white fs-5 {{ request()->routeIs('tas_inicioView') ? 'active' : '' }}"
                           href="{{ route('tas_inicioView') }}">
                           Inicio
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white fs-5 {{ request()->routeIs('tas_subir') ? 'active' : '' }}"
                           href="#">
                           Subir Receta
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white fs-5 {{ request()->routeIs('tas_contacto') ? 'active' : '' }}"
                           href="#">
                           Contacto
                        </a>
                    </li>
                </ul>

                <div class="d-flex">
                    <a class="btn btn-accent" href="{{ route('tas_loginView') }}">Iniciar Sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
