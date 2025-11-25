document.addEventListener('DOMContentLoaded', () => {

    const inputTarjeta = document.getElementById('numero_tarjeta');
    const imgBrand = document.getElementById('cardBrand');

    if (!inputTarjeta) return;

    function detectarMarca(numero) {
        numero = numero.replace(/\s+/g, '');

        if (/^4/.test(numero)) return 'visa';
        if (/^5[1-5]/.test(numero)) return 'mastercard';
        if (/^3[47]/.test(numero)) return 'amex';
        return null;
    }

    function actualizarLogo(marca) {
        if (!marca) {
            imgBrand.classList.add('d-none');
            return;
        }

        const logos = {
            visa: '/images/cards/visa.png',
            mastercard: '/images/cards/mastercard.png',
            amex: '/images/cards/amex.png'
        };

        imgBrand.src = logos[marca];
        imgBrand.classList.remove('d-none');
    }

    inputTarjeta.addEventListener('input', function () {
        let valor = this.value.replace(/\D/g, '');
        valor = valor.substring(0, 16);

        this.value = valor.replace(/(.{4})/g, '$1 ').trim();

        const marca = detectarMarca(valor);
        actualizarLogo(marca);
    });


    const inputFecha = document.getElementById('fecha_vencimiento');

    if (inputFecha) {
        inputFecha.addEventListener('input', function () {
            let v = this.value.replace(/\D/g, '');
            if (v.length >= 3)
                v = v.substring(0, 2) + '/' + v.substring(2, 4);
            this.value = v;
        });
    }

    const inputCVV = document.querySelector('input[name="cvv"]');

    if (inputCVV) {
        inputCVV.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').substring(0, 4);
        });
    }

});
