let savedAddresses = []; // Array para almacenar las direcciones y coordenadas
document.addEventListener("DOMContentLoaded", function () {
    if (savedAddresses.length > 0) {
        savedAddresses = savedAddresses.map(dir => ({
            address: {
                calle: dir.calle || '',
                numero: dir.numero || '',
                colonia: dir.colonia || '',
                municipio: dir.municipio || '',
                ciudad: dir.ciudad || '',
                estado: dir.estado || '',
                cp: dir.cp || '',
                pais: dir.pais || '',
                referencia: dir.referencia || ''
            },
            latlng: dir.latitud && dir.longitud ? { lat: parseFloat(dir.latitud), lng: parseFloat(dir.longitud) } : null
        }));

        updateAddressList();
    }
    let map;
    let marker;
    let selectedLatLng = null; // Variable para almacenar las coordenadas seleccionadas


    function initMap(lat, lon) {
        if (!map) {
            // Crear el mapa
            map = L.map('map').setView([lat, lon], 13);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // Evento de clic para mover marcador
            map.on('click', function (e) {
                // Eliminar marcador si existe
                if (marker) {
                    map.removeLayer(marker);
                }

                // Crear marcador en la ubicación clickeada
                marker = L.marker(e.latlng).addTo(map);

                // Guardar la nueva latitud y longitud
                selectedLatLng = e.latlng;
            });
        } else {
            // Cambiar la vista si el mapa ya está inicializado
            map.setView([lat, lon], 13);
        }
    }
    function closeModal() {
        document.getElementById("position-modal").classList.remove("active");
    }
    function searchLocation(query) {
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${query}`;

        const resultsDiv = document.getElementById('search-results');
        const resultsContainer = document.getElementById('search-results-container');
        resultsDiv.innerHTML = '<p>Cargando resultados...</p>'; // Mostrar mensaje de carga

        fetch(url)
            .then(response => response.json())
            .then(data => {
                resultsDiv.innerHTML = ''; // Limpiar resultados previos

                if (data.length > 0) {
                    data.forEach(result => {
                        const resultItem = document.createElement('div');
                        resultItem.classList.add('result-item');
                        resultItem.innerHTML = `${result.display_name}`;
                        resultItem.addEventListener('click', () => {
                            // Mostrar marcador en la ubicación seleccionada
                            const lat = result.lat;
                            const lon = result.lon;
                            if (marker) {
                                map.removeLayer(marker);
                            }
                            marker = L.marker([lat, lon]).addTo(map);
                            map.setView([lat, lon], 13); // Centrar el mapa en la ubicación
                            selectedLatLng = L.latLng(lat, lon); // Guardar coordenadas
                        });
                        resultsDiv.appendChild(resultItem);
                    });
                } else {
                    resultsDiv.innerHTML = 'No se encontraron resultados.';
                }
            })
            .catch(error => {
                console.error('Error al buscar ubicación:', error);
                resultsDiv.innerHTML = 'Error al buscar la ubicación. Intenta nuevamente.';
            });
    }



    document.getElementById('search-location').addEventListener('input', function () {
        const query = this.value;
        if (query.length > 3) { // Solo realizar búsqueda si la longitud es mayor a 3 caracteres
            searchLocation(query);
        }
    });

    document.getElementById("add-manual-address").addEventListener("click", function () {
        addManualAddress();
        closeModal(); // Cierra el modal automáticamente después de agregar una dirección manualmente
    });

    function addManualAddress() {
        const emptyAddress = {
            calle: "",
            numero: "",
            colonia: "",
            municipio: "",
            ciudad: "",
            estado: "",
            cp: "",
            pais: "",
            referencia: ""
        };

        savedAddresses.push({
            address: emptyAddress,
            latlng: null // No hay coordenadas para direcciones manuales
        });

        updateAddressList(); // Actualizar la vista con la nueva dirección vacía
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

                listItem.innerHTML = `<div class="row align-items-end">
        <div class="col-md-2 mb-3">
            <label>Calle</label>
            <input type="text" class="form-control" value="${entry.address.calle}" id="calle-${index}" oninput="autoSaveAddress(${index})">
        </div>
        <div class="col-md-2 mb-3">
            <label>Número</label>
            <input type="text" class="form-control" value="${entry.address.numero}" id="numero-${index}" oninput="autoSaveAddress(${index})">
        </div>
        <div class="col-md-2 mb-3">
            <label>Colonia</label>
            <input type="text" class="form-control" value="${entry.address.colonia}" id="colonia-${index}" oninput="autoSaveAddress(${index})">
        </div>
        <div class="col-md-2 mb-3">
            <label>Municipio</label>
            <input type="text" class="form-control" value="${entry.address.municipio}" id="municipio-${index}" oninput="autoSaveAddress(${index})">
        </div>
        <div class="col-md-2 mb-3">
            <label>Ciudad</label>
            <input type="text" class="form-control" value="${entry.address.ciudad}" id="ciudad-${index}" oninput="autoSaveAddress(${index})">
        </div>
        <div class="col-md-2 mb-3">
            <label>Estado</label>
            <input type="text" class="form-control" value="${entry.address.estado}" id="estado-${index}" oninput="autoSaveAddress(${index})">
        </div>
        <div class="col-md-2 mb-3">
            <label>Código Postal</label>
            <input type="text" class="form-control" value="${entry.address.cp}" id="cp-${index}" oninput="autoSaveAddress(${index})">
        </div>
        <div class="col-md-2 mb-3">
            <label>País</label>
            <input type="text" class="form-control" value="${entry.address.pais}" id="pais-${index}" oninput="autoSaveAddress(${index})">
        </div>
        <div class="col-md-2 mb-3">
            <label>Referencia</label>
            <input type="text" class="form-control" value="${entry.address.referencia}" id="referencia-${index}" placeholder="Capturar referencia" oninput="autoSaveAddress(${index})">
        </div>
       <div class="col-md-2 mb-3">
    <label>Latitud</label>
    <input type="text" class="form-control" value="${entry.latlng ? entry.latlng.lat : ''}" 
           id="latitud-${index}" oninput="updateCoordinates(${index})">
</div>
<div class="col-md-2 mb-3">
    <label>Longitud</label>
    <input type="text" class="form-control" value="${entry.latlng ? entry.latlng.lng : ''}" 
           id="longitud-${index}" oninput="updateCoordinates(${index})">
</div>
        <div class="col-md-2 mb-3 d-flex align-items-end">
            <button type="button" class="btn btn-danger w-100" onclick="removeAddress(${index})">Eliminar</button>
        </div>
    </div>
`;

                addressList.appendChild(listItem);
            });
        }
    }

    window.updateCoordinates = function (index) {
        const latInput = document.getElementById(`latitud-${index}`).value;
        const lngInput = document.getElementById(`longitud-${index}`).value;

        const lat = parseFloat(latInput);
        const lng = parseFloat(lngInput);

        if (!isNaN(lat) && !isNaN(lng)) {
            savedAddresses[index].latlng = { lat, lng };
        }
    };

    // Función para eliminar una dirección del arreglo
    window.removeAddress = function (index) {
        savedAddresses.splice(index, 1); // Eliminar del arreglo
        updateAddressList(); // Actualizar la vista
    };

    // Función para guardar la ubicación seleccionada
    window.savePosition = function () {
        if (selectedLatLng) {
            getAddressFromCoordinates(selectedLatLng.lat, selectedLatLng.lng, selectedLatLng);

            document.getElementById('position-modal').classList.remove('active');
        } else {
            alert("Por favor selecciona una ubicación en el mapa.");
        }
    };

    function autoSaveAddress(index) {
        // Obtener los valores actualizados de los inputs
        const calle = document.getElementById(`calle-${index}`).value;
        const numero = document.getElementById(`numero-${index}`).value;
        const colonia = document.getElementById(`colonia-${index}`).value;
        const municipio = document.getElementById(`municipio-${index}`).value;
        const ciudad = document.getElementById(`ciudad-${index}`).value;
        const estado = document.getElementById(`estado-${index}`).value;
        const cp = document.getElementById(`cp-${index}`).value;
        const pais = document.getElementById(`pais-${index}`).value;
        const referencia = document.getElementById(`referencia-${index}`).value;
    
        console.log("Valores actualizados:", {
            calle,
            numero,
            colonia,
            municipio,
            ciudad,
            estado,
            cp,
            pais,
            referencia
        });
        
        // Actualizar el arreglo savedAddresses con los nuevos valores
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
    
        // También actualiza las coordenadas si es necesario
        const latitud = document.getElementById(`latitud-${index}`).value;
        const longitud = document.getElementById(`longitud-${index}`).value;
    
        if (latitud && longitud) {
            savedAddresses[index].latlng = {
                lat: parseFloat(latitud),
                lng: parseFloat(longitud)
            };
        }
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

    function submitForm() {
        // Convertir las direcciones a formato JSON
        const directionsJSON = JSON.stringify(savedAddresses);
    
        // Asignar el valor al campo oculto
        document.getElementById('direcciones-input').value = directionsJSON;
    
        // Ahora el formulario puede ser enviado
        document.getElementById('proveedor-form').submit();
    }
    
    document.querySelector('button[type="submit"]').addEventListener('click', function (event) {
        event.preventDefault(); // Evitar el envío normal del formulario
        submitForm(); // Llamar a la función para guardar las direcciones
    });
});
// Mover la función autoSaveAddress al ámbito global
window.autoSaveAddress = function (index) {
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

    // También actualiza las coordenadas si es necesario
    const latitud = document.getElementById(`latitud-${index}`).value;
    const longitud = document.getElementById(`longitud-${index}`).value;

    if (latitud && longitud) {
        savedAddresses[index].latlng = {
            lat: parseFloat(latitud),
            lng: parseFloat(longitud)
        };
    }

    console.log("Dirección actualizada:", savedAddresses[index]);
};
