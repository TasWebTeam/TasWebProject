document.addEventListener("DOMContentLoaded", async () => {
    const map = L.map("map").setView([24.8091, -107.394], 13);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors",
    }).addTo(map);

    let sucursales = [];
    let markers = [];
    const filtro = document.getElementById("filtro_cadena");

    try {
        const response = await fetch("/sucursales");

        if (!response.ok) {
            throw new Error("Error al cargar sucursales");
        }

        sucursales = await response.json();

        llenarFiltros(sucursales);
        mostrarSucursales(sucursales);
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "No se pudieron cargar las sucursales",
        });
    }

    function llenarFiltros(data) {
        const cadenas = [
            ...new Set(data.map((s) => s.cadena?.nombre).filter(Boolean)),
        ];

        cadenas.forEach((cadena) => {
            const op = document.createElement("option");
            op.value = cadena;
            op.textContent = cadena;
            filtro.appendChild(op);
        });
    }

    filtro.addEventListener("change", () => {
        const c = filtro.value;
        const filtradas = c
            ? sucursales.filter((s) => s.cadena?.nombre === c)
            : sucursales;
        mostrarSucursales(filtradas);
    });

    function mostrarSucursales(lista) {
        markers.forEach((m) => map.removeLayer(m));
        markers = [];

        lista.forEach((f) => {
            const iconMap = {
                "Farmacias Guadalajara": "/images/farmacias/gdl.png",
                "Farmacias Benavides": "/images/farmacias/bnv.png",
                "Farmacias del Ahorro": "/images/farmacias/aho.png",
                "Farmacias Similares": "/images/farmacias/sim.png",
                "Farmacias Farmacon": "/images/farmacias/far.png",
            };

            const iconUrl =
                iconMap[f.cadena?.nombre] ||
                "/images/farmacias/icon_default.jpg";

            const customIcon = L.icon({
                iconUrl: iconUrl,
                iconSize: [60, 60],
                iconAnchor: [30, 30],
                popupAnchor: [0, -30],
            });

            const marker = L.marker([f.latitud, f.longitud], {
                icon: customIcon,
            }).addTo(map);
            markers.push(marker);

            marker.bindPopup(`
                <div style="text-align:center;">
                    <strong>${f.nombre}</strong><br>
                    <small>${f.cadena?.nombre ?? "Sin cadena"}</small><br>
                    <button class='btn btn-sm btn-tas-outline mt-2'
                        onclick="seleccionarFarmacia('${f.cadena?.nombre}', '${
                f.nombre
            }')">
                        <i class="fas fa-check-circle me-1"></i>Seleccionar
                    </button>
                </div>
            `);
        });
    }
});

function seleccionarFarmacia(cadena, nombre) {
    const nombreCompleto = `${cadena} — Sucursal ${nombre}`;

    Swal.fire({
        title: "¿Confirmar selección?",
        html: `Has elegido:<br><strong>${nombreCompleto}</strong>`,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Sí, continuar",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#005B96",
        cancelButtonColor: "#6c757d",
    }).then((result) => {
        if (result.isConfirmed) {
            localStorage.setItem("farmaciaCadena", cadena);
            localStorage.setItem("farmaciaSucursal", nombre);

            const elementoFarmacia = document.getElementById(
                "farmacia-seleccionada"
            );
            if (elementoFarmacia) {
                elementoFarmacia.textContent = nombreCompleto;
            }

            const mapSection = document.getElementById("map-section");
            const formSection = document.getElementById("form-section");

            if (mapSection && formSection) {
                mapSection.classList.add("d-none");
                formSection.classList.remove("d-none");

                const stepperContainer =
                    document.querySelector(".stepper-container");
                if (stepperContainer) {
                    stepperContainer.innerHTML = `
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
            } else {
                Swal.fire({
                    title: "¡Farmacia seleccionada!",
                    html: `
                        <i class="fas fa-check-circle text-success fa-3x mb-3"></i><br>
                        Redirigiendo al siguiente paso...
                    `,
                    icon: "success",
                    timer: 1500,
                    showConfirmButton: false,
                }).then(() => {
                    window.location.href = "/subir_receta";
                });
            }
        }
    });
}

function toggleTextarea() {
    const container = document.getElementById("textarea-container");
    if (container) {
        container.classList.toggle("d-none");
    }
}
