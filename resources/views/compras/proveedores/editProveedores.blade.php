@extends('layouts.app')
@section('title', 'Editar Proveedor')
@section('activedesplegablefamilias', 'active')
@section('activeCollapseCompras', 'show')
@section('activeProveedores', 'active')
@section('activeFondoPermanenteProveedores', 'background-permanent')
@section('contend')
@livewire('proveedor.edit-proveedor')
@endsection