<div>
    <div class="text-left mb-3">
        <button class="btn btn-custom" onclick="location.href='/ruta-para-agregar-usuario'"
            style="background-color: #4c72de; color: white;">Agregar Usuario</button>
    </div>

    <x-dialog-modal wire:model="open">
        <x-slot name='title'>
            Título del Modal
        </x-slot>
        <x-slot name='content'>
            Contenido del Modal
        </x-slot>
        <x-slot name='footer'>
            Pie de página del Modal
        </x-slot>
    </x-dialog-modal>
</div>
