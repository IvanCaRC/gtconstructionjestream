<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemEspecifico extends Model
{
    use HasFactory;

    protected $table = 'item_especifico';

    protected $fillable = [
        'item_id',
        'image',
        'marca',
        'cantidad_piezas_mayoreo',
        'cantidad_piezas_minorista',
        'porcentaje_venta_minorista',
        'porcentaje_venta_mayorista',
        'precio_venta_minorista',
        'precio_venta_mayorista',
        'stock',
        'unidad',
        'especificaciones',
        'ficha_tecnica_pdf',
        'estado',
        'moc',
        'estado_eliminacion',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class); // Relación con la entidad item
    }

    public function familias()
    {
        return $this->belongsToMany(Familia::class, 'item_especifico_has_familia', 'item_especifico_id', 'familia_id');
    }

    public function proveedores()
    {
        return $this->belongsToMany(Proveedor::class, 'item_especifico_proveedor', 'item_especifico_id', 'proveedor_id')
            ->withPivot('precio_compra'); // Incluir el campo precio_compra en la tabla intermedia 
    }

    public static function rules($prefix = '', $id = null)
    {
        return [
            // $prefix . 'image' => 'nullable|mimes:jpg,jpeg,png,gif,webp|max:1024',
            $prefix . 'marca' => 'required|string|max:255',
            $prefix . 'descripcion' => 'required',
            $prefix . 'pz_Mayoreo' => 'required|integer',
            $prefix . 'porcentaje_venta_minorista' => 'required|numeric|min:0',
            $prefix . 'porcentaje_venta_mayorista' => 'required|numeric|min:0',
            $prefix . 'precio_venta_minorista' => 'nullable|numeric|min:0',
            $prefix . 'precio_venta_mayorista' => 'nullable|numeric|min:0',
            $prefix . 'unidad' => 'required|string|max:50',
            $prefix . 'stock' => 'nullable|integer|min:0',
            $prefix . 'especificaciones' => 'nullable',
            $prefix . 'moc' => 'nullable|integer|min:0',
            $prefix . 'ficha_Tecnica_pdf' => 'nullable|mimes:pdf|max:2048',
        ];
    }

    public static function messages($prefix = '')
    {
        return [
            $prefix . 'image.mimes' => 'La imagen debe ser un archivo de tipo: jpg, jpeg, png, gif, webp.',
            $prefix . 'image.max' => 'La imagen no puede exceder los 1024KB.',
            $prefix . 'marca.required' => 'Registrar una marca es obligatorio.',
            $prefix . 'marca.string' => 'La marca debe ser un texto.',
            $prefix . 'marca.max' => 'El nombre de la marca excede la cantidad de caracteres.',
            $prefix . 'descripcion.required' => 'Registra una descripcion para el item',
            $prefix . 'pz_Mayoreo.required' => 'Registra la cantidad de piezas de mayoreo.',
            $prefix . 'pz_Mayoreo.integer' => 'Registra la cantidad de piezas de mayoreo como un numero.',
            $prefix . 'porcentaje_venta_minorista.required' => 'Registra este porcentaje.',
            $prefix . 'porcentaje_venta_minorista.numeric' => 'El porcentaje de venta minorista debe ser un número.',
            $prefix . 'porcentaje_venta_minorista.min' => 'No puedes ingresar un porcentaje negativo.',
            $prefix . 'porcentaje_venta_mayorista.required' => 'Registra este porcentaje.',
            $prefix . 'porcentaje_venta_mayorista.numeric' => 'El porcentaje de venta mayorista debe ser un número.',
            $prefix . 'porcentaje_venta_mayorista.min' => 'No puedes ingresar un porcentaje negativo',
            $prefix . 'precio_venta_minorista.numeric' => 'El precio de venta minorista debe ser un número.',
            $prefix . 'precio_venta_minorista.min' => 'El precio de venta minorista no puede ser negativo.',
            $prefix . 'precio_venta_mayorista.numeric' => 'El precio de venta mayorista debe ser un número.',
            $prefix . 'precio_venta_mayorista.min' => 'El precio de venta mayorista no puede ser negativo.',
            $prefix . 'unidad.required' => 'Ingrese el tipo de unidad.',
            $prefix . 'unidad.string' => 'La unidad debe ser un texto.',
            $prefix . 'unidad.max' => 'La unidad no puede tener más de 50 caracteres.',
            $prefix . 'stock.integer' => 'El stock debe ser un número entero.',
            $prefix . 'stock.min' => 'No puedes registrar un stock negativo.',
            $prefix . 'moc.integer' => 'El valor minimo de venta al cliente debe ser un valor numerico entero.',
            $prefix . 'moc.min' => 'No se permiten valores negativos.',
            $prefix . 'ficha_Tecnica_pdf.mimes' => 'La ficha técnica debe ser un archivo PDF.',
            $prefix . 'ficha_Tecnica_pdf.max' => 'La ficha técnica no puede exceder los 2048KB.',
        ];
    }
}
