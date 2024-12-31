<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
    <h1 class="pl-1">Registrar Familias</h1>
    <div class="card">
        <div class="card-body">
            <div>
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                <form>
                    <div class="container-fluid px-0 sm:px-1 lg:px-1 py-3">
                        <button type="submit" class="btn btn-primary" wire:click="save2">Registrar</button>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" wire:model="nombre" class="form-control" id="nombre"
                            placeholder="Ingrese el nombre de la familia">
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea wire:model="descripcion" class="form-control" id="descripcion"
                            placeholder="Ingrese la descripción de la familia"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <input type="checkbox" wire:model="estado" id="estado">
                    </div>

                    <div class="form-group">
                        <label for="familia">Familia</label>
                        <select wire:model="selectedFamilia" class="form-control" wire:change="updateSelectedFamilia($event.target.value)">
                            <option value="">Seleccione una familia</option>
                            @foreach($familias as $familia)
                                <option value="{{ $familia->id }}">{{ $familia->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    @foreach ($subfamilias as $nivel => $subfamiliasNivel)
                        @if ($subfamiliasNivel->isNotEmpty())
                            <div class="form-group slide-in" x-data="{ show: false }" x-init="$nextTick(() => { show = true })" :class="{ 'show': show }">
                                <label for="subfamilia-nivel-{{ $nivel }}">Subfamilia (Nivel {{ $nivel }})</label>
                                <select wire:model="selectedSubfamilias.{{ $nivel }}" class="form-control" wire:change="updateSelectedSubfamilia($event.target.value, {{ $nivel }})">
                                    <option value="">Seleccione una subfamilia</option>
                                    @foreach ($subfamiliasNivel as $subfamilia)
                                        <option value="{{ $subfamilia->id }}">{{ $subfamilia->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    @endforeach
                </form>
            </div>
        </div>
    </div>
</div>
