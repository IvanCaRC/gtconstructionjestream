<?php

namespace App\Livewire\Proveedor;

use Livewire\Component;

class EditProveedor extends Component
{
    public $openModalDireccion = false;
    public function render()
    {
        return view('livewire.proveedor.edit-proveedor');
    }
}
