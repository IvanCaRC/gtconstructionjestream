@extends('layouts.app')

@section('title', 'Uusaios')
@section('activeAdministracion', 'inactive')
@section('activeUsuarios', 'active')
@section('contend')
@livewire('show-users')
@endsection
