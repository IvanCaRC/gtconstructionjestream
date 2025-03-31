<div>
    <x-dialog-modal wire:model="openModalItemPersonalisado">
        <x-slot name='title'>
            Registrar Nuevo Item Personalizado
        </x-slot>
        <x-slot name='content'>
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" class="form-control" wire:model.defer="nombreProyecto">
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-10">
                        <label for="nombre">Unidad</label>
                        <input type="text" id="nombre" class="form-control" wire:model.defer="nombreProyecto">
                    </div>
                    <div class="col-md-2">
                        <label for="nombre">Cantidad</label>
                        <input type="text" id="nombre" class="form-control" wire:model.defer="nombreProyecto">
                    </div>
                </div>

            </div>

            <div class="form-group">
                <label for="nombre">Descripcion</label>
                <textarea id="lista" class="form-control" wire:model.lazy="listaACotizarTxt" rows="7"></textarea>
            </div>

        </x-slot>
        <x-slot name='footer'>
            <button type="button" class="btn btn-secondary mr-2 disabled:opacity-50" wire:click="cancelar"
            wire:loading.attr="disabled">Cancelar</button>
        <button type="button" class="btn btn-primary disabled:opacity-50" wire:loading.attr="disabled"
            >Agregar proyecto</button>
        </x-slot>
    </x-dialog-modal>
</div>
