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
        return $this->belongsTo(Item::class); // Relación con la entidad item
    }

    public function familias()
    {
        return $this->belongsToMany(Familia::class, 'item_especifico_has_familia', 'item_especifico_id', 'familia_id');
    }

    public function proveedores()
     { 
        return $this->belongsToMany(Proveedor::class, 'item_especifico_proveedor', 'item_especifico_id', 'proveedor_id') ->withPivot('precio_compra'); // Incluir el campo precio_compra en la tabla intermedia 
     }
}