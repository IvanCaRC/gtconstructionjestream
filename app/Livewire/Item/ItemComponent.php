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
    
        $itemEspecificos = $query->paginate(2);
    
        return view('livewire.item.item-component', [
            'itemEspecificos' => $itemEspecificos,
        ]);
    }
    
}
