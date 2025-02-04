<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // Definir la relación hasMany con ItemEspecifico 
    public function itemEspecificos()
    {
        return $this->hasMany(ItemEspecifico::class);
    }

    public static function rules($prefix = '', $id = null)
    {
        return [
            $prefix . 'nombre' => 'required|string|max:255',
            $prefix . 'descripcion' => 'nullable|string',
        ];
    }

    public static function messages($prefix = '')
    {
        return [
            $prefix . 'nombre.required' => 'Registrar el nombre es obligatorio.',
            $prefix . 'nombre.string' => 'El nombre debe ser un texto.',
            $prefix . 'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            $prefix . 'descripcion.string' => 'La descripción debe ser una cadena de texto.',
        ];
    }
}
