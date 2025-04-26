<div>
    @include('livewire.cliente.modal-respuesta-cancelacion-proyecto')
    <h1 class="pl-4">Cancelaciones de proyectos</h1>
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    @if ($proyectos->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre del Proyecto</th>
                                    <th>Cliente</th>
                                    <th>Tipo de Proyecto</th>
                                    <th>Proceso</th>
                                    <th>Usuario Asignado</th>
                                    <th>Fecha de registro</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proyectos as $proyecto)
                                    <tr>
                                        <td>{{ $proyecto->nombre }}</td>
                                        <td>{{ $proyecto->cliente->nombre ?? 'Sin asignar' }}</td>
                                        {{-- 0 Obra 1 Suministro --}}
                                        <td>
                                            @if ($proyecto->tipo == 0)
                                                <span class="badge"
                                                    style="background-color: #494d52; color: white;">Obra</span>
                                            @elseif ($proyecto->tipo == 1)
                                                <span class="badge"
                                                    style="background-color: #fd43149c; color: white;">Suministro</span>
                                                    @else
                                                    <span class="badge badge-danger">Sin asignar</span>
                                                @endif
                                        </td>
                                        <td>
                                            @if ($proyecto->proceso == 0)
                                                <span class="badge"
                                                    style="background-color: #6c757d; color: white;">Creando lista a
                                                    cotizar</span>
                                            @elseif ($proyecto->proceso == 1)
                                                <span class="badge"
                                                    style="background-color: #fd7e14; color: white;">Creando
                                                    cotizacion</span>
                                            @elseif ($proyecto->proceso == 2)
                                                <span class="badge"
                                                    style="background-color:#1a1ca7; color: white;">Cotizado</span>
                                            @elseif ($proyecto->proceso == 3)
                                                <span class="badge" style="background-color:#15d4db; color: white;">En
                                                    proceso de venta</span>
                                                    @elseif ($proyecto->proceso == 4)
                                                    <span class="badge" style="background-color: #28a745; color: white;">Venta terminada</span>
                                                    @elseif ($proyecto->proceso == 5)
                                                    <span class="badge" style="background-color: #f10808; color: white;">Cancelado</span>
                                            @else
                                                <span class="badge" style="background-color: #742532; color: white;">Estado desconocido</span>
                                            @endif
                                        </td>
                                        <td>{{ $proyecto->cliente->user->name ?? '' }}
                                            {{ $proyecto->cliente->user->first_last_name ?? '' }}
                                            {{ $proyecto->cliente->user->second_last_name ?? '' }}</td>
                                        <td>{{ $proyecto->fecha }}</td>
                                        <td>
                                            <button class="btn btn-info btn-custom"
                                                wire:click="viewProyecto({{ $proyecto->id }})" title="Ver proyecto">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-warning btn-custom"
                                                wire:click="evaluarCancelacion({{ $proyecto->id }})"
                                                title="Ver motivos de cancelacion">
                                                <i class="fas fa-file-alt"></i> Ver motivos
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if ($proyectos->hasPages())
                            <div class="px-6 py-3">
                                {{ $proyectos->links() }}
                            </div>
                        @endif
                    @else
                        <div class='px-6 py-2'>
                            <p>No hay resultados</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
