<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class ShowUsers extends Component
{
    use WithFileUploads;

    public $searchTerm = '';
    public $sort = 'id';
    public $image;
    public $direction = 'desc';
    public $open = false;
    public $userEditId = '';
    public $imageRecuperada;
    public $role; // Añadir esta línea
    public $roles; // Añadir esta línea
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
        'userEdit.email' => 'required|email|unique:users,email',
        'userEdit.status' => 'required',
        'userEdit.password' => 'required',
        'role' => 'required'
    ];

    protected function rules()
    {
        return [
            'userEdit.name' => 'required',
            'userEdit.first_last_name' => 'required',
            'userEdit.email' => 'required|email|unique:users,email,' . $this->userEditId,
            'userEdit.status' => 'required',
            'userEdit.password' => 'required',
            'role' => 'required'
        ];
    }

    public function viewUser($userId)
    {
        return redirect()->route('admin.usersView', ['iduser' => $userId]);
    }

    protected $listeners = ['userAdded' => 'render'];

    public function render()
    {
        $this->roles = Role::where('id', '!=', 1)->get(); // Excluir rol de Administrador
        $users = User::where('name', 'LIKE', "%$this->searchTerm%")
            ->orWhere('first_last_name', 'LIKE', "%$this->searchTerm%")
            ->orWhere('second_last_name', 'LIKE', "%$this->searchTerm%")
            ->orWhere(DB::raw("CONCAT(name, ' ', first_last_name, ' ', second_last_name)"), 'LIKE', "%$this->searchTerm%")
            ->orWhere('email', 'LIKE', "%$this->searchTerm%")
            ->orWhere('number', 'LIKE', "%$this->searchTerm%")
            ->orderBy($this->sort, $this->direction)
            ->with('roles') // Cargar la relación con los roles
            ->get();

        return view('livewire.show-users', [
            'users' => $users,
            'roles' => $this->roles
        ]);
    }
    public function search() {}

    public function update()
    {
        // Validar los datos del formulario primero
        $this->validate($this->rules());

        $user = User::find($this->userEditId);
        $image = null; // Mantener la imagen actual por defecto

        // Verificar si se subió una nueva imagen
        if ($this->image) {
            // Verificar si la nueva imagen es diferente de la imagen recuperada
            if ($this->image != $this->imageRecuperada) {
                $image = $this->image->store('users', 'public');
            } else {
                $image = $this->imageRecuperada; // Mantener la imagen recuperada si es igual
            }
        } else {
            $image = $this->imageRecuperada; // Mantener la imagen recuperada si no hay nueva imagen
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

        $user->syncRoles([$this->role]); // Asignar el rol al usuario

        $this->reset('open', 'image', 'role');
        $this->dispatch('userAdded');
    }


    public function resetManual()
    {
        $this->reset('open', 'role');
        $this->resetValidation();
        $this->dispatch('userAdded');
    }

    public function destroy($userId)
    {
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
        $this->imageRecuperada = $user->image;
        $this->role = $user->roles->pluck('name')->first(); // Asignar el rol actual del usuario
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
