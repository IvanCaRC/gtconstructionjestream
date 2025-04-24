@extends('layouts.app')
@section('title', 'Ver cotisaciones')
@section('activedesplegablecotizaciones', 'active')
@section('activeCollapseCotizaciones', 'show')
@section('activeItemsCotizar', 'active')
@section('activeFondoPermanenteItemsCotizar', 'background-permanent')
@section('contend')
@livewire('items-cotizar.vista-de-catalogo')
@livewire('cotisaciones.ver-carrito-cotisaciones', ['idCotisacion' => $idCotisacion])


@endsection