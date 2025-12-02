document.addEventListener("DOMContentLoaded", function () {
    const segmentos = window.segmentosMapa || [];

    if (!Array.isArray(segmentos) || segmentos.length === 0) {
        console.warn("No hay líneas de surtido para mostrar");
        return;
    }
     // === 1) Definir iconos ===
    const iconCentral = L.icon({
        iconUrl: "/images/farmacias/icon_central.png",
        iconSize: [60, 60],
        iconAnchor: [30, 60],
        popupAnchor: [0, -55],
    });

    const iconCadenaDefault = L.icon({
        iconUrl: "/images/farmacias/icon_default.jpg",
        iconSize: [60, 60],
        iconAnchor: [30, 60],
        popupAnchor: [0, -55],
    });

    function iconoPorCadena(nombreCadena) {
        if (!nombreCadena) return iconCadenaDefault;
        const n = nombreCadena;

        // aquí puedes hacer includes según tus cadenas reales
        if (n.includes("Farmacias del Ahorro"))    return L.icon({
            iconUrl: "/images/farmacias/aho.png",
            iconSize: [60, 60],
            iconAnchor: [30, 60],
            popupAnchor: [0, -55],
        });
        if (n.includes("Farmacias Benavides")) return L.icon({
            iconUrl: "/images/farmacias/bnv.png",
            iconSize: [60, 60],
            iconAnchor: [30, 60],
            popupAnchor: [0, -55],
        });
        if (n.includes("Farmacias Similares")) return L.icon({
            iconUrl: "/images/farmacias/sim.png",
            iconSize: [60, 60],
            iconAnchor: [30, 60],
            popupAnchor: [0, -55],
        });

        if (n.includes("Farmacias Farmacon")) return L.icon({
            iconUrl: "/images/farmacias/far.png",
            iconSize: [60, 60],
            iconAnchor: [30, 60],
            popupAnchor: [0, -55],
        });

        if (n.includes("Farmacias Guadalajara")) return L.icon({
            iconUrl: "/images/farmacias/gdl.png",
            iconSize: [60, 60],
            iconAnchor: [30, 60],
            popupAnchor: [0, -55],
        });

        return iconCadenaDefault;
    }
    console.log("SEGMENTOS:", segmentos);

    // === 1) La sucursal CENTRAL es el DESTINO del primer segmento ===
    const central = segmentos[0].destino;
    const map = L.map("map").setView([central.lat, central.lng], 13);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution: "&copy; OpenStreetMap contributors",
    }).addTo(map);

    const allLatLngs = [];

     // Marcador central (destino) – TAS
    L.marker([central.lat, central.lng], { icon: iconCentral })
        .addTo(map)
        .bindPopup("Sucursal Destino: " + central.nombre);

    allLatLngs.push([central.lat, central.lng]);

    // === 2) Dibujar líneas ORIGEN -> CENTRO ===
    segmentos.forEach((seg) => {
        const origen = seg.origen;
        const destino = seg.destino; // debería ser siempre la central

        if (!origen || origen.lat == null || origen.lng == null) return;

        const mismoPunto =
            origen.lat === destino.lat && origen.lng === destino.lng;

        let latlngs = null;

        if (!mismoPunto && seg.ruta && seg.ruta.length > 1) {
            // usar ruta con varios puntos
            latlngs = seg.ruta.map((p) => [p.lat, p.lng]);
        } else if (!mismoPunto) {
            // línea recta origen -> central
            latlngs = [
                [origen.lat, origen.lng],
                [destino.lat, destino.lng],
            ];
        }

        if (latlngs) {
            latlngs.forEach((ll) => allLatLngs.push(ll));

            // línea roja simple
            L.polyline(latlngs, {
                color: "red",
                weight: 3,
                opacity: 0.8,
            }).addTo(map);
        }

        // marcador de sucursal origen con icono de cadena
        const iconOrigen = iconoPorCadena(origen.cadena);
        L.marker([origen.lat, origen.lng], { icon: iconOrigen })
            .addTo(map)
            .bindPopup(
                "Sucursal origen: " +
                    origen.nombre +
                    "<br>Cadena: " +
                    (origen.cadena || "N/A")
            );
    });

    // === 3) Ajustar vista ===
    if (allLatLngs.length > 0) {
        const bounds = L.latLngBounds(allLatLngs);
        map.fitBounds(bounds, { padding: [30, 30] });
    }
});