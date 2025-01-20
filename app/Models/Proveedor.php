<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores'; // Nombre de la tabla

    protected $fillable = [
        'nombre',
        'descripcion',
        'correo',
        'rfc',
        'archivo_facturacion_pdf',
        'datos_bancarios_pdf',
        'estado',
        'estado_eliminacion',
    ];

    public function itemEspecificos()
    {
        return $this->belongsToMany(ItemEspecifico::class, 'item_especifico_proveedor', 'proveedor_id', 'item_especifico_id')
        ->withPivot('precio_compra'); // Incluir el campo precio_compra en la tabla intermedia 
    }
}

