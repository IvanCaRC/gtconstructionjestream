<?php

namespace App\Livewire\Familia;

use Livewire\Component;
use App\Models\Familia;

class CreateCategoria extends Component
{
    public $nombre;
    public $descripcion;

    public $niveles = []; // Array para almacenar las familias de cada nivel
    public $seleccionadas = []; // Array para almacenar las opciones seleccionadas

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
    ];

    public function mount()
    {
        // Cargar las familias del nivel raíz (nivel 1)
        $this->niveles[1] = Familia::whereNull('id_familia_padre')->get();
    }

    public function calcularSubfamilias($idFamiliaSeleccionada, $nivel)
    {
        // Llama al método del modelo y actualiza las propiedades locales
        $resultado = Familia::calcularSubfamilias($idFamiliaSeleccionada, $nivel, $this->niveles, $this->seleccionadas);

        $this->niveles = $resultado['niveles'];
        $this->seleccionadas = $resultado['seleccionadas'];
    }

    public function submit()
    {
        $this->validate();

        $familia = new Familia();
        $familia->nombre = $this->nombre;
        $familia->descripcion = $this->descripcion;
        $familia->nivel = 1; // Nivel inicial ya que no hay familia padre

        $familia->save();

        session()->flash('message', 'Familia creada exitosamente!');

        $this->reset(['nombre', 'descripcion', 'niveles', 'seleccionadas']);
        $this->mount(); // Recargar niveles iniciales
    }

    public function render()
    {
        return view('livewire.familia.create-categoria');
    }
}
