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
    public $selectedRoles = [];
    public $roles;

    public function render()
    {
        $this->roles = Role::where('id', '!=', 1)->get(); // Excluir rol de Administrador
        return view('livewire.create-user', ['roles' => $this->roles]);
    }

    public function resetValidation2()
    {
        $this->resetErrorBag('selectedRoles');
    }

    public function save()
    {
        $this->validate(User::rules(), User::messages());

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

        // Asignar múltiples roles seleccionados
        foreach ($this->selectedRoles as $role) {
            $user->assignRole($role);
        }

        session()->flash('message', 'Usuario creado con éxito.');

        $this->reset('open', 'name', 'first_last_name', 'second_last_name', 'email', 'number', 'status', 'password', 'image', 'selectedRoles');
        $this->dispatch('userAdded');

        return true;
    }

    public function resetManual()
    {
        $this->reset('open', 'name', 'first_last_name', 'second_last_name', 'email', 'number', 'status', 'password', 'image', 'selectedRoles');
        $this->resetValidation();
        $this->dispatch('userAdded');
    }
}
