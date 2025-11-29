
@extends('layouts.template')

@section('title', 'Subir Receta')

@section('content')
<link rel="stylesheet" href="{{ asset('css/receta-styles.css') }}">

{{-- Wrapper que controla altura total de la página en esta vista --}}
<div class="page-subir-receta d-flex flex-column">

    {{-- Contenido principal (crece y empuja el footer hacia abajo) --}}
    <div class="flex-grow-1">
        <div class="container mt-4 pt-4">
            <x-stepper :currentStep="1" />

            <div id="map-section" class="d-flex justify-content-center">
                <x-mapa_farmacias :sucursales="$sucursales" />
            </div>

            <div id="form-section" class="d-none justify-content-center">
                <x-formulario_receta />
            </div>
        </div>
    </div>

    {{-- Footer al final del wrapper --}}
    @include('layouts.footer')

</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/footer.css') }}">
@endpush

