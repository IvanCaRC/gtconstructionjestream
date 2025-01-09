    <div>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <div class="container my-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>Detalle familias</h2>
                        </div>
                        <div class="card-body d-flex">
                            <div class="pl-3">
                                <a href="#" wire:click="editCategory({{ $familia->id }})" class="d-block mb-3" wire:click="">Editar Familia</a>
                                <h5 class="card-title mt-4 role-description">Familia Padre Directa:
                                    {{ $familiaPadre->nombre ?? 'Esta familia no tiene familia padre directa por lo que es una familia primaria' }}
                                </h5>
                                <h5 class="card-title mt-3">Nombre: {{ $familia->nombre ?? '' }}</h5>
                                <h5 class="card-title mt-3">Descripcion: {{ $familia->descripcion ?? '' }}</h5>
                                <h5 class="card-title mt-3">Nivel de jerarquia al que pertenece:
                                    {{ $familia->nivel ?? '' }}</h5>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="#" class=" text-danger"
                                onclick="confirmDeletion2({{ $familia->id }},'{{ $familia->nombre }}' )">Eliminar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container my-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>Jerarquia de subfamilias</h2>
                        </div>
                        <div class="card-body">
                            @if ($subfamilias2->count() > 0)
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="categoria-header">
                                            <div><strong>Nombre</strong></div>
                                            <div><strong>Descripción</strong></div>
                                            <div><strong>Acciones</strong></div>
                                        </div>
                                    </li>
                                    @foreach ($subfamilias2 as $subfamilia)
                                        <li class="list-group-item nivel-{{ $nivel }}">
                                            <div class="categoria-content">
                                                <div>
                                                    @if ($subfamilia->subfamilias->count() > 0)
                                                        <span class="icon"
                                                            id="folderIconcat{{ $subfamilia->id }}{{ $nivel }}"
                                                            onclick="toggleVisibility('cat{{ $subfamilia->id }}{{ $nivel }}')"
                                                            style="cursor: pointer;"> <i class="fas fa-folder"></i>
                                                        </span>
                                                    @else
                                                        <span><i class="fas fa-file"></i></span>
                                                    @endif <label
                                                        class="font-weight-bold">{{ $subfamilia->nombre }}</label>
                                                </div>
                                                <div class="categoria-buttons"> <button
                                                        class="btn btn-primary btn-sm"><i
                                                            class="fas fa-pencil-alt"></i></button> <button
                                                        class="btn btn-secondary btn-sm"
                                                        wire:click="viewFamilia({{ $subfamilia->id }})"><i
                                                            class="fas fa-eye"></i></button>
                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="confirmDeletion({{ $subfamilia->id }},'{{ $subfamilia->nombre }}' )"><i
                                                            class="fas fa-trash-alt"></i></button>
                                                    @if ($subfamilia->subfamilias->count() > 0)
                                                        <button class="btn btn-secondary btn-sm"
                                                            onclick="toggleVisibility('cat{{ $subfamilia->id }}{{ $nivel }}')"><i
                                                                class="fas fa-chevron-down"></i></button>
                                                    @endif
                                                </div>
                                            </div>
                                            @if ($subfamilia->subfamilias->count() > 0)
                                                <div id="cat{{ $subfamilia->id }}{{ $nivel }}"
                                                    class="subcategorias">
                                                    <ul class="list-group">
                                                        @foreach ($subfamilia->subfamilias as $subsubfamilia)
                                                            @include('livewire.familia.categoria', [
                                                                'familia' => $subsubfamilia,
                                                                'nivel' => $nivel,
                                                            ])
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No hay subfamilias disponibles para esta familia.</p>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function confirmDeletion2(idFamilia, familiasName) {
            // Obtener las subfamilias activas primero
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
                                // Verificar asignación de la familia y sus subfamilias
                                @this.call('verificarAsignacion', idFamilia).then((asignada) => {
                                    if (asignada) {
                                        Swal.fire(
                                            'No se puede eliminar',
                                            'Esta familia o alguna de sus subfamilias está asignada a proveedores o productos, no se puede eliminar.',
                                            'error'
                                        );
                                    } else {
                                        @this.call('eliminarFamiliaConSubfamilias', idFamilia);
                                        Swal.fire(
                                            'Eliminado!',
                                            `${familiasName} y sus subfamilias han sido eliminadas.`,
                                            'success'
                                        );
                                    }
                                });
                            } else {
                                Swal.fire(
                                    'Cancelado',
                                    'La eliminación ha sido cancelada.',
                                    'info'
                                );
                            }
                        });
                    } else {
                        // Verificar asignación de la familia antes de eliminarla
                        @this.call('verificarAsignacion', idFamilia).then((asignada) => {
                            if (asignada) {
                                Swal.fire(
                                    'No se puede eliminar',
                                    'Esta familia está asignada a proveedores o productos, no se puede eliminar.',
                                    'error'
                                );
                            } else {
                                @this.call('eliminar', idFamilia);
                                Swal.fire(
                                    'Eliminado!',
                                    `${familiasName} ha sido eliminado.`,
                                    'success'
                                );
                            }
                        });
                    }
                }
            });
        }
    </script>
    @include('livewire.familia.script')
