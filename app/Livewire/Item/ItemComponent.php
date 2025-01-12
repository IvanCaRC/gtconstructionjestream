<?php

namespace App\Livewire\Item;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Item;
use App\Models\ItemEspecifico;

class ItemComponent extends Component
{

    use WithPagination;

    public $searchTerm = '';
    public $sort = 'id';
    public $direction = 'desc';

    public function search()
    {
        $this->resetPage();
    }

    public function render()
    {

        $query1 = Item::query()->select('nombre', 'updated_at')->get();
        $query2 = ItemEspecifico::where('estado_eliminacion', 1);

        if ($this->searchTerm) {
            $query1->where(function ($q) {
                $q->where('nombre', 'LIKE', "%{$this->searchTerm}%")
                    ->orWhere('updated_at', 'LIKE', "%{$this->searchTerm}%");
            });
        }

        if ($this->searchTerm) {
            $query2->where(function ($q) {
                $q->where('marca', 'LIKE', "%{$this->searchTerm}%");
            });
        }


        $item1 = $query1;

        return view('livewire.item.item-component', [
            'items1' => $item1
        ]);
        
    }
}
