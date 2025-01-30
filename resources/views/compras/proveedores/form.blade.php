<div >
    <!-- Botón para abrir el modal -->
    <div class="form-group">
        <button type="button" id="add-position" class="btn btn-primary" style="width: 200px;">
            Seleccionar ubicación
        </button>
    </div>

    <!-- Modal de ubicación -->
    <div id="position-modal" class="modal">
        <div class="mapm-content">
            <div >Seleccionar Ubicación</div>
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
</div>