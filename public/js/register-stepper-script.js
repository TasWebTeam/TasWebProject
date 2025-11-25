document.addEventListener("DOMContentLoaded", function () {

    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = '/css/card-input-styles.css';
    document.head.appendChild(link);

    const paso1 = document.getElementById("formPaso1");
    const paso2 = document.getElementById("formPaso2");

    const btnContinuar = document.getElementById("btnContinuar");
    const btnVolver = document.getElementById("btnVolver");

    const contErrorsPaso1 = document.getElementById("erroresPaso1");
    const erroresPaso2 = document.getElementById("erroresPaso2");

    if (erroresPaso2) {
        paso1.style.display = "none";
        paso2.style.display = "flex";
        actualizarStepper(2);
    }

    function cambiarPaso(actual, siguiente) {
        actual.style.display = "none";
        siguiente.style.display = "flex";
        const pasoNum = siguiente === paso2 ? 2 : 1;
        actualizarStepper(pasoNum);
    }

    function actualizarStepper(pasoActual) {
        const items = document.querySelectorAll(".stepper-item");
        const lines = document.querySelectorAll(".stepper-line");

        items.forEach((item, idx) => {
            const numPaso = idx + 1;
            item.classList.remove("active", "completed");
            if (numPaso < pasoActual) item.classList.add("completed");
            if (numPaso === pasoActual) item.classList.add("active");
        });

        lines.forEach((line, idx) => {
            line.classList.remove("completed");
            if (idx < pasoActual - 1) line.classList.add("completed");
        });
    }

    btnContinuar.addEventListener("click", function () {
        contErrorsPaso1.style.display = "none";
        contErrorsPaso1.innerHTML = "";

        let formData = new FormData(paso1);

        fetch("/registro/validar-cliente", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name=_token]').value
            },
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.ok) {
                    document.getElementById("correoHidden").value = paso1.correo.value;
                    document.getElementById("nombreHidden").value = paso1.nombre.value;
                    document.getElementById("apellidoHidden").value = paso1.apellido.value;
                    document.getElementById("nipHidden").value = paso1.nip.value;

                    cambiarPaso(paso1, paso2);

                } else {
                    contErrorsPaso1.innerHTML =
                        "<ul>" + data.errores.map(e => `<li>${e}</li>`).join("") + "</ul>";
                    contErrorsPaso1.style.display = "block";
                }
            })
            .catch(() => {
                contErrorsPaso1.innerHTML = "<ul><li>Error al procesar la solicitud</li></ul>";
                contErrorsPaso1.style.display = "block";
            });
    });

    btnVolver.addEventListener("click", () => cambiarPaso(paso2, paso1));

});
