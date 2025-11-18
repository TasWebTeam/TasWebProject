@extends('layouts.template')

@section('title', request()->routeIs('tas_registroView') ? 'Registro' : 'Login')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/register-stepper-styles.css') }}">
@endpush

@section('body-class', 'login-page')

@section('content')

    <div class="login-container {{ request()->routeIs('tas_registroView') ? 'active' : '' }}" id="container">

        <div class="form-container sign-up">

            <form id="formPaso1">
                @csrf

                <x-register_stepper :currentStep="1" />

                @if ($errors->registro->any())
                    <div id="erroresPaso1" class="alert alert-danger">
                        <ul>
                            @foreach ($errors->registro->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div id="erroresPaso1" class="alert alert-danger" style="display:none;"></div>
                @endif

                <h1>Crear Cuenta</h1>

                <input type="text" name="correo" placeholder="Correo electrónico" required value="{{ old('correo') }}">
                <input type="text" name="nombre" placeholder="Nombre" required value="{{ old('nombre') }}">
                <input type="text" name="apellido" placeholder="Apellido" required value="{{ old('apellido') }}">
                <input type="password" name="nip" placeholder="Contraseña" required>

                <button type="button" id="btnContinuar">Continuar</button>
            </form>

            <form id="formPaso2" method="POST" action="{{ route('tas_crearCuenta') }}" style="display:none;">
                @csrf

                <x-register_stepper :currentStep="2" />

                @if ($errors->tarjeta->any())
                    <div id="erroresPaso2" class="alert alert-danger">
                        <ul>
                            @foreach ($errors->tarjeta->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <h1>Método de Pago</h1>
                <p class="step-description">Agrega una tarjeta para agilizar tus compras (opcional)</p>

                <div class="fila-tarjeta">
                    <img id="cardBrand" class="d-none" alt="brand">
                    <input type="text" id="numero_tarjeta" name="numero_tarjeta" placeholder="Número de tarjeta"
                        maxlength="19">
                </div>

                <input type="text" name="nombre_tarjeta" placeholder="Nombre en la tarjeta">

                <div class="input-group">
                    <input type="text" name="fecha_vencimiento" id="fecha_vencimiento" placeholder="MM/AA"
                        maxlength="5">
                    <input type="text" name="cvv" placeholder="CVV" maxlength="4">
                </div>

                <input type="hidden" name="correo" id="correoHidden" value="{{ old('correo') }}">
                <input type="hidden" name="nombre" id="nombreHidden" value="{{ old('nombre') }}">
                <input type="hidden" name="apellido" id="apellidoHidden" value="{{ old('apellido') }}">
                <input type="hidden" name="nip" id="nipHidden" value="{{ old('nip') }}">
                <input type="hidden" name="omitir_pago" id="omitir_pago" value="0">

                <div class="button-group">
                    <button type="button" id="btnVolver">Volver</button>
                    <button type="button" id="btnOmitir">Omitir</button>
                </div>

                <button type="submit" id="btnRegistrarse">Registrarse</button>
            </form>

        </div>

        <div class="form-container sign-in">

            <form method="POST" action="{{ route('tas_inicioSesion') }}">
                @csrf

                <h1>Iniciar Sesión</h1>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->login->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->login->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <input type="text" name="correo" placeholder="Correo electrónico" required value="{{ old('correo') }}">
                <input type="password" name="nip" placeholder="Contraseña" required>

                <a href="#">¿Olvidaste tu contraseña?</a>
                <button type="submit" class="btn-login-submit">Ingresar</button>
            </form>

        </div>

        <div class="toggle-container">
            <div class="toggle">

                <div class="toggle-panel toggle-left">
                    <h1>Qué gusto verte de nuevo</h1>
                    <p>Accede con tu cuenta para continuar usando los servicios de TAS.</p>
                    <button class="hidden" id="signIn" type="button" data-url="{{ route('tas_loginView') }}">
                        Iniciar Sesión
                    </button>
                </div>

                <div class="toggle-panel toggle-right">
                    <h1>Bienvenido a TAS</h1>
                    <p>Regístrate y accede a una mejor experiencia en tus compras.</p>
                    <button class="hidden" id="signUp" type="button" data-url="{{ route('tas_registroView') }}">
                        Crear Cuenta
                    </button>
                </div>

            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/login-script.js') }}"></script>
    <script src="{{ asset('js/register-stepper-script.js') }}"></script>
@endpush
