<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
class ShowUsers extends Component
{

    public $searchTerm = '';

    public $sort = 'id';
    public $direction = 'desc';
    protected $listeners = ['userAdded' => 'render'];

    public function render()
    {
        $users = User::where('name', 'LIKE', "%$this->searchTerm%")
            ->orWhere('first_last_name', 'LIKE', "%$this->searchTerm%")
            ->orWhere('second_last_name', 'LIKE', "%$this->searchTerm%")
            ->orWhere('email', 'LIKE', "%$this->searchTerm%")
            ->orWhere('number', 'LIKE', "%$this->searchTerm%")
            ->orderBy($this->sort, $this->direction)
            ->get();
        return view('livewire.show-users', compact('users'));
    }

    public function search()
    {

    }
    
    public function order($sort)
    {
        if ($this->sort == $sort) {
            if ($this->direction == 'desc') {
                $this->direction = 'asc';
            } else {
                $this->direction = 'desc';
            }
        } else {
            $this->sort = $sort;
            $this->direction = 'asc';
        }
    }

   
}
