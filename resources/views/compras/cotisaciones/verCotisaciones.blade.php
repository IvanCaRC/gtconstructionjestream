@extends('layouts.app')
@section('title', 'Ver cotisaciones')
@section('activedesplegablecotizaciones', 'active')
@section('activeCollapseCotizaciones', 'show')
@section('activeCortisaciones', 'active')
@section('activeFondoPermanentecotisaciones', 'background-permanent')
@section('contend')

@livewire('cotisaciones.ver-cotisaciones')

@endsection