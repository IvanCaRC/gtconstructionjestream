<?php

namespace App\Livewire\Familia;

use Livewire\Component;
use App\Models\Familia;

class CreateCategoria extends Component
{
    public $nombre;
    public $descripcion;
    protected $listeners = ['FamiliaCreate' => 'render'];
    public $niveles = []; // Array para almacenar las familias de cada nivel
    public $seleccionadas = []; // Array para almacenar las opciones seleccionadas

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
    ];

    public function mount()
    {
        // Cargar las familias del nivel raíz (nivel 1) con estado_eliminacion = 0
        $this->niveles[1] = Familia::whereNull('id_familia_padre')
            ->where('estado_eliminacion', 0)
            ->get();
    }

    public function calcularSubfamilias($idFamiliaSeleccionada, $nivel)
    {
        // Llama al método del modelo y actualiza las propiedades locales
        $resultado = Familia::calcularSubfamilias($idFamiliaSeleccionada, $nivel, $this->niveles, $this->seleccionadas);

        $this->niveles = $resultado['niveles'];
        $this->seleccionadas = $resultado['seleccionadas'];
    }

    public function save()
    {
        // Validar los datos del formulario
        $this->validate();

        // Determinar el ID de la familia padre
        // Encontrar el último valor seleccionado que no sea 0
        $idFamiliaPadre = null;
        foreach (array_reverse($this->seleccionadas) as $seleccionada) {
            if ($seleccionada != 0) {
                $idFamiliaPadre = $seleccionada;
                break;
            }
        }

        // Calcular el nivel
        // Si hay una familia padre, el nivel será el de la familia padre + 1; de lo contrario, será 1
        $nivel = $idFamiliaPadre ? Familia::find($idFamiliaPadre)->nivel + 1 : 1;

        // Crear una nueva familia
        $familia = new Familia();
        $familia->nombre = $this->nombre;
        $familia->descripcion = $this->descripcion;
        $familia->estado_eliminacion = false; // Siempre guardar como "false"
        $familia->id_familia_padre = $idFamiliaPadre; // Asignar familia padre o `null`
        $familia->nivel = $nivel; // Asignar nivel
        $familia->save();

        // Resetear los campos
        $this->reset(['nombre', 'descripcion', 'niveles', 'seleccionadas']);
        $this->mount(); // Recargar las familias iniciales
        $this->dispatch('FamiliaCreate');

        // Mensaje de éxito (puede ser capturado en el frontend)
        session()->flash('message', 'La familia ha sido creada exitosamente.');

        return true;
    }




    public function render()
    {
        return view('livewire.familia.create-categoria');
    }
}
