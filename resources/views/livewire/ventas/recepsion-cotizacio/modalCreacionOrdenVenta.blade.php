<div>
    <x-dialog-modal wire:model="openModalOrdenVenta">
        <x-slot name='title'>
            Crear orden de venta             @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        </x-slot>
        <x-slot name='content'>
            <div class="form-group">
                <label for="nombre">Metodo de pago</label>
                <select id="unidad" wire:model="metodoPago" class="form-control"
                    wire:change="asignarMetodoPago($event.target.value)">
                    <option value="1">Deposito</option>
                    <option value="2">Efectivo</option>
                    <option value="3">Transferencia</option>
                </select>
            </div>

            <div class="form-group">
                <label for="descripcion">Forma de pago</label>
                <select id="unidad" wire:model="formaPago" class="form-control"
                    wire:change="asignarFormaPago($event.target.value)">
                    <option value="1">Parcial</option>
                    <option value="2">Total</option>
                </select>
            </div>
        </x-slot>
        <x-slot name='footer'>
            <button type="button" class="btn btn-secondary mr-2" wire:click="cerrarModal">Cancelar</button>
            <button type="button" class="btn btn-primary mr-2" wire:click="aceptar">Crear orden de venta</button>
        </x-slot>
    </x-dialog-modal>
</div>
