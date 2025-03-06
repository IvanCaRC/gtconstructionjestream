<?php

namespace App\Livewire\Cliente;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class RecepcionLlamada extends Component
{

    public $nombre, $correo, $rfc;
    public $telefonos = [['nombre' => '', 'numero' => '']];
    public $cuentas = [['titular' => '', 'numero' => '']];
    public $claves = [['titular' => '', 'numero' => '']];
    public function render()
    {
        return view('livewire.cliente.recepcion-llamada');
    }



    public function addTelefono()
    {
        $this->telefonos[] = ['nombre' => '', 'numero' => ''];
    }

    public function removeTelefono($index)
    {
        unset($this->telefonos[$index]);
        $this->telefonos = array_values($this->telefonos); // Reindexar el array
    }



    public function addCuenta()
    {
        $this->cuentas[] = ['titular' => '', 'numero' => ''];
    }

    public function removeCuenta($index)
    {
        unset($this->cuentas[$index]);
        $this->cuentas = array_values($this->cuentas); // Reindexar el array
    }



    public function addClave()
    {
        $this->claves[] = ['titular' => '', 'numero' => ''];
    }

    public function removeClave($index)
    {
        unset($this->claves[$index]);
        $this->claves = array_values($this->claves); // Reindexar el array
    }

    public function save()
    {


        $user = Auth::user();
        $idUser = $user->id;

        $cliente = Cliente::create([
            'nombre' => $this->nombre,
            'correo' => $this->correo,
            'rfc' => $this->rfc,
            'cuenta' => json_encode($this->cuentas), // Convertir a JSON
            'clave' => json_encode($this->claves),   // Convertir a JSON
            'telefono' => json_encode($this->telefonos), // Convertir a JSON
            'user_id' => $idUser,
            'fecha' => now(),
        ]);



        $this->reset('nombre', 'correo', 'rfc', 'cuentas', 'claves', 'telefonos');


        return ['cliente_id' => $cliente->id];
    }
}
