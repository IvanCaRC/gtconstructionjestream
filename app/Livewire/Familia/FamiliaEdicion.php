<?php

namespace App\Livewire\Familia;

use App\Models\Familia;
use Livewire\Component;

class FamiliaEdicion extends Component
{
    public $familia;
    public function render()
    {
        return view('livewire.familia.familia-edicion');
    }

    public function mount($idfamilia)
    {
        $this->familia = Familia::findOrFail($idfamilia);
    }
}
