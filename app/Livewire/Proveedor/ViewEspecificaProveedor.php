<?php

namespace App\Livewire\Proveedor;

use App\Models\Proveedor;
use Livewire\Component;

class ViewEspecificaProveedor extends Component
{
    public $proveedor;


    public function render()
    {
        if (!$this->proveedor->estado_eliminacion) {
            abort(404); // Lanza un error 404 si la familia está eliminada
        }

        return view('livewire.proveedor.view-especifica-proveedor', [
            'proveedor' => $this->proveedor
        ]);
    }

    public function mount($idproveedor)
    {
        $this->proveedor = Proveedor::findOrFail($idproveedor);
    }

    public function eliminar($proveedroId)
    {
        $Proveedor = Proveedor::findOrFail($proveedroId);
        $Proveedor->update(['estado_eliminacion' => false]);
        return redirect()->route('compras.proveedores.viewProveedores');
    }
}
