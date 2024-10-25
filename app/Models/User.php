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
            'role' => 'required',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif,webp|max:1024'
        ];
    }

    public function getRoleView()
    {
        $this->role= '';
        $this->role = $this->roles()->first()->name;

        switch ($this->role) {
            case 'Administrador':
                return 'admin.dashboardAdmin';
            case 'Ventas':
                return 'ventas.dashboardVentas';
            case 'Compras':
                return 'compras.dashboardCompras';
            case 'Finanzas':
                return 'finanzas.dashboardFinanzas';
            default:
                return 'home';
        }
    }
}






