<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ShowUsers extends Component
{
    use WithFileUploads, WithPagination;

    public $currentUserId;
    public $searchTerm = '';
    public $sort = 'id';
    public $image;
    public $direction = 'desc';
    public $open = false;
    public $userEditId = '';
    public $imageRecuperada;
    public $role;
    public $roles;
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

    public function viewUser($userId)
    {
        return redirect()->route('admin.usersView', ['iduser' => $userId]);
    }

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->currentUserId = Auth::user()->id;
    }

    protected $listeners = ['userAdded' => 'render'];

    public function render()
    {
        $this->roles = Role::where('id', '!=', 1)->get();

        $users = User::where('estadoEliminacion', false)
            ->where(function ($query) {
                $query->where('name', 'LIKE', "%{$this->searchTerm}%")
                    ->orWhere('first_last_name', 'LIKE', "%{$this->searchTerm}%")
                    ->orWhere('second_last_name', 'LIKE', "%{$this->searchTerm}%")
                    ->orWhere(DB::raw("CONCAT(name, ' ', first_last_name, ' ', second_last_name)"), 'LIKE', "%{$this->searchTerm}%")
                    ->orWhere('email', 'LIKE', "%{$this->searchTerm}%")
                    ->orWhere('number', 'LIKE', "%{$this->searchTerm}%");
            })
            ->orderBy($this->sort, $this->direction)
            ->with('roles')
            ->paginate(10);

        return view('livewire.show-users', [
            'users' => $users,
            'roles' => $this->roles
        ]);
    }


    public function search()
    {
        $this->resetPage();
    }

    public function update()
    {
        $this->validate(User::rules('userEdit.', $this->userEditId));

        $user = User::find($this->userEditId);
        $image = null;

        if ($this->image) {
            if ($this->image != $this->imageRecuperada) {
                $image = $this->image->store('users', 'public');
            } else {
                $image = $this->imageRecuperada;
            }
        } else {
            $image = $this->imageRecuperada;
        }

        $user->update([
            'image' => $image,
            'name' => $this->userEdit['name'],
            'first_last_name' => $this->userEdit['first_last_name'],
            'second_last_name' => $this->userEdit['second_last_name'],
            'email' => $this->userEdit['email'],
            'number' => $this->userEdit['number'],
            'status' => $this->userEdit['status'],
            'password' => $this->userEdit['password'],
        ]);

        $user->syncRoles([$this->role]);

        $this->reset('open', 'image', 'role');
        $this->dispatch('userAdded');
        return true;
    }

    public function resetManual()
    {
        $this->reset('open', 'role');
        $this->resetValidation();
        $this->dispatch('userAdded');
    }

    public function eliminar($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['estadoEliminacion' => true]);
    
        $this->dispatch('userAdded'); // Si necesitas refrescar la lista de usuarios
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
        $this->imageRecuperada = $user->image;
        $this->role = $user->roles->pluck('name')->first();
    }

    public function order($sort)
    {
        if ($this->sort == $sort) {
            $this->direction = $this->direction == 'desc' ? 'asc' : 'desc';
        } else {
            $this->sort = $sort;
            $this->direction = 'asc';
        }
    }
}
