<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
    protected $listeners = ['userAddedEdit' => 'render'];
    public $sort = 'id';
    public $image;
    public $direction = 'desc';
    public $imageRecuperada;
    public $current_password;
    public $new_password;
    public $confirm_password;
    public $selectedRoles = [];
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
        ];
    }

    public function resetManual()
    {
        $this->reset('open2', 'current_password', 'new_password', 'confirm_password');
        $this->resetValidation();
        $this->dispatch('userAddedEdit');
    }

    protected function rules2()
    {
        return [
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8|different:current_password',
            'confirm_password' => 'required|string|min:8|same:new_password',
        ];
    }

    protected function messages2()
    {
        return [
            'current_password.required' => 'Por favor, ingresa tu contraseña actual.',
            'current_password.min' => 'La contraseña actual debe tener al menos 8 caracteres.',
            'new_password.required' => 'Por favor, ingresa la nueva contraseña.',
            'new_password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'new_password.different' => 'La nueva contraseña debe ser diferente de la actual.',
            'confirm_password.required' => 'Por favor, confirma la nueva contraseña.',
            'confirm_password.same' => 'Las contraseñas no coinciden.',
        ];
    }

    public function updatePassword()
    {

        $user = User::find($this->currentUserId);
        if (!$user) {
            session()->flash('error', 'Usuario no encontrado.');
            return;
        }

        if (!Hash::check($this->current_password, $user->password)) {
            session()->flash('error', 'La contraseña actual no es correcta.');
            return;
        }

        if ($this->new_password !== $this->confirm_password) {
            session()->flash('error', 'Las contraseñas no coinciden.');
            return;
        }

        if (empty($this->new_password) || empty($this->confirm_password)) {
            session()->flash('error', 'La nueva contraseña no puede estar vacía.');
            return;
        }

        if (strlen($this->new_password) < 8) {
            session()->flash('error', 'La nueva contraseña debe tener al menos 8 caracteres.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        session()->flash('message', 'Contraseña actualizada correctamente.');
        $this->reset('open2', 'current_password', 'new_password', 'confirm_password');
        $this->dispatch('userAddedEdit');

        return true;
    }


    public function mount()
    {
        $this->currentUserId = Auth::user()->id;
        $this->user = User::findOrFail($this->currentUserId);
        $this->roles = Role::where('id', '!=', 1)->get(); // Excluir rol de Administrador
    }

    public function render()
    {
        if ($this->user->estadoEliminacion) {
            abort(404); // Lanza un error 404 si el usuario está eliminado
        }

        return view('livewire.show-user-log', ['user' => $this->user, 'roles' => $this->roles]);
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


        $this->reset('open', 'image', 'selectedRoles');
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
        $this->userEdit['password'] = $user->password;
        $this->imageRecuperada = $user->image;
    }
}
