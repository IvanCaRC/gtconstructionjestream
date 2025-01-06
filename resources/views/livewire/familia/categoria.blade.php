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
            <button class="btn btn-primary btn-sm" wire:click="confirmDeletion('{{ $familia->id }}')"><i
                    class="fas fa-pencil-alt"></i></button>
            <button class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i></button>
            <button class="btn btn-danger btn-sm" onclick="confirmDeletion({{ $familia->id }},'{{ $familia->nombre }}' )">
                <i class="fas fa-trash-alt"></i>
            </button>
            <script>
                function confirmDeletion(idFamilia, familiasName) {
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
                            @this.call('eliminar', idFamilia);
                            Swal.fire(
                                'Eliminado!',
                                `${familiasName}  ha sido eliminado.`,
                                'success'
                            )
                        }
                    })
                }
            </script>
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
