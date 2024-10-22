<div>

    <style>
        .table thead th {
            border-top: 0;
            /* Elimina el borde superior de los encabezados de la tabla */
        }

        .invalid-feedback {
            display: block;
            color: #e3342f;
        }
        .is-invalid {
    border-color: #e3342f;
}
    </style>
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
                                <th class="d-none d-md-table-cell" style=" width: 40%;">Descripción</th>
                                <!-- Ajustar ancho de la columna -->
                                <th style="width: 12.5%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td class="align-middle">{{ $role->name }}</td>
                                    <td class="align-middle d-none d-md-table-cell">{{ $role->description }}</td>

                                    <td>
                                        <button class="btn btn-primary btn-sm"
                                            wire:click="editRole({{ $role->id }})" >
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <x-dialog-modal wire:model="open" >
        <x-slot name='title'>
            Editar Rol
        </x-slot>
        <x-slot name='content'>
            <form>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label wire:key="roleEdit.name">{{ $roleEdit['name'] }}</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea id="description" class="form-control @error('roleEdit.description') is-invalid @enderror" rows="5" wire:model.defer="roleEdit.description"></textarea>
                    @error('roleEdit.description')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </form>
        </x-slot>
        <x-slot name='footer'>
            <button class="btn btn-secondary mr-2" wire:click="$set('open', false)" wire:loading.attr="disabled">Cancelar</button>
            <button class="btn btn-primary" wire:loading.attr="disabled" onclick="confirmUpdate2()">Actualizar</button>
        </x-slot>
    </x-dialog-modal>
    
    <script>
        function confirmUpdate2() {
            // Llamar al método update2 de Livewire
            @this.call('updateRole').then(response => {
                if (response) {
                    // Mostrar la alerta después de la actualización si todo es correcto
                    Swal.fire({
                        title: 'Usuario actualizado',
                        text: 'El rol ha sido actualizado exitosamente.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                }
            }).catch(error => {
                // Manejar error si es necesario
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al actualizar el rol.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    </script>





</div>
