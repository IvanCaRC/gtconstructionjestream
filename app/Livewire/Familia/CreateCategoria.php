<?php

namespace App\Livewire\Familia;

use Livewire\Component;
use App\Models\Familia;

class CreateCategoria extends Component
{
    public $nombre;
    public $descripcion;
    public $estado = false;
    public $id_familia;

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string|max:65535',
        'estado' => 'boolean',
        'id_familia' => 'nullable|exists:familias,id'
    ];

    public function submit()
    {
        $this->validate();

        Familia::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'estado' => $this->estado,
            'id_familia' => $this->id_familia
        ]);

        session()->flash('message', 'Categoría creada exitosamente.');

        return redirect()->to('/categorias');
    }

    public function render()
    {
        return view('livewire.familia.create-categoria');
    }
}
