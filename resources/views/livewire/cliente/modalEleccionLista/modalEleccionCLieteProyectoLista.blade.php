<div>
    <x-dialog-modal wire:model="openModalAsignarLista">
        <x-slot name='title'>
            Asignar lista a proyecto
        </x-slot>

        <x-slot name='content'>
            <div class="mb-3">
                <input type="text" wire:model="searchTearmProyecto" class="form-control"
                    placeholder="Buscar proyecto por nombre ..." wire:keydown='obtenerProyectos' />
            </div>

            @if ($searchTearmProyecto)
                @if (count($proyectosAsignables) > 0)
                    <div class="list-group">
                        @foreach ($proyectosAsignables as $proyecto)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $proyecto->nombre }}</strong> 
                                    <br>
                                    <small class="text-muted">Cliente: {{ $proyecto->cliente->nombre }}</small>
                                </div>
                                <button class="btn btn-primary btn-sm"
                                    wire:click="seleccionarProyecto({{ $proyecto->id }})">
                                    Seleccionar
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-danger mt-2">No hay proyectos disponibles. Por favor, crea un proyecto.</p>
                @endif
            @endif
        </x-slot>

        <x-slot name='footer'>
            <button type="button" class="btn btn-secondary" wire:click="cancelarAsignacion">Cancelar</button>
        </x-slot>
    </x-dialog-modal>
</div>
