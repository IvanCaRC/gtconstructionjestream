<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ordenVenta extends Model
{
    use HasFactory;

    protected $table = 'orden_venta'; 

    protected $fillable = [
        'id_cliente',
        'id_usuario',
        'id_cotizacion',
        'direccion_id',
        'formaPago',
        'metodoPago',
        'monto',
        'estado',
    ];

    // public function proveedor()
    // {
    //     return $this->belongsTo(Proveedor::class); //Relacion con un proveedor registrado
    // }

    // public function familia()
    // {
    //     return $this->belongsTo(Familia::class); //Relacion con una familia registrada
    // }
}
