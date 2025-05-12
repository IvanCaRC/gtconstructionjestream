<?php
namespace App\Livewire\Finanzas\VerPagosVentas;

use App\Models\ordenVenta;
use Livewire\Component;

class VerPagosVentas extends Component
{
    public $ordenventa;
    public $historialPagos = [];

    public function mount($id)
    {
        $this->ordenventa = ordenVenta::find($id);
        $this->historialPagos = json_decode($this->ordenventa->historial, true) ?? [];
    }

    public function render()
    {
        return view('livewire.finanzas.ver-pagos-ventas.ver-pagos-ventas');
    }
}