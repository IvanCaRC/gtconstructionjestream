<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemEspecificoProveedor extends Model
{
    use HasFactory;

    protected $table = 'item_especifico_proveedor';

    protected $fillable = [
        'item_especifico_id',
        'proveedor_id',
        'tiempo_max_entrega',
        'tiempo_min_entrega',
        'precio_compra',
        'estado',
    ];

    public function itemEspecifico()
    {
        return $this->belongsTo(ItemEspecifico::class);// Relacion con items registrados en el sistema
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class); //Relacion con proveedores registrados en el sistema
    }
}

