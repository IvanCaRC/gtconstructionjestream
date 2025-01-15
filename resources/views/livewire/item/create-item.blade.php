<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <div>
        <div>
            <div>
                <div class="card">
                    <div class="card-header">
                        <h2>Crear Nuevo Ítem</h2>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="crearItem">
                            <div class="form-group"> <label for="imagen">Imagen</label> <input type="file"
                                id="imagen" class="form-control-file" wire:model="imagen"> </div> 
                            <div class="form-group"> <label for="nombre">Nombre</label> <input type="text"
                                    id="nombre" class="form-control" wire:model="nombre" required> </div>
                            <div class="form-group"> <label for="descripcion">Descripción</label>
                                <textarea id="descripcion" class="form-control" wire:model="descripcion"></textarea>
                            </div>
                            <div class="form-group"> <label for="marca">Marca</label> <input type="text"
                                    id="marca" class="form-control" wire:model="marca" required> </div>
                            <div class="form-group"> <label for="cantidad_piezas_mayoreo">Cantidad Piezas
                                    Mayoreo</label> <input type="number" id="cantidad_piezas_mayoreo"
                                    class="form-control" wire:model="cantidad_piezas_mayoreo" required> </div>
                            <div class="form-group"> <label for="cantidad_piezas_minorista">Cantidad Piezas
                                    Minorista</label> <input type="number" id="cantidad_piezas_minorista"
                                    class="form-control" wire:model="cantidad_piezas_minorista" required> </div>
                            <div class="form-group"> <label for="porcentaje_venta">Porcentaje Venta</label> <input
                                    type="number" step="0.01" id="porcentaje_venta" class="form-control"
                                    wire:model="porcentaje_venta" required> </div>
                            <div class="form-group"> <label for="precio_venta">Precio Venta</label> <input
                                    type="number" step="0.01" id="precio_venta" class="form-control"
                                    wire:model="precio_venta" required> </div>
                            <div class="form-group"> <label for="unidad">Unidad</label> <input type="text"
                                    id="unidad" class="form-control" wire:model="unidad" required> </div>
                            <div class="form-group"> <label for="especificaciones">Especificaciones</label>
                                <textarea id="especificaciones" class="form-control" wire:model="especificaciones"></textarea>
                            </div>
                            <div class="form-group"> <label for="ficha_tecnica_pdf">Ficha Técnica PDF</label> <input
                                    type="file" id="ficha_tecnica_pdf" class="form-control-file"
                                    wire:model="ficha_tecnica_pdf"> </div>
                            <button
                                type="submit" class="btn btn-primary">Crear Ítem</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
