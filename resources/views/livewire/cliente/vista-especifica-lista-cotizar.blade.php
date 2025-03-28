<div>
    <div class="row bg-white py-4  shadow">

        @if ($listadeUsuarioActiva == null)
            <div class="col-md-9">
                <h4 class="px-3">
                    No hay una lista activa. Activa o crea una para realizar la lista.
                </h4>
            </div>
        @else
            <div class="col-md-9">
                <h4 class="px-3">
                    Lista activa de cliente "<span class="fw-bold text-primary ">{{ $nombreCliente }}</span>",
                    del proyecto "<span class="fw-bold text-primary ">{{ $nombreProyecto }}</span>",
                    y lista "<span class="fw-bold text-primary ">{{ $listadeUsuarioActiva }}</span>".
                </h4>
            </div>
        @endif
        <div class="col-md-1">
            <a href="#" class=" text-danger d-block">Cancelar</a>
        </div>
        <div class="col-md-1">
            <a href="#" class="d-block">Desactivar</a>
        </div>
        <div class="col-md-1">
            <button class="btn btn-light border-0 shadow-sm " style="width: 50px; height: 50px;"
                wire:click="verLista({{ $idLista }})">
                <i class="fas fa-shopping-cart text-primary" style="font-size: 24px;"></i>
            </button>
        </div>
    </div>


    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
        <div class="flex h-screen gap-4 p-4">
            <!-- Primera sección (85%) -->

            <div class="flex-1 bg-white p-4 rounded-lg border border-black" style="flex: 0 0 80%;">
                <div>
                    <h3>Items de la lista</h3>
                </div>
                <div class="card-body">
                    @if ($itemsDeLaLista->count() > 0)
                        {{-- <div class="row">
                        <divc lass="col-md-9">

                        </div class="col-md-9">
                        <div>

                        </div>
                    </div> --}}
                        <table class="table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($itemsDeLaLista as $itemEspecifico)
                                    <tr>
                                        <td class="align-middle d-none d-md-table-cell">
                                            <div class="card shadow-sm">
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
                                                            class="card-img-top"
                                                            alt="{{ $itemEspecifico->item->nombre }}"
                                                            style="width: 200px; height: 200px; object-fit: cover;"
                                                            wire:click="viewItem({{ $itemEspecifico->id }})">
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle d-none d-md-table-cell">
                                            <h4>
                                                {{ $itemEspecifico->item->nombre }}
                                            </h4>
                                            <label for="">
                                                {{ $itemEspecifico->item->descripcion }}
                                            </label>
                                            <br>
                                            <label for="">
                                                Unidad: {{ $itemEspecifico->unidad }}
                                            </label>
                                            <br>
                                            <input type="text">
                                            {{ $itemEspecifico->cantidad }}
                                            </input>


                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class='px-6 py-2'>
                            <p>No hay items en la lista</p>
                        </div>
                    @endif


                    @foreach ($itemsDeLaLista as $item)
                    @endforeach
                </div>
            </div>
            <!-- Segunda sección (15%) -->
            <div class="bg-white rounded-lg border border-black p-4" style="flex: 0 0 20%;">
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>
</div>
