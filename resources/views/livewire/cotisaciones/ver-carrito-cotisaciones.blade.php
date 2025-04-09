<div class="container-fluid px-4 sm:px-6 lg:px-8 py-3">
    <div class="mb-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        Resumen
                    </div>
                    <div class="col-md-6">
                        <button>crear cotisacion</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3>Items solicitados</h3>
                        @if (count($itemsDeLaLista) > 0)
                            @foreach ($itemsDeLaLista as $itemEspecifico)
                                <div>
                                    <hr>
                                </div>

                                <div class="row">


                                    <div class="col-md-4">
                                        <div>
                                            <div class="card-body text-center d-flex justify-content-center align-items-center"
                                                style="cursor: pointer;">
                                                <!-- Imagen del Item -->
                                                @if (empty($itemEspecifico->image))
                                                    <!-- Mostrar ícono o mensaje alternativo -->
                                                    <div class="no-image-icon"
                                                        style="width: 200px; height: 200px; display: flex; justify-content: center; align-items: center; border: 1px solid #ddd; background-color: #f8f8f8;">
                                                        📷 No hay imagen subida
                                                    </div>
                                                @else
                                                    <!-- Mostrar imagen -->
                                                    <img src="{{ asset('storage/' . explode(',', $itemEspecifico->image)[0]) }}"
                                                        class="card-img-top" alt="{{ $itemEspecifico->item->nombre }}"
                                                        style="width: 200px; height: 200px; object-fit: cover;"
                                                        wire:click="viewItem({{ $itemEspecifico->id }})">
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <h4>{{ $itemEspecifico->item->nombre }}</h4>
                                        {!! $itemEspecifico->estado == 0
                                            ? '<span class="badge badge-danger">Sin cotizar</span>'
                                            : ($itemEspecifico->estado == 1
                                                ? '<span class="badge badge-success">Cotizado</span>'
                                                : '<span class="badge badge-secondary">Estado desconocido</span>') !!}
                                        
                                                <br>
                                        <label>{{ $itemEspecifico->item->descripcion }}</label>
                                        <br>
                                        <label>Unidad: {{ $itemEspecifico->unidad }}</label>
                                        <br>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div>
                                                    <label for="">
                                                        Cantidad: {{ $itemEspecifico->cantidad }}
                                                    </label>
                                                </div>
                                            </div>                                          

                                        </div>
                                        <div class="">
                                            <button class="btn btn-primary btn-custom" wire:click="viewItem({{ $itemEspecifico->id }})">
                                                Cotizar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class='px-6 py-2'>
                                <p>No hay items en la lista</p>
                            </div>
                        @endif
                        @if (count($itemsTemporalesDeLaLista) > 0)
                            <h4>Item Personalizados solicitados</h4>
                            @foreach ($itemsTemporalesDeLaLista as $itemEspecifico)
                                <div>
                                    <hr>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h4>{{ $itemEspecifico->item->nombre }}</h4>
                                            </div>
                                            <div class="col-md-4">
                                                <button class="btn btn-primary btn-custom" wire:click="viewItem({{ $itemEspecifico->id }})">
                                                    Buscar en catalogo
                                                </button>
                                            </div>
                                        </div>
                                       
                                        <label>{{ $itemEspecifico->item->descripcion }}</label>
                                        <br>
                                        <label>Unidad: {{ $itemEspecifico->unidad }}</label>
                                        <br>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div>
                                                    <label for="">
                                                        Cantidad: {{ $itemEspecifico->cantidad }}
                                                    </label>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class='px-6 py-2'>
                                <p>No hay items temporales en la lista</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
