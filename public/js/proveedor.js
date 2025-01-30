document.addEventListener("DOMContentLoaded", function () {
    let map;
    let marker;
    let selectedLatLng = null; // Variable para almacenar las coordenadas seleccionadas
    let savedAddresses = []; // Array para almacenar las direcciones y coordenadas

    function initMap(lat, lon) {
        if (!map) {
            // Crear el mapa si no existe
            map = L.map('map').setView([lat, lon], 13);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // Agregar evento de clic para obtener dirección y mostrar marcador
            map.on('click', function (e) {
                // Eliminar el marcador anterior si existe
                if (marker) {
                    map.removeLayer(marker);
                }

                // Crear un nuevo marcador en la ubicación del clic
                marker = L.marker(e.latlng).addTo(map);

                // Almacenar las coordenadas seleccionadas
                selectedLatLng = e.latlng;
            });
        } else {
            // Si el mapa ya está creado, solo cambiar la vista
            map.setView([lat, lon], 13);
        }
    }

    // Obtener la ubicación del usuario
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                initMap(position.coords.latitude, position.coords.longitude);
            },
            function () {
                alert("No se pudo obtener tu ubicación, se usará una por defecto.");
                initMap(19.432608, -99.133209); // Ciudad de México por defecto
            }
        );
    } else {
        alert("Geolocalización no soportada en este navegador.");
        initMap(19.432608, -99.133209); // Ubicación por defecto
    }

    // Función para obtener la dirección a partir de coordenadas
    function getAddressFromCoordinates(lat, lng, latlng) {
        const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`;
    
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.address) {
                    const addressComponents = {
                        calle: data.address.road || 'Campo no recuperado',
                        numero: data.address.house_number || 'Campo no recuperado',
                        colonia: data.address.neighbourhood || data.address.suburb || 'Campo no recuperado',
                        municipio: data.address.town || data.address.city_district || 'Campo no recuperado',
                        ciudad: data.address.city || 'Campo no recuperado',
                        estado: data.address.state || 'Campo no recuperado',
                        cp: data.address.postcode || 'Campo no recuperado',
                        pais: data.address.country || 'Campo no recuperado',
                        referencia: '' // Este campo se ingresará manualmente
                    };
    
                    savedAddresses.push({
                        address: addressComponents,
                        latlng: latlng
                    });
    
                    updateAddressList(); // Actualizar la lista de direcciones
                } else {
                    alert("No se pudo obtener la dirección.");
                }
            })
            .catch(error => console.error("Error al obtener la dirección:", error));
    }

    // Función para actualizar la vista con las direcciones guardadas
    function updateAddressList() {
        const addressList = document.getElementById("address-list");
        addressList.innerHTML = ''; // Limpiar la lista antes de actualizar
    
        if (savedAddresses.length === 0) {
            addressList.innerHTML = "<p>No hay direcciones guardadas.</p>";
        } else {
            savedAddresses.forEach((entry, index) => {
                const listItem = document.createElement("div");
                listItem.classList.add("address-item");
    
                // Crear campos de entrada para cada parte de la dirección, incluyendo la referencia
                listItem.innerHTML = `
                    <div class="form-group">
                        <label>Calle</label>
                        <input type="text" class="form-control" value="${entry.address.calle}" id="calle-${index}" oninput="autoSaveAddress(${index})">
                    </div>
                    <div class="form-group">
                        <label>Número</label>
                        <input type="text" class="form-control" value="${entry.address.numero}" id="numero-${index}" oninput="autoSaveAddress(${index})">
                    </div>
                    <div class="form-group">
                        <label>Colonia</label>
                        <input type="text" class="form-control" value="${entry.address.colonia}" id="colonia-${index}" oninput="autoSaveAddress(${index})">
                    </div>
                    <div class="form-group">
                        <label>Municipio</label>
                        <input type="text" class="form-control" value="${entry.address.municipio}" id="municipio-${index}" oninput="autoSaveAddress(${index})">
                    </div>
                    <div class="form-group">
                        <label>Ciudad</label>
                        <input type="text" class="form-control" value="${entry.address.ciudad}" id="ciudad-${index}" oninput="autoSaveAddress(${index})">
                    </div>
                    <div class="form-group">
                        <label>Estado</label>
                        <input type="text" class="form-control" value="${entry.address.estado}" id="estado-${index}" oninput="autoSaveAddress(${index})">
                    </div>
                    <div class="form-group">
                        <label>Código Postal</label>
                        <input type="text" class="form-control" value="${entry.address.cp}" id="cp-${index}" oninput="autoSaveAddress(${index})">
                    </div>
                    <div class="form-group">
                        <label>País</label>
                        <input type="text" class="form-control" value="${entry.address.pais}" id="pais-${index}" oninput="autoSaveAddress(${index})">
                    </div>
                    <div class="form-group">
                        <label>Referencia</label>
                        <input type="text" class="form-control" value="${entry.address.referencia}" id="referencia-${index}" placeholder="Capturar referencia" oninput="autoSaveAddress(${index})">
                    </div>
                    <button type="button" class="btn btn-danger" onclick="removeAddress(${index})">Eliminar</button>
                `;
                addressList.appendChild(listItem);
            });
        }
    }
    

    // Función para eliminar una dirección del arreglo
    window.removeAddress = function(index) {
        savedAddresses.splice(index, 1); // Eliminar del arreglo
        updateAddressList(); // Actualizar la vista
    };

    // Función para guardar la ubicación seleccionada
    window.savePosition = function() {
        if (selectedLatLng) {
            getAddressFromCoordinates(selectedLatLng.lat, selectedLatLng.lng, selectedLatLng);
           
            document.getElementById('position-modal').classList.remove('active');
        } else {
            alert("Por favor selecciona una ubicación en el mapa.");
        }
    };
    function autoSaveAddress(index) {
        const calle = document.getElementById(`calle-${index}`).value;
        const numero = document.getElementById(`numero-${index}`).value;
        const colonia = document.getElementById(`colonia-${index}`).value;
        const municipio = document.getElementById(`municipio-${index}`).value;
        const ciudad = document.getElementById(`ciudad-${index}`).value;
        const estado = document.getElementById(`estado-${index}`).value;
        const cp = document.getElementById(`cp-${index}`).value;
        const pais = document.getElementById(`pais-${index}`).value;
        const referencia = document.getElementById(`referencia-${index}`).value;
    
        // Actualizar los datos en el arreglo de direcciones automáticamente
        savedAddresses[index].address = {
            calle: calle || 'Campo no recuperado',
            numero: numero || 'Campo no recuperado',
            colonia: colonia || 'Campo no recuperado',
            municipio: municipio || 'Campo no recuperado',
            ciudad: ciudad || 'Campo no recuperado',
            estado: estado || 'Campo no recuperado',
            cp: cp || 'Campo no recuperado',
            pais: pais || 'Campo no recuperado',
            referencia: referencia || ''
        };
    }
    // Abrir el modal al hacer clic en el botón
    const addPositionButton = document.getElementById('add-position');
    const modalPos = document.getElementById('position-modal');
    const closeModalPosButton = document.getElementById('close-modalpos');

    addPositionButton.addEventListener('click', () => {
        modalPos.classList.add('active');
        setTimeout(() => {  
            map.invalidateSize();
        }, 300);
    });

    // Cerrar el modal
    closeModalPosButton.addEventListener('click', () => {
        modalPos.classList.remove('active');
    });
    
});
