@extends('layouts.app')
@section('title', 'Item especifico')
@section('activedesplegablefamilias', 'active')
@section('activeCollapseCompras', 'show')
@section('activeMateriales', 'active')
@section('activeFondoPermanenteMateriales', 'background-permanent')
@section('contend')
   
    @livewire('item.vista-especifica', ['idItem' => $idItem])
@endsection
