<div>
    <x-dialog-modal wire:model="openModalCancelarProyecto">
        <x-slot name='title'>
            <div class="text-center p-3 rounded" style="background-color: #2d2d2d; color: #ffffff;">
                Cancelación del Proyecto
            </div>
        </x-slot>
        <x-slot name='content'>
            <div class="bg-white p-6 rounded shadow-lg w-96">
                <!-- Título con el nombre del proyecto -->
                <h2 class="text-lg font-bold text-center">
                    Cancelación para: <strong>{{ $nombreProyecto }}</strong>
                </h2>

                <!-- Motivo (por defecto "Cancelación del proyecto") -->
                <div class="mt-4">
                    <label class="block text-sm font-semibold">Motivo de la cancelación</label>
                    <select wire:model="motivo_finalizacion"
                        class="form-control @error('motivo_finalizacion') is-invalid @enderror"
                        wire:change="$set('motivo_finalizacion', $event.target.value)">
                        <option value="">Seleccione el motivo de la cancelacion</option>
                        <option value="Inconformidad con el precio">Inconformidad con el precio</option>
                        <option value="Inconformidad con el tiempo de entrega">Inconformidad con el tiempo de entrega
                        </option>
                        <option value="No contamos con los materiales solicitados">No contamos con los materiales
                            solicitados</option>
                        <option value="No se cumplen especificaciones del cliente">No se cumplen especificaciones del
                            cliente</option>
                        <option value="otro">Otro (a especificar)</option>
                    </select>
                </div>
                <small class="form-text text-muted">Selecciona el motivo por el cual se solicita la cancelacion del
                    proyecto.</small>

                <!-- Campo de texto que aparece solo si el usuario selecciona "Otro (a especificar)" -->
                @if ($motivo_finalizacion === 'otro')
                    <div class="mt-4">
                        <label class="block text-sm font-semibold">Especifica el motivo</label>
                        <input type="text" wire:model="motivo_finalizacion_alterno" class="border rounded w-full p-2"
                            placeholder="Especifica el motivo aquí...">
                    </div>
                @endif
                <!-- Área de texto para detalles de cancelación -->
                <div class="mt-4">
                    <label class="block text-sm font-semibold">Detalles de la cancelacion</label>
                    <textarea wire:model="motivo_detallado" rows="3" class="border rounded w-full p-2"
                        placeholder="Especifica el motivo de la cancelación..."></textarea>
                </div>
                <small class="form-text text-muted">Describe con detalle el motivo de dicha cancelacion para que el
                    administrador pueda dar seguimiento al proceso de cancelacion en cuestion.</small>
                <x-slot name='footer'>
                    <button type="button" class="btn btn-secondary mr-2 disabled:opacity-50"
                        wire:click="removeCancelacion" wire:loading.attr="disabled">Cancelar</button>
                    <button type="button" class="btn btn-primary disabled:opacity-50" wire:loading.attr="disabled"
                        wire:click="enviarSolicitudCancelar">
                        Actualizar Proyecto
                    </button>
                </x-slot>
        </x-slot>
    </x-dialog-modal>
</div>
