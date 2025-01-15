<div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="container my-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2>Detalle Proveedor</h2>
                    </div>
                    <div class="card-body d-flex">
                        <div class="pl-3">
                            <a href="#" wire:click="" class="d-block mb-3" wire:click="">Editar Proveedor</a>
                            <h5 class="card-title mt-4 role-description">Nombre:
                                {{ $proveedor->nombre }}
                            </h5>
                            <h5 class="card-title mt-3">Descripcion: {{ $proveedor->descripcion ?? '' }}</h5>
                            <h5 class="card-title mt-3">Correo: {{ $proveedor->correo ?? '' }}</h5>
                            <h5 class="card-title mt-3">Estado:
                                @if ($proveedor->estado)
                                    <span class="badge badge-success">Actualizado</span>
                                @else
                                    <span class="badge badge-danger">Desactualizado</span>
                                @endif
                            </h5>

                            Aqui
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="#" class=" text-danger" onclick="">Eliminar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
