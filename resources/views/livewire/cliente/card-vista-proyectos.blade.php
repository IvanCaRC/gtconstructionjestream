<div>
    <h2 class="ml-3">Proyectos del cliente</h2>
    <div class="card">
        <div class="card-body">
            <div class="text-left mb-3">
                <button class="btn btn-custom" style="background-color: #4c72de; color: white;"
                    wire:click="$set('openModalCreacionProyecto', true)">Agregar proyecto</button>
            </div>
            <div class="row mb-3">
                <div class="col-md-10">
                    <!-- Input de búsqueda -->
                    <input type="text" class="form-control mr-2" id="searchInput" placeholder="Buscar proyecto...">

                    <!-- Filtro de Estado -->

                </div>
                <div class="col-md-2">
                    <select class="form-control mr-2">
                        <option value="3">Todos los estados</option>
                        <option value="2">Activo</option>
                        <option value="1">Inactivo</option>
                        <option value="0">Cancelados</option>
                    </select>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Estado</th>
                        <th>
                            Proceso

                        </th>
                        <th >
                            Nombre

                        </th>
                        <th >
                            Tipo

                        </th>
                        <th>Direccion</th>
                        <th>Listas</th>
                        <th>Cotisaciones</th>
                        <th>Ordenes</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>

    </div>

    
</div>
