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
    $query = Familia::whereNull('id_familia_padre')
        ->where('estado_eliminacion', 0); // Filtrar por estado_eliminacion igual a 0

    if ($this->searchTerm) {
        $query->where('nombre', 'LIKE', "%{$this->searchTerm}%");
    }

    $familias = $query->with(['subfamiliasRecursivas' => function ($q) {
        $q->where('estado_eliminacion', 0); // Filtrar subfamilias por estado_eliminacion igual a 0
    }])->paginate(10);

    return view('livewire.familia.familia-component', [
        'familias' => $familias
    ]);
}


    public function editCategory($id)
    {
        // Lógica para editar una categoría
    }

    public function deleteCategory($id)
    {
        $familia = Familia::find($id);

        if ($familia) {
            $familia->delete();
            session()->flash('message', 'Familia eliminada correctamente.');
        } else {
            session()->flash('error', 'Familia no encontrada.');
        }
    }
}
