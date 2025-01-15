@extends('layouts.app')
@section('title', 'Ver provedor Especifico')
@section('activedesplegablefamilias', 'active')
@section('activeCollapseCompras', 'show')
@section('activeProveedores', 'active')
@section('activeFondoPermanenteProveedores', 'background-permanent')
@section('contend')
@livewire('proveedor.view-especifica-proveedor', ['idproveedor' => $idproveedor])
@endsection