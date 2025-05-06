@extends('layouts.app')
@section('title', 'Ver ordenes compra lista especifica')
@section('activedesplegablecotizaciones', 'active')
@section('activeCollapseCotizaciones', 'show')
@section('activeOrdenesCompra', 'active')
@section('activeFondoPermanenteOrdenesCompra', 'background-permanent')
@section('contend')

@livewire('cotisaciones.ordene-compra.vista-especifica-de-orden', ['idCotisaciones' => $cotisacion->id])

@endsection