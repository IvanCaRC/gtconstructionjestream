<?php

namespace App\Livewire\Cliente;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Familia;
use App\Models\ItemEspecifico;
use App\Models\ListasCotizar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FichasTecnicas extends Component
{
    use WithPagination;


    protected $listeners = [
        'mountFichasTecnicas' => 'mount',
        'renderFichasTecnicas' => 'render',
    ];

    public $searchTerm = '';
    public $sort = 'id';
    public $direction = 'desc';
    public $tipoDeVista = true;
    public $statusFiltroDeBusqueda;
    public $familiasSeleccionadas = [];
    public $desplegables = [];
    public $listadeUsuarioActiva;
    public $usuarioActual;

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



    public function seleccionarFamilia($familiaId)
    {
        $familia = Familia::with('subfamiliasRecursivas')->find($familiaId);

        if (!$familia) {
            return;
        }

        // Obtener todos los IDs de la familia y sus subfamilias
        $idsFamilia = $this->obtenerTodosLosIds($familia);

        if (in_array($familiaId, $this->familiasSeleccionadas)) {
            // Si ya estaba seleccionada, eliminar todas las familias relacionadas
            $this->familiasSeleccionadas = array_diff($this->familiasSeleccionadas, $idsFamilia);
        } else {
            // Agregar todas las familias relacionadas
            $this->familiasSeleccionadas = array_merge($this->familiasSeleccionadas, $idsFamilia);
        }

        // Eliminar duplicados
        $this->familiasSeleccionadas = array_unique($this->familiasSeleccionadas);
    }

    public $itemsLista = [];

    public function agregarItemLista($idItem)
    {
        // Buscar la lista existente (asegúrate de tener el ID de la lista almacenado

        if ($this->idLista !== null) {
            $lista = ListasCotizar::find($this->idLista);

            if (!$lista) {
                session()->flash('error', 'No se encontró la lista de cotización.');
                return;
            }

            // Decodificar los items ya guardados
            $items = json_decode($lista->items_cotizar, true) ?? [];

            // Buscar si el item ya está en la lista
            $itemKey = array_search($idItem, array_column($items, 'id'));

            if ($itemKey !== false) {
                // Si el item ya existe, aumentar la cantidad
                $items[$itemKey]['cantidad'] += 1; 
            } else {
                // Si el item no existe, agregarlo con cantidad inicial 1
                $items[] = [
                    'id' => $idItem,
                    'cantidad' => 1,
                    'estado' => 0,
                ];
            }

            // Guardar la lista actualizada en la base de datos
            $lista->update([
                'items_cotizar' => json_encode($items)
            ]);

            $this->itemsEnLista = array_column($items, 'id');
            return redirect()->route('ventas.clientes.vistaEspecificaListaCotizar', ['idLista' => $lista->id]);
        } else {
            $usuario  = $this->usuarioActual->id;
           

            $user = Auth::user();
            $idUser = $user->id;

            $listaACotizar = ListasCotizar::create([
                'usuario_id' => $idUser,
                'estado' => 1,
                'estado' => 0,
            ]);

            Auth::user()->update(['lista' => $listaACotizar->id]);
            
            $this->idLista = $listaACotizar->id;
            //
            $items = json_decode($listaACotizar->items_cotizar, true) ?? [];

            // Buscar si el item ya está en la lista
            $itemKey = array_search($idItem, array_column($items, 'id'));

            if ($itemKey !== false) {
                // Si el item ya existe, aumentar la cantidad
                $items[$itemKey]['cantidad'] += 1;
            } else {
                // Si el item no existe, agregarlo con cantidad inicial 1
                $items[] = [
                    'id' => $idItem,
                    'cantidad' => 1,
                    'estado' => 0,
                ];
            }

            // Guardar la lista actualizada en la base de datos
            $listaACotizar->update([
                'items_cotizar' => json_encode($items)
            ]);

            $this->itemsEnLista = array_column($items, 'id');
            //

            return redirect()->route('ventas.clientes.vistaEspecificaListaCotizar', ['idLista' => $listaACotizar->id]);
        }
    }


    /**
     * Función recursiva para obtener todos los IDs de una familia y sus subfamilias
     */
    private function obtenerTodosLosIds($familia)
    {
        $ids = [$familia->id];

        foreach ($familia->subfamiliasRecursivas as $subfamilia) {
            $ids = array_merge($ids, $this->obtenerTodosLosIds($subfamilia));
        }

        return $ids;
    }

    public function search()
    {
        $this->resetPage();
    }

    public function viewItem($idItem)
    {
        $item = ItemEspecifico::find($idItem);

        if ($item === null) {
            abort(404, 'item no encontrada');
        }
        return redirect()->route('ventas.fichasTecnicas.fichaEspecificaItem', ['idItem' => $idItem]);
    }

    public function render()
    {
        // return view('livewire.cliente.fichas-tecnicas');
        $queryfamilia = Familia::whereNull('id_familia_padre')
            ->where('estadoEliminacion', 0);

        $familias = $queryfamilia->with(['subfamiliasRecursivas' => function ($q) {
            $q->where('estadoEliminacion', 0); // Filtrar subfamilias por estado_eliminacion igual a 0
        }])->get();

        $query = ItemEspecifico::query()
            ->where('estado_eliminacion', 1)
            ->with(['item', 'familias', 'proveedores']);

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('estado', 'LIKE', "%{$this->searchTerm}%")
                    ->orWhere('precio_venta_minorista', 'LIKE', "%{$this->searchTerm}%")
                    ->orWhere('precio_venta_mayorista', 'LIKE', "%{$this->searchTerm}%")
                    ->orWhere('unidad', 'LIKE', "%{$this->searchTerm}%")
                    ->orWhereHas('item', function ($query) {
                        $query->where('nombre', 'LIKE', "%{$this->searchTerm}%");
                    });
            });
        }
        if (!empty($this->familiasSeleccionadas)) {
            $query->whereHas('familias', function ($q) {
                $q->whereIn('familia_id', $this->familiasSeleccionadas);
            });
        }

        if ($this->statusFiltroDeBusqueda !== "2" && $this->statusFiltroDeBusqueda !== null) {
            $query->where('estado', $this->statusFiltroDeBusqueda);
        }

        $itemEspecificos = $query->paginate(35);

        return view('livewire.cliente.fichas-tecnicas', [
            'itemEspecificos' => $itemEspecificos,
            'familias' => $familias, // Agregar familias a la vista
        ]);
    }

    public function filter()
    {
        $this->resetPage();  // Asegura que la paginación se restablezca
    }

    public function toggleDesplegable($idfamilia)
    {
        if (isset($this->desplegables[$idfamilia])) {
            $this->desplegables[$idfamilia] = !$this->desplegables[$idfamilia];
        } else {
            $this->desplegables[$idfamilia] = true;
        }
    }

    public function verLista($idLista)
    {
        return redirect()->route('ventas.clientes.vistaEspecificaListaCotizar', ['idLista' => $idLista]);
    }
}
