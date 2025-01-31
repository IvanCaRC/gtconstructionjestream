<div>
    <!-- Botón para abrir el modal -->
    <div class="form-group">
        <button type="button" id="add-position" class="btn btn-primary" style="width: 200px;">
            Seleccionar ubicación
        </button>
    </div>

    <!-- Modal de ubicación -->
    <div id="position-modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Seleccionar Ubicación</h5>
                    <button type="button" id="close-modalpos" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="search-location">Buscar ubicación</label>
                            <input type="text" id="search-location" class="form-control" placeholder="Ingresa una dirección o lugar...">
                            <!-- Contenedor para resultados con scroll -->
                            <div id="search-results-container" style="max-height: 200px; overflow-y: auto;" class="mt-2">
                                <div id="search-results"></div>
                            </div>
                        </div>
    
                        <div id="map" style="height: 400px;"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="add-manual-address" class="btn btn-secondary">Agregar dirección manualmente</button>
                    <button type="button" class="btn btn-primary" onclick="savePosition()">Guardar Ubicación</button>
                </div>
            </div>
        </div>
    </div>
</div>
