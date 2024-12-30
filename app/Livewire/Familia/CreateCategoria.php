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
    public $selectedSubfamilias = []; // Arreglo para almacenar las subfamilias seleccionadas
    public $familias = [];
    public $subfamilias = [];

    public function mount()
    {
        $this->familias = Familia::whereNull('id_familia')->get();
        $this->subfamilias = [];
    }

    public function loadSubfamilias($familiaId, $nivel)
    {
        if ($familiaId) {
            $this->subfamilias[$nivel] = Familia::where('id_familia', $familiaId)->get();
        } else {
            $this->subfamilias[$nivel] = collect([]);
        }
    }

    public function updateSubfamilias($value, $key)
    {
        $nivel = intval($key) + 1;
        $this->loadSubfamilias($value, $nivel);
    }

    public function save()
    {
        $this->loadSubfamilias($this->selectedFamilia, 0);
    }

    public function save2()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'boolean',
        ]);

        // Obtener el id de la última subfamilia seleccionada o de la familia principal
        $ultimaSubfamiliaSeleccionada = end($this->selectedSubfamilias) ?: $this->selectedFamilia;

        Familia::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'estado' => $this->estado,
            'id_familia' => $ultimaSubfamiliaSeleccionada ?: null, // Usar null si no hay familia seleccionada
        ]);

        session()->flash('message', 'Familia registrada exitosamente.');
    }

    public function render()
    {
        return view('livewire.familia.create-categoria', [
            'familias' => $this->familias,
            'subfamilias' => $this->subfamilias,
            'selectedSubfamilias' => $this->selectedSubfamilias,
        ]);
    }
}
