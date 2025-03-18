<div>
    <h3 class="ml-3">Listas del proyecto</h3>
    <div class="card">
        <div class="card-body">
            <div class="text-left mb-3">
                <button class="btn btn-custom" style="background-color: #4c72de; color: white;"
                    wire:click="saveListaNueva">Agregar proyecto</button>
            </div>
            <div class="row mb-3">
                <div class="col-md-10">
                    <!-- Input de búsqueda -->
                    <input type="text" class="form-control mr-2" id="searchInput" placeholder="Buscar proyecto..."
                      >

                    <!-- Filtro de Estado -->

                </div>
                <div class="col-md-2">
                    <select class="form-control mr-2"  >
                        <option value="0">Todos los estados</option>
                        <option value="1">Activo</option>
                        <option value="2">Inactivo</option>
                        <option value="3">Cancelados</option>
                    </select>
                </div>
                
            </div>
           
                <div>
                    No hay proyectos registrados para este cliente.
                </div>
            
        </div>

    </div>


</div>
