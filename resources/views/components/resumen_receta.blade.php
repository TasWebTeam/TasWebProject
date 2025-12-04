<div class="resumen-section">
    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i>
        Revise cuidadosamente la información antes de procesar la receta
    </div>

    <form id="form-procesar-receta" method="POST" action="{{ route('procesarReceta') }}">
        @csrf

        <input type="hidden" name="cedula_profesional" id="hidden-cedula">
        <input type="hidden" name="farmacia_sucursal_id" id="farmacia_sucursal_id">
        <input type="hidden" name="farmacia_cadena" id="hidden-farmacia-cadena">
        <input type="hidden" name="farmacia_sucursal" id="hidden-farmacia-sucursal">
        <input type="hidden" name="medicamentos" id="hidden-medicamentos">

        <!-- Información básica -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-user-md me-2"></i>Información del Médico
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-0">
                    <strong>Cédula Profesional:</strong>
                    <span class="ms-2" id="resumen-cedula"></span>
                </p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-hospital-user me-2"></i>Farmacia
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-0">
                    <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                    <span id="resumen-farmacia"></span>
                </p>
            </div>
        </div>

        <!-- Tabla de medicamentos con totales -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-pills me-2"></i>Medicamentos Recetados
                    <span class="badge bg-light text-dark ms-2" id="total-medicamentos-badge">0</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%">#</th>
                                <th style="width: 40%">Medicamento</th>
                                <th style="width: 10%" class="text-center">Cant.</th>
                                <th style="width: 20%">Laboratorio</th>
                                <th style="width: 12%" class="text-end">P. Unit.</th>
                                <th style="width: 13%" class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody id="resumen-medicamentos-tbody">
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    No hay medicamentos
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="5" class="text-end fw-bold">Subtotal Medicamentos:</td>
                                <td class="text-end fw-bold" id="resumen-total">$0.00</td>
                            </tr>
                            <tr class="table-success">
                                <td colspan="5" class="text-end fw-bold fs-5">
                                    <i class="fas fa-percentage me-2"></i>Comisión 15% (Total a Pagar):
                                </td>
                                <td class="text-end fw-bold fs-5 text-success" id="resumen-comision">$0.00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="alert alert-success mt-3 mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Nota:</strong> El total a pagar corresponde únicamente al 15% de comisión sobre el valor de los medicamentos.
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between gap-3 mt-4">
            <button type="button" class="btn btn-secondary btn-lg" id="btn-volver-editar">
                <i class="fas fa-arrow-left me-2"></i>Volver a editar
            </button>
            <button type="submit" class="btn btn-primary btn-lg" id="btn-procesar-receta">
                <i class="fas fa-check-circle me-2"></i>Procesar Receta
            </button>
        </div>
    </form>
</div>

<template id="template-fila-resumen">
    <tr class="fila-resumen-medicamento">
        <td class="text-center fw-bold numero-resumen"></td>
        <td class="nombre-resumen"></td>
        <td class="text-center cantidad-resumen"></td>
        <td class="laboratorio-resumen"></td>
        <td class="text-end precio-resumen"></td>
        <td class="text-end total-resumen"></td>
    </tr>
</template>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form-procesar-receta');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const cedula = document.getElementById('resumen-cedula').textContent.trim();

                const medicamentos = [];
                document.querySelectorAll('.fila-resumen-medicamento').forEach(fila => {
                    medicamentos.push({
                        nombre: fila.querySelector('.nombre-resumen').textContent.trim(),
                        cantidad: fila.querySelector('.cantidad-resumen').textContent.trim(),
                        laboratorio: fila.querySelector('.laboratorio-resumen').textContent.trim(),
                        precio_actual: fila.querySelector('.precio-resumen').textContent.trim().replace('$', ''),
                        total: fila.querySelector('.total-resumen').textContent.trim().replace('$', '')
                    });
                });

                document.getElementById('hidden-farmacia-cadena').value = localStorage.getItem("farmaciaCadena");
                document.getElementById('hidden-farmacia-sucursal').value = localStorage.getItem("farmaciaSucursal");
                document.getElementById('hidden-cedula').value = cedula;
                document.getElementById('hidden-medicamentos').value = JSON.stringify(medicamentos);

                this.submit();
            });
        });
    </script>
@endpush