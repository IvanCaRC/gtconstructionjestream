@extends('layouts.app')

@section('title', 'Vista Usuario')
@section('contend')
<br>
@livewire('view-user', ['iduser' => $iduser])
@endsection