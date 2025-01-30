<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Direccion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'direcciones';

    protected $fillable = [
        'cp', 'estado', 'ciudad', 'municipio', 'colonia', 'calle', 'numero', 'referencia', 'Latitud', 'Longitud', 'proveedor_id', 'cliente_id'
    ];
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }
}
