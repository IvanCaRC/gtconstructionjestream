<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ShowUsers extends Component
{
    use WithFileUploads;

    public $searchTerm = '';
    public $open = false;
    public $sort = 'id';
    public $direction = 'desc';
    public $userEditId = '';
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

    protected $rules = [
        'userEdit.name' => 'required',
        'userEdit.first_last_name' => 'required',
        'userEdit.email' => 'required|email',
        'userEdit.status' => 'required',
        'userEdit.password' => 'required'
    ];


    protected $listeners = ['userAdded' => 'render'];

    public function render()
    {
        $users = User::where('name', 'LIKE', "%$this->searchTerm%")
            ->orWhere('first_last_name', 'LIKE', "%$this->searchTerm%")
            ->orWhere('second_last_name', 'LIKE', "%$this->searchTerm%")
            ->orWhere(DB::raw("CONCAT(name, ' ', first_last_name, ' ', second_last_name)"), 'LIKE', "%$this->searchTerm%")
            ->orWhere('email', 'LIKE', "%$this->searchTerm%")
            ->orWhere('number', 'LIKE', "%$this->searchTerm%")
            ->orderBy($this->sort, $this->direction)
            ->get();

        return view('livewire.show-users', compact('users'));
    }

    public function search() {}

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

        if ($this->userEdit['image'] && $this->userEdit['image']) {
            $imagePath = $this->userEdit['image']->store('users', 'public');
            $this->userEdit['image'] = $imagePath;
        } else {
            $this->userEdit['image'] = $user->image;
        }

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
        $this->resetValidation();
        $this->dispatch('userAdded');
    }

    public function destroy($userId) {
        $user = User::find($userId);
        $user->delete();
        $this->dispatch('userAdded');
    }


    public function edit($userId)
    {
        $this->open = true;
        $this->userEditId = $userId;
        $user = User::find($userId);

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
