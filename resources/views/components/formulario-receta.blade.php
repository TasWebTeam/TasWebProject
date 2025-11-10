<div class="card shadow-lg border-0 w-100 formulario-receta-card">
    <div class="card-header text-white text-center formulario-receta-header">
        <h4 class="mb-0">
            <i class="fas fa-file-medical me-2"></i>
            Subir o escribir receta médica
        </h4>
    </div>

    <div class="card-body p-4 p-md-5">
        <div class="farmacia-badge text-center">
            <i class="fas fa-hospital-user me-2"></i>
            <span class="text-muted">Farmacia seleccionada:</span>
            <div class="farmacia-nombre mt-2" id="farmacia-seleccionada"></div>
        </div>

        <form>
            @csrf
            
            <p class="section-title text-center">Selecciona cómo subir tu receta</p>

            <div class="d-flex flex-wrap justify-content-center gap-3 mb-4">
                <button type="button" class="option-button position-relative">
                    <input type="file" name="foto_receta" accept="image/*" capture="environment">
                    <i class="fas fa-camera fa-lg me-2"></i>
                    <span>Seleccionar foto</span>
                </button>

                <button type="button" class="option-button" onclick="toggleTextarea()">
                    <i class="fas fa-keyboard fa-lg me-2"></i>
                    <span>Escribir receta</span>
                </button>
            </div>

            <div id="textarea-container" class="mb-4 d-none">
                <label for="receta_texto" class="form-label fw-semibold text-secondary">
                    <i class="fas fa-pills me-2"></i>Escribe los medicamentos recetados:
                </label>
                <textarea name="receta_texto" id="receta_texto" rows="6"
                    class="form-control receta-textarea"
                    placeholder="Ejemplo: Paracetamol 500mg, una tableta cada 8 horas..."></textarea>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-paper-plane me-2"></i>Enviar receta
                </button>
            </div>
        </form>
    </div>

    <div class="card-footer text-center formulario-receta-footer">
        <span class="step-badge">
            <i class="fas fa-check-circle me-2"></i>Paso 2 de 3
        </span>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/formulario-receta-styles.css') }}">
@endpush