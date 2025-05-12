<?php

namespace App\Livewire\Finanzas\VerPagosCompras;

use App\Models\ordenCompra;
use Livewire\Component;

class VerPagosCompras extends Component
{

    public $ordenventa;
    public $historialPagos = [];

    public function mount($id)
    {
        $this->ordenventa = ordenCompra::find($id);
        $this->historialPagos = json_decode($this->ordenventa->historial, true) ?? [];
    }
    
    public function render()
    {
        return view('livewire.finanzas.ver-pagos-compras.ver-pagos-compras');
    }
}
