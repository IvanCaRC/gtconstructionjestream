<?php

namespace App\Livewire;

use App\Models\User;
use DragonCode\PrettyArray\Services\Formatters\Php;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class CreateUser extends Component
{
    use WithFileUploads;
    public $open = false;
    public $name, $first_last_name, $second_last_name, $email, $number, $status = true, $password, $image;

    public function render()
    {
        return view('livewire.create-user');
    }

    public function save()
    {
        $image = null;
        if ($this->image) {
            $image = $this->image->store('users', 'public');
        }

        User::create([
            'image' => $image,
            'name' => $this->name,
            'first_last_name' => $this->first_last_name,
            'second_last_name' => $this->second_last_name,
            'email' => $this->email,
            'number' => $this->number,
            'status' =>  $this->status,
            'password' => $this->password,
        ]);

        $this->reset('open', 'name', 'first_last_name', 'second_last_name', 'email', 'number', 'status', 'password', 'image');
        $this->dispatch('userAdded');
    }
}
