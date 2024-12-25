<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes'; // Nombre de la tabla

    protected $fillable = [
        'nombre',
        'correo',
        'rfc',
        'cuenta',
        'clave',
        'telefono',
        'fecha',
        'user_id',
    ];

    // Definir relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Definir relación con el modelo Direccion
    public function direcciones()
    {
        return $this->hasMany(Direccion::class);
    }
}

