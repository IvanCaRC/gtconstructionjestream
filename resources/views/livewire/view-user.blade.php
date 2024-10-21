<div>
    <div class="container my-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2>Perfil del Usuario</h2>
                    </div>
                    <div class="card-body d-flex">
                        <div class="mr-3 text-center">
                            @if ($user->image && $user->image !== 'users/')
                                <img src="{{ asset('storage/' . $user->image) }}" alt="Imagen del Usuario"
                                    class="rounded-circle img-fluid" style="width: 200px; height: 200px;">
                            @else
                                <img src="{{ asset('storage/StockImages/stockUser.png') }}" alt="Imagen del Usuario"
                                    class="rounded-circle img-fluid" style="width: 200px; height: 200px;">
                            @endif
                        </div>
                        <div class="pl-3">
                            <h1 class="mt-2">{{ $user->name }} {{ $user->first_last_name }}
                                {{ $user->second_last_name }}</h1>
                            <a href="#" class="d-block mb-3" wire:click="edit({{ $user->id }})">Editar
                                perfil</a>
                            <h5 class="card-title mt-4">Rol: {{ $user->role ?? 'Por definir' }}</h5>
                            <h5 class="card-title mt-3">Estado: {{ $user->status == 1 ? 'Activo' : 'Inactivo' }}</h5>
                            <h5 class="card-title mt-3">Correo: {{ $user->email ?? '' }}</h5>
                            <h5 class="card-title mt-3">Número Telefónico: {{ $user->number ?? '' }}</h5>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        @if (auth()->user()->id !== $user->id)
                        <a  href="#" class="text-danger" onclick="confirmDeletion({{ $user->id }}, '{{ $user->name }}', '{{ $user->first_last_name }}')">Eliminar usuario</a>
                        <script>
                            function confirmDeletion(userId, userName, userFirstLastName) {
                                Swal.fire({
                                    title: `¿Estás seguro de que deseas eliminar a ${userName} ${userFirstLastName}?`,
                                    text: "¡No podrás revertir esto!",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#d33',
                                    cancelButtonColor: '#3085d6',
                                    confirmButtonText: 'Sí, eliminar',
                                    cancelButtonText: 'Cancelar'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        @this.call('destroy', userId);
                                        Swal.fire(
                                            'Eliminado!',
                                            `${userName} ${userFirstLastName} ha sido eliminado.`,
                                            'success'
                                        );
                                    }
                                });
                            }
                        </script>
                        
                        @endif
                    </div>

                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-left-primary shadow mb-3" style="min-height: 100px;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Utilidades
                                    (Mensuales)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">$40,000</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-left-success shadow mb-3" style="min-height: 100px;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Utilidades
                                    (Anuales)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">$215,000</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-left-info shadow mb-3" style="min-height: 100px;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Proyectos</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">30%</div>
                                    </div>
                                    <div class="col">
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 50%"
                                                aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-left-warning shadow mb-3" style="min-height: 100px;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pendientes</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">3</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row py-10">
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Estado de Ganancias</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Dropdown Header:</div>
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="myAreaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Estado de Proyectos</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Dropdown Header:</div>
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="myPieChart"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            <span class="mr-2">
                                <i class="fas fa-circle text-primary"></i> En proceso
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-success"></i> Cancelados
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-info"></i> Concretados
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <x-dialog-modal wire:model="open">
        <x-slot name='title'>
            Editar Usuario
        </x-slot>
        <x-slot name='content'>
            <form>
                <label for="name">Imagen de usuario</label>
                <div class="form-group">
                    <div class="mb-3 d-flex align-items-center">
                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}" alt="Imagen"
                                style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px;">
                        @elseif ($userEdit['image'] && $userEdit['image'] !== 'users/')
                            <img src="{{ asset('storage/' . $userEdit['image']) }}" alt="Imagen"
                                style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px;">
                        @else
                            <img src="{{ asset('storage/StockImages/stockUser.png') }}" alt="Imagen por Defecto"
                                style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px;">
                        @endif
                        <label class="btn btn-primary">
                            Elegir archivo <input type="file" wire:model="image" name="image"
                                style="display: none;">
                        </label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="name">Nombre</label>
                        <input type="text" id="name"
                            class="form-control @error('userEdit.name') is-invalid @enderror"
                            wire:model.defer="userEdit.name">
                        @error('userEdit.name')
                            <span class="invalid-feedback">{{ 'Este campo es obligatorio' }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="first_last_name">Primer Apellido</label>
                        <input type="text" id="first_last_name"
                            class="form-control @error('userEdit.first_last_name') is-invalid @enderror"
                            wire:model.defer="userEdit.first_last_name">
                        @error('userEdit.first_last_name')
                            <span class="invalid-feedback">{{ 'Este campo es obligatorio' }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="second_last_name">Segundo Apellido</label>
                        <input type="text" id="second_last_name" class="form-control"
                            wire:model.defer="userEdit.second_last_name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email"
                        class="form-control @error('userEdit.email') is-invalid @enderror"
                        wire:model.defer="userEdit.email">
                    @error('userEdit.email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="number">Teléfono</label>
                    <input type="text" id="number" class="form-control" wire:model.defer="userEdit.number">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="status">Estado</label>
                        <select id="status" class="form-control" wire:model.defer="userEdit.status">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="department">Departamento</label>
                        <select id="department" class="form-control">
                            <option value="ventas">Ventas</option>
                            <option value="compras">Compras</option>
                            <option value="finanzas">Finanzas</option>
                        </select>
                    </div>
                </div>

            </form>
        </x-slot>
        <x-slot name='footer'>
            <button class="btn btn-secondary mr-2 disabled:opacity-50" wire:click="$set('open',false)"
                wire:loading.attr="disabled">Cancelar</button>


            <button class="btn btn-primary disabled:opacity-50" wire:loading.attr="disabled"
                onclick="confirmUpdate2()">Actualizar</button>





        </x-slot>
    </x-dialog-modal>
    <script>
        function confirmUpdate2() {
            // Aquí puedes llamar a la función update de Livewire
            @this.call('update2');

            // Mostrar la alerta después de la actualización
            Swal.fire({
                title: 'Usuario actualizado',
                text: 'El usuario ha sido actualizado exitosamente.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</div>
