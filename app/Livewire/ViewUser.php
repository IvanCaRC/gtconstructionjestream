<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
class ViewUser extends Component
{
    use WithFileUploads;
    public $currentUserId;
    public $open = false;
    public $user;
    public $iduser;
    public $userEditId = '';
    public $role; // Añadir esta línea
    public $roles; // Añadir esta línea
    protected $listeners = ['userAddedEdit' => 'render'];
    public $sort = 'id';
    public $image;
    public $direction = 'desc';
    public $imageRecuperada;
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

    protected function rules()
    {
        return [
            'userEdit.name' => 'required',
            'userEdit.first_last_name' => 'required',
            'userEdit.email' => 'required|email|unique:users,email,' . $this->userEditId,
            'userEdit.status' => 'required',
            'userEdit.password' => 'required',
            'role' => 'required' // Añadir esta línea para la validación del rol
        ];
    }

    public function mount($iduser)
    {
        $this->currentUserId = Auth::user()->id;
        $this->user = User::findOrFail($iduser);
        $this->roles = Role::where('id', '!=', 1)->get(); // Excluir rol de Administrador
    }

    public function render()
    {
        return view('livewire.view-user', ['user' => $this->user, 'roles' => $this->roles]);
    }

    public function update2()
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
        $this->dispatch('userAddedEdit');
        return true; // Indicar que la actualización fue exitosa
    }

    public function destroy($iduser)
    {
        $user = User::find($iduser);
        $user->delete();
        $this->dispatch('userAddedEdit');
        // Redirigir a la ruta especificada
        return redirect()->route('admin.users');
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
}

