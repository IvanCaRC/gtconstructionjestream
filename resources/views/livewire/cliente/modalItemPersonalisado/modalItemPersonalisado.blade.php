<div>
    <x-dialog-modal wire:model="openModalItemPersonalisado">
        <x-slot name='title'>
            Registrar Nuevo Item Personalizado
        </x-slot>
        <x-slot name='content'>
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" class="form-control" wire:model.defer="nombreItem">
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-10">
                        <label for="unidad">Unidad</label>
                        <input type="text" id="unidad" class="form-control" wire:model.defer="unidadItem">
                    </div>
                    <div class="col-md-2">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" id="cantidad" class="form-control" wire:model.defer="cantidadItem"
                            min="1">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" class="form-control" wire:model.defer="descripcionItem" rows="7"></textarea>
            </div>
        </x-slot>
        <x-slot name='footer'>
            <button type="button" class="btn btn-secondary mr-2" wire:click="cancelar">Cancelar</button>
            <button type="button" class="btn btn-primary mr-2" wire:click="saveItemPersonalizado">Guardar Item</button>
        </x-slot>
    </x-dialog-modal>
</div>
