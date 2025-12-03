document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('medicamentoSelect');

    if (!select) return;

    select.addEventListener('change', function () {
        let id = this.value;

        if (!id) return;

        fetch(`/medicamento/info/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Medicamento no encontrado');
                    return;
                }

                document.getElementById('precio').value = data.precio ?? 'No disponible';
            })
            .catch(error);
    });
});