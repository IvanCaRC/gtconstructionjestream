<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProveedorHasFamilia extends Model
{
    use HasFactory;

    protected $table = 'proveedor_has_familia'; 

    protected $fillable = [
        'proveedor_id',
        'familia_id',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class); //Relacion con un proveedor registrado
    }

    public function familia()
    {
        return $this->belongsTo(Familia::class); //Relacion con una familia registrada
    }
}

