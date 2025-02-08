<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    public $role;

    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable, HasRoles;

    protected $fillable = [
        'image', 'name', 'first_last_name', 'second_last_name', 'email', 'number', 'status', 'password','estadoEliminacion',
    ];

    protected $hidden = [
        'password', 'remember_token', 'two_factor_recovery_codes', 'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    public static function rules($prefix = '', $id = null)
    {
        return [
            $prefix . 'name' => 'required',
            $prefix . 'first_last_name' => 'required',
            $prefix . 'email' => 'required|email|unique:users,email,' . $id,
            $prefix . 'status' => 'required',
            $prefix . 'password' => 'required',
            'selectedRoles' => 'required',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif,webp|max:1024'
        ];
    }

    public static function messages($prefix = '')
{
    return [
        $prefix . 'name.required' => 'Registrar un nombre es obligatorio.',
        $prefix . 'first_last_name.required' => 'Al menos un apellido es obligatorio',
        $prefix . 'email.required' => 'El correo electrónico es obligatorio.',
        $prefix . 'email.email' => 'Debe proporcionar una dirección de correo electrónico válida.',
        $prefix . 'email.unique' => 'Esta dirección de correo electrónico ya está registrada.',
        $prefix . 'status.required' => 'El estado es obligatorio.',
        $prefix . 'password.required' => 'Registrar una contraseña es obligatorio',
        'selectedRoles.required' => 'Debe seleccionar al menos un rol.',
        'image.mimes' => 'La imagen debe ser un archivo de tipo: jpg, jpeg, png, gif, webp.',
        'image.max' => 'La imagen no puede exceder los 1024KB.',
    ];
}

}






