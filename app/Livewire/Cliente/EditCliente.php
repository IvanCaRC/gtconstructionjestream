<?php

namespace App\Livewire\Cliente;

use App\Models\Cliente;
use Livewire\Component;

class EditCliente extends Component
{
    public $idDecliente;

    public $clienteActual;
    public $nombreClienteEdit;

    public function mount($idcliente)
    {
        $this->clienteActual = Cliente::findOrFail($idcliente);
        $this->nombreClienteEdit = $this->clienteActual->nombre;

        $this->idDecliente = $idcliente;
    }


    public function render()
    {
        return view('livewire.cliente.edit-cliente');
    }
}
