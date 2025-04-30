@extends('layouts.app')
@section('title', 'Recepcion de cotizaciones')
@section('activedesplegableVentas', 'active')
@section('activeCollapseVentas', 'show')

@section('activeRecepcionCotizacion', 'active')
@section('activeFondoPermanenteRecepcionCotizacion', 'background-permanent')
@section('contend')


@livewire('Ventas.RecepsionCotizacio.Recepcioncotiosacion')

@endsection
