document.addEventListener("DOMContentLoaded", () => {
    // Inicializa el mapa centrado en Culiacán
    const map = L.map('map').setView([24.8091, -107.3940], 13);

    // Capa base de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Datos de farmacias
    const farmacias = [
        { nombre: "Farmacia Central", lat: 24.8091, lng: -107.3940 },
        { nombre: "Farmacia Norte", lat: 24.8150, lng: -107.3850 },
        { nombre: "Farmacia Sur", lat: 24.8020, lng: -107.4000 },
    ];

    // Agregar marcadores
    farmacias.forEach(f => {
        const marker = L.marker([f.lat, f.lng]).addTo(map);
        marker.bindPopup(`
            <div style="text-align:center;">
                <strong>${f.nombre}</strong><br>
                <button class='btn btn-sm btn-tas-outline mt-2' onclick="seleccionarFarmacia('${f.nombre}')">
                    <i class="fas fa-check-circle me-1"></i>Seleccionar
                </button>
            </div>
        `);
    });
});

// Función global para selección de farmacia
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
            Swal.fire({
                title: '¡Farmacia seleccionada!',
                html: `
                    <i class="fas fa-check-circle text-success fa-3x mb-3"></i><br>
                    Redirigiendo al siguiente paso...
                `,
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '/subir-receta';
            });
        }
    });
}
