<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    use HasFactory;

    protected $fillable = ['id_familia_padre', 'nombre', 'descripcion'];

    public function subfamilias()
    {
        return $this->hasMany(Familia::class, 'id_familia_padre');
    }

    public function subfamiliasRecursivas()
    {
        return $this->subfamilias()->with('subfamiliasRecursivas');
    }
}
