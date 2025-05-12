<div>
    <x-dialog-modal wire:model="openModalCrearOrdenCompra">
        <x-slot name='title'>
            Crear Orden de compra
        </x-slot>

        <x-slot name='content'>
            <div class="mb-4">
                <label class="block font-medium">Ingresa forma de pago:</label>
                <select id="unidad" wire:model="metodoPago" class="form-control"
                    wire:change="asignarMetodoPago($event.target.value)">
                    <option value="1">Deposito</option>
                    <option value="2">Efectivo</option>
                    <option value="3">Transferencia</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block font-medium">Ingresa la modalidad:</label>
                <select id="unidad" wire:model="modalida" class="form-control"
                    wire:change="asignarModalida($event.target.value)">
                    <option value="1">PPD</option>
                    <option value="2">PUE</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block font-medium">Uso de CFDI:</label>
                <select wire:model="usoCfdi" class="form-control">
                    <option value="">-- Selecciona un uso de CFDI --</option>
                    @foreach ($usosCfdi as $clave => $descripcion)
                        <option value="{{ $clave }}">{{ $clave }} - {{ $descripcion }}</option>
                    @endforeach
                </select>
                @error('usoCfdi') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                
            </div>
        </x-slot>
        <x-slot name='footer'>
            <button type="button" class="btn btn-secondary mr-2" wire:click="cerrarModal" wire:loading.attr="disabled">
                Cancelar
            </button>
            <button type="button" class="btn btn-primary" onclick="confirmarPago()" wire:loading.attr="disabled">
                Crear
            </button>
        </x-slot>
    </x-dialog-modal>

    <script>
        function confirmarPago() {
            Swal.fire({
                title: 'Confirmar creación de orden de compra',
                html: 'No se puede deshacer esta acción',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('createOrdenCompra').then(() => {
                        Swal.fire({
                            title: 'Órdenes de compra creadas',
                            html: 'Consúltalas en la vista de órdenes de compra',
                            icon: 'success'
                        });
                    });
                }
            });
        }
    </script>

</div>
