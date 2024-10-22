<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Spatie\Permission\Models\Role;

class CreateUser extends Component
{
    use WithFileUploads;

    public $open = false;
    public $name, $first_last_name, $second_last_name, $email, $number, $status = true, $password, $image;
    public $role; // Agregar esta línea
    public $roles;

    protected $rules = [
        'role' => 'required', 
        'name' => 'required',
        'first_last_name' => 'required',
        'email' => 'required|email|unique:users,email',
        'status' => 'required',
        'password' => 'required',
        'image' => 'nullable|mimes:jpg,jpeg,png,gif,webp|max:1024' // Validar que sea una imagen de ciertos tipos y tamaño máximo de 1MB
    ];
    
    public function render()
    {
        $this->role = '';
        $this->roles = Role::where('id', '!=', 1)->get(); // Excluir rol de Administrador
        return view('livewire.create-user', ['roles' => $this->roles]);
    }

    public function save()
{
    $this->validate();
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
    $user->assignRole($this->role); // Asignar el rol al usuario
    session()->flash('message', 'Usuario creado con éxito.');
    $this->reset('open', 'name', 'first_last_name', 'second_last_name', 'email', 'number', 'status', 'password', 'image', 'role');
    $this->dispatch('userAdded');

    return true; // Indicar que la creación fue exitosa
}


    public function resetManual()
    {
        $this->reset('open', 'name', 'first_last_name', 'second_last_name', 'email', 'number', 'status', 'password', 'image', 'role');
        $this->resetValidation();
        $this->dispatch('userAdded');
    }
}

