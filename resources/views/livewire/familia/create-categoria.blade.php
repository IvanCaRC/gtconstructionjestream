<div class="container-fluid px-4 sm:px-6 lg:px-8 py-1">
    <div class="card">
        <div class="card-body">
            <div>
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                <form wire:submit.prevent="save">
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

                    <livewire:familia.categoria-select />

                    <button type="submit" class="btn btn-primary">Registrar</button>
                </form>
            </div>
        </div>
    </div>
</div>
