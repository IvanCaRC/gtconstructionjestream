@extends('layouts.app')
@section('title', 'Control de ingresos y egresos')
@section('activedesplegableFinansas', 'active')
@section('activeCollapseFinanzas', 'show')
@section('activeIngresosEgresos', 'active')
@section('activeFondoPermanenteIngresosEgresos2', 'background-permanent')
@section('contend')


@livewire('finanzas.ingresos-egeresos-totales')

@endsection
