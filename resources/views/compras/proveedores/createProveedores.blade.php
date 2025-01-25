@extends('layouts.app')
@section('title', 'Crear proveedor')
@section('activedesplegablefamilias', 'active')
@section('activeCollapseCompras', 'show')
@section('activeProveedores', 'active')
@section('activeFondoPermanenteProveedores', 'background-permanent')
@section('contend')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }
    
            .container-fluid {
                max-width: 800px;
                margin: 20px auto;
                padding: 20px;
                background-color: #fff;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
            }
    
            .card {
                border: 1px solid #ddd;
                border-radius: 5px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                margin-bottom: 20px;
                padding: 20px;
            }
    
            .form-group {
                margin-bottom: 15px;
            }
    
            .form-group label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }
    
            .form-group input,
            .form-group textarea,
            .form-group select,
            .form-group button {
                width: 100%;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
            }
    
            .form-group button {
                background-color: #007bff;
                color: #fff;
                cursor: pointer;
            }
    
            .form-group button:hover {
                background-color: #0056b3;
            }
    
            .file-upload {
                border: 2px dashed #ddd;
                border-radius: 5px;
                text-align: center;
                padding: 20px;
                cursor: pointer;
            }
    
            .file-upload-icon {
                font-size: 50px;
                color: #007bff;
            }
    
            .file-upload-text {
                margin-top: 10px;
                font-size: 14px;
            }
    
            #map {
                height: 300px;
                width: 100%;
                margin-top: 20px;
                border: 1px solid #ddd;
                border-radius: 5px;
            }
    
            .input-group {
                display: flex;
                align-items: center;
            }
    
            .input-group button {
                margin-left: 10px;
                padding: 10px;
                background-color: #dc3545;
                color: #fff;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
    
            .input-group button:hover {
                background-color: #c82333;
            }
    
            /* Estilos para el modal */
            .modal {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                justify-content: center;
                align-items: center;
            }
    
            .modal.active {
                display: flex;
            }
    
            .modal-content {
                background-color: white;
                padding: 20px;
                border-radius: 8px;
                width: 400px;
                text-align: center;
            }
    
            .mapm-content {
                background-color: white;
                padding: 20px;
                border-radius: 8px;
                width: 600px;
                text-align: center;
            }
    
            .modal-header {
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 15px;
            }
    
            .close-btn {
                cursor: pointer;
                background-color: #dc3545;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
            }
    
            .close-btn:hover {
                background-color: #c82333;
            }
        </style>
        <div class="container-fluid">
            <h1>Crear Nuevo Proveedor</h1>
    
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" class="form-control" placeholder="Nombre">
                    </div>
    
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea rows="5" id="descripcion" class="form-control" placeholder="Descripción"></textarea>
                    </div>
    
                    <div class="form-group">
                        <label for="email">Correo</label>
                        <input type="email" id="email" class="form-control" placeholder="Correo Electrónico">
                    </div>
    
                    <div class="form-group">
                        <label for="rfc">RFC</label>
                        <input type="text" id="rfc" class="form-control" placeholder="RFC">
                    </div>
    
                    <div class="form-group">
                        <label>Teléfonos</label>
                        <input type="text" id="telefono" class="form-control" placeholder="Teléfono">
                        <div id="telefonos-container"></div>
                        <button type="button" id="add-telefono" class="btn btn-primary mt-2" style="width: 200px;">Agregar
                            otro teléfono</button>
                    </div>
    
                    <div class="form-group">
                        <label for="archivosFacturacion">Archivos de facturación</label>
                        <div class="file-upload" id="upload-facturacion">
                            <span class="file-upload-icon">&#x1F4C2;</span>
                            <span class="file-upload-text">Buscar archivos<br>Arrastre y suelte archivos aquí</span>
                            <input type="file" id="archivosFacturacion" class="form-control-file" accept=".pdf"
                                style="display: none;">
                        </div>
                    </div>
    
                    <div class="form-group">
                        <label for="archivosBancarios">Datos Bancarios</label>
                        <div class="file-upload" id="upload-bancarios">
                            <span class="file-upload-icon">&#x1F4C2;</span>
                            <span class="file-upload-text">Buscar archivos<br>Arrastre y suelte archivos aquí</span>
                            <input type="file" id="archivosBancarios" class="form-control-file" accept=".pdf"
                                style="display: none;">
                        </div>
                    </div>
    
                    <div class="form-group">
                        <label for="archivosBancarios">Familias</label>
                        <div id="no-families-selected" style="display: none;">No hay familias seleccionadas</div>
                    </div>
    
                    <div class="form-group">
                        <button type="button" id="add-family" class="btn btn-primary" style="width: 200px;">Agregar
                            Familia</button>
                    </div>
    
                    <div class="form-group">
                        <button type="button" id="add-position" class="btn btn-primary" style="width: 200px;">Seleccionar
                            ubicación</button>
                    </div>
    
                    <button type="button" onclick="confirmSave()" class="btn btn-primary mt-3" style="width: 200px;">Crear
                        proveedor</button>
                </div>
            </div>
        </div>
    
        <!-- Modal -->
        <div id="family-modal" class="modal">
            <div class="modal-content">
                <div class="modal-header">Agregar Familia</div>
                <form>
                    <div class="form-group">
                        <label for="family-name" style="text-align: left;">Nivel 1</label>
                        <select id="family-name" class="form-control">
                            <option value="">Selecciona un nivel</option>
                            <option value="nivel1">Familia A</option>
                            <option value="nivel1">Familia B</option>
                            <option value="nivel1">Familia C</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description" style="text-align: left;">Nivel 2</label>
                        <select id="family-name" class="form-control">
                            <option value="">Selecciona un nivel</option>
                            <option value="nivel1">Familia AA</option>
                            <option value="nivel1">Familia AB</option>
                            <option value="nivel1">Familia AC</option>
                        </select>
                    </div>
                    <button type="button" id="close-modal" class="close-btn">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="saveFamily()">Guardar Familia</button>
                </form>
            </div>
        </div>
    
        <!-- Modal -->
        <div id="position-modal" class="modal">
            <div class="mapm-content">
                <div class="modal-header">Seleccionar Ubicación</div>
                <form>
                    <div class="form-group">
                        <label>Mapa de Dirección</label>
                        <div id="map"></div>
                    </div>
                    <button type="button" id="close-modalpos" class="close-btn">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="savePosition()">Guardar Ubicación</button>
                </form>
            </div>
        </div>
    
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            // Inicializar mapa
            var map = L.map('map').setView([19.4326, -99.1332], 14); // Coordenadas por defecto
    
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
                alert(Ubicación seleccionada: ${e.latlng.lat}, ${e.latlng.lng});
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
                input.style.width = '300px';
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
    
            const noFamiliesSelectedElement = document.getElementById('no-families-selected');
    
            const addFamilyButton = document.getElementById('add-family');
            const modal = document.getElementById('family-modal');
            const closeModalButton = document.getElementById('close-modal');
    
            // Mostrar modal
            addFamilyButton.addEventListener('click', () => {
                modal.classList.add('active');
            });
    
            window.addEventListener('load', () => {
                const familyNameSelect = document.getElementById('family-name');
                if (familyNameSelect.value === '') {
                    noFamiliesSelectedElement.style.display = 'block';
                } else {
                    noFamiliesSelectedElement.style.display = 'none';
                }
            });
    
            // Cerrar modal
            closeModalButton.addEventListener('click', () => {
                modal.classList.remove('active');
            });
    
            // Simular guardar familia
            function saveFamily() {
                const familyName = document.getElementById('family-name').value;
                const description = document.getElementById('description').value;
                if (familyName.trim() && description.trim()) {
                    alert(Familia guardada: ${familyName});
                    modal.classList.remove('active');
                } else {
                    alert('Por favor, llena todos los campos.');
                }
            }
    
            // ------------------------UBICACIÓN-------------------------------
            const addPositionButton = document.getElementById('add-position');
            const modalPos = document.getElementById('position-modal');
            const closeModalPosButton = document.getElementById('close-modalpos');
    
            // Mostrar modal
            addPositionButton.addEventListener('click', () => {
                modalPos.classList.add('active');
            });
    
            // Cerrar modal
            closeModalPosButton.addEventListener('click', () => {
                modalPos.classList.remove('active');
            });
    
            // Simular guardar familia
            function savePosition() {
                const positionName = document.getElementById('position-name').value;
                const description = document.getElementById('description').value;
                if (positionName.trim() && description.trim()) {
                    alert(Ubicación guardada: ${positionName});
                    modalPos.classList.remove('active');
                } else {
                    alert('Por favor, llena todos los campos.');
                }
            }
        </script>
    {{-- @livewire('proveedor.create-proveedor') --}}
@endsection
