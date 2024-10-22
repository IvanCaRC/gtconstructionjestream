@extends('layouts.app')

@section('title', 'Vista Usuario')
@section('contend')
@livewire('view-user', ['iduser' => $iduser])
@endsection