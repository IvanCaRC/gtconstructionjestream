@extends('layouts.app')
@section('title', 'Crear proveedor')
@section('activedesplegablefamilias', 'active')
@section('activeCollapseCompras', 'show')
@section('activeProveedores', 'active')
@section('activeFondoPermanenteProveedores', 'background-permanent')
@section('contend')
@livewire('proveedor.create-proveedor')
@endsection