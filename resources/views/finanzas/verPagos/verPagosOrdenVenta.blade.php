@extends('layouts.app')
@section('title', 'Orden de venta')
@section('contend')

@livewire('finanzas.ver-pagos-ventas.ver-pagos-ventas', ['id' => $id])

@endsection
