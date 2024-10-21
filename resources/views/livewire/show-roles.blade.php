<style>
    .table thead th {
        border-top: 0;
        /* Elimina el borde superior de los encabezados de la tabla */
    }
</style>

<div>
    <h1 class="pl-4">Roles del sistema</h1>
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-3"> <!-- Reduce el padding -->
        <div class="card" style="width: 60%; margin: 0 auto; float: left;"> <!-- Ajustar el ancho de la tarjeta -->
            <div class="card-body p-2"> <!-- Reduce el padding -->
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <div class="table-responsive">
                    <table class="table table-sm" style="width: 100%;"> <!-- Tabla compacta -->
                        <thead>
                            <tr>
                                <th style="width: 35%;">Nombre del rol</th> <!-- Ajustar ancho de la columna -->
                                <th class="d-none d-md-table-cell" wire:click="order('first_last_name')"
                                    style="cursor: pointer; width: 40%;">Descripción</th>
                                <!-- Ajustar ancho de la columna -->
                                <th style="width: 12.5%;"></th>
                                <th style="width: 12.5%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="align-middle">Administrador</td>
                                <td class="align-middle d-none d-md-table-cell">Se encarga de administrar el usuario
                                </td>
                                <td>
                                    <button class="btn btn-info btn-sm" style="margin-left: 10px;">
                                        <!-- Añadido margen para más espacio -->
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <x-dialog-modal wire:model="open">
        <x-slot name='title'>
            Editar Rol
        </x-slot>
        <x-slot name='content'>
            <form>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <h2>Administrador</h2>
                        
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea id="description" class="form-control" rows="5"></textarea> <!-- Convertido a textarea -->
                </div>
                
                
            </form>
        </x-slot>
        <x-slot name='footer'>
            <button class="btn btn-secondary mr-2 disabled:opacity-50" >Cancelar</button>
            <button class="btn btn-primary disabled:opacity-50" >Actualizar</button>





        </x-slot>
    </x-dialog-modal>

</div>
