<?php

namespace App\Livewire\Proveedor;

use Livewire\Component;

class CreateProveedor extends Component
{
    public $open = false;

    public function render()
    {
        return view('livewire.proveedor.create-proveedor');
    }

    public function editRole($id)
    {
        $this->open = true;
    }
}
