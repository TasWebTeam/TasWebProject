document.addEventListener("DOMContentLoaded", function () {
    const cedulaInput = document.getElementById("cedula-profesional");
    const btnGuardarCedula = document.getElementById("btn-guardar-cedula");
    const btnModificarCedula = document.getElementById("btn-modificar-cedula");
    const seccionMedicamentos = document.getElementById("seccion-medicamentos");

    const nombreMedInput = document.getElementById("nombre_medicamento");
    const cantidadMedInput = document.getElementById("cantidad-medicamento");
    const tbodyMedicamentos = document.getElementById("tbody-medicamentos");
    const filaVacia = document.getElementById("fila-vacia");
    const medicamentosJson = document.getElementById("medicamentos-json");
    const medicationsCount = document.getElementById("medications_count");
    const templateFilaMedicamento = document.getElementById(
        "template-fila-medicamento"
    );

    const searchPopup = document.getElementById("search-popup");
    const searchResults = document.getElementById("search-results");
    const closePopup = document.getElementById("close-popup");

    const btnGenerarReceta = document.getElementById("btn-generar-receta");
    const seccionResumen = document.getElementById("seccion-resumen");

    const configReceta = document.getElementById("config-receta");
    const seleccionarMedicamentoUrl = configReceta
        ? configReceta.dataset.urlSeleccionar
        : null;

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    let medicamentos = [];
    let contadorMedicamentos = 0;
    let searchTimeout;

    cargarInformacionFarmacia();

    btnGuardarCedula.addEventListener("click", guardarCedula);
    btnModificarCedula.addEventListener("click", modificarCedula);

    if (btnGenerarReceta) {
        btnGenerarReceta.addEventListener("click", mostrarResumen);
    }

    nombreMedInput.addEventListener("input", function (e) {
        clearTimeout(searchTimeout);
        const query = e.target.value.trim();

        if (query.length >= 3) {
            searchTimeout = setTimeout(() => {
                buscarMedicamentos(query);
                searchPopup.classList.add("active");
            }, 300);
        } else {
            searchPopup.classList.remove("active");
        }
    });

    closePopup.addEventListener("click", () => {
        searchPopup.classList.remove("active");
    });

    searchPopup.addEventListener("click", (e) => {
        if (e.target === searchPopup) {
            searchPopup.classList.remove("active");
        }
    });

    async function buscarMedicamentos(query) {
        try {
            const response = await fetch(
                `/medicamentos/buscar?nombre_medicamento=${encodeURIComponent(
                    query
                )}`
            );

            if (!response.ok) {
                throw new Error("Error en la búsqueda");
            }

            const resultados = await response.json();
            mostrarResultados(resultados);
        } catch (error) {
            searchResults.innerHTML = `
                <div class="no-results">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                    <p>Error al realizar la búsqueda</p>
                </div>
            `;
        }
    }

    function mostrarResultados(resultados) {
        if (resultados.length === 0) {
            searchResults.innerHTML = `
                <div class="no-results">
                    <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                    <p>No se encontraron medicamentos</p>
                </div>
            `;
            return;
        }

        searchResults.innerHTML = resultados
            .map(
                (med) => `
            <div class="medicamento-item" 
                 data-id="${med.id_medicamento}" 
                 data-nombre="${med.nombre}" 
                 data-laboratorio="${med.laboratorio || "Sin especificar"}">
                <img src="${
                    med.imagen?.url || "/images/medicamentos/default.png"
                }" 
                     alt="${med.nombre}" 
                     class="medicamento-imagen"
                     onerror="this.src='/images/medicamentos/default.png'">
                <div class="medicamento-info">
                    <div class="medicamento_nombre">${med.nombre}</div>
                    <div class="medicamento-especificacion">${
                        med.especificacion || ""
                    }</div>
                    <div class="medicamento-laboratorio">
                        <i class="fas fa-building me-1"></i>${
                            med.laboratorio || "Sin especificar"
                        }
                    </div>
                    ${
                        med.es_controlado
                            ? '<span class="medicamento-controlado"><i class="fas fa-exclamation-triangle me-1"></i>CONTROLADO</span>'
                            : ""
                    }
                </div>
            </div>
        `
            )
            .join("");

        document.querySelectorAll(".medicamento-item").forEach((item) => {
            item.addEventListener("click", function () {
                seleccionarMedicamento(
                    this.dataset.id,
                    this.dataset.nombre,
                    this.dataset.laboratorio
                );
            });
        });
    }

    function seleccionarMedicamento(id, nombre, laboratorio) {
        const cantidad = parseInt(cantidadMedInput.value) || 1;

        const medicamentoExistente = medicamentos.find(
            (med) =>
                med.id_medicamento === id && med.laboratorio === laboratorio
        );

        let medicamento;

        if (medicamentoExistente) {
            medicamentoExistente.cantidad += cantidad;
            medicamento = medicamentoExistente;
        } else {
            contadorMedicamentos++;

            const medicamento = {
                id: contadorMedicamentos,
                id_medicamento: id,
                nombre: nombre,
                laboratorio: laboratorio,
                cantidad: cantidad,
            };
            medicamentos.push(medicamento);
        }
        notificarMedicamentoSeleccionado(medicamento);

        actualizarTablaMedicamentos();
        limpiarFormularioMedicamento();
        actualizarJsonMedicamentos();
        actualizarContadorMedicamentos();
        actualizarBotonEnviar();

        searchPopup.classList.remove("active");
    }

    async function notificarMedicamentoSeleccionado(medicamento) {
        if (!seleccionarMedicamentoUrl) {
            return;
        }

        try {
            const response = await fetch(seleccionarMedicamentoUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    Accept: "application/json",
                },
                body: JSON.stringify({
                    id_medicamento: medicamento.id_medicamento,
                    cantidad: medicamento.cantidad,
                    laboratorio: medicamento.laboratorio,
                }),
            });

            if (!response.ok) {
                throw new Error("Error en la petición al servidor");
            }

            const data = await response.json();
        } catch (error) {
            return;
        }
    }

    function guardarCedula() {
        const cedula = cedulaInput.value.trim();

        if (cedula === "" || !cedulaInput.checkValidity()) {
            cedulaInput.classList.add("is-invalid");
            return;
        }

        cedulaInput.classList.remove("is-invalid");
        cedulaInput.readOnly = true;
        cedulaInput.classList.add("bg-light");

        btnGuardarCedula.classList.add("d-none");
        btnModificarCedula.classList.remove("d-none");

        seccionMedicamentos.classList.remove("d-none");

        setTimeout(() => {
            nombreMedInput.focus();
        }, 300);
    }

    function modificarCedula() {
        cedulaInput.readOnly = false;
        cedulaInput.classList.remove("bg-light");
        cedulaInput.focus();

        btnModificarCedula.classList.add("d-none");
        btnGuardarCedula.classList.remove("d-none");

        seccionMedicamentos.classList.add("d-none");
    }

    function eliminarMedicamento(id) {
        if (confirm("¿Está seguro de eliminar este medicamento?")) {
            medicamentos = medicamentos.filter((med) => med.id !== id);
            actualizarTablaMedicamentos();
            actualizarJsonMedicamentos();
            actualizarContadorMedicamentos();
            actualizarBotonEnviar();
        }
    }

    function actualizarTablaMedicamentos() {
        if (medicamentos.length === 0) {
            filaVacia.classList.remove("d-none");
            const filas =
                tbodyMedicamentos.querySelectorAll(".fila-medicamento");
            filas.forEach((fila) => fila.remove());
            return;
        }

        filaVacia.classList.add("d-none");

        const filasExistentes =
            tbodyMedicamentos.querySelectorAll(".fila-medicamento");
        filasExistentes.forEach((fila) => fila.remove());

        const medicamentosOrdenados = [...medicamentos].sort((a, b) => {
            return a.nombre.localeCompare(b.nombre, "es", {
                sensitivity: "base",
            });
        });

        medicamentosOrdenados.forEach((med, index) => {
            const fila = crearFilaMedicamento(med, index + 1);
            tbodyMedicamentos.appendChild(fila);
        });
    }

    function crearFilaMedicamento(medicamento, numero) {
        const template = templateFilaMedicamento.content.cloneNode(true);
        const fila = template.querySelector(".fila-medicamento");

        fila.querySelector(".numero-fila").textContent = numero;
        fila.querySelector(".nombre_medicamento").textContent =
            medicamento.nombre;
        fila.querySelector(".cantidad-medicamento").textContent =
            medicamento.cantidad;
        fila.querySelector(".laboratorio-medicamento").textContent =
            medicamento.laboratorio;

        const btnEliminar = fila.querySelector(".btn-eliminar-med");
        btnEliminar.addEventListener("click", function () {
            eliminarMedicamento(medicamento.id);
        });

        return fila;
    }

    function limpiarFormularioMedicamento() {
        nombreMedInput.value = "";
        cantidadMedInput.value = "1";
        nombreMedInput.focus();
    }

    function actualizarJsonMedicamentos() {
        medicamentosJson.value = JSON.stringify(medicamentos);
    }

    function actualizarContadorMedicamentos() {
        medicationsCount.value = medicamentos.length;
    }

    function actualizarBotonEnviar() {
        if (btnGenerarReceta) {
            btnGenerarReceta.disabled = medicamentos.length === 0;
        }
    }

    function mostrarResumen() {
        const cedula = cedulaInput.value.trim();
        const cadena = localStorage.getItem("farmaciaCadena");
        const sucursal = localStorage.getItem("farmaciaSucursal");
        const farmacia = `${cadena} — Sucursal ${sucursal}`;

        btnGenerarReceta.classList.add("processing");
        btnGenerarReceta.disabled = true;

        setTimeout(() => {
            document.querySelector(".cedula-section").classList.add("d-none");
            seccionMedicamentos.classList.add("d-none");

            const farmaciaBadge = document.querySelector(".farmacia-badge");
            if (farmaciaBadge) {
                farmaciaBadge.classList.add("d-none");
            }

            seccionResumen.classList.remove("d-none");

            llenarResumen(cedula, farmacia);

            actualizarStepper(3);

            document
                .getElementById("btn-volver-editar")
                .addEventListener("click", volverAEditar);
            document
                .getElementById("btn-procesar-receta")
                .addEventListener("click", procesarReceta);

            btnGenerarReceta.classList.remove("processing");
        }, 500);
    }

    function llenarResumen(cedula, farmacia) {
        document.getElementById("resumen-cedula").textContent = cedula;

        document.getElementById("resumen-farmacia").textContent = farmacia;

        document.getElementById("total-medicamentos-badge").textContent =
            medicamentos.length;

        const tbodyResumen = document.getElementById(
            "resumen-medicamentos-tbody"
        );
        const templateFilaResumen = document.getElementById(
            "template-fila-resumen"
        );

        tbodyResumen.innerHTML = "";

        const medicamentosOrdenados = [...medicamentos].sort((a, b) => {
            return a.nombre.localeCompare(b.nombre, "es", {
                sensitivity: "base",
            });
        });

        medicamentosOrdenados.forEach((med, index) => {
            const template = templateFilaResumen.content.cloneNode(true);
            const fila = template.querySelector(".fila-resumen-medicamento");

            fila.querySelector(".numero-resumen").textContent = index + 1;
            fila.querySelector(".nombre-resumen").textContent = med.nombre;
            fila.querySelector(".cantidad-resumen").textContent = med.cantidad;
            fila.querySelector(".laboratorio-resumen").textContent =
                med.laboratorio;

            tbodyResumen.appendChild(fila);
        });
    }

    function volverAEditar() {
        seccionResumen.classList.add("d-none");
        document.querySelector(".cedula-section").classList.remove("d-none");
        seccionMedicamentos.classList.remove("d-none");

        const farmaciaBadge = document.querySelector(".farmacia-badge");
        if (farmaciaBadge) {
            farmaciaBadge.classList.remove("d-none");
        }

        btnGenerarReceta.disabled = false;

        actualizarStepper(2);
    }

    function procesarReceta() {
        document.getElementById("farmacia_cadena").value =
            localStorage.getItem("farmaciaCadena");
        document.getElementById("farmacia_sucursal").value =
            localStorage.getItem("farmaciaSucursal");

        Swal.fire({
            title: "Procesando receta...",
            text: "Por favor espere",
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });

        document.getElementById("form-receta").submit();
    }

    async function enviarRecetaAlServidor() {
        const cedula = cedulaInput.value.trim();
        const cadena = localStorage.getItem("farmaciaCadena");
        const sucursal = localStorage.getItem("farmaciaSucursal");

        Swal.fire({
            title: "Procesando receta...",
            text: "Por favor espere",
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });

        try {
            const response = await fetch("/procesarReceta", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    Accept: "application/json",
                },
                body: JSON.stringify({
                    cedula_profesional: cedula,
                    farmacia_cadena: cadena,
                    farmacia_sucursal: sucursal,
                    medicamentos: medicamentos,
                }),
            });

            const contentType = response.headers.get("content-type");

            if (!contentType || !contentType.includes("application/json")) {
                const text = await response.text();
                console.error("Respuesta no JSON:", text);
                throw new Error(
                    "El servidor no devolvió una respuesta JSON válida"
                );
            }

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || "Error al procesar la receta");
            }

            Swal.fire({
                title: "¡Éxito!",
                text: data.message || "Receta procesada correctamente",
                icon: "success",
                confirmButtonColor: "#005B96",
            }).then(() => {});
        } catch (error) {
            console.error("Error completo:", error);

            Swal.fire({
                title: "Error",
                text: error.message || "Ocurrió un error al procesar la receta",
                icon: "error",
                confirmButtonColor: "#005B96",
            });
        }
    }

    function actualizarStepper(paso) {
        const stepperContainer = document.querySelector(".stepper-container");
        if (!stepperContainer) return;

        const steps = [
            { numero: 1, nombre: "Seleccionar<br>Sucursal" },
            { numero: 2, nombre: "Subir<br>Receta" },
            { numero: 3, nombre: "Confirmar<br>Pedido" },
        ];

        let html = '<div class="stepper-wrapper">';

        steps.forEach((step, index) => {
            const estado =
                step.numero < paso
                    ? "completed"
                    : step.numero === paso
                    ? "active"
                    : "";

            html += `
            <div class="stepper-item ${estado}">
                <div class="step-counter">${step.numero}</div>
                <div class="step-name">${step.nombre}</div>
            </div>
        `;

            if (index < steps.length - 1) {
                const lineaEstado = step.numero < paso ? "completed" : "";
                html += `<div class="stepper-line ${lineaEstado}"></div>`;
            }
        });

        html += "</div>";
        stepperContainer.innerHTML = html;

        const footerBadges = document.querySelectorAll(
            ".formulario-receta-footer .step-badge"
        );
        footerBadges.forEach((footerBadge) => {
            footerBadge.classList.remove("d-none");
            footerBadge.innerHTML = `<i class="fas fa-check-circle me-2"></i>Paso ${paso} de 3`;
        });
    }
    function cargarInformacionFarmacia() {
        const cadena = localStorage.getItem("farmaciaCadena");
        const sucursal = localStorage.getItem("farmaciaSucursal");

        const elementoFarmacia = document.getElementById(
            "farmacia-seleccionada"
        );

        if (elementoFarmacia && cadena && sucursal) {
            elementoFarmacia.textContent = `${cadena} — Sucursal ${sucursal}`;
        }
    }

    actualizarBotonEnviar();
});