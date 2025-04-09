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
            $prefix . 'pz_Mayoreo' => 'required|integer|min:1',
            $prefix . 'precio_venta_minorista' => 'nullable|numeric|min:0',
            $prefix . 'precio_venta_mayorista' => 'nullable|numeric|min:0',
            $prefix . 'stock' => 'nullable|integer|min:0',
            $prefix . 'especificaciones' => 'nullable',
            $prefix . 'moc' => 'nullable|integer|min:0',
            // $prefix . 'ficha_Tecnica_pdf' => 'nullable|mimes:pdf|max:2048',
        ];
    }

    public static function messages($prefix = '')
    {
        return [
            $prefix . 'image.mimes' => 'La imagen debe ser un archivo de tipo: jpg, jpeg, png, gif, webp.',
            $prefix . 'image.max' => 'La imagen no puede exceder los 1024KB.',
            $prefix . 'marca.required' => 'Registrar una marca es obligatorio.',
            $prefix . 'marca.string' => 'La marca debe ser un texto.',
            $prefix . 'marca.max' => 'El nombre de la marca excede el limite de caracteres.',
            $prefix . 'descripcion.required' => 'Registra una descripcion para el item',
            $prefix . 'pz_Mayoreo.required' => 'Registra la cantidad de piezas de mayoreo.',
            $prefix . 'pz_Mayoreo.integer' => 'Registra la cantidad de piezas de mayoreo como un numero.',
            $prefix . 'pz_Mayoreo.min' => 'Ingrese un valor valido, (min 1).',
            $prefix . 'precio_venta_minorista.numeric' => 'El precio de venta minorista debe ser un número.',
            $prefix . 'precio_venta_minorista.min' => 'El precio de venta minorista no puede ser negativo.',
            $prefix . 'precio_venta_mayorista.numeric' => 'El precio de venta mayorista debe ser un número.',
            $prefix . 'precio_venta_mayorista.min' => 'El precio de venta mayorista no puede ser negativo.',
            $prefix . 'stock.integer' => 'El stock debe ser un número entero.',
            $prefix . 'stock.min' => 'No puedes registrar un stock negativo.',
            $prefix . 'moc.integer' => 'El valor minimo de venta al cliente debe ser un valor numerico entero.',
            $prefix . 'moc.min' => 'No se permiten valores negativos.',
            $prefix . 'ficha_Tecnica_pdf.mimes' => 'La ficha técnica debe ser un archivo PDF.',
            $prefix . 'ficha_Tecnica_pdf.max' => 'La ficha técnica no puede exceder los 2048KB.',
        ];
    }

    public static function rulesUpdate($prefix = '', $id = null)
    {
        return [
            'itemEspecificoEdit.marca' => 'required|string|max:255',
            'itemEspecificoEdit.descripcion' => 'required',
            'itemEspecificoEdit.cantidad_piezas_mayoreo' => 'required|integer|min:1',
            'itemEspecificoEdit.precio_venta_minorista' => 'nullable|numeric|min:0',
            'itemEspecificoEdit.precio_venta_mayorista' => 'nullable|numeric|min:0',
            // 'itemEspecificoEdit.unidad' => 'required|string|max:50',
            // 'itemEspecificoEdit.stock' => 'nullable|integer|min:0',
            // 'itemEspecificoEdit.especificaciones' => 'nullable',
            // 'itemEspecificoEdit.moc' => 'nullable|integer|min:0',
            // 'itemEspecificoEdit.ficha_Tecnica_pdf' => 'nullable|mimes:pdf|max:2048',
        ];
    }

    public static function messagesUpdate($prefix = '')
    {
        return [
            'itemEspecificoEdit.image.mimes' => 'La imagen debe ser un archivo de tipo: jpg, jpeg, png, gif, webp.',
            'itemEspecificoEdit.image.max' => 'La imagen no puede exceder los 1024KB.',
            'itemEspecificoEdit.marca.required' => 'Registrar una marca es obligatorio.',
            'itemEspecificoEdit.marca.string' => 'La marca debe ser un texto.',
            'itemEspecificoEdit.marca.max' => 'El nombre de la marca excede el limite de caracteres.',
            'itemEspecificoEdit.descripcion.required' => 'Registra una descripcion para el item.',
            'itemEspecificoEdit.cantidad_piezas_mayoreo.required' => 'Registra la cantidad de piezas de mayoreo.',
            'itemEspecificoEdit.cantidad_piezas_mayoreo.integer' => 'Registra un valor numerico.',
            'itemEspecificoEdit.cantidad_piezas_mayoreo.min' => 'Ingrese un valor valido, (min 1).',
            'itemEspecificoEdit.precio_venta_minorista.numeric' => 'El precio de venta minorista debe ser un número.',
            'itemEspecificoEdit.precio_venta_minorista.min' => 'El precio de venta minorista no puede ser negativo.',
            'itemEspecificoEdit.precio_venta_mayorista.numeric' => 'El precio de venta mayorista debe ser un número.',
            'itemEspecificoEdit.precio_venta_mayorista.min' => 'El precio de venta mayorista no puede ser negativo.',
            'itemEspecificoEdit.unidad.required' => 'Ingrese el tipo de unidad.',
            'itemEspecificoEdit.unidad.string' => 'La unidad debe ser un texto.',
            'itemEspecificoEdit.unidad.max' => 'La unidad no puede tener más de 50 caracteres.',
            'itemEspecificoEdit.stock.integer' => 'El stock debe ser un número entero.',
            'itemEspecificoEdit.stock.min' => 'No puedes registrar un stock negativo.',
            'itemEspecificoEdit.moc.integer' => 'El valor minimo de venta al cliente debe ser un valor numerico entero.',
            'itemEspecificoEdit.moc.min' => 'No se permiten valores negativos.',
            'itemEspecificoEdit.ficha_Tecnica_pdf.mimes' => 'La ficha técnica debe ser un archivo PDF.',
            'itemEspecificoEdit.ficha_Tecnica_pdf.max' => 'La ficha técnica no puede exceder los 2048KB.',
        ];
    }

    //Reglas especiales en caso de haber proveedor asignado
    public static function rulesProveedor($prefix = '', $id = null)
    {
        return 
        [
            $prefix . 'porcentaje_venta_minorista' => 'required|numeric|min:0',
            $prefix . 'porcentaje_venta_mayorista' => 'required|numeric|min:0', 
        ];
    }

    public static function messagesProveedor($prefix = '')
    {
        return 
        [
            $prefix . 'porcentaje_venta_minorista.required' => 'Registra este porcentaje.',
            $prefix . 'porcentaje_venta_minorista.numeric' => 'El porcentaje de venta minorista debe ser un número.',
            $prefix . 'porcentaje_venta_minorista.min' => 'No puedes ingresar un porcentaje negativo.',
            $prefix . 'porcentaje_venta_mayorista.required' => 'Registra este porcentaje.',
            $prefix . 'porcentaje_venta_mayorista.numeric' => 'El porcentaje de venta mayorista debe ser un número.',
            $prefix . 'porcentaje_venta_mayorista.min' => 'No puedes ingresar un porcentaje negativo',

        ];
    }

    public static function rulesUpdateProveedor($prefix = '', $id = null)
    {
        return 
        [
            'porcentaje_venta_minorista' => 'required|numeric|min:0',
            'porcentaje_venta_mayorista' => 'required|numeric|min:0',
        ];
    }

    public static function messagesUpdateProveedor($prefix = '')
    {
        return 
        [
            'porcentaje_venta_minorista.required' => 'Registra este porcentaje.',
            'porcentaje_venta_minorista.numeric' => 'El porcentaje de venta minorista debe ser un número.',
            'porcentaje_venta_minorista.min' => 'No puedes ingresar un porcentaje negativo.',
            'porcentaje_venta_mayorista.required' => 'Registra este porcentaje.',
            'porcentaje_venta_mayorista.numeric' => 'El porcentaje de venta mayorista debe ser un número.',
            'porcentaje_venta_mayorista.min' => 'No puedes ingresar un porcentaje negativo',

        ];
    }
}
