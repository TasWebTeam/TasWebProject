<div id="textarea-container" class="mb-4">
    <div class="row g-3 mb-3">
        <div class="col-12 col-md-4">
            <label for="medication" class="form-label fw-bold">
                <i class="fas fa-pills me-2"></i>Medicamento
            </label>
            <input type="text" class="form-control receta-textarea" id="medication" placeholder="Escribe el medicamento">
        </div>

        <div class="col-12 col-md-4">
            <label for="quantity" class="form-label fw-bold">
                <i class="fas fa-hashtag me-2"></i>Número de Unidades
            </label>
            <input type="number" class="form-control receta-textarea" id="quantity" placeholder="0" min="1">
        </div>

        <div class="col-12 col-md-4">
            <label for="inStock" class="form-label fw-bold">
                <i class="fas fa-box me-2"></i>En existencia
            </label>
            <input type="text" class="form-control receta-textarea bg-light" id="inStock" value="0" readonly>
        </div>
    </div>

    <div class="d-flex justify-content-center mb-3">
        <button type="button" class="btn btn-success px-4 py-2" id="btnAgregar">
            <i class="fas fa-plus-circle me-2"></i>Agregar
        </button>
    </div>

    <div class="card bg-light border-secondary mb-3">
        <div class="card-body">
            <h5 class="card-title">
                <i class="fas fa-clipboard-list me-2"></i>Descripción de la Receta
            </h5>
            <table class="table table-bordered table-hover">
                <thead class="table-secondary">
                    <tr>
                        <th>#</th>
                        <th>Medicamento</th>
                        <th>Unidades</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="prescriptionDescription">
                    <tr>
                        <td colspan="4" class="text-center text-muted">No hay medicamentos agregados</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <input type="hidden" name="medications_count" id="medications_count" value="0">
</div>