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
        // Obtener el usuario actual
        $this->usuarioActual = Auth::user();

        // Consultar el primer registro con estado 0 para el usuario actual
        $registro = ListasCotizar::where('usuario_id', $this->usuarioActual->id) // Verificar usuario actual
            ->where('estado', 1) // Estado igual a 0
            ->first(); // Obtener el primer registro (o null si no hay)

        if ($registro) {
            // Recuperamos el proyecto relacionado
            // Si se encuentra el registro, guardar el nombre; si no, asignar null
            $this->idLista = $registro->id;
            $this->listadeUsuarioActiva = $registro->nombre ?? 'Sin nombre';
            $proyecto = $registro->proyecto ?? 'Sin proyecto';
            $cliente = $proyecto->cliente ?? 'Sin cliente    ';

            // Asignamos los valores
            $this->nombreProyecto = $proyecto->nombre ?? 'Sin nombre';

            $this->nombreCliente = $cliente->nombre ?? 'Sin cliente';

            // Obtener los IDs de los items en la lista
            $itemsData = json_decode($registro->items_cotizar, true) ?? [];
            $this->itemsEnLista = array_column($itemsData, 'id');
        } else {
            // Si no existe el registro
            $this->idLista = null;
            $this->listadeUsuarioActiva = null;
            $this->nombreProyecto = null;
            $this->nombreCliente = null;
        }
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
        $lista = ListasCotizar::find($idLista);

        $lista->update([
            'estado' => 2
        ]);

        return redirect()->route('ventas.fichasTecnicas.fichasTecnicas');
    }
}
