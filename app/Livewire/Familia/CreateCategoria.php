<?php
namespace App\Livewire\Familia;

use Livewire\Component;
use App\Models\Familia;

class CreateCategoria extends Component
{
    public $nombre;
    public $descripcion;
    public $estado = false;
    public $selectedFamilia = null;

    protected $listeners = ['subcategoriaSelected' => 'handleSubcategoriaSelected'];

    public function handleSubcategoriaSelected($subcategoriaId)
    {
        $this->selectedFamilia = $subcategoriaId;
    }

    public function save()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'boolean',
        ]);

        Familia::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'estado' => $this->estado,
            'id_familia' => $this->selectedFamilia,
        ]);

        session()->flash('message', 'Familia registrada exitosamente.');
    }

    public function render()
    {
        return view('livewire.familia.create-categoria');
    }
}
