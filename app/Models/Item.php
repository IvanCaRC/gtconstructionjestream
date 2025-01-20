<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // Definir la relación hasMany con ItemEspecifico 
    public function itemEspecificos()
    {
        return $this->hasMany(ItemEspecifico::class);
    }
}
