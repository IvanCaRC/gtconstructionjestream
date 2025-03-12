<?php

namespace App\Livewire\Cliente;

use App\Models\Cliente;
use Livewire\Component;

class VistaEspecifica extends Component
{
    protected $listeners = ['refresh' => 'render'];

   

    public $clienteEspecifico;
    public $telefonos = [['nombre' => '', 'numero' => '']];
    public $bancarios = [['banco' => '','titular' => '', 'cuenta' => '', 'clave' => '']];
    public $proyectos;
    public $proyectosActivos;

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

        $bancarios = json_decode($this->clienteEspecifico->bancarios, true);
        $bancarios = array_filter($bancarios, function ($bancarios) {
            return !empty($bancarios['banco']) || !empty($bancarios['titular']) || !empty($bancarios['cuenta']) || !empty($bancarios['clave']);
        });

        if (!empty($bancarios)) {
            $this->bancarios = $bancarios;
        } else {
            $this->bancarios = null;
        }
    }

    public function render()
    {
        return view('livewire.cliente.vista-especifica');
    }

    //____________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________
    
    public $openModalCreacionProyecto = false;
    public $tipoDeProyectoSelecionado;
    
    
    
    

    public function cancelar(){
        $this->reset('openModalCreacionProyecto');

        $this->dispatch('refresh');
    }

    public function asignarTipoDeProyecto($tipo)
    {
        $this->tipoDeProyectoSelecionado = $tipo;
    }

    //____________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________
    //Para obra
    public $archivoDeProyecto;
    public $datosGenrales = [['frente' => '','fondo' => '','alturaTecho' => '','areaTotal' => '','alturaMuros' => '','canalon' => '','perimetral' => '', 'caballete' => '']]; 
    public $adicionales = [['estructura' => '', 'cantidad' => '']];
    //____________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________
    //Ppara suministro
    public $archivoDeListaDeItems;
    public $archivoDeListaDeItemsPdf;
    

}
