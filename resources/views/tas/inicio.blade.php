@extends('layouts.template')

@section('title', 'Inicio')

@section('content')
    <div class="container py-5 vh-100 d-flex justify-content-center">
        <div class="row justify-content-center w-100">
            <div class="col-lg-8 text-center">
                <div class="mb-4">
                    <img src="/images/logo.png" alt="TAS Logo" class="img-fluid" style="max-height: 200px;">
                </div>
                <h1 class="display-4 fw-bold mb-3" style="color: #003865;">
                    Todos tus medicamentos, en un solo lugar
                </h1>
                <p class="lead text-muted mb-4 fs-4">
                    Te Acerco Salud (TAS) es una plataforma tecnológica completa que une a pacientes con farmacias de forma
                    eficiente, asegurando que sus recetas médicas estén siempre disponibles. Usando nuestra aplicación en
                    línea y móvil, los usuarios pueden elegir las sucursales, enviar sus recetas y obtener sus medicamentos
                    sin complicaciones, ahorrando tiempo y dinero y garantizando un acceso rápido a los tratamientos. TAS
                    facilita la comunicación entre farmacias y ofrece una experiencia de salud más sencilla, confiable y
                    accesible para todos los pacientes.
                </p>
            </div>
        </div>
    </div>
@endsection
