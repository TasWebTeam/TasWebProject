function seleccionarFarmacia(nombre) {
    Swal.fire({
        title: '¿Confirmar selección?',
        text: `Has elegido: ${nombre}`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#005B96',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('farmacia-seleccionada').textContent = nombre;

            document.getElementById('map-section').classList.add('d-none');
            document.getElementById('form-section').classList.remove('d-none');

            document.querySelector('.stepper-container').innerHTML = `
                <div class='stepper-wrapper'>
                    <div class='stepper-item completed'>
                        <div class='step-counter'>1</div>
                        <div class='step-name'>Seleccionar<br>Sucursal</div>
                    </div>
                    <div class='stepper-line completed'></div>
                    <div class='stepper-item active'>
                        <div class='step-counter'>2</div>
                        <div class='step-name'>Subir<br>Receta</div>
                    </div>
                    <div class='stepper-line'></div>
                    <div class='stepper-item'>
                        <div class='step-counter'>3</div>
                        <div class='step-name'>Confirmar<br>Pedido</div>
                    </div>
                </div>
            `;
        }
    });
}

function toggleTextarea() {
    const container = document.getElementById('textarea-container');
    container.classList.toggle('d-none');
}