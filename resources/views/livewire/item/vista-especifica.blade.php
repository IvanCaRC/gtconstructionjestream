<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div>
        <div>
            <div>
                <div class="card">
                    <div class="card-header">
                        <h2>Vista especifica de item {{ $item->nombre }}</h2>
                    </div>
                    <div class="card-body">

                        <div class="container">
                            <div class="row">
                                <!-- Área de carga de la imagen -->


                                <div class="col-md-4 text-center">
                                    <label for="name">Imagen de item</label>
                                    <div class="form-group">


                                        <div class="form-group text-center mt-3">




                                            @if ($imagenesCargadas == null)
                                                <div class="imagen-predeterminada">
                                                    <span class="file-upload-icon">&#128247;</span>
                                                    <span class="file-upload-text">Sin imagenes que mostrar</span>
                                                </div>
                                            @else
                                                @foreach ($imagenesCargadas as $index => $imaCarg)
                                                    {{-- <label for="">{{$imaCarg}}1</label> --}}
                                                    <div class="mi-div"
                                                        style="padding: 20px; display: inline-block; position: relative;">
                                                        <img src="{{ asset('storage/' . $imaCarg) }}" alt="Imagen"
                                                            class="imagen-cuadrada">


                                                    </div>
                                                @endforeach

                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">

                                        <h1 for="">{{ $item->nombre }}</h1>
                                    </div>
                                    <div class="form-group">
                                        <h3 for="">Descripcion</h3>
                                        <label for="">{{ $item->descripcion }} </label>
                                    </div>
                                    <div class="form-group">
                                        <h3 for="">Marca</h3>
                                        <label for="">{{ $itemEspecifico->marca }} </label>
                                    </div>
                                    <div class="form-group">
                                        <label> Provedor</label>
                                        <div class="input-group mb-2">

                                            @if (count($ProvedoresAsignados) > 0)
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>Nombre</th>
                                                            <th>Tiempo Mínimo de Entrega</th>
                                                            <th>Tiempo Máximo de Entrega</th>
                                                            <th>Precio de Compra</th>
                                                            <th>Unidad</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        @foreach ($ProvedoresAsignados as $index => $conexion)
                                                            @php
                                                                $conexionObjeto = (object) $conexion;
                                                            @endphp
                                                            <tr>
                                                                <style>
                                                                    .checkbox-btn {
                                                                        display: inline-flex;
                                                                        align-items: center;
                                                                        justify-content: center;
                                                                        width: 24px;
                                                                        height: 24px;
                                                                        border: 2px solid #ccc;
                                                                        border-radius: 4px;
                                                                        background-color: #fff;
                                                                        cursor: pointer;
                                                                        transition: all 0.2s ease;
                                                                    }

                                                                    .checkbox-btn.selected {
                                                                        border-color: #4caf50;
                                                                        background-color: #4caf50;
                                                                        color: #fff;
                                                                    }

                                                                    .checkbox-btn:hover {
                                                                        border-color: #999;
                                                                    }

                                                                    .checkbox-btn.selected:hover {
                                                                        background-color: #45a045;
                                                                        border-color: #45a045;
                                                                    }

                                                                    .checkbox-icon {
                                                                        font-size: 16px;
                                                                    }
                                                                </style>

                                                                <td>
                                                                    <span
                                                                        class="badge {{ $conexionObjeto->estado == 1 ? 'bg-success' : 'bg-danger' }}">
                                                                        {{ $conexionObjeto->estado == 1 ? 'Seleccionado' : 'Deseleccionado' }}
                                                                    </span>
                                                                </td>
                                                                <td>{{ $conexionObjeto->proveedor_nombre }}</td>
                                                                <td><label>{{ $ProvedoresAsignados[$index]['tiempo_minimo_entrega'] }}</label>
                                                                </td>
                                                                <td><label>{{ $ProvedoresAsignados[$index]['tiempo_maximo_entrega'] }}</label>
                                                                </td>
                                                                <td><label>{{ $ProvedoresAsignados[$index]['precio_compra'] }}</label>
                                                                </td>
                                                                <td><label>{{ $ProvedoresAsignados[$index]['unidad'] }}</label>
                                                                </td>

                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <div class="no-familias-seleccionadas w-100">
                                                    No hay provedores seleccionadas
                                                </div>
                                            @endif

                                        </div>

                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label>Familias</label>
                                            <div class="input-group mb-2">
                                                @if (count($familiasSeleccionadas) > 0)
                                                    @foreach ($familiasSeleccionadas as $index => $familia)
                                                        <div class="w-100 d-flex align-items-center mb-2">
                                                            <div class="flex-grow-1">
                                                                {{ $familia['nombre'] }}
                                                            </div>
    
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="no-familias-seleccionadas w-100">
                                                        No hay familias seleccionadas
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <h4 for="unidad">Unidad</h4>
                                            <label>{{ $itemEspecifico->unidad }}/label>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="stock" class="mr-2">Stock Acutal del Producto</label>
                                            <br>
                                            <label for="">{{ $itemEspecifico->stock }}</label>
                                        </div>

                                    </div>
                                    <label>El parametro por el que se hacen los calculos se basan en el proveedor seleccionado</label>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2 mb-3">
                                                <label for="cantidad_piezas_mayoreo" class="mr-2">Cant. Piezas
                                                    Mayoreo</label>
                                                <label for="">{{ $itemEspecifico->cantidad_piezas_mayoreo  }}</label>
                                            </div>

                                            <div class="col-md-2 mb-3">
                                                <label for="porcentaje_venta_mayorista" class="mr-2">% Venta
                                                    Mayorista</label>
                                                    <label for="">{{ $itemEspecifico->porcentaje_venta_mayorista  }}</label>
                                            </div>

                                            <div class="col-md-2 mb-3">
                                                <label for="porcentaje_venta_minorista" class="mr-2">% Venta
                                                    Minorista</label>
                                                    <label for="">{{ $itemEspecifico->porcentaje_venta_minorista  }}</label>
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <label for="precio_venta_mayorista" class="mr-2"><br>Precio
                                                    Mayorista</label>
                                                    <label for="">{{ $itemEspecifico->precio_venta_mayorista  }}</label>
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <label for="precio_venta_minorista" class="mr-2"><br>Precio
                                                    Minorista</label>
                                                    <label for="">{{ $itemEspecifico->precio_venta_minorista  }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <style>
                                            .enunciado-label {
                                                color: #333; /* Color más oscuro */
                                                font-size: 1.25rem; /* Tamaño de letra más grande */
                                                font-weight: bold; /* Texto en negritas */
                                                margin-right: 5px; /* Espacio entre labels */
                                            }
                                            .input-group {
                                                display: flex;
                                                align-items: center; /* Alinea verticalmente los labels */
                                            }
                                        </style>
                                    
                                        @foreach ($especificaciones as $index => $especificacion)
                                            <div class="input-group mb-2">
                                                <label class="enunciado-label">
                                                    {{ $especificacion['enunciado'] }}:
                                                </label>
                                                <label>
                                                    {{ $especificacion['concepto'] }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    
                                    
                                    
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
