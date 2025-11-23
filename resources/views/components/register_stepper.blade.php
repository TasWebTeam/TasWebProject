@props(['currentStep' => 1])

<div class="stepper-container">
    <div class="stepper-wrapper">

        <div class="stepper-item {{ $currentStep == 1 ? 'active' : '' }}" data-step="1">
            <div class="step-counter">1</div>
            <div class="step-name">Datos<br>Personales</div>
        </div>

        <div class="stepper-line"></div>

        <div class="stepper-item {{ $currentStep == 2 ? 'active' : '' }}" data-step="2">
            <div class="step-counter">2</div>
            <div class="step-name">Método de<br>Pago</div>
        </div>

    </div>
</div>
