<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class ViewUser extends Component
{
    public $open =false;
    public $user;
    public $iduser;
    public $userEditId = '';
    protected $listeners = ['userAddedEdit' => 'render'];

    public $userEdit = [
        'id' => '',
        'image' => '',
        'name' => '',
        'first_last_name' => '',
        'second_last_name' => '',
        'email' => '',
        'number' => '',
        'status' => '',
        'password' => '',
    ];
    
    public function mount($iduser)
    {
        $this->user = User::findOrFail($iduser);
    }

    public function render()
    {
        return view('livewire.view-user', ['user' => $this->user]);
    }

    public function update()
    {
        // Validar los datos del formulario primero
        $this->validate([
            'userEdit.name' => 'required',
            'userEdit.first_last_name' => 'required',
            'userEdit.email' => 'required|email|unique:users,email,' . $this->userEditId,
            'userEdit.status' => 'required',
            'userEdit.password' => 'required'
        ]);

        $user = User::find($this->userEditId);

        // if ($this->userEdit['image'] && $this->userEdit['image']) {
        //     $imagePath = $this->userEdit['image']->store('users', 'public');
        //     $this->userEdit['image'] = $imagePath;
        // } else {
        //     $this->userEdit['image'] = $user->image;
        // }

        $user->update([
            'name' => $this->userEdit['name'],
            'first_last_name' => $this->userEdit['first_last_name'],
            'second_last_name' => $this->userEdit['second_last_name'],
            'email' => $this->userEdit['email'],
            'number' => $this->userEdit['number'],
            'status' => $this->userEdit['status'],
            'password' => $this->userEdit['password'],
            'image' => $this->userEdit['image'],
        ]);

        $this->reset('open');
        
        $this->dispatch('userAddedEdit');
    }
    public function destroy($iduser) {
        $user = User::find($iduser);
        $user->delete();
        $this->dispatch('userAdded');
    }

    public function edit($iduser){
        $this->userEditId = $iduser;
        $this->open = true;
        $user = User::find($iduser);
        $this->userEdit['id'] = $user->id;
        $this->userEdit['image'] = $user->image;
        $this->userEdit['name'] = $user->name;
        $this->userEdit['first_last_name'] = $user->first_last_name;
        $this->userEdit['second_last_name'] = $user->second_last_name;
        $this->userEdit['email'] = $user->email;
        $this->userEdit['number'] = $user->number;
        $this->userEdit['status'] = $user->status;
        $this->userEdit['password'] = $user->password;
    }
    
}
