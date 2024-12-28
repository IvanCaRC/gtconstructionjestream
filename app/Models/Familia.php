<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    use HasFactory;

    protected $fillable = ['id_familia', 'nombre', 'descripcion', 'estado'];

    public function subfamilias()
    {
        return $this->hasMany(Familia::class, 'id_familia')->where('estado', 0);
    }
}
