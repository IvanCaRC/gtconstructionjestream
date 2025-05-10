@extends('layouts.app')

@section('title', 'Vista Usuario')
@section('contend')
    <br>
    @livewire('view-user', ['iduser' => $iduser])
    @php
        use App\Models\User;

        $user = User::find($iduser);
        $esCompras = $user->roles()->where('name', 'Compras')->exists();
        $esVentas = $user->roles()->where('name', 'Ventas')->exists();
    @endphp
    
    <div class="container mt-3">
        <form method="GET" action="{{ route('admin.usersView', ['iduser' => $iduser]) }}" class="mb-4">
            <label for="filtro_tiempo" class="font-weight-bold">Filtrar por tiempo:</label>
            <select name="filtro_tiempo" id="filtro_tiempo" class="form-control custom-select-width d-inline-block"
                onchange="this.form.submit()">
                <option value="todos" {{ $filtroTiempo == 'todos' ? 'selected' : '' }}>Todos</option>
                <option value="1m" {{ $filtroTiempo == '1m' ? 'selected' : '' }}>Último mes</option>
                <option value="3m" {{ $filtroTiempo == '3m' ? 'selected' : '' }}>Últimos 3 meses</option>
                <option value="6m" {{ $filtroTiempo == '6m' ? 'selected' : '' }}>Últimos 6 meses</option>
            </select>
        </form>
    </div>


    @if ($esVentas)
        <div>
            <div class="container mt-3">
                <h4>Graficas de Ventas</h4>
            </div>
            <div>
                <x-tarjeta-ordenes-usuario :iduser="$iduser" :filtro-tiempo="$filtroTiempo" />
            </div>
        </div>
    @endif

    @if ($esCompras)
        <div>
            <div class="container mt-3">
                <h4>Graficas de Compras</h4>
            </div>
            <div>
                <x-grafica-ventas :iduser="$iduser" :filtro-tiempo="$filtroTiempo" />
            </div>
        </div>
    @endif



@endsection
