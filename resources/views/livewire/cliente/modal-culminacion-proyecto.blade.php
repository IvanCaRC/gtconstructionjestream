<div>
    <x-dialog-modal wire:model="openModalCulminarProyecto">
        <x-slot name='title'>
            <div class="text-center p-3 rounded" style="background-color: #489ea4; color: #ffffff;">
                Culminacion de proyecto
            </div>
        </x-slot>
        <x-slot name='content'>
            <div class="bg-white p-6 rounded shadow-lg w-96">
                <!-- Título con el nombre del proyecto -->
                <h2 class="text-lg font-bold text-center">
                    Confirmo la culminacion exitosa del proyecto: <strong
                        style="color:rgb(46, 196, 9)">{{ $nombreProyecto }}</strong>
                </h2>

                <!-- Motivo (por defecto "Cancelación del proyecto") -->
                <div class="mt-4">
                    <label class="block text-sm font-semibold">Segun tu perspectiva, cual de los siguientes motivos
                        influyo en mayor medida para concretar este proyecto</label>
                    <select wire:model="motivo_finalizacion"
                        class="form-control @error('motivo_finalizacion') is-invalid @enderror"
                        wire:change="$set('motivo_finalizacion', $event.target.value)">
                        <option value="">Seleccione el motivo</option>
                        <option value="Inconformidad con el precio">Conformidad total con los precios manejados</option>
                        <option value="Inconformidad con el tiempo de entrega">Se ofrecieron los materiales exactos que
                            el cliente solicitó
                        </option>
                        <option value="No contamos con los materiales solicitados">Se manejaron tiempos de entrega
                            favorables</option>
                        <option value="No se cumplen especificaciones del cliente">Excelencia en la atencion y servicio
                            al cliente</option>
                        <option value="otro">Algun otro (a especificar)</option>
                    </select>
                    @error('motivo_finalizacion')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <small class="form-text text-muted">Selecciona el motivo que mas influyo para culminar el
                    proyecto.</small>

                <!-- Campo de texto que aparece solo si el usuario selecciona "Otro (a especificar)" -->
                @if ($motivo_finalizacion === 'otro')
                    <div class="mt-4">
                        <label class="block text-sm font-semibold">Especifica el motivo</label>
                        <input type="text" wire:model="motivo_finalizacion_alterno"
                            class="form-control @error('motivo_finalizacion_alterno') is-invalid @enderror"
                            placeholder="Especifica el motivo aquí...">

                        @error('motivo_finalizacion_alterno')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
                <!-- Área de texto para detalles de cancelación -->
                <div class="mt-4">
                    <label class="block text-sm font-semibold">Detalles de la culminacion</label>
                    <textarea wire:model="motivo_detallado" rows="3"
                        class="form-control @error('motivo_detallado') is-invalid @enderror"
                        placeholder="Especifica el motivo de la culminacion..."></textarea>
                </div>
                @error('motivo_detallado')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <small class="form-text text-muted">Describe con detalle tus impresiones acerca de lo que mas favorecio
                    para que el cliente nos otorge su preferencia.</small>
                <x-slot name='footer'>
                    <button type="button" class="btn btn-secondary mr-2 disabled:opacity-50"
                        wire:click="removeCulminacion" wire:loading.attr="disabled">Cancelar</button>
                    <button type="button" class="btn btn-success disabled:opacity-50" wire:loading.attr="disabled"
                        wire:click="enviarSolicitudCulminar">
                        Enviar solicitud
                    </button>
                </x-slot>
        </x-slot>
    </x-dialog-modal>
</div>
