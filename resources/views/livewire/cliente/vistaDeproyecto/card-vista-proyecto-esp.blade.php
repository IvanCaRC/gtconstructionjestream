<div>
    <div class="ml-3">
        <div class="row align-items-center">
            <h3 class="">Detalles de proyecto</h3>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-11">
                    <h3>Nombre: {{ $proyecto->nombre }}</h3>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary d-block mb-3" wire:click="editarProyecto({{ $proyecto->id }})">
                        Editar proyecto
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-1 ">
                    <h5>Preferencia</h5>
                    <div>
                        <label>
                            {{ $proyecto->preferencia == 1 ? 'Tiempo de entrega' : ($proyecto->preferencia == 2 ? 'Precio' : 'Sin preferencia') }}
                        </label>

                    </div>
                </div>
                <div class="col-md-1 ">
                    <h5>Tipo</h5>
                    <div>
                        <label>
                            {!! $proyecto->tipo == 0
                                ? '<span class="badge badge-secondary">Obra</span>'
                                : ($proyecto->tipo == 1
                                    ? '<span class="badge badge-secondary">Suministro</span>'
                                    : '<span class="badge badge-secondary">Estado desconocido</span>') !!}
                        </label>
                    </div>
                </div>
                <div class="col-md-2 ">
                    <h5>Proceso</h5>
                    <div>
                        <label>
                            {!! $proyecto->proceso == 0
                                ? '<span class="badge badge-primary">Creando lista a cotizar</span>'
                                : ($proyecto->proceso == 1
                                    ? '<span class="badge badge-primary">Creando cotización</span>'
                                    : ($proyecto->proceso == 2
                                        ? '<span class="badge badge-primary">Cotizado</span>'
                                        : ($proyecto->proceso == 3
                                            ? '<span class="badge badge-warning">Esperando pago</span>'
                                            : ($proyecto->proceso == 4
                                                ? '<span class="badge badge-primary">Pagado/span>'
                                                : ($proyecto->proceso == 5
                                                    ? '<span class="badge badge-warning">Preparando</span>'
                                                    : ($proyecto->proceso == 6
                                                        ? '<span class="badge badge-warning">En proceso de entrga</span>'
                                                        : ($proyecto->proceso == 7
                                                            ? '<span class="badge badge-success">Venta terminada</span>'
                                                            : '<span class="badge badge-secondary">Estado desconocido</span>'))))))) !!}
                        </label>
                    </div>
                </div>
                <div class="col-md-1 ">
                    <h5>Estado</h5>
                    <div>
                        {!! $proyecto->estado == 1
                            ? '<span class="badge badge-success">Activo</span>'
                            : ($proyecto->estado == 2
                                ? '<span class="badge badge-warning">Inactivo</span>'
                                : ($proyecto->estado == 3
                                    ? '<span class="badge badge-danger">Cancelado</span>'
                                    : '<span class="badge badge-secondary">Estado desconocido</span>')) !!}
                    </div>
                </div>
                <div class="col-md-1 ">
                    <h5>Fecha</h5>
                    <div>
                        {{ $proyecto->fecha }}
                    </div>
                </div>

                <div class="col-md-1 ">
                    <h5>Listas</h5>
                    <div>
                        {{ $proyecto->listas }}
                    </div>
                </div>
                <div class="col-md-2 ">
                    <h5>Cotizaciones</h5>
                    <div>
                        {{ $proyecto->cotisaciones }}
                    </div>
                </div>
                <div class="col-md-2 ">
                    <h5>Ordenes de venta</h5>
                    <div>
                        {{ $proyecto->ordenes }}
                    </div>
                </div>
            </div>
            <br>
            <div>
                <div>
                    @if ($proyecto->tipo == 1)
                        <div class="row">
                            <div class="col-md-3 ">
                                <h5>Archivo de suministro</h5>
                                @if ($proyecto->archivo)
                                    <a href="{{ asset('storage/' . $proyecto->archivo) }}" target="_blank"
                                        class="btn btn-secondary">
                                        Ver Archivo de suministro
                                    </a>
                                @else
                                    <p>No hay archivo de suministro disponible.</p>
                                @endif
                            </div>
                            <div class="col-md-3 text-center">
                                <h5>Lista a cotizar</h5>
                                @if ($pdfUrl)
                                    <div class="btn-group">
                                        <a href="{{ $pdfUrl }}" target="_blank" class="btn btn-outline-secondary">
                                            📄 Ver PDF
                                        </a>
                                        <a href="{{ $pdfUrl }}" download="reporte_proyecto.pdf"
                                            class="btn btn-outline-primary">
                                            ⬇️ Descargar PDF
                                        </a>
                                    </div>
                                @else
                                    <p>No hay lista de items adjunta.</p>
                                @endif
                            </div>
                        </div>
                    @elseif ($proyecto->tipo == 0)
                        <div class="row">
                            <div class="col-md-3 ">
                                <h5>Plano de obra o archivo adicional</h5>
                                @if ($proyecto->archivo)
                                    <a href="{{ asset('storage/' . $proyecto->archivo) }}" target="_blank"
                                        class="btn btn-secondary">
                                        Ver plano o archivo adiciona
                                    </a>
                                @else
                                    <p>No hay archivo.</p>
                                @endif
                            </div>
                            <div class="col-md-3">
                                <h5>Datos adicionales</h5>
                                @if (!empty($adicionales) && is_array($adicionales))
                                    @foreach ($adicionales as $index => $adicionale)
                                        <div class="row">
                                            <div class="col-md-6">
                                                {{ $adicionale['estructura'] ?? 'N/A' }}
                                            </div>
                                            <div class="col-md-6">
                                                {{ $adicionale['cantidad'] ?? 'N/A' }}
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p>No hay datos adicionales registrados.</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if (!empty($datosGenrales) && is_array($datosGenrales))
                                    @foreach ($datosGenrales as $index => $datosGenrale)
                                        <div class="row">
                                            <div class="col-md-3">
                                                <h5>(A)Frente</h5>
                                                <div>
                                                    {{ !empty($datosGenrale['frente']) ? $datosGenrale['frente'] : '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <h5>(B)Fondo</h5>
                                                <div>
                                                    {{ !empty($datosGenrale['fondo']) ? $datosGenrale['fondo'] : '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <h5>(C)Altura Techo</h5>
                                                <div>
                                                    {{ !empty($datosGenrale['alturaTecho']) ? $datosGenrale['alturaTecho'] : '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <h5>Área total</h5>
                                                <div>
                                                    {{ !empty($datosGenrale['areaTotal']) ? $datosGenrale['areaTotal'] : '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <h5>Área de muros</h5>
                                                <div>
                                                    {{ !empty($datosGenrale['alturaMuros']) ? $datosGenrale['alturaMuros'] : '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <h5>Canalón</h5>
                                                <div>
                                                    {{ !empty($datosGenrale['canalon']) ? $datosGenrale['canalon'] : '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <h5>Perimetral</h5>
                                                <div>
                                                    {{ !empty($datosGenrale['perimetral']) ? $datosGenrale['perimetral'] : '-' }}
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <h5>Caballete</h5>
                                                <div>
                                                    {{ !empty($datosGenrale['caballete']) ? $datosGenrale['caballete'] : '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p>No hay datos generales disponibles.</p>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>
            </div>
            <br>
            <div>
                <div>
                    <h5>Direcciones</h5>
                    <div>
                        @if ($proyecto->direccion)
                            {{ $proyecto->direccion->calle }}, {{ $proyecto->direccion->numero }},
                            {{ $proyecto->direccion->colonia }}, {{ $proyecto->direccion->ciudad }},
                            {{ $proyecto->direccion->estado }}, {{ $proyecto->direccion->pais }}, C.P.
                            {{ $proyecto->direccion->cp }}
                        @else
                            Sin dirección
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
