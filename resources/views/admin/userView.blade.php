@extends('layouts.app')

@section('title', 'Vista Usuario')
@section('activeAdministracion', 'inactive')
@section('activeUsuarios', 'active')
@section('contend')
@livewire('view-user', ['iduser' => $iduser])
@endsection