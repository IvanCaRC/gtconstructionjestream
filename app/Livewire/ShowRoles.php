<?php

namespace App\Livewire;

use Livewire\Component;

class ShowRoles extends Component
{
    public $open = true;
    public function render()
    {
        return view('livewire.show-roles');
    }
}
