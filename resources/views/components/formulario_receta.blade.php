<div class="card shadow-lg border-0 w-100 formulario-receta-card">
    <div class="card-header text-white text-center formulario-receta-header">
        <h4 class="mb-0">
            <i class="fas fa-file-medical me-2"></i>
            Capturar receta médica
        </h4>
    </div>

    <div class="card-body p-4 p-md-5">
        <div class="farmacia-badge text-center">
            <i class="fas fa-hospital-user me-2"></i>
            <span class="text-muted">Farmacia seleccionada:</span>
            <div class="farmacia-nombre mt-2" id="farmacia-seleccionada"></div>
        </div>

        <x-formulario_escribir_receta />
    </div>

    <div class="card-footer formulario-receta-footer d-flex justify-content-center">
    <span class="step-badge">
        <i class="fas fa-check-circle me-2"></i>Paso 2 de 3
    </span>
</div>

</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/formulario-receta-styles.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/subir-receta-script.js') }}"></script>
@endpush
