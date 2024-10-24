<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Component
{
    use WithFileUploads;

    public $open = false;
    public $name, $first_last_name, $second_last_name, $email, $number, $status = true, $password, $image;
    public $role;
    public $roles;

    public function render()
    {
        $this->role = '';
        $this->roles = Role::where('id', '!=', 1)->get(); // Excluir rol de Administrador
        return view('livewire.create-user', ['roles' => $this->roles]);
    }
    public function resetValidation2()
    {
        // Resetear solo la validación del campo 'role'
        $this->resetErrorBag('role');
    }
    
    

    public function save()
    {
        $this->validate(User::rules());

        $image = null;
        if ($this->image) {
            $image = $this->image->store('users', 'public');
        }

        $user = User::create([
            'image' => $image,
            'name' => $this->name,
            'first_last_name' => $this->first_last_name,
            'second_last_name' => $this->second_last_name,
            'email' => $this->email,
            'number' => $this->number,
            'status' => $this->status,
            'password' => Hash::make($this->password),
        ]);

        $user->assignRole($this->role);

        session()->flash('message', 'Usuario creado con éxito.');
        $this->reset('open', 'name', 'first_last_name', 'second_last_name', 'email', 'number', 'status', 'password', 'image', 'role');
        $this->dispatch('userAdded');

        return true;
    }

    public function resetManual()
    {
        $this->reset('open', 'name', 'first_last_name', 'second_last_name', 'email', 'number', 'status', 'password', 'image', 'role');
        $this->resetValidation();
        $this->dispatch('userAdded');
    }
}
