<div>
    <Style>
        #map {
            height: 40px;
            width: 40px;
        }
    </Style>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <a href="#" wire:click="$set('openModalDireccion', true)" class="btn btn-primary mt-3">Agregar Direccion</a>
    <x-dialog-modal wire:model="openModalDireccion">
        <x-slot name='title'>
            Añadir Dirección
        </x-slot>
        <x-slot name='content'>

            <div id="map"></div>


        </x-slot>
        <x-slot name='footer'>
            <button class="btn btn-secondary mr-2 disabled:opacity-50"
                wire:click="$set('openModalDireccion',false)">Cancelar</button>
            <button class="btn btn-primary disabled:opacity-50" wire:click="">Agregar Dirección</button>
        </x-slot>
    </x-dialog-modal>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        var map = L.map('map').setView([51.505, -0.09], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
    </script>
</div>
