<?php

namespace App\Livewire\Item;

use App\Models\Familia;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Item;
use App\Models\ItemEspecifico;
use App\Models\ItemEspecificoHasFamilia;
use App\Models\ItemEspecificoProveedor;


class ItemComponent extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $sort = 'id';
    public $direction = 'desc';
    public $tipoDeVista = true;
    public $statusFiltroDeBusqueda;
    public $familiasSeleccionadas = [];
    public $desplegables = [];
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

    public function eliminar($itemId)
    {
        ItemEspecificoHasFamilia::where('item_especifico_id', $itemId)->delete();
        ItemEspecificoProveedor::where('item_especifico_id', $itemId)->delete();
        $ItemEspecifico = ItemEspecifico::findOrFail($itemId);
        $ItemEspecifico->update(['estado_eliminacion' => false]);
        $this->dispatch('renderVistaProv');
    }

    public function editItem($idItem)
    {
        return redirect()->route('compras.items.edicionItem', ['idItem' => $idItem]);
    }

    public function viewItem($idItem)
    {
        return redirect()->route('compras.items.vistaEspecificaItem', ['idItem' => $idItem]);
    }

    public function render()
    {
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

        return view('livewire.item.item-component', [
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
}
