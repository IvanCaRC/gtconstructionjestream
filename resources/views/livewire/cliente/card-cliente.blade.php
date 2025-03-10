<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            
            <div class="row col-md-3"> 
                <button type="button" class="btn-icon">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h3 class="ml-3">Datos de cliente</h3>
            </div>
            <div class="col-md-7"></div>
            <div class="col-md-1">
                <a href="#" class="text-danger"
                            {{-- onclick="confirmDeletion({{ $proveedor->id }}, '{{ $proveedor->nombre }}')" --}}
                            >Eliminar</a>
            </div>
            <div class="col-md-1">
                <a href="#" class="d-block"
                            {{-- wire:click="editProveedor({{ $proveedor->id }})" --}}
                            >Editar cliente</a>
            </div>
        </div>
        
    </div>
    <div class="card-body">
        <div>
            <h2>{{ $clienteEspecifico->nombre }}</h2>
        </div>
        <div class="row">
            <div class="col-md-3 ">
                <h4>Datos generales</h4>
                <div>
                    <Span>Correo: </Span>
                    <label>{{ $clienteEspecifico->correo }}</label>
                </div>
                <div>
                    <Span>RFC: </Span>
                    <label>{{ $clienteEspecifico->rfc }}</label>
                </div>
            </div>
            <div class="col-md-2 ">
                @if ($telefonos)
                    <h4>Telefonos</h4>
                    @foreach ($telefonos as $index => $telefono)
                        <div >
                            <label class="enunciado-label">
                                {{ $telefono['nombre'] }}:
                            </label>
                            <label>
                                {{ $telefono['numero'] }}
                            </label>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="col-md-2 ">
                @if ($cuentas)
                    <h4>Cuentas Bancarias</h4>
                    @foreach ($cuentas as $index => $cuenta)
                        <div >
                            <label class="enunciado-label">
                                {{ $cuenta['titular'] }}:
                            </label>
                            <label>
                                {{ $cuenta['numero'] }}
                            </label>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="col-md-2 ">
                @if ($claves)
                    <h4>Claves Bancarias</h4>
                    @foreach ($claves as $index => $clave)
                        <div>
                            <label class="enunciado-label">
                                {{ $clave['titular'] }}:
                            </label>
                            <label>
                                {{ $clave['numero'] }}
                            </label>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="col-md-3 ">
                <h4>Direcciones</h4>
                @if ($clienteEspecifico->direcciones->count() > 0)
                    @foreach ($clienteEspecifico->direcciones as $direccion)
                        {{ $direccion->estado }}, {{ $direccion->ciudad }}...<br>
                    @endforeach
                @else
                    N/A
                @endif
            </div>
        </div>
    </div>
</div>