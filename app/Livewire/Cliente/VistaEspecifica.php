<?php

namespace App\Livewire\Cliente;

use App\Models\Cliente;
use App\Models\Proyecto;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class VistaEspecifica extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $searchTerm = '';
    protected $listeners = ['refresh' => 'render'];

    public $statusFiltro = 0; // Filtro de estado
    public $statusProcesos = 6; // Filtro de procesos
    public $statusTipos = 3; // Filtro de tipos

    public $clienteEspecifico;
    public $telefonos = [['nombre' => '', 'numero' => '']];
    public $bancarios = [['banco' => '', 'titular' => '', 'cuenta' => '', 'clave' => '']];

    public $openModalCreacionProyecto = false;
    public $tipoDeProyectoSelecionado;
    public $fileNamePdf;
    public $archivoSubido;
    public $idDireccionParaProyecto;
    public $nombreProyecto;
    public $datosGenrales = [['frente' => '', 'fondo' => '', 'alturaTecho' => '', 'areaTotal' => '', 'alturaMuros' => '', 'canalon' => '', 'perimetral' => '', 'caballete' => '']];
    public $adicionales = [['estructura' => '', 'cantidad' => '']];
    public $preferencia;
    public $listaACotizarTxt;

    public function mount($idCliente)
    {
        $this->clienteEspecifico = Cliente::findOrFail($idCliente);

        // Procesar teléfonos
        $telefonos = json_decode($this->clienteEspecifico->telefono, true);
        $telefonos = array_filter($telefonos, function ($telefonos) {
            return !empty($telefonos['nombre']) || !empty($telefonos['numero']);
        });
        $this->telefonos = !empty($telefonos) ? $telefonos : null;

        // Procesar datos bancarios
        $bancarios = json_decode($this->clienteEspecifico->bancarios, true);
        $bancarios = array_filter($bancarios, function ($bancarios) {
            return !empty($bancarios['banco']) || !empty($bancarios['titular']) || !empty($bancarios['cuenta']) || !empty($bancarios['clave']);
        });
        $this->bancarios = !empty($bancarios) ? $bancarios : null;
    }

    public function render()
    {
        // Obtener los proyectos del cliente específico y aplicar los filtros y la búsqueda
        $proyectos = Proyecto::where('cliente_id', $this->clienteEspecifico->id)
            ->when($this->searchTerm, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('tipo', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('estado', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->when($this->statusFiltro != 0, function ($query) {
                $query->where('estado', $this->statusFiltro);
            })
            ->when($this->statusProcesos != 6, function ($query) {
                $query->where('proceso', $this->statusProcesos);
            })
            ->when($this->statusTipos != 3, function ($query) {
                $query->where('tipo', $this->statusTipos);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Usar paginate() con el trait WithPagination

        // Pasar los proyectos a la vista
        return view('livewire.cliente.vista-especifica', [
            'proyectos' => $proyectos,
        ]);
    }
    public function search()
    {
        $this->resetPage(); // Reiniciar la paginación al realizar una búsqueda
    }

    public function cancelar()
    {
        $this->reset('openModalCreacionProyecto', 'archivoSubido', 'tipoDeProyectoSelecionado', 'nombreProyecto', 'listaACotizarTxt', 'idDireccionParaProyecto', 'datosGenrales', 'adicionales');
        $this->dispatch('refresh');
    }

    public function editCliente($idCliente)
    {
        return redirect()->route('ventas.cliente.EditCliente', ['idcliente' => $idCliente]);
    }

    public function asignarTipoDeProyecto($tipo)
    {
        $this->tipoDeProyectoSelecionado = $tipo;
    }

    public function updatedArchivoSubido()
    {
        $this->fileNamePdf = $this->archivoSubido ? $this->archivoSubido->getClientOriginalName() : '';
    }

    public function addAdicionales()
    {
        $this->adicionales[] = ['estructura' => '', 'cantidad' => ''];
    }

    public function removeAdicionales($index)
    {
        unset($this->adicionales[$index]);
        $this->adicionales = array_values($this->adicionales); // Reindexar el array
    }

    public function saveProyecto()
    {
        $clienteId = $this->clienteEspecifico->id;
        $archivoSubido = $this->archivoSubido ? $this->archivoSubido->store('archivosFacturacionProveedores', 'public') : null;

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
            'datos_medidas' => json_encode($this->datosGenrales),
            'datos_adicionales' => json_encode($this->adicionales),
            'fecha' => now(),
        ]);
        $this->clienteEspecifico->increment('proyectos'); // Incrementa el campo "proyectos" en 1
        $this->clienteEspecifico->increment('proyectos_activos');

        $this->reset('openModalCreacionProyecto', 'archivoSubido', 'tipoDeProyectoSelecionado', 'nombreProyecto', 'listaACotizarTxt', 'idDireccionParaProyecto', 'datosGenrales', 'adicionales');
        $this->dispatch('refresh');
        return redirect()->route('ventas.clientes.vistaEspecProyecto', ['idProyecto' => $proyecto->id]);
    }

    public function asignarDireccion($idDIreccion)
    {
        $this->idDireccionParaProyecto = $idDIreccion;
    }

    public function asiganrPreferencia($preferencia)
    {
        $this->preferencia = $preferencia;
    }

    public function viewProyecto($idProyecto)
    {
        $proyecto = Proyecto::find($idProyecto);

        if ($proyecto === null) {
            abort(404, 'proyecto no encontrado');
        }

        return redirect()->route('ventas.clientes.vistaEspecProyecto', ['idProyecto' => $idProyecto]);
    }
}
