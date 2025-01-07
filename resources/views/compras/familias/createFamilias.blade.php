@extends('layouts.app')


<style>
.slide-in {
    opacity: 0;
    transform: translateY(-20px);
    transition: all 0.5s ease-in-out;
}

.slide-in.show {
    opacity: 1;
    transform: translateY(0);
}
</style>


@section('title', 'Crear Categorias')
@section('activeCollapseCompras', 'show')
@section('activeCategorias', 'active')
@section('activeFondoPermanente','style="background-color: #003366;"')
@section('contend')
@livewire('familia.create-categoria')
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.hook('element.updated', (el, component) => {
            if (el.classList.contains('slide-in')) {
                setTimeout(() => {
                    el.classList.add('show');
                }, 100); // Delay to ensure the element is in the DOM
            }
        });
    });
</script>

@endsection
