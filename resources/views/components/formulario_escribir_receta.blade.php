{{-- Componente: resources/views/components/formulario_escribir_receta.blade.php --}}

<form id="form-receta-medica">
    <div class="cedula-section mb-4">
        <div class="row align-items-end">
            <div class="col-md-8 mb-3 mb-md-0">
                <label for="cedula-profesional" class="form-label fw-bold">
                    <i class="fas fa-id-card me-2"></i>Cédula Profesional
                </label>
                <input type="text" class="form-control form-control-lg" id="cedula-profesional"
                    name="cedula_profesional" placeholder="Ingrese su cédula profesional" maxlength="8"
                    inputmode="numeric" pattern="^[0-9]{7,8}$" title="La cédula debe contener 7 u 8 dígitos numéricos"
                    required>

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

        <h5 class="mb-4">
            <i class="fas fa-pills me-2"></i>Medicamentos a recetar
        </h5>

        <div class="medicamentos-form">
            <div class="row g-3">
                <div class="col-md-5">
                    <label for="nombre-medicamento" class="form-label">
                        <i class="fas fa-prescription-bottle me-2"></i>Nombre del medicamento
                    </label>
                    <input type="text" class="form-control" id="nombre-medicamento"
                        placeholder="Ej: Paracetamol 500mg">
                </div>
                <div class="col-md-2">
                    <label for="cantidad-medicamento" class="form-label">
                        <i class="fas fa-sort-numeric-up me-2"></i>Cantidad
                    </label>
                    <input type="number" class="form-control" id="cantidad-medicamento" placeholder="1" min="1"
                        value="1">
                </div>
                <div class="col-md-5">
                    <label for="indicaciones-medicamento" class="form-label">
                        <i class="fas fa-notes-medical me-2"></i>Indicaciones
                    </label>
                    <input type="text" class="form-control" id="indicaciones-medicamento"
                        placeholder="Ej: Cada 8 horas por 5 días">
                </div>
            </div>

            <div class="text-end mt-3">
                <button type="button" class="btn btn-primary" id="btn-agregar-medicamento">
                    <i class="fas fa-plus-circle me-2"></i>Agregar medicamento
                </button>
            </div>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-hover table-bordered" id="tabla-medicamentos">
                <thead class="table-primary">
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 35%">Medicamento</th>
                        <th style="width: 10%">Cantidad</th>
                        <th style="width: 40%">Indicaciones</th>
                        <th style="width: 10%">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbody-medicamentos">
                    <tr id="fila-vacia">
                        <td colspan="5" class="text-center text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay medicamentos agregados
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <input type="hidden" name="medicamentos" id="medicamentos-json">
    <input type="hidden" name="medications_count" id="medications_count" value="0">
</form>

<template id="template-fila-medicamento">
    <tr class="fila-medicamento">
        <td class="text-center fw-bold numero-fila"></td>
        <td class="nombre-medicamento"></td>
        <td class="text-center cantidad-medicamento"></td>
        <td class="indicaciones-medicamento"></td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm btn-eliminar-med">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
</template>
