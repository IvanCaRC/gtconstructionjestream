<?php

namespace App\Livewire;

use Livewire\Component;

class CreateUser extends Component
{
    public $open = true;
    public function render()
    {
        return view('livewire.create-user');
    }
}
