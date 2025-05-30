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
        'cliente_id','proveedor_id','pais', 'calle', 'numero', 'colonia', 'municipio', 'ciudad', 'estado', 'cp', 'referencia', 'Latitud', 'Longitud'
    ];
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'direccion_id');
    }
}
