document.addEventListener('DOMContentLoaded', function() {
    const cedulaInput = document.getElementById('cedula-profesional');
    const btnGuardarCedula = document.getElementById('btn-guardar-cedula');
    const btnModificarCedula = document.getElementById('btn-modificar-cedula');
    const seccionMedicamentos = document.getElementById('seccion-medicamentos');
    
    const nombreMedInput = document.getElementById('nombre-medicamento');
    const cantidadMedInput = document.getElementById('cantidad-medicamento');
    const indicacionesMedInput = document.getElementById('indicaciones-medicamento');
    const btnAgregarMed = document.getElementById('btn-agregar-medicamento');
    const tbodyMedicamentos = document.getElementById('tbody-medicamentos');
    const filaVacia = document.getElementById('fila-vacia');
    const medicamentosJson = document.getElementById('medicamentos-json');
    const medicationsCount = document.getElementById('medications_count');
    const templateFilaMedicamento = document.getElementById('template-fila-medicamento');
    
    let medicamentos = [];
    let contadorMedicamentos = 0;

    cargarInformacionFarmacia();

    btnGuardarCedula.addEventListener('click', guardarCedula);
    btnModificarCedula.addEventListener('click', modificarCedula);

    btnAgregarMed.addEventListener('click', agregarMedicamento);
    
    [nombreMedInput, cantidadMedInput, indicacionesMedInput].forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                agregarMedicamento();
            }
        });
    });

    function guardarCedula() {
        const cedula = cedulaInput.value.trim();
        
        if (cedula === '') {
            cedulaInput.classList.add('is-invalid');
            mostrarNotificacion('Por favor ingrese su cédula profesional', 'warning');
            return;
        }
        
        cedulaInput.classList.remove('is-invalid');
        cedulaInput.readOnly = true;
        cedulaInput.classList.add('bg-light');
        
        btnGuardarCedula.classList.add('d-none');
        btnModificarCedula.classList.remove('d-none');
        
        seccionMedicamentos.classList.remove('d-none');
        
        mostrarNotificacion('Cédula guardada correctamente', 'success');
    }

    function modificarCedula() {
        cedulaInput.readOnly = false;
        cedulaInput.classList.remove('bg-light');
        cedulaInput.focus();
        
        btnModificarCedula.classList.add('d-none');
        btnGuardarCedula.classList.remove('d-none');
        
        mostrarNotificacion('Puede modificar la cédula profesional', 'info');
    }

    function agregarMedicamento() {
        const nombre = nombreMedInput.value.trim();
        const cantidad = parseInt(cantidadMedInput.value);
        const indicaciones = indicacionesMedInput.value.trim();
        
        if (nombre === '') {
            nombreMedInput.focus();
            mostrarNotificacion('Ingrese el nombre del medicamento', 'warning');
            return;
        }
        
        if (!cantidad || cantidad < 1) {
            cantidadMedInput.focus();
            mostrarNotificacion('Ingrese una cantidad válida', 'warning');
            return;
        }
        
        contadorMedicamentos++;
        
        const medicamento = {
            id: contadorMedicamentos,
            nombre: nombre,
            cantidad: cantidad,
            indicaciones: indicaciones || 'Sin indicaciones específicas'
        };
        
        medicamentos.push(medicamento);
        
        actualizarTablaMedicamentos();
        limpiarFormularioMedicamento();
        actualizarJsonMedicamentos();
        actualizarContadorMedicamentos();
        actualizarBotonEnviar();
        
        mostrarNotificacion('Medicamento agregado correctamente', 'success');
    }

    function eliminarMedicamento(id, fila) {
        if (confirm('¿Está seguro de eliminar este medicamento?')) {
            fila.style.animation = 'fadeOut 0.3s ease-out';
            
            setTimeout(() => {
                medicamentos = medicamentos.filter(med => med.id !== id);
                actualizarTablaMedicamentos();
                actualizarJsonMedicamentos();
                actualizarContadorMedicamentos();
                actualizarBotonEnviar();
                mostrarNotificacion('Medicamento eliminado', 'info');
            }, 300);
        }
    }

    function actualizarTablaMedicamentos() {
        if (medicamentos.length === 0) {
            filaVacia.classList.remove('d-none');
            const filas = tbodyMedicamentos.querySelectorAll('.fila-medicamento');
            filas.forEach(fila => fila.remove());
            return;
        }
        
        filaVacia.classList.add('d-none');
        
        const filasExistentes = tbodyMedicamentos.querySelectorAll('.fila-medicamento');
        filasExistentes.forEach(fila => fila.remove());
        
        medicamentos.forEach((med, index) => {
            const fila = crearFilaMedicamento(med, index + 1);
            tbodyMedicamentos.appendChild(fila);
        });
    }

    function crearFilaMedicamento(medicamento, numero) {
        const template = templateFilaMedicamento.content.cloneNode(true);
        const fila = template.querySelector('.fila-medicamento');
        
        fila.querySelector('.numero-fila').textContent = numero;
        fila.querySelector('.nombre-medicamento').textContent = medicamento.nombre;
        fila.querySelector('.cantidad-medicamento').textContent = medicamento.cantidad;
        fila.querySelector('.indicaciones-medicamento').textContent = medicamento.indicaciones;
        
        const btnEliminar = fila.querySelector('.btn-eliminar-med');
        btnEliminar.addEventListener('click', function() {
            eliminarMedicamento(medicamento.id, fila);
        });
        
        return fila;
    }

    function limpiarFormularioMedicamento() {
        nombreMedInput.value = '';
        cantidadMedInput.value = '1';
        indicacionesMedInput.value = '';
        nombreMedInput.focus();
    }

    function actualizarJsonMedicamentos() {
        medicamentosJson.value = JSON.stringify(medicamentos);
    }

    function actualizarContadorMedicamentos() {
        medicationsCount.value = medicamentos.length;
    }

    function actualizarBotonEnviar() {
        const btnEnviar = document.querySelector('.btn-submit');
        
        if (btnEnviar) {
            if (medicamentos.length === 0) {
                btnEnviar.disabled = true;
                btnEnviar.classList.add('disabled');
                btnEnviar.style.opacity = '0.5';
                btnEnviar.style.cursor = 'not-allowed';
            } else {
                btnEnviar.disabled = false;
                btnEnviar.classList.remove('disabled');
                btnEnviar.style.opacity = '1';
                btnEnviar.style.cursor = 'pointer';
            }
        }
    }

    function cargarInformacionFarmacia() {
        const cadena = localStorage.getItem("farmaciaCadena");
        const sucursal = localStorage.getItem("farmaciaSucursal");
        
        const elementoFarmacia = document.getElementById("farmacia-seleccionada");
        
        if (elementoFarmacia && cadena && sucursal) {
            elementoFarmacia.textContent = `${cadena} — Sucursal ${sucursal}`;
        }
    }

    function mostrarNotificacion(mensaje, tipo) {
        if (typeof Swal !== 'undefined') {
            const iconos = {
                'success': 'success',
                'error': 'error',
                'warning': 'warning',
                'info': 'info'
            };
            
            Swal.fire({
                title: mensaje,
                icon: iconos[tipo] || 'info',
                confirmButtonColor: '#005B96',
                timer: 3000,
                timerProgressBar: true
            });
        } else {
            console.log(`[${tipo.toUpperCase()}] ${mensaje}`);
        }
    }

    actualizarBotonEnviar();
});