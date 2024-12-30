<?php
namespace App\Livewire\Familia;

use Livewire\Component;
use App\Models\Familia;

class CategoriaSelect extends Component
{
    public $familia;
    public $subfamilias = [];
    public $selectedSubfamilia = null;

    public function mount($familiaId = null)
    {
        if ($familiaId) {
            $this->familia = Familia::find($familiaId);
            $this->subfamilias = Familia::where('id_familia', $familiaId)->get();
        } else {
            $this->familia = null;
            $this->subfamilias = Familia::whereNull('id_familia')->get();
        }
    }

    public function updatedSelectedSubfamilia($subfamiliaId)
    {
        $this->emit('subcategoriaSelected', $subfamiliaId);
    }

    public function render()
    {
        return view('livewire.familia.categoria-select', [
            'subfamilias' => $this->subfamilias,
        ]);
    }
}
