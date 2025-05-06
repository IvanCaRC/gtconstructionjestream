@extends('layouts.app')
@section('title', 'Ver ordenes compra')
@section('activedesplegablecotizaciones', 'active')
@section('activeCollapseCotizaciones', 'show')
@section('activeOrdenesCompra', 'active')
@section('activeFondoPermanenteOrdenesCompra', 'background-permanent')
@section('contend')
@livewire('cotisaciones.ordene-compra.orden-compra-vista-uno')

@endsection