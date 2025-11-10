{{-- Blade View --}}
@extends('layouts.template')

@section('title', 'Subir Receta')

@section('content')
<link rel="stylesheet" href="{{ asset('css/receta-styles.css') }}">

<div class="container mt-4 pt-4">
    <x-stepper :currentStep="1" />

    <div id="map-section" class="d-flex justify-content-center">
        <x-mapa-farmacias />
    </div>

    <div id="form-section" class="d-none justify-content-center">
        <x-formulario-receta />
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/receta-script.js') }}"></script>
@endpush
@endsection