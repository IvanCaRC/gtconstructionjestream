<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class ShowUsers extends Component
{
    use WithFileUploads, WithPagination;

    public $currentUserId;
    public $searchTerm = '';
    public $statusFiltroDeBusqueda;
    public $roleFiltroDeBusqueda;
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

    public function mount()
    {
        $this->currentUserId = Auth::user()->id;
        $this->roles = Role::where('id', '!=', 1)->get(); // Cargar todos los roles excepto Administrador
    }

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['statusFiltroDeBusqueda', 'roleFiltroDeBusqueda'])) {
            $this->filter();  // Llama al método de filtro para restablecer la página y aplicar los filtros
        }
    }

    public function filter()
    {
        $this->resetPage();  // Asegura que la paginación se restablezca
    }


    public function viewUser($userId)
    {
        return redirect()->route('admin.usersView', ['iduser' => $userId]);
    }

    protected $listeners = ['userAdded' => 'render'];

    public function render()
    {
        $query = User::where('estadoEliminacion', false);

        // Búsqueda por término
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('name', 'LIKE', "%{$this->searchTerm}%")
                    ->orWhere('first_last_name', 'LIKE', "%{$this->searchTerm}%")
                    ->orWhere('second_last_name', 'LIKE', "%{$this->searchTerm}%")
                    ->orWhere(DB::raw("CONCAT(first_last_name, ' ', second_last_name, ' ', name)"), 'LIKE', "%{$this->searchTerm}%")
                    ->orWhere('email', 'LIKE', "%{$this->searchTerm}%")
                    ->orWhere('number', 'LIKE', "%{$this->searchTerm}%");
            });
        }

        // Filtro de estado
        if ($this->statusFiltroDeBusqueda !== "2" && $this->statusFiltroDeBusqueda !== null) {
            $query->where('status', $this->statusFiltroDeBusqueda);
        }

        // // Filtro de roles
        if ($this->roleFiltroDeBusqueda) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->roleFiltroDeBusqueda);
            });
        }

        $users = $query->orderBy($this->sort, $this->direction)
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
