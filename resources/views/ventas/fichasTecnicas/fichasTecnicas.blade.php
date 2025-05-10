@extends('layouts.app')
@section('title', 'Fichas Tecnicas')
@section('activedesplegableVentas', 'active')
@section('activeCollapseVentas', 'show')

@section('activeFichasTecnicas', 'active')
@section('activeFondoPermanenteFichasTecnicas', 'background-permanent')
@section('contend')

@livewire('cliente.vista-de-lista')
@livewire('cliente.fichas-tecnicas')

@endsection
