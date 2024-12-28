<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemEspecifico extends Model
{
    use HasFactory;

    protected $table = 'item_especifico'; 

    protected $fillable = [
        'item_id',
        'marca',
        'cantidad_piezas_mayoreo',
        'cantidad_piezas_minorista',
        'porcentaje_venta',
        'precio_venta',
        'unidad',
        'especificaciones',
        'ficha_tecnica_pdf',
        'estado',
    ];

    
    public function item()
    {
        return $this->belongsTo(Item::class);//Relacion con la entidad item
    }
}

