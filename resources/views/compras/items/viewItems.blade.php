@extends('layouts.app')



<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .subcategorias, .subsubcategorias {
        overflow: hidden;
        display: none;
    }

    .categoria-header, .categoria-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        font-size: 12px;
    }

    .categoria-content > div {
        display: flex;
        align-items: center;
        gap: 20px; /* Ajuste del espacio entre elementos */
    }

    .categoria-buttons {
        display: flex;
        align-items: center;
        margin-left: auto; /* Mueve los botones a la derecha */
        gap: 10px; /* Espacio entre botones */
    }

    .categoria-buttons button {
        margin-left: 10px;
    }

    .categoria-header > div:nth-child(2) {
        margin-left: 30px;
    }

    .list-group-item {
        border: none;
        margin-bottom: 5px;
        padding-left: 0;
    }

    .nivel-1 { padding-left: 20px; }
    .nivel-2 { padding-left: 40px; }
    .nivel-3 { padding-left: 60px; }
    /* y así sucesivamente para otros niveles */

    .categoria-content span.icon {
        cursor: pointer;
    }
</style>





<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> @livewireStyles
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@section('title', 'Items')
@section('activeCollapseCompras', 'show')
@section('activeItems', 'active')
@section('contend')
    @livewire('item.item-component')
    <script>
        function toggleVisibility(id) {
            $('#' + id).animate({
                height: 'toggle'
            }, 500);
        }

        function editCategory(id) {
            alert('Editar item: ' + id);
        }

        // Mostrar el botón de despliegue solo si hay subcategorías
        $(document).ready(function() {
            if ($('#cat1 .list-group-item').length > 0) {
                $('#toggleButtonCat1').html(
                    '<button class="btn btn-secondary btn-sm" onclick="toggleVisibility(\'cat1\')"><i class="fas fa-chevron-down"></i></button>'
                    );
            }
        });
    </script>

@endsection