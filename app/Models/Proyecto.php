<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id', 'direccion_id', 'proceso','nombre','preferencia','listas','cotisaciones','ordenes','tipo','estado','archivo', 'items_cotizar','datos_medidas','datos_adicionales','fecha'
    ];
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

}
