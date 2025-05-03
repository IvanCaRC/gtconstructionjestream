@extends('layouts.app')
@section('title', 'Orden de venta')
@section('activedesplegableVentas', 'active')
@section('activeCollapseVentas', 'show')

@section('activeOrdenesVenta', 'active')
@section('activeFondoPermanenteOrdenesVenta', 'background-permanent')
@section('contend')
@livewire('Ventas.OrdeneVenta.OrdenVenta')
@endsection
