<div>
    <x-dialog-modal wire:model="openModalAsignarUsuario">
        <x-slot name='title'>
            Asignar proyecto a usuario
        </x-slot>

        <x-slot name='content'>

            <div>
                @if (!$selecionarOtroUsuario)
                    <div class="form-group">
                        <div>
                            <button class="btn btn-sm btn-primary" wire:click="seleccionarususarioactual({{ Auth::id() }})">
                                Seleccionar mi usuario
                            </button>
                            <div>
                                <br>
                            </div>
                            <button class="btn btn-sm btn-primary" wire:click="selecionactivadeotrousuario">
                                Seleccionar otro usuario
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <div>
                @if ($selecionarOtroUsuario)
                    <div>
                        <div class="mb-3">
                            <input type="text" wire:model="searchTearmUsuario" class="form-control"
                                placeholder="Buscar Usuario..." wire:keydown='obtenerUsuarios' />
                        </div>

                        @if ($searchTearmUsuario)
                            @if ($usuariosAsignables->count() > 0)
                                <ul class="list-group">

                                    @foreach ($usuariosAsignables as $usuario)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>{{ $usuario->name }} {{ $usuario->first_last_name }}
                                                {{ $usuario->second_last_name }} <br>({{ $usuario->email }})</span>
                                            <button class="btn btn-sm btn-success"
                                                wire:click="seleccionarususarioactual({{ $usuario->id }})">
                                                Seleccionar
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-danger mt-2">No hay usuarios disponibles. Por favor, crea uno.</p>
                            @endif
                        @endif
                    </div>
                @endif
            </div>




        </x-slot>

        <x-slot name='footer'>
            <button type="button" class="btn btn-secondary" wire:click="cancelarAsignacion">Cancelar</button>
        </x-slot>
    </x-dialog-modal>
</div>
