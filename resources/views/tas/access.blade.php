@extends('layouts.template')

@section('title', request()->routeIs('register') ? 'Registro - TAS' : 'Login - TAS')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login-styles.css') }}">
@endpush

@section('body-class', 'login-page')

@section('content')
    <div class="login-container {{ request()->routeIs('register') ? 'active' : '' }}" id="container">

        <div class="form-container sign-up">
            <form method="POST" action="{{ route('tas_crearCuenta') }}">
                @csrf
                <h1>Crear Cuenta</h1>
                <input type="text" placeholder="Correo electrónico" name="correo" value="{{ old('correo') }}" required>
                <input type="text" placeholder="Nombre" name="nombre" value="{{ old('nombre') }}" required>
                <input type="text" placeholder="Apellido" name="apellido" value="{{ old('apellido') }}" required>
                <input type="password" placeholder="Contraseña" name="nip" required>
                <button type="submit">Registrarse</button>
            </form>
        </div>

        <div class="form-container sign-in">
            <form method="POST" action="{{ route('tas_inicioSesion') }}">
                @csrf
                <h1>Iniciar Sesión</h1>
                <input type="text" placeholder="Correo electrónico" name="correo" value="{{ old('correo') }}" required>
                <input type="password" placeholder="Contraseña" name="nip" required>
                <a href="#">¿Olvidaste tu contraseña?</a>
                <button type="submit">Ingresar</button>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Qué gusto verte de nuevo</h1>
                    <p>Accede con tu cuenta para continuar usando todos los servicios de <strong>TAS</strong>.</p>
                    <button class="hidden" id="signIn" type="button" data-url="{{ route('tas_loginView') }}">Iniciar Sesión</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Bienvenido a TAS</h1>
                    <p>Regístrate y descubre cómo podemos ayudarte a conseguir tus medicamentos de forma rápida y sencilla.</p>
                    <button class="hidden" id="signUp" type="button" data-url="{{ route('tas_registroView') }}">Crear Cuenta</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/login-script.js') }}"></script>
@endpush