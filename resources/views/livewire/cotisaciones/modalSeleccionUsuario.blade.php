<div>
    <x-dialog-modal wire:model="openModalAsignarUsuario">
        <x-slot name='title'>
            <h2 class="text-xl font-semibold text-gray-800">Asignar Proyecto a Usuario</h2>
        </x-slot>

        <x-slot name='content'>
            <div class="p-4">
                @if (!$selecionarOtroUsuario)
                    <div class="flex flex-col items-center gap-4">
                        <button class="btn btn-outline-success px-4 py-2 fw-bold shadow-sm"
                            wire:click="seleccionarususarioactual({{ Auth::id() }})">
                            <i class="fas fa-user-check me-2"></i> Seleccionar mi usuario
                        </button>

                        <button class="btn btn-outline-primary px-4 py-2 fw-bold shadow-sm"
                            wire:click="selecionactivadeotrousuario">
                            <i class="fas fa-user-plus me-2"></i> Seleccionar otro usuario
                        </button>
                    </div>
                @endif

                @if ($selecionarOtroUsuario)
                    <div class="mt-4">
                        <div class="mb-3">
                            <input type="text" wire:model="searchTearmUsuario" class="form-control rounded-lg shadow-sm"
                                placeholder="🔍 Buscar Usuario..." wire:keydown='obtenerUsuarios' />
                        </div>

                        @if ($searchTearmUsuario)
                            @if ($usuariosAsignables->count() > 0)
                                <ul class="list-group border rounded-lg shadow-sm">
                                    @foreach ($usuariosAsignables as $usuario)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span class="text-gray-700 font-medium">
                                                {{ $usuario->name }} {{ $usuario->first_last_name }} {{ $usuario->second_last_name }} <br>
                                                <small class="text-gray-500">({{ $usuario->email }})</small>
                                            </span>
                                            <button class="btn btn-sm btn-success shadow-sm"
                                                wire:click="seleccionarususarioactual({{ $usuario->id }})">
                                                <i class="fas fa-check-circle me-1"></i> Seleccionar
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-danger mt-2 text-center font-semibold">
                                    🚫 No hay usuarios disponibles. Verifica tu busqueda o crea un nuevo usuario.
                                </p>
                            @endif
                        @endif
                    </div>
                @endif
            </div>
        </x-slot>

        <x-slot name='footer'>
            <button type="button" class="btn btn-outline-danger shadow-sm" wire:click="cancelarAsignacion">
                <i class="fas fa-times-circle me-1"></i> Cancelar
            </button>
        </x-slot>
    </x-dialog-modal>
</div>
