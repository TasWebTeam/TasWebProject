document.addEventListener("DOMContentLoaded", () => {
    const map = L.map("map").setView([24.8091, -107.394], 13);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors",
    }).addTo(map);

    const sucursales = window.SUCURSALES || [];
    let markers = [];
    const filtro = document.getElementById("filtro_cadena");

    llenarFiltros(sucursales);
    mostrarSucursales(sucursales);

    function llenarFiltros(data) {
        const cadenas = [
            ...new Set(data.map(s => s.cadena?.nombre).filter(Boolean))
        ];

        cadenas.forEach(cadena => {
            const op = document.createElement("option");
            op.value = cadena;
            op.textContent = cadena;
            filtro.appendChild(op);
        });
    }

    filtro.addEventListener("change", () => {
        const c = filtro.value;
        const filtradas = c ? sucursales.filter(s => s.cadena?.nombre === c) : sucursales;
        mostrarSucursales(filtradas);
    });

    function mostrarSucursales(lista) {
        markers.forEach(m => map.removeLayer(m));
        markers = [];

        lista.forEach(f => {
            const marker = L.marker([f.latitud, f.longitud]).addTo(map);
            markers.push(marker);

            marker.bindPopup(`
                <div style="text-align:center;">
                    <strong>${f.nombre}</strong><br>
                    <small>${f.cadena?.nombre ?? "Sin cadena"}</small><br>
                    <button class='btn btn-sm btn-tas-outline mt-2'
                        onclick="seleccionarFarmacia('${f.cadena?.nombre}', '${f.nombre}')">
                        <i class="fas fa-check-circle me-1"></i>Seleccionar
                    </button>
                </div>
            `);
        });
    }
});
