document.addEventListener("DOMContentLoaded", () => {
    const cadena = localStorage.getItem("farmaciaCadena");
    const sucursal = localStorage.getItem("farmaciaSucursal");
    
    const elementoFarmacia = document.getElementById("farmacia-seleccionada");
    
    if (cadena && sucursal) {
        elementoFarmacia.textContent = `${cadena} — Sucursal ${sucursal}`;
    }
});

function agregarMedicamento() {
    const medication = document.getElementById('medication').value.trim();
    const quantity = document.getElementById('quantity').value.trim();
    
    if (!medication || !quantity || quantity <= 0) {
        Swal.fire({
            title: 'Campos incompletos',
            text: 'Por favor, completa todos los campos correctamente',
            icon: 'warning',
            confirmButtonColor: '#005B96'
        });
        return;
    }
    
    const tbody = document.getElementById('prescriptionDescription');
    const count = parseInt(document.getElementById('medications_count').value) + 1;
    
    if (count === 1) {
        tbody.innerHTML = '';
    }
    
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${count}</td>
        <td>${medication}</td>
        <td>${quantity}</td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm" onclick="eliminarMedicamento(this)">
                Eliminar
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    
    document.getElementById('medications_count').value = count;
    
    actualizarBotonEnviar();
    
    document.getElementById('medication').value = '';
    document.getElementById('quantity').value = '';
    document.getElementById('inStock').value = '0';
    
    row.style.animation = 'slideDown 0.3s ease-out';
}

function eliminarMedicamento(button) {
    const row = button.closest('tr');
    const tbody = document.getElementById('prescriptionDescription');
    
    row.style.animation = 'fadeOut 0.3s ease-out';
    
    setTimeout(() => {
        row.remove();
        
        const rows = tbody.querySelectorAll('tr');
        rows.forEach((r, index) => {
            const firstCell = r.querySelector('td:first-child');
            if (firstCell) {
                firstCell.textContent = index + 1;
            }
        });
        
        document.getElementById('medications_count').value = rows.length;
        
        actualizarBotonEnviar();
        
        if (rows.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No hay medicamentos agregados</td></tr>';
        }
    }, 300);
}

function actualizarBotonEnviar() {
    const btnEnviar = document.querySelector('.btn-submit');
    const count = parseInt(document.getElementById('medications_count').value);
    
    if (btnEnviar) {
        if (count === 0) {
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

document.addEventListener('DOMContentLoaded', function() {
    const btnAgregar = document.getElementById('btnAgregar');
    
    if (btnAgregar) {
        btnAgregar.addEventListener('click', agregarMedicamento);
    }
    
    actualizarBotonEnviar();
});