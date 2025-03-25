@extends('layouts.app')
@section('title', 'Item Especifico')
@section('activedesplegableVentas', 'active')
@section('activeCollapseVentas', 'show')
@section('activeFichasTecnicas', 'active')
@section('activeFondoPermanenteFichasTecnicas', 'background-permanent')
@section('contend')
@livewire('cliente.vista-especifica-lista-cotizar', ['idLista' => $idLista])

@endsection
