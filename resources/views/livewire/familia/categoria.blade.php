<li class="list-group-item nivel-{{ $nivel }}">
    <div class="categoria-content">
        <div>
            @if ($familia->subfamilias->count() > 0)
                <span class="icon" id="folderIconcat{{ $familia->id }}{{ $nivel }}"
                    onclick="toggleVisibility('cat{{ $familia->id }}{{ $nivel }}')" style="cursor: pointer;">
                    <i class="fas fa-folder"></i>
                </span>
            @else
                <span><i class="fas fa-file"></i></span>
            @endif
            <label class="font-weight-bold">{{ $familia->nombre }}</label>
            <span>{{ $familia->descripcion }}</span>
        </div>
        <div class="categoria-buttons">
            <button class="btn btn-primary btn-sm" wire:click="editCategory('{{ $familia->id }}')"><i
                    class="fas fa-pencil-alt"></i></button>
            <button class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i></button>
            @if ($familia->subfamilias->count() > 0)
                <button class="btn btn-secondary btn-sm"
                    onclick="toggleVisibility('cat{{ $familia->id }}{{ $nivel }}')"><i
                        class="fas fa-chevron-down"></i></button>
            @endif
        </div>
    </div>
    @if ($familia->subfamilias->count() > 0)
        <div id="cat{{ $familia->id }}{{ $nivel }}" class="subcategorias">
            <ul class="list-group">
                @foreach ($familia->subfamilias as $subfamilia)
                    @include('livewire.familia.categoria', [
                        'familia' => $subfamilia,
                        'nivel' => $nivel + 1,
                    ])
                @endforeach
            </ul>
        </div>
    @endif
</li>
