<?php

namespace App\Livewire\Finanzas;

use App\Models\ordenCompra;
use Illuminate\Support\Facades\Auth;

use Livewire\Component;

class OrdenCompraFin extends Component
{
    public function render()
    {
        $query = Auth::user()->hasRole('Administrador')
            ? $this->getQueryForAdmin()
            : $this->getQueryForRegularUser();
        $ordenesVenta = $query->paginate(10);

        return view('livewire.finanzas.orden-compra-fin', compact('ordenesVenta'));
    }

    private function getQueryForAdmin()
    {
        return ordenCompra::query()
            ->orderBy('created_at', 'desc')
        ;
    }

    /**
     * Construye la consulta para usuarios regulares
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getQueryForRegularUser()
    {
        $usuarioId = Auth::id();

        return ordenCompra::query()
            ->where('id_usuario', $usuarioId) // Filtrar por usuario
            ->orderBy('created_at', 'desc')
        ;
    }
}
