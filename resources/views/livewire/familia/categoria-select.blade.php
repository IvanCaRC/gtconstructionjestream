<div class="form-group">
    <label for="categoria">{{ $familia ? 'Subfamilia de ' . $familia->nombre : 'Familia' }}</label>
    <select wire:model="selectedSubfamilia" class="form-control" >
        <option value="">{{ $familia ? 'Seleccione una subfamilia' : 'Seleccione una familia' }}</option>
        @foreach($subfamilias as $subfamilia)
            <option value="{{ $subfamilia->id }}">{{ $subfamilia->nombre }}</option>
        @endforeach
    </select>
</div>

@if($selectedSubfamilia)
    <livewire:familia.categoria-select :familia-id="$selectedSubfamilia" />
@endif
