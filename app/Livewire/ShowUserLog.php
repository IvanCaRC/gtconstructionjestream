<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ShowUserLog extends Component
{
    use WithFileUploads;

    public $currentUserId;
    public $open = false;
    public $open2 = false;
    public $user;
    public $iduser;
    public $userEditId = '';
    public $role;
    public $roles;
    public $current_password;
    public $new_password;
    public $confirm_password;
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
            'role' => 'required',
            'current_password' => 'required',
            'new_password' => 'required|min:8|same:confirm_password',
        ];
    }

    public function mount()
    {
        $this->currentUserId = Auth::user()->id;
        $this->user = User::findOrFail(Auth::user()->id);
        $this->roles = Role::where('id', '!=', 1)->get();
    }

    public function render()
    {
        return view('livewire.show-user-log', ['user' => $this->user, 'roles' => $this->roles]);
    }

    public function updatePassword()
    {   
        if (!$this->user) {
            throw ValidationException::withMessages(['user' => 'Usuario no encontrado.']);
        }
        if (!Hash::check($this->current_password, $this->user->password)) {
            throw ValidationException::withMessages(['current_password' => 'La contraseña actual no es correcta.']);
        }

        $this->user->update([
            'password' => Hash::make($this->new_password),
        ]);
        $this->reset('open2', 'current_password', 'new_password', 'confirm_password');
        $this->dispatch('userAddedEdit');
        return true;
    }


    public function update2()
    {
        $this->validate($this->rules());

        $user = User::find($this->userEditId);
        $image = $this->image ? ($this->image != $this->imageRecuperada ? $this->image->store('users', 'public') : $this->imageRecuperada) : $this->imageRecuperada;
        $user->update([
            'image' => $image,
            'name' => $this->userEdit['name'],
            'first_last_name' => $this->userEdit['first_last_name'],
            'second_last_name' => $this->userEdit['second_last_name'],
            'email' => $this->userEdit['email'],
            'number' => $this->userEdit['number'],
            'status' => $this->userEdit['status'],
        ]);

        $user->syncRoles([$this->role]);

        $this->reset('open', 'image', 'role');
        $this->dispatch('userAddedEdit');
        return true;
    }

    public function destroy($iduser)
    {
        $user = User::find($iduser);
        $user->delete();
        $this->dispatch('userAddedEdit');
        return redirect()->route('admin.users');
    }

    public function eliminar($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['estadoEliminacion' => true]);
        $this->dispatch('userAddedEdit');
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
        $this->imageRecuperada = $user->image;
        $this->role = $user->roles->pluck('name')->first();
    }
}
