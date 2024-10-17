<div>
    <div class="text-left mb-3">
        <button class="btn btn-custom" onclick="location.href='/ruta-para-agregar-usuario'"
            style="background-color: #4c72de; color: white;">Agregar Usuario</button>
    </div>

    <x-jet-dialog-modal wire:model="open">
        <x-slot name='title'>

        </x-slot>
        <x-slot name='content'>

        </x-slot>
        <x-slot name='footer'>

        </x-slot>
    </x-jet-dialog-modal>
</div>
