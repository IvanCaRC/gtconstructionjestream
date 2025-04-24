<div class="form-group">
    <div class="d-flex justify-content-center align-items-center mt-2">
        <!-- Botón de menos -->
        <button class="btn btn-danger btn-sm me-2" wire:click="decrementarCantidad">-</button>

        <!-- Input de cantidad -->
        <input type="text" min="1" class="form-control text-center"
            style="width: 90px;" wire:model="cantidad" oninput="validatePhoneInput(this)">
        <!-- Botón de más -->
        <button class="btn btn-success btn-sm ms-2" wire:click="incrementarCantidad">+</button>
    </div>
    <br>
    <div>
        <button class="btn btn-success btn-custom"
            wire:click="agregarItemStockLista({{ $itemEspecifico->id }})"
            title="Agrega este item a tu lista">
            <i class="fas fa-shopping-cart"></i> Añadir a la lista
        </button>
    </div>
</div>