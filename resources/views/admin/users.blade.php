@extends('layouts.app')

@section('title', 'Usuarios')
@section('activeUsuarios', 'active')
@section('contend')
<style>
.table th,
.table td {
    border-top: 1px solid #dee2e6;
    border-left: none;
    border-right: none;
}

.btn-custom {
    font-size: 1rem;
    padding: 0.45rem .75rem;
}

.table-responsive .btn {
    margin-left: -10%;
    /* Ajusta el margen para acercar los botones entre sí */
}
</style>
@livewire('show-users')
@endsection
