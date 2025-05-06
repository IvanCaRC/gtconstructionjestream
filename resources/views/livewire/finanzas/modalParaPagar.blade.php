<div>
    <x-dialog-modal wire:model="openModalPagar">
        <x-slot name='title'>
           Pagar orden de compra
        </x-slot>

        <x-slot name='content'>
            <div class="mb-4">
                <label class="block font-medium">Total a pagar:</label>
                <p class="text-xl font-bold">${{ number_format($montoPagar ?? 0, 2) }} MXN</p>
            </div>
            <div class="mb-4">
                <label for="cantidadPagar" class="block font-medium">Cantidad a pagar:</label>
                <input type="number" id="cantidadPagar"
                    class="form-control @error('cantidadPagar') border-red-500 @enderror"
                    wire:model.defer="cantidadPagar" step="0.01" min="0.01"
                    max="{{ $ordenVentaSelecionada->montoPagar ?? 0 }}" required>

                @error('cantidadPagar')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </x-slot>

        <x-slot name='footer'>
            <button type="button" class="btn btn-secondary mr-2" wire:click="cerrarModal" wire:loading.attr="disabled">
                Cancelar
            </button>
            
            <button type="button" class="btn btn-primary" onclick="confirmarPago({{$montoPagar}})" wire:loading.attr="disabled">
                <span wire:loading.remove>Pagar</span>
                <span wire:loading>
                    <i class="fas fa-spinner fa-spin"></i> Procesando...
                </span>
            </button>
        </x-slot>
    </x-dialog-modal>


    <script>
        function confirmarPago(montoPagar) {
            const cantidad = parseFloat(document.getElementById('cantidadPagar').value);
            console.log("Monto pagar:", montoPagar, "Cantidad ingresada:", cantidad);

            if (!cantidad || cantidad <= 0) {
                Swal.fire('Error', 'Ingrese una cantidad válida', 'error');
                return;
            }

            if (cantidad > montoPagar) {
                Swal.fire('Error', 'La cantidad no puede ser mayor al monto pendiente', 'error');
                return;
            }

            const esPagoCompleto = cantidad >= montoPagar;
            const titulo = esPagoCompleto ?
                "¿Confirmar pago completo?" :
                "¿Registrar abono parcial?";

            const html = esPagoCompleto ?
                `Se marcará la orden como <strong>completamente pagada</strong>.` :
                `Se registrará un abono de <strong>$${cantidad.toFixed(2)}</strong>.<br>
               Saldo restante: <strong>$${(montoPagar - cantidad).toFixed(2)}</strong>`;

            Swal.fire({
                title: titulo,
                html: html,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('aceptar').then(result => {
                        if (result) {
                            Swal.fire({
                                title: esPagoCompleto ? '¡Pago completado!' : '¡Abono registrado!',
                                html: esPagoCompleto ?
                                    'La orden ha sido liquidada completamente.' :
                                    `Abono registrado:<br><strong>$${cantidad.toFixed(2)} MXN</strong>`,
                                icon: 'success'
                            });
                        }
                    });
                }
            });
        }
    </script>

</div>
