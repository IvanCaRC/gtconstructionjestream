@extends('layouts.app')
@section('title', 'Orden de venta')
@section('contend')

@livewire('finanzas.ver-pagos-compras.ver-pagos-compras', ['id' => $id])

@endsection
