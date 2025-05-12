@extends('layouts.app')
@section('title', 'Dashboard Compras')
@section('activeCompras', 'active')

@section('contend')
    <br>
    @php
        use Illuminate\Support\Facades\Auth;
        use App\Models\User;

        $user = Auth::user(); // Obtiene el usuario autenticado
        $iduser = $user->id;
        $esCompras = $user->roles()->where('name', 'Compras')->exists();
    @endphp




    @if ($esCompras)
        <div>
            <div class="container mt-3">
                <h4>¡Tus estadisticas de Ventas!</h4>
                <form method="GET" action="{{ route('compras.dashboardCompras') }}" class="mb-4">
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

            <div>
                <x-grafica-ventas :iduser="$iduser" :filtro-tiempo="$filtroTiempo" />
            </div>
        </div>
    @endif
@endsection
