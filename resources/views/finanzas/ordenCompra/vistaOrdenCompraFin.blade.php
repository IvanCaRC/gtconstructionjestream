@extends('layouts.app')
@section('title', 'Ordenes de compra')
@section('activedesplegableFinansas', 'active')
@section('activeCollapseFinanzas', 'show')

@section('activeOrdenesCompra2', 'active')
@section('activeFondoPermanenteOrdenesCompra2', 'background-permanent')
@section('contend')



@livewire('finanzas.orden-compra')
@endsection
