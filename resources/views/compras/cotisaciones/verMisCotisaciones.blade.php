@extends('layouts.app')
@section('title', 'Ver cotisaciones')
@section('activedesplegablecotizaciones', 'active')
@section('activeCollapseCotizaciones', 'show')
@section('activeMisCortisaciones', 'active')
@section('activeFondoPermanenteMiscotisaciones', 'background-permanent')
@section('contend')
@livewire('cotisaciones.ver-mis-cotisaciones')
@endsection