<li class="list-group-item nivel-{{ $nivel }}">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <div class="categoria-content">
        <div>
            @if ($familia->subfamilias->count() > 0)
                <span class="icon" id="folderIconcat{{ $familia->id }}{{ $nivel }}"
                    onclick="toggleVisibility('cat{{ $familia->id }}{{ $nivel }}')" style="cursor: pointer;">
                    <i class="fas fa-folder"></i>
                </span>
            @else
                <span><i class="fas fa-file"></i></span>
            @endif
            <label class="font-weight-bold">{{ $familia->nombre }}</label>
        </div>
        <div class="categoria-buttons">
            <button class="btn btn-primary btn-sm"><i class="fas fa-pencil-alt"></i></button>
            <button class="btn btn-secondary btn-sm" wire:click="viewFamilia({{ $familia->id }})"><i class="fas fa-eye" ></i></button>
            <button class="btn btn-danger btn-sm"
                onclick="confirmDeletion({{ $familia->id }},'{{ $familia->nombre }}' )"><i
                    class="fas fa-trash-alt"></i></button>
            @if ($familia->subfamilias->count() > 0)
                <button class="btn btn-secondary btn-sm"
                    onclick="toggleVisibility('cat{{ $familia->id }}{{ $nivel }}')"><i
                        class="fas fa-chevron-down"></i></button>
            @endif
        </div>
    </div>
    @if ($familia->subfamilias->count() > 0)
        <div id="cat{{ $familia->id }}{{ $nivel }}" class="subcategorias">
            <ul class="list-group">
                @foreach ($familia->subfamilias as $subfamilia)
                    @include('livewire.familia.categoria', [
                        'familia' => $subfamilia,
                        'nivel' => $nivel + 1,
                    ])
                @endforeach
            </ul>
        </div>
    @endif
</li>

<script>
    function confirmDeletion(idFamilia, familiasName) {
        @this.call('obtenerSubfamiliasActivas', idFamilia);
        Swal.fire({
            title: `¿Estás seguro de que deseas eliminar a ${familiasName}?`,
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si tiene subfamilias 
                if (@this.subfamilias.length > 0) {
                    Swal.fire({
                        title: 'Advertencia!',
                        text: 'Esta familia tiene subfamilias. Estas también serán eliminadas.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Llamar al método de verificación para ver si la familia o subfamilias están asignadas
                            @this.call('verificarAsignacion', idFamilia).then((asignada) => {
                                if (asignada) {
                                    // Si está asignada, mostrar mensaje de error
                                    Swal.fire(
                                        'No se puede eliminar',
                                        'Esta familia o alguna de sus subfamilias está asignada a proveedores o productos, no se puede eliminar.',
                                        'error'
                                    );
                                } else {
                                    // Si no está asignada, proceder con la eliminación
                                    @this.call('eliminarFamiliaConSubfamilias', idFamilia);
                                    Swal.fire(
                                        'Eliminado!',
                                        `${familiasName} y sus subfamilias han sido eliminadas.`,
                                        'success'
                                    );
                                }
                            });
                        } else {
                            // Si el usuario cancela, mostrar mensaje de cancelación
                            Swal.fire(
                                'Cancelado',
                                'La eliminación ha sido cancelada.',
                                'info'
                            );
                        }
                    });
                } else {
                    // Si no tiene subfamilias, eliminar la familia directamente
                    @this.call('eliminar', idFamilia);
                    Swal.fire(
                        'Eliminado!',
                        `${familiasName} ha sido eliminado.`,
                        'success'
                    );
                }


            }
        })
    }
</script>
