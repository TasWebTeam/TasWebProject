<div class="cedula-section mb-4">
    <div class="row align-items-end">
        <div class="col-md-8 mb-3 mb-md-0">
            <label for="cedula-profesional" class="form-label fw-bold">
                <i class="fas fa-id-card me-2"></i>Cédula Profesional
            </label>
            <input type="text" class="form-control form-control-lg" id="cedula-profesional" name="cedula_profesional"
                placeholder="Ingrese su cédula profesional" maxlength="8" inputmode="numeric" pattern="^[0-9]{7,8}$"
                title="La cédula debe contener 7 u 8 dígitos numéricos" required>
            <div class="invalid-feedback">
                Por favor ingrese su cédula profesional
            </div>
        </div>
        <div class="col-md-4">
            <button type="button" class="btn btn-success w-100" id="btn-guardar-cedula">
                <i class="fas fa-save me-2"></i>Guardar
            </button>
            <button type="button" class="btn btn-warning w-100 d-none" id="btn-modificar-cedula">
                <i class="fas fa-edit me-2"></i>Modificar
            </button>
        </div>
    </div>
</div>

<div id="seccion-medicamentos" class="d-none">
    <hr class="my-4">

    <h5 class="section-title">
        <i class="fas fa-pills me-2"></i>Medicamentos a recetar
    </h5>

    <div class="medicamentos-form">
        <div class="row g-3 align-items-end">
            <div class="col-md-7">
                <label for="nombre_medicamento" class="form-label">
                    <i class="fas fa-prescription-bottle me-2"></i>Buscar medicamento
                </label>
                <input name="nombre_medicamento" type="text" class="form-control" id="nombre_medicamento"
                    placeholder="Escriba el nombre del medicamento..." autocomplete="off">
            </div>
            <div class="col-md-2">
                <label for="cantidad-medicamento" class="form-label">
                    <i class="fas fa-sort-numeric-up me-2"></i>Cantidad
                </label>
                <input type="number" class="form-control" id="cantidad-medicamento" placeholder="1" min="1"
                    value="1" max="99">
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-primary w-100" id="btn-anadir-medicamento" disabled>
                    <i class="fas fa-plus me-2"></i>Añadir
                </button>
            </div>
        </div>
    </div>

    <div class="table-responsive mt-4">
        <table class="table table-hover table-bordered" id="tabla-medicamentos">
            <thead class="table-primary">
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 30%">Medicamento</th>
                    <th style="width: 10%" class="text-center">Cantidad</th>
                    <th style="width: 20%">Laboratorio</th>
                    <th style="width: 12%" class="text-end">Precio Unit.</th>
                    <th style="width: 13%" class="text-end">Total</th>
                    <th style="width: 10%" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody id="tbody-medicamentos">
                <tr id="fila-vacia">
                    <td colspan="7" class="text-center text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        No hay medicamentos agregados
                    </td>
                </tr>
            </tbody>
           <tfoot class="table-light">
                <tr>
                    <td colspan="5" class="text-end fw-bold">Total Medicamentos:</td>
                    <td class="fw-bold text-end" id="subtotal-medicamentos">$0.00</td>
                    <td></td>
                </tr>
                <tr class="table-success">
                    <td colspan="5" class="text-end fw-bold fs-5">
                       Comisión 15% (Total a Pagar):
                    </td>
                    <td class="fw-bold fs-5 text-success text-end" id="total-medicamentos">$0.00</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="alert alert-info mt-3">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Nota:</strong> El total a cobrar corresponde únicamente al 15% de comisión sobre el valor total de los medicamentos.
    </div>

    <div class="text-center mt-4">
        <button type="button" class="btn btn-submit" id="btn-generar-receta" disabled>
            <i class="fas fa-circle-notch me-2"></i>Generar Receta
        </button>
    </div>
</div>

<div id="seccion-resumen" class="d-none">
    <x-resumen_receta />
</div>

<input type="hidden" name="medicamentos" id="medicamentos-json">
<input type="hidden" name="medications_count" id="medications_count" value="0">
<input type="hidden" name="farmacia_cadena" id="farmacia_cadena">
<input type="hidden" name="farmacia_sucursal_id" id="farmacia_sucursal_id">
<input type="hidden" name="farmacia_sucursal" id="farmacia_sucursal">

<div class="search-popup" id="search-popup">
    <div class="search-popup-content">
        <div class="search-header">
            <h5 class="mb-0">
                <i class="fas fa-search me-2"></i>Resultados de búsqueda
            </h5>
            <button type="button" class="close-popup" id="close-popup">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="search-results" id="search-results">
            <div class="no-results">
                <i class="fas fa-search fa-3x mb-3"></i>
                <p>Escribe para buscar medicamentos</p>
            </div>
        </div>
    </div>
</div>

<template id="template-fila-medicamento">
    <tr class="fila-medicamento">
        <td class="text-center fw-bold numero-fila"></td>
        <td class="nombre_medicamento"></td>
        <td class="text-center cantidad-medicamento"></td>
        <td class="laboratorio-medicamento"></td>
        <td class="text-end precio-unitario-medicamento"></td>
        <td class="text-end total-medicamento"></td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm btn-eliminar-med">
                <i class="fas fa-trash me-1"></i>Eliminar
            </button>
        </td>
    </tr>
</template>