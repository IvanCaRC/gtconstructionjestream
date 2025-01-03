<?php

namespace App\Livewire\Familia;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Familia;

class FamiliaComponent extends Component
{
    use WithPagination;

    public $searchTerm;

    public function search()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Familia::whereNull('id_familia_padre');

        if ($this->searchTerm) {
            $query = Familia::query();
            $query->where(function ($q) {
                $q->where('nombre', 'LIKE', "%{$this->searchTerm}%");
            });
        }

        $familias = $query->with('subfamiliasRecursivas')->paginate(10);

        return view('livewire.familia.familia-component', [
            'familias' => $familias
        ]);
    }

    public function editCategory($id)
    {
        // Lógica para editar una categoría
    }
}
