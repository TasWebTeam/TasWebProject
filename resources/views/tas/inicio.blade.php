
@extends('layouts.template')
@section('title', 'Inicio')

@section('content')

{{-- VIDEO HERO SECTION - Full Width pegado a navbar --}}
<section class="video-hero-section">
    <div class="video-container">
        <video autoplay muted loop playsinline class="hero-video">
            <source src="{{ asset('videos/VideoTAS.mp4') }}" type="video/mp4">
            Tu navegador no soporta el video.
        </video>
        
        {{-- Overlay oscuro para mejor legibilidad del texto --}}
        <div class="video-overlay"></div>
        
        {{-- Contenido sobre el video --}}
        <div class="video-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center text-white">
                        <h1 class="display-3 fw-bold mb-4 animate-fade-in">
                            Todos tus medicamentos, en un solo lugar
                        </h1>
                        <p class="lead fs-4 mb-5 animate-fade-in-delay">
                            Te Acerco Salud (TAS) es una plataforma tecnológica completa que une a pacientes con farmacias de forma eficiente.
                        </p>
                        <div class="animate-fade-in-delay-2">


                            <!--<a href="#farmacias" class="btn btn-light btn-lg px-5 py-3 me-3 mb-3">
                                Ver farmacias
                            </a>
                            <a href="{{ route('servicio') }}" class="btn btn-outline-light btn-lg px-5 py-3 mb-3">
                                Saber más
                            </a> -->
                        

                            {{-- SI NO HAY SESIÓN --}}
                            @if (!session()->has('usuario'))
                                <a href="{{ route('tas_loginView') }}" class="btn btn-light btn-lg px-5 py-3 me-3 mb-3">
                                    Iniciar sesión
                                </a>

                                <a href="#farmacias" class="btn btn-outline-light btn-lg px-5 py-3 mb-3">
                                    Ver farmacias
                                </a>
                            @endif

                            {{-- SI HAY SESIÓN --}}
                            @if (session()->has('usuario'))
                                <a href="#farmacias" class="btn btn-light btn-lg px-5 py-3 me-3 mb-3">
                                    Ver farmacias
                                </a>

                                <a href="{{ route('servicio') }}" class="btn btn-outline-light btn-lg px-5 py-3 mb-3">
                                    Saber más
                                </a>
                            @endif    
                        
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Indicador de scroll --}}
        <div class="scroll-indicator">
            <i class="fas fa-chevron-down"></i>
        </div>
    </div>
</section>


<!-- SECCIÓN: ¿CÓMO FUNCIONA TAS? -->
<section class="como-funciona-section py-5">
    <div class="container text-center">

        <h2 class="fw-bold mb-4" style="color:#003865;">¿Cómo funciona TAS?</h2>
        <p class="text-muted mb-5 fs-5">
            Tan simple como seguir tres pasos. Nosotros nos encargamos del resto.
        </p>

        <div class="row g-4">
            <!-- PASO 1 -->
            <div class="col-md-4">
                <div class="funciona-card p-4 h-100">
                    <div class="icon-wrapper mb-3">
                        <i class="fas fa-file-medical fa-3x"></i>
                    </div>
                    <h4 class="fw-bold" style="color:#003865;">Sube tu receta</h4>
                    <p class="text-muted">
                        Carga tu receta médica y selecciona la sucursal a recoger.
                    </p>
                </div>
            </div>

            <!-- PASO 2 -->
            <div class="col-md-4">
                <div class="funciona-card p-4 h-100">
                    <div class="icon-wrapper mb-3">
                        <i class="fas fa-prescription-bottle-alt fa-3x"></i>
                    </div>
                    <h4 class="fw-bold" style="color:#003865;">Buscamos farmacias</h4>
                    <p class="text-muted">
                        TAS encuentra qué farmacias tienen tus medicamentos disponibles.
                    </p>
                </div>
            </div>

            <!-- PASO 3 -->
            <div class="col-md-4">
                <div class="funciona-card p-4 h-100">
                    <div class="icon-wrapper mb-3">
                        <i class="fas fa-truck-medical fa-3x"></i>
                    </div>
                    <h4 class="fw-bold" style="color:#003865;">Recibe tus medicamentos</h4>
                    <p class="text-muted">
                        Recoge sin complicaciones.
                    </p>
                </div>
            </div>
        </div>

    </div>
</section>


<!-- SECCIÓN: BENEFICIOS DE TAS -->
<section class="beneficios-section py-3"> <!--"beneficios-section py-3"-->
    <div class="container text-center">
        <h2 class="fw-bold mb-4" style="color:#003865;">Beneficios de usar TAS</h2>
        <p class="text-muted mb-5 fs-5">
            Disfruta de una experiencia rápida, segura y confiable al surtir tus medicamentos.
        </p>

        <div class="row g-4">
            <!-- Beneficio 1 -->
            <div class="col-md-3">
                <div class="beneficio-card p-4 h-100">
                    <div class="icon-wrapper mb-3">
                        <i class="fas fa-clock fa-3x"></i>
                    </div>
                    <h5 class="fw-bold" style="color:#003865;">Ahorro de tiempo</h5>
                    <p class="text-muted">
                        Evita filas y espera; tu pedido está listo cuando llegas.
                    </p>
                </div>
            </div>

            <!-- Beneficio 2 -->
            <div class="col-md-3">
                <div class="beneficio-card p-4 h-100">
                    <div class="icon-wrapper mb-3">
                        <i class="fas fa-map-marker-alt fa-3x"></i>
                    </div>
                    <h5 class="fw-bold" style="color:#003865;">Varias sucursales disponibles</h5>
                    <p class="text-muted">
                        Encuentra tu medicamento en farmacias cercanas.
                    </p>
                </div>
            </div>

            <!-- Beneficio 3 -->
            <div class="col-md-3">
                <div class="beneficio-card p-4 h-100">
                    <div class="icon-wrapper mb-3">
                        <i class="fas fa-mobile-alt fa-3x"></i>
                    </div>
                    <h5 class="fw-bold" style="color:#003865;">Mira el estado de tu pedido desde tu celular</h5>
                    <p class="text-muted">
                        Controla cada paso de tu pedido en tiempo real.
                    </p>
                </div>
            </div>

            <!-- Beneficio 4 -->
            <div class="col-md-3">
                <div class="beneficio-card p-4 h-100">
                    <div class="icon-wrapper mb-3">
                        <i class="fas fa-check-circle fa-3x"></i>
                    </div>
                    <h5 class="fw-bold" style="color:#003865;">Farmacias verificadas</h5>
                    <p class="text-muted">
                        Solo trabajamos con farmacias confiables y certificadas.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- SECCIÓN DE FARMACIAS COLABORADORAS --}}
<div class="container py-5" id="farmacias">
    @php
        $partnerLogos = [
            ['name' => 'Farmacias del Ahorro', 'file' => 'aho.png'],
            ['name' => 'Farmacias Guadalajara', 'file' => 'gdl.png'],
            ['name' => 'Farmacias Benavides', 'file' => 'bnv.png'],
            ['name' => 'Farmacias Similares', 'file' => 'sim.png'],
            ['name' => 'Farmacias Farmacon', 'file' => 'far.png'],
        ];
    @endphp

    <div class="row justify-content-center mt-0">
        <div class="col-lg-10">
            <div class="partner-carousel position-relative p-4 p-md-5">
                <div class="d-flex flex-column align-items-center gap-3 mb-4 text-center">
                    <div>
                        <p class="text-uppercase text-muted fw-semibold mb-1 fs-5">Farmacias colaboradoras</p>
                        <h2 class="h3 fw-bold mb-0" style="color: #003865;">Las marcas que confían en TAS</h2>
                    </div>
                </div>

                <div class="logo-strip position-relative overflow-hidden">
                    <div class="fade-edge start"></div>
                    <div class="fade-edge end"></div>
                    <div class="logo-track d-flex align-items-center">
                        @foreach (range(1, 2) as $loopIndex)
                            @foreach ($partnerLogos as $logo)
                                <div class="logo-card text-center">
                                    <div class="logo-wrapper d-flex align-items-center justify-content-center mb-2">
                                        <img src="{{ asset('images/farmacias/' . $logo['file']) }}" alt="Logo de {{ $logo['name'] }}"
                                            class="img-fluid" loading="lazy">
                                    </div>
                                    <p class="mb-0 fw-semibold text-muted">{{ $logo['name'] }}</p>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



{{-- Footer al final del contenido --}}
@include('layouts.footer') 

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/video-background.css') }}">
<link rel="stylesheet" href="{{ asset('css/comofuncionatas.css') }}">
<link rel="stylesheet" href="{{ asset('css/beneficiostas.css') }}">
<link rel="stylesheet" href="{{ asset('css/carrusel.css') }}">
<link rel="stylesheet" href="{{ asset('css/footer.css') }}">
@endpush

@push('scripts')
<script>
    // Smooth scroll para el indicador
    document.querySelector('.scroll-indicator')?.addEventListener('click', function() {
        document.querySelector('#farmacias').scrollIntoView({ 
            behavior: 'smooth' 
        });
    });
</script>
@endpush
