@extends('layouts.app')
@section('title', 'Crear item')
@section('activedesplegablefamilias', 'active')
@section('activeCollapseCompras', 'show')
@section('activeProveedores', 'active')
@section('activeFondoPermanenteitem', 'background-permanent')
@section('contend')
@livewire('proveedor.create-proveedor')
@endsection