<?php

namespace App\Livewire\Cliente;

use App\Models\Cliente;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class VistaEspecifica extends Component
{
    use WithFileUploads;

    protected $listeners = ['refresh' => 'render'];
    public $clienteEspecifico;
    public $telefonos = [['nombre' => '', 'numero' => '']];
    public $bancarios = [['banco' => '', 'titular' => '', 'cuenta' => '', 'clave' => '']];
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
        $this->proyectos = Proyecto::where('cliente_id', $this->clienteEspecifico->id)->get();
        return view('livewire.cliente.vista-especifica');
    }

    //____________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________

    public $openModalCreacionProyecto = false;
    public $tipoDeProyectoSelecionado;
    public $fileNamePdf;

    public function cancelar()
    {
        $this->reset('openModalCreacionProyecto', 'archivoSubido', 'tipoDeProyectoSelecionado', 'nombreProyecto', 'listaACotizarTxt', 'idDireccionParaProyecto', 'datosGenrales', 'adicionales');

        $this->dispatch('refresh');
    }

    public function asignarTipoDeProyecto($tipo)
    {
        $this->tipoDeProyectoSelecionado = $tipo;
    }

    public function updatedArchivoSubido()
    {
        // Verificar si se ha seleccionado un archivo
        if ($this->archivoSubido) {
            // Obtener el nombre del archivo
            $this->fileNamePdf = $this->archivoSubido->getClientOriginalName();
        } else {
            // Si no hay archivo, reiniciar el nombre
            $this->fileNamePdf = '';
        }
    }

    public $archivoSubido;
    public $idDireccionParaProyecto;
    public $nombreProyecto;
    //Para obra
    public $datosGenrales = [['frente' => '', 'fondo' => '', 'alturaTecho' => '', 'areaTotal' => '', 'alturaMuros' => '', 'canalon' => '', 'perimetral' => '', 'caballete' => '']];
    public $adicionales = [['estructura' => '', 'cantidad' => '']];
    public $preferencia;

    public function addAdicionales()
    {
        $this->adicionales[] = ['estructura' => '', 'cantidad' => ''];
    }

    public function removeAdicionales($index)
    {
        unset($this->adicionales[$index]);
        $this->adicionales = array_values($this->adicionales); // Reindexar el array
    }

    //Para suministro

    public $listaACotizarTxt;

    public function saveProyecto()
    {
        $clienteId = $this->clienteEspecifico->id;
        $archivoSubido = null;
        if ($this->archivoSubido) {
            $archivoSubido = $this->archivoSubido->store('archivosFacturacionProveedores', 'public');
        }
        if ($this->tipoDeProyectoSelecionado == 1) {
            $proyecto = Proyecto::create([
                'cliente_id' => $clienteId,
                'direccion_id' => $this->idDireccionParaProyecto,
                'proceso' => 0,
                'nombre' => $this->nombreProyecto,
                'preferencia' => $this->preferencia,
                'listas' => 0,
                'cotisaciones' => 0,
                'ordenes' => 0,
                'tipo' => $this->tipoDeProyectoSelecionado,
                'estado' => 1,
                'archivo' => $archivoSubido,
                'items_cotizar' => $this->listaACotizarTxt,
                'datos_medidas' => json_encode($this->datosGenrales), // Guardar como JSON
                'datos_adicionales' => json_encode($this->adicionales), // Guardar como JSON
                'fecha' => now(),
            ]);
        } else if ($this->tipoDeProyectoSelecionado == 0) {
            $proyecto = Proyecto::create([
                'cliente_id' => $clienteId,
                'direccion_id' => $this->idDireccionParaProyecto,
                'proceso' => 0,
                'nombre' => $this->nombreProyecto,
                'preferencia' => $this->preferencia,
                'listas' => 0,
                'cotisaciones' => 0,
                'ordenes' => 0,
                'tipo' => $this->tipoDeProyectoSelecionado,
                'estado' => 1,
                'archivo' => $archivoSubido,
                'items_cotizar' => $this->listaACotizarTxt,
                'datos_medidas' => json_encode($this->datosGenrales), // Guardar como JSON
                'datos_adicionales' => json_encode($this->adicionales), // Guardar como JSON
                'fecha' => now(),
            ]);
        }


        $this->reset('openModalCreacionProyecto', 'archivoSubido', 'tipoDeProyectoSelecionado', 'nombreProyecto', 'listaACotizarTxt', 'idDireccionParaProyecto', 'datosGenrales', 'adicionales');

        $this->dispatch('refresh');

        return true;
    }

    public function asignarDireccion($idDIreccion)
    {
        $this->idDireccionParaProyecto = $idDIreccion;
    }

    public function asiganrPreferencia($preferencia)
    {
        $this->preferencia = $preferencia;
    }
}
