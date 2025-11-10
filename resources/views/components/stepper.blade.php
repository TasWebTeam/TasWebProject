@props(['currentStep' => 1])

<div class="stepper-container mb-4">
    <div class="stepper-wrapper">
        {{-- Paso 1 --}}
        <div class="stepper-item {{ $currentStep >= 1 ? 'active' : '' }} {{ $currentStep > 1 ? 'completed' : '' }}">
            <div class="step-counter">
                1
            </div>
            <div class="step-name">Seleccionar<br>Sucursal</div>
        </div>

        <div class="stepper-line {{ $currentStep > 1 ? 'completed' : '' }}"></div>

        {{-- Paso 2 --}}
        <div class="stepper-item {{ $currentStep >= 2 ? 'active' : '' }} {{ $currentStep > 2 ? 'completed' : '' }}">
            <div class="step-counter">
                2
            </div>
            <div class="step-name">Subir<br>Receta</div>
        </div>

        <div class="stepper-line {{ $currentStep > 2 ? 'completed' : '' }}"></div>

        <div class="stepper-item {{ $currentStep >= 3 ? 'active' : '' }}">
            <div class="step-counter">
                3
            </div>
            <div class="step-name">Confirmar<br>Pedido</div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/stepper-styles.css') }}">
@endpush