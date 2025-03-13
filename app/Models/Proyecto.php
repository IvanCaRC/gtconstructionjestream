<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id', 'direccion_id', 'nombre','preferencia','tipo','estado','archivo', 'items_cotizar','datos_medidas','datos_adicionales','fecha'
    ];

}
