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
        // Guardar la selección actual en el nivel correspondiente
        $this->seleccionadas[$nivel] = $idFamiliaSeleccionada;

        // Limpiar los niveles más profundos que este
        foreach ($this->niveles as $key => $value) {
            if ($key > $nivel) {
                unset($this->niveles[$key]);
                unset($this->seleccionadas[$key]);
            }
        }

        // Cargar subfamilias solo si hay una selección válida
        if ($idFamiliaSeleccionada != 0) {
            $familia = Familia::find($idFamiliaSeleccionada);
            if ($familia) {
                $this->niveles[$nivel + 1] = $familia->subfamilias()->get();
                // Restablecer el valor del siguiente select a "Seleccione una familia"
                $this->seleccionadas[$nivel + 1] = 0;
            }
        } else {
            // Si la selección no es válida, también restablece el valor a "Seleccione una familia"
            if (isset($this->niveles[$nivel + 1])) {
                unset($this->niveles[$nivel + 1]);
            }
        }
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
