<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-11">
                <h2>Nombre: {{ $clienteEspecifico->nombre }}</h2>
            </div>
            <div class="col-md-1">
                <a href="#" wire:click="editCliente({{ $clienteEspecifico->id }})" class="d-block mb-3">Editar cliente</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2 ">
                <h4>RFC</h4>
                <div>
                    <label>{{ $clienteEspecifico->rfc }}</label>
                </div>
            </div>
            <div class="col-md-2 ">
                <h4>Correo</h4>
                <div>
                    <label>{{ $clienteEspecifico->correo }}</label>
                </div>
            </div>
            <div class="col-md-2 ">
                @if ($telefonos)
                    <h4 style="font-weight: bold;">Telefonos</h4>
                    @foreach ($telefonos as $index => $telefono)
                        <div>
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
                <h4>Proyectos</h4>
                <div>
                    <div>
                        <label>{{ $clienteEspecifico->proyectos }}</label>
                    </div>
                </div>
            </div>
            <div class="col-md-2 ">
                <h4>Proyectos activos</h4>
                <div>
                    <div>
                        <div>
                            <label style="color: {{ $clienteEspecifico->proyectos_activos > 0 ? 'green' : '' }};">
                                {{ $clienteEspecifico->proyectos_activos }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <br>
        </div>
        <div class="row">
            
            <div class="col-md-5 ">
                <h4>Direcciones</h4>
                @if ($clienteEspecifico->direcciones->count() > 0)
                    @foreach ($clienteEspecifico->direcciones as $direccion)
                    {{ $direccion->estado }}, {{ $direccion->ciudad }},
                    {{ $direccion->calle }}, {{ $direccion->numero }}, {{ $direccion->referencia }}<br>
                    @endforeach
                @else
                    N/A
                @endif
            </div>
            <div class="col-md-5 ">
                <h4 style="font-weight: bold;">Cuentas bancarias</h4>
                @if ($bancarios)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Banco</th>
                                <th>Titular</th>
                                <th>Cuenta</th>
                                <th>Clave</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bancarios as $index => $bancario)
                                <tr>
                                    <td class="align-middle d-none d-md-table-cell">
                                        {{ $bancario['banco'] ?? 'N/A' }}
                                    </td>
                                    <td class="align-middle d-none d-md-table-cell">
                                        {{ $bancario['titular'] ?? 'N/A' }}
                                    </td>
                                    <td class="align-middle d-none d-md-table-cell">
                                        {{ $bancario['cuenta'] ?? 'N/A' }}
                                    </td>
                                    <td class="align-middle d-none d-md-table-cell">
                                        {{ $bancario['clave'] ?? 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

    </div>
</div>
