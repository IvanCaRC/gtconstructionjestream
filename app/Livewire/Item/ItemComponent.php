<?php

namespace App\Livewire\Item;

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
        // Consulta para el modelo Item y sus ItemEspecifico relacionados
        $query = Item::query()
            ->with(['itemEspecificos' => function ($query) {
                $query->where('estado_eliminacion', 1)
                    ->with(['familias', 'proveedores']); //Relaciones con familias y proveedores
                if ($this->searchTerm) {
                    $query->where('estado', 'LIKE', "%{$this->searchTerm}%")
                        ->orWhere('precio_venta_minorista', 'LIKE', "%{$this->searchTerm}%")
                        ->orWhere('precio_venta_mayorista', 'LIKE', "%{$this->searchTerm}%")
                        ->orWhere('unidad', 'LIKE', "%{$this->searchTerm}%");
                }
            }]);

        if ($this->searchTerm) {
            $query->where('nombre', 'LIKE', "%{$this->searchTerm}%")
                ->orWhere('updated_at', 'LIKE', "%{$this->searchTerm}%");
        }

        $items = $query->get();

        return view('livewire.item.item-component', [
            'items' => $items,
        ]);
    }
}
