document.addEventListener("DOMContentLoaded", function () {
    // Inicializar mapa con Leaflet
    var map = L.map('map').setView([51.505, -0.09], 13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);
    let marker;
    map.on('click', function (e) {
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker(e.latlng).addTo(map);
        alert(`Ubicación seleccionada: ${e.latlng.lat}, ${e.latlng.lng}`);
    });

    // Manejar teléfonos dinámicos
    const telefonosContainer = document.getElementById('telefonos-container');
    document.getElementById('add-telefono').addEventListener('click', () => {
        const inputGroup = document.createElement('div');
        inputGroup.classList.add('input-group', 'mb-2');

        const input = document.createElement('input');
        input.type = 'text';
        input.className = 'form-control';
        input.placeholder = 'Teléfono';
        inputGroup.appendChild(input);

        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'Eliminar';
        deleteButton.style.width = '200px';
        deleteButton.addEventListener('click', () => {
            telefonosContainer.removeChild(inputGroup);
        });
        inputGroup.appendChild(deleteButton);

        telefonosContainer.appendChild(inputGroup);
    });

    // Manejo de subida de archivos
    document.getElementById('upload-facturacion').addEventListener('click', () => {
        document.getElementById('archivosFacturacion').click();
    });

    document.getElementById('upload-bancarios').addEventListener('click', () => {
        document.getElementById('archivosBancarios').click();
    });

    function confirmSave() {
        alert('Proveedor creado correctamente.');
    }

    // Manejo de modal de familias
    const addFamilyButton = document.getElementById('add-family');
    const modal = document.getElementById('family-modal');
    const closeModalButton = document.getElementById('close-modal');

    addFamilyButton.addEventListener('click', () => modal.classList.add('active'));
    closeModalButton.addEventListener('click', () => modal.classList.remove('active'));

    function saveFamily() {
        alert("Familia guardada.");
        modal.classList.remove('active');
    }

    // Manejo de modal de ubicación
    const addPositionButton = document.getElementById('add-position');
    const modalPos = document.getElementById('position-modal');
    const closeModalPosButton = document.getElementById('close-modalpos');

    addPositionButton.addEventListener('click', () => modalPos.classList.add('active'));
    closeModalPosButton.addEventListener('click', () => modalPos.classList.remove('active'));
});
