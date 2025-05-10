<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ordenCompra extends Model
{
    use HasFactory;

    protected $table = 'orden_compra';
    
    protected $fillable = [
        'id_provedor',
        'id_cotizacion',
        'id_usuario',
        'formaPago',
        'modalidad',
        'monto',
        'montoPagar',
        'items_cotizar_proveedor',
        'estado',
        'nombre',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_provedor');
    }

    /**
     * Relación con el modelo User para el usuario de compras.
     */

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'id_cotizacion');
    }
}
