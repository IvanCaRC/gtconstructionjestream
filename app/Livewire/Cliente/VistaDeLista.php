<?php

namespace App\Livewire\Cliente;

use Livewire\Component;

use Livewire\WithPagination;
use App\Models\Familia;
use App\Models\ItemEspecifico;
use App\Models\ListasCotizar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VistaDeLista extends Component
{


    public $searchTerm = '';
    public $sort = 'id';
    public $direction = 'desc';
    public $tipoDeVista = true;
    public $statusFiltroDeBusqueda;
    public $familiasSeleccionadas = [];
    public $desplegables = [];
    public $listadeUsuarioActiva;
    public $usuarioActual;

    protected $listeners = [
        'mountVistaDeLista' => 'mount',
        'renderVistaDeLista' => 'render',
    ];
    
    

    public $nombreProyecto;
    public $nombreCliente;
    public $idLista;

    public $itemsEnLista = [];
   

    public function mount()
    {
        // Obtener el usuario autenticado
        $this->usuarioActual = Auth::user();

        // Verificar si el usuario tiene una lista activa asignada
        if ($this->usuarioActual->lista) {
            // Obtener el ID de la lista activa
            $listaId = $this->usuarioActual->lista;

            // Buscar la lista activa usando el ID obtenido
            $listaActiva = ListasCotizar::find($listaId);

            if ($listaActiva) {
                // Si existe la lista activa, obtener sus detalles
                $this->idLista = $listaActiva->id;
                $this->listadeUsuarioActiva = $listaActiva->nombre ?? 'Sin nombre';
                $proyecto = $listaActiva->proyecto ?? 'Sin proyecto';
                $cliente = $proyecto->cliente ?? 'Sin cliente    ';
                $this->nombreProyecto = $proyecto->nombre ?? 'Sin nombre';

                // Obtener el cliente relacionado con el proyecto
               
                $this->nombreCliente = $cliente->nombre ?? 'Sin cliente';

                // Obtener los IDs de los items en la lista
                $itemsData = json_decode($listaActiva->items_cotizar, true) ?? [];
                $this->itemsEnLista = array_column($itemsData, 'id');
            } else {
                // Si no se encuentra la lista, establecer las propiedades en null
                $this->establecerPropiedadesNulas();
            }
        } else {
            // Si el usuario no tiene una lista asignada, establecer las propiedades en null
            $this->establecerPropiedadesNulas();
        }
    }

    private function establecerPropiedadesNulas()
    {
        $this->idLista = null;
        $this->listadeUsuarioActiva = null;
        $this->nombreProyecto = null;
        $this->nombreCliente = null;
        $this->itemsEnLista = [];
    }

    public function verLista($idLista)
    {
        return redirect()->route('ventas.clientes.vistaEspecificaListaCotizar', ['idLista' => $idLista]);
    }


    public function render()
    {
        return view('livewire.cliente.vista-de-lista');
    }

    public function desactivarLista($idLista)
    {
        Auth::user()->update(['lista' => null]);
        return redirect()->route('ventas.fichasTecnicas.fichasTecnicas');
    }
}
