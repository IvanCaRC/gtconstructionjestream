<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class ShowRoles extends Component
{
    public $open = false;
    public $roles; // Añadir esta línea
    protected $listeners = ['roleUpdate' => 'render'];
    public $roleEdit = [
        'id' => '',
        'name' => '',
        'description' => ''
    ];

    protected $rules = [
        'roleEdit.name' => 'required',
        'roleEdit.description' => 'required',
    ];

    public function render()
    {
        
        $this->roles = Role::all(); // Cargar todos los roles
        return view('livewire.show-roles', ['roles' => $this->roles]);
    }

    public function editRole($roleId)
    {
        $role = Role::findOrFail($roleId);
        $this->roleEdit['id'] = $role->id;
        $this->roleEdit['name'] = $role->name;
        $this->roleEdit['description'] = $role->description;
        $this->open = true;
    }




    public function updateRole()
    {
        $this->validate();
        $role = Role::findOrFail($this->roleEdit['id']);
        $role->update([
            'description' => $this->roleEdit['description'],
        ]);

        $this->reset('open', 'roleEdit');
        $this->resetValidation();
        $this->dispatch('roleUpdate');
        return true;
    }

    public function resetManual()
    {
        $this->reset('open', 'roleEdit');
        $this->resetValidation();
        $this->dispatch('roleUpdate');
    }
}
