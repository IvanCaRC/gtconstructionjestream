<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telefono extends Model
{
    use HasFactory;

    protected $table = 'telefonos';

    protected $fillable = [
        'proveedor_id',
        'numero',
        'nombre'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public static function rules($prefix = '', $id = null)
    {
        return [
            $prefix . 'telefonos.0.numero' => 'required|numeric' . $id,
            $prefix . 'telefonos.0.nombre' => 'required|string|max:255',
        ];
    }

    public static function messages($prefix = '')
    {
        return [
            $prefix . 'telefonos.0.numero.required' => 'Registra al menos un telefono de contacto al proveedor.',
            $prefix . 'telefonos.0.numero.numeric' => 'El teléfono registrado no es válido',
            $prefix . 'telefonos.0.nombre.required' => 'Registra al menos un contacto al proveedor.',
            $prefix . 'telefonos.0.nombre.string' => 'El nombre registrado no es aceptado.',
            $prefix . 'telefonos.0.nombre.max' => 'El nombre es demasiado largo.',
        ];
    }
}
