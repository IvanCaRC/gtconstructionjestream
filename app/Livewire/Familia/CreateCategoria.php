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
    public $selectedSubfamilias = [];
    public $familias = [];
    public $subfamilias = [];

    public function mount()
    {
        $this->familias = Familia::whereNull('id_familia')->get();
        $this->subfamilias = [];
    }

    public function updateSelectedFamilia($value)
    {
        $this->selectedFamilia = $value;
        $this->selectedSubfamilias = [];
        $this->subfamilias = [];

        if ($this->selectedFamilia) {
            $familia = Familia::find($this->selectedFamilia);
            if ($familia && $familia->estado) {
                $this->loadSubfamilias($this->selectedFamilia, 1);
            }
        }
    }

    public function loadSubfamilias($familiaId, $nivel)
    {
        if ($familiaId) {
            $this->subfamilias[$nivel] = Familia::where('id_familia', $familiaId)->get();
        } else {
            $this->subfamilias[$nivel] = collect([]);
        }
    }

    public function updateSelectedSubfamilia($value, $key)
    {
        
        $nivel = intval($key) + 1;
        $this->selectedSubfamilias[$key] = $value;
        // Reiniciar niveles posteriores
        $this->selectedSubfamilias = array_slice($this->selectedSubfamilias, 0, $key + 1);
        $this->subfamilias = array_slice($this->subfamilias, 0, $nivel);

        if ($value) {
            $subfamilia = Familia::find($value);
            if ($subfamilia && $subfamilia->estado) {
                $this->loadSubfamilias($value, $nivel);
            }
        }
    }

    public function save()
    {
        // Lógica de guardado puede ir aquí si es necesario.
    }

    public function save2()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'boolean',
        ]);

        $ultimaSubfamiliaSeleccionada = end($this->selectedSubfamilias) ?: $this->selectedFamilia;

        Familia::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'estado' => $this->estado,
            'id_familia' => $ultimaSubfamiliaSeleccionada ?: null,
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
