<div class="form-group">
    <label for="nombre">Nombre</label>
    <input type="text" id="nombre" class="form-control" placeholder="Nombre">
</div>
<div class="form-group">
<label for="descripcion">Descripción</label>
<textarea rows="5" id="descripcion" class="form-control" placeholder="Descripción"></textarea>
</div>

<div class="form-group">
    <label for="email">Correo</label>
    <input type="email" id="email" class="form-control" placeholder="Correo Electrónico">
</div>

<div class="form-group">
    <label for="rfc">RFC</label>
    <input type="text" id="rfc" class="form-control" placeholder="RFC">
</div>

<div class="form-group">
    <label>Teléfonos</label>
    <input type="text" id="telefono" class="form-control" placeholder="Teléfono">
    <div id="telefonos-container"></div>
    <button type="button" id="add-telefono" class="btn btn-primary mt-2" style="width: 200px;">Agregar
        otro teléfono</button>
</div>

<div class="form-group">
    <label for="archivosFacturacion">Archivos de facturación</label>
    <div class="file-upload" id="upload-facturacion">
        <span class="file-upload-icon">&#x1F4C2;</span>
        <span class="file-upload-text">Buscar archivos<br>Arrastre y suelte archivos aquí</span>
        <input type="file" id="archivosFacturacion" class="form-control-file" accept=".pdf" style="display: none;">
    </div>
</div>

<div class="form-group">
    <label for="archivosBancarios">Datos Bancarios</label>
    <div class="file-upload" id="upload-bancarios">
        <span class="file-upload-icon">&#x1F4C2;</span>
        <span class="file-upload-text">Buscar archivos<br>Arrastre y suelte archivos aquí</span>
        <input type="file" id="archivosBancarios" class="form-control-file" accept=".pdf" style="display: none;">
    </div>
</div>

<div class="form-group">
    <label for="archivosBancarios">Familias</label>
    <div id="no-families-selected" style="display: none;">No hay familias seleccionadas</div>
</div>

<div class="form-group">
    <button type="button" id="add-family" class="btn btn-primary" style="width: 200px;">Agregar
        Familia</button>
</div>

<div class="form-group">
    <button type="button" id="add-position" class="btn btn-primary" style="width: 200px;">Seleccionar
        ubicación</button>
</div>

<button type="button" onclick="confirmSave()" class="btn btn-primary mt-3" style="width: 200px;">Crear
    proveedor</button>
