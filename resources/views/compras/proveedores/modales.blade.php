<div class="container-fluid">
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
</div>
