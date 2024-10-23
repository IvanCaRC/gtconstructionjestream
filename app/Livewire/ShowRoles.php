<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Role;

class ShowRoles extends Component
{
    public $open = false;
    public $roles;
    protected $listeners = ['roleUpdate' => 'render'];
    public $roleEdit = [
        'id' => '',
        'name' => '',
        'description' => ''
    ];

    public function render()
    {
        $this->roles = Role::all();
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
        $this->validate(Role::rules($this->roleEdit['id']));
        $role = Role::findOrFail($this->roleEdit['id']);
        $role->update([
            'description' => $this->roleEdit['description'],
        ]);
        $this->reset('open', 'roleEdit');
        $this->resetValidation();
        $this->dispatch('roleUpdate');
        return true;
    }

    public function getShortDescription($description)
    {
        $maxLength = 100;
        if (strlen($description) > $maxLength) {
            return substr($description, 0, $maxLength) . '...';
        }
        return $description;
    }

    public function resetManual()
    {
        $this->reset('open', 'roleEdit');
        $this->resetValidation();
        $this->dispatch('roleUpdate');
    }
}

