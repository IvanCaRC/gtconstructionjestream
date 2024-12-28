<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telefono extends Model
{
    use HasFactory;

    protected $table = "telefonos";

    protected $fillable = [
        'numero',
        'proveedor_id',
    ]; 

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);//Relacion con proveedor
    }
}
