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
    public $bancarios = [['banco' => '','titular' => '', 'cuenta' => '', 'clave' => '']];
    public $proyectos = 0;
    public $proyectosActivos = 0;
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

    public function addBancarios()
    {
        $this->bancarios[] = ['banco' => '','titular' => '', 'cuenta' => '', 'clave' => ''];
    }

    public function removeBancarios($index)
    {
        unset($this->bancarios[$index]);
        $this->bancarios = array_values($this->bancarios); // Reindexar el array
    }

    public function save()
    {


        $user = Auth::user();
        $idUser = $user->id;

        $cliente = Cliente::create([
            'nombre' => $this->nombre,
            'correo' => $this->correo,
            'rfc' => $this->rfc,
            'bancarios' => json_encode($this->bancarios), // Convertir a JSON
            'proyectos' => $this->proyectos,
            'proyectos_activos' => $this->proyectosActivos,
            'telefono' => json_encode($this->telefonos), // Convertir a JSON
            'user_id' => $idUser,
            'fecha' => now(),
        ]);



        $this->reset('nombre', 'correo', 'rfc', 'bancarios', 'proyectos', 'telefonos', 'proyectosActivos');


        return ['cliente_id' => $cliente->id];
    }
}
