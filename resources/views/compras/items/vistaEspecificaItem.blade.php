@extends('layouts.app')
@section('title', 'Item especifico')
@section('activedesplegablefamilias', 'active')
@section('activeCollapseCompras', 'show')
@section('activeMateriales', 'active')
@section('activeFondoPermanenteMateriales', 'background-permanent')
@section('contend')
   <style>
    
   </style>
    @livewire('item.vista-especifica', ['idItem' => $idItem])
@endsection
