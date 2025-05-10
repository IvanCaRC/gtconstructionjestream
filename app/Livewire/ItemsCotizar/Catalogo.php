<?php

namespace App\Livewire\ItemsCotizar;

use App\Models\Cotizacion;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Familia;
use App\Models\ItemEspecifico;
use App\Models\ListasCotizar;
use Illuminate\Support\Facades\Auth;

class Catalogo extends Component
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

    public $idCotizaciones;

    public function mount()
    {
        // Obtener el usuario actual
        $this->usuarioActual = Auth::user();

        if ($this->usuarioActual->cotizaciones) {
            // Recuperamos el proyecto relacionado
            // Si se encuentra el registro, guardar el nombre; si no, asignar null
            $cotizacionId = $this->usuarioActual->cotizaciones;
            $cotizacionActiva = Cotizacion::find($cotizacionId);
            if ($cotizacionActiva) {
                // Si existe la lista activa, obtener sus detalles
                $this->idCotizaciones = $cotizacionActiva->id;
                $this->listadeUsuarioActiva = $cotizacionActiva->nombre ?? 'Sin nombre';
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
        $this->idCotizaciones = null;
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
        return redirect()->route('compras.catalogoCotisacion.vistaEspecificaItemCotizar', ['idItem' => $idItem]);
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

        return view('livewire.items-cotizar.catalogo', [
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
