@extends('layouts.app')
@section('title', 'Recepcion de llamada')
@section('activedesplegableVentas', 'active')
@section('activeCollapseVentas', 'show')

@section('activeRecepcion', 'active')
@section('activeFondoPermanenteRecepcion', 'background-permanent')
@section('contend')

@livewire('cliente.recepcion-llamada')

@endsection
