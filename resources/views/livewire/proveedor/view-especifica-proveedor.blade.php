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
                            <a href="#"  onclick="window.location.href='{{ route('compras.proveedores.createProveedores') }}'" class="d-block mb-3" wire:click="">Editar Proveedor</a>
                            <h5 class="card-title mt-4 role-description">Nombre: {{ $proveedor->nombre }}</h5>
                            <h5 class="card-title mt-3">Descripción: {{ $proveedor->descripcion ?? '' }}</h5>
                            <h5 class="card-title mt-3">Correo: {{ $proveedor->correo ?? '' }}</h5>
                            <h5 class="card-title mt-3">Estado:
                                @if ($proveedor->estado)
                                    <span class="badge badge-success">Actualizado</span>
                                @else
                                    <span class="badge badge-danger">Desactualizado</span>
                                @endif
                            </h5>
                            <h5 class="card-title mt-3">Archivo de Facturación PDF:</h5>
                            @if ($proveedor->archivo_facturacion_pdf)
                                <iframe src="{{ asset('storage/' . $proveedor->archivo_facturacion_pdf) }}" width="100%" height="600px"></iframe>
                            @else
                                <p>No hay archivo de facturación disponible.</p>
                            @endif
                            <h5 class="card-title mt-3">Datos bancarios:</h5>
                            @if ($proveedor->datos_bancarios_pdf)
                                <iframe src="{{ asset('storage/' . $proveedor->datos_bancarios_pdf) }}" width="100%" height="600px"></iframe>
                            @else
                                <p>No hay archivo de bancario disponible.</p>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="#" class="text-danger" onclick="confirmDeletion({{ $proveedor->id }},'{{ $proveedor->nombre }}' )">Eliminar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function confirmDeletion(proveedorId, proveedorNombre) {
        Swal.fire({
            title: `¿Estás seguro de que deseas eliminar a ${proveedorNombre}?`,
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('eliminar', proveedorId);
                Swal.fire(
                    'Eliminado!',
                    `${proveedorNombre} ha sido eliminado.`,
                    'success'
                )
            }
        })
    }
</script>