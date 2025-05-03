@extends('layouts.app')
@section('title', 'Orden de venta')
@section('activedesplegableFinansas', 'active')
@section('activeCollapseFinanzas', 'show')

@section('activeOrdenesVenta2', 'active')
@section('activeFondoPermanenteOrdenesVenta2', 'background-permanent')
@section('contend')


@livewire('Ventas.OrdeneVenta.OrdenVentaVista')


@endsection
