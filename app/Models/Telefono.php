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
            $prefix . 'telefonos.*.numero' => 'required|numeric|unique:telefonos,numero,' . $id,
            $prefix . 'telefonos.*.nombre' => 'required|string|max:255',
        ];
    }

    public static function messages($prefix = '')
    {
        return [
            $prefix . 'telefonos.*.numero.required' => 'Registra el telefono de contacto',
            $prefix . 'telefonos.*.numero.numeric' => 'El telefono registrado no es valido',
            $prefix . 'telefonos.*.numero.unique' => 'Este número de teléfono ya está registrado.',
            $prefix . 'telefonos.*.nombre.required' => 'Registra el nombre de contacto al proveedor.',
            $prefix . 'telefonos.*.nombre.string' => 'El nombre registrado no es aceptado.',
            $prefix . 'telefonos.*.nombre.max' => 'El nombre es demasiado largo.',
        ];
    }
}

