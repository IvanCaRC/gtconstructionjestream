<?php

namespace App\Livewire;

use App\Models\User;
use DragonCode\PrettyArray\Services\Formatters\Php;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class CreateUser extends Component
{
    use WithFileUploads;
    public $open = false;
    public $name, $first_last_name, $second_last_name, $email, $number, $status = true, $password, $image;
    protected $rules= [
        'name' => 'required',
        'first_last_name' => 'required',
        'email' => 'required|email|unique:users,email',
        'status' => 'required',
        'password' => 'required'
    ];

    public function render()
    {
        return view('livewire.create-user');
    }

    public function save()
    {

        $this->validate();

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
            'password' => Hash::make($this->password),
        ]);

        $this->reset('open', 'name', 'first_last_name', 'second_last_name', 'email', 'number', 'status', 'password', 'image');
        $this->dispatch('userAdded');
    }
    public function resetManual() {
        $this->reset('open', 'name', 'first_last_name', 'second_last_name', 'email', 'number', 'status', 'password', 'image');
        $this->resetValidation();
        $this->dispatch('userAdded');
    }
}
