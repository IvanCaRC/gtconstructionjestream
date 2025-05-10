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
        'montoPagar',
        'estado',
        'nombre',
    ];

  public function usuario(){
      return $this->belongsTo(User::class, 'id_usuario');}

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'id_cotizacion');
    }

    public function direccion()
    {
        return $this->belongsTo(Direccion::class, 'direccion_id');
    }
}