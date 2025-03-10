<?php

namespace App\Livewire\Cliente;

use App\Models\Cliente;
use Livewire\Component;

class VistaEspecifica extends Component
{
    protected $listeners = ['refresh' => 'render'];

    public $openModalCreacionProyecto = true;
    public $tipoDeProyectoSelecionado;
    public $archivoDeListaDeItems;

    public $clienteEspecifico;
    public $telefonos = [['nombre' => '', 'numero' => '']];
    public $cuentas = [['titular' => '', 'numero' => '']];
    public $claves = [['titular' => '', 'numero' => '']];

    public function mount($idCliente)
    {
        $this->clienteEspecifico = Cliente::findOrFail($idCliente);

        $telefonos = json_decode($this->clienteEspecifico->telefono, true);
        $telefonos = array_filter($telefonos, function ($telefonos) {
            return !empty($telefonos['nombre']) || !empty($telefonos['numero']);
        });

        if (!empty($telefonos)) {
            $this->telefonos = $telefonos;
        } else {
            $this->telefonos = null;
        }

        $cuentas = json_decode($this->clienteEspecifico->cuenta, true);
        $cuentas = array_filter($cuentas, function ($cuentas) {
            return !empty($cuentas['titular']) || !empty($cuentas['numero']);
        });

        if (!empty($cuentas)) {
            $this->cuentas = $cuentas;
        } else {
            $this->cuentas = null;
        }

        $claves = json_decode($this->clienteEspecifico->clave, true);
        $claves = array_filter($claves, function ($claves) {
            return !empty($claves['titular']) || !empty($claves['numero']);
        });

        if (!empty($claves)) {
            $this->claves = $claves;
        } else {
            $this->claves = null;
        }
    }

    public function render()
    {
        return view('livewire.cliente.vista-especifica');
    }
    
    public function cancelar(){
        $this->reset('openModalCreacionProyecto');

        $this->dispatch('refresh');
    }

    public function asignarTipoDeProyecto($tipo)
    {
        $this->tipoDeProyectoSelecionado = $tipo;
    }
}
