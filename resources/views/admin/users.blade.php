@extends('layouts.app')

@section('title', 'Usuarios')
@section('activeAdministracion', 'inactive')
@section('activeUsuarios', 'active')
@section('contend')
@livewire('show-users')
@endsection
