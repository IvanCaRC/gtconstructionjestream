<div class="container mt-5">
    <h2>Crear Nueva Categoría</h2>
    <form wire:submit.prevent="submit">
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" wire:model="nombre">
            @error('nombre') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" wire:model="descripcion"></textarea>
            @error('descripcion') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="estado">Estado</label>
            <input type="checkbox" id="estado" wire:model="estado">
        </div>

        <div class="form-group">
            <label for="id_familia">Categoría Padre (opcional)</label>
            <select class="form-control" id="id_familia" wire:model="id_familia">
                <option value="">Seleccione una categoría padre</option>
                @foreach(App\Models\Familia::whereNull('id_familia')->get() as $familia)
                    <option value="{{ $familia->id }}">{{ $familia->nombre }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Crear Categoría</button>
    </form>
</div>
