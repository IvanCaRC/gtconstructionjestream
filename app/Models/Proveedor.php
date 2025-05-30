<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Rules\ValidaRFC; // Regla de validacion personalizada

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores'; // Nombre de la tabla

    protected $fillable = [
        'nombre',
        'descripcion',
        'correo',
        'rfc',
        'archivo_facturacion_pdf',
        'datos_bancarios_pdf',
        'estado',
        'estado_eliminacion',
    ];

    public function itemEspecificos()
    {
        return $this->belongsToMany(ItemEspecifico::class, 'item_especifico_proveedor', 'proveedor_id', 'item_especifico_id')
            ->withPivot('precio_compra'); // Incluir el campo precio_compra en la tabla intermedia 
    }

    public function familias()
    {
        return $this->belongsToMany(Familia::class, 'proveedor_has_familia', 'proveedor_id', 'familia_id');
    }

    public function direcciones()
    {
        return $this->hasMany(Direccion::class, 'proveedor_id');
    }

    public function telefonos()
    {
        return $this->hasMany(Telefono::class, 'proveedor_id');
    }

    public static function rules($prefix = '', $id = null)
    {
        return [
            $prefix . 'nombre' => 'required|string|max:255',
            $prefix . 'descripcion' => 'nullable|string',
            $prefix . 'correo' => 'required|email|unique:proveedores,correo,' . $id,
            $prefix . 'rfc' => ['required', 'string', 'unique:proveedores,rfc,' . $id, new ValidaRfc],
            // $prefix . 'archivo_facturacion_pdf' => 'nullable|mimes:pdf|max:2048',
            // $prefix . 'datos_bancarios_pdf' => 'nullable|mimes:pdf|max:2048',
        ];
    }

    public static function messages($prefix = '')
    {
        return [
            $prefix . 'nombre.required' => 'Agregar un nombre es obligatorio.',
            $prefix . 'nombre.string' => 'El nombre debe ser un texto.',
            $prefix . 'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            $prefix . 'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            $prefix . 'correo.required' => 'Agregar un correo electronico es obligatorio',
            $prefix . 'correo.email' => 'Debe proporcionar una dirección de correo electrónico válida.',
            $prefix . 'correo.unique' => 'Esta dirección de correo electrónico ya está registrada.',
            $prefix . 'rfc.required' => 'El RFC es obligatorio.',
            $prefix . 'rfc.string' => 'El RFC debe ser una cadena de texto.',
            $prefix . 'rfc.unique' => 'Este RFC ya está registrado.',
            $prefix . 'rfc.valid' => 'El RFC registrado no es valido.',
            $prefix . 'archivo_facturacion_pdf.mimes' => 'El archivo de facturación debe ser un archivo PDF.',
            $prefix . 'archivo_facturacion_pdf.max' => 'El archivo de facturación no puede exceder los 2048KB.',
            $prefix . 'datos_bancarios_pdf.mimes' => 'El archivo de datos bancarios debe ser un archivo PDF.',
            $prefix . 'datos_bancarios_pdf.max' => 'El archivo de datos bancarios no puede exceder los 2048KB.',
        ];
    }

    public static function rulesUpdate($prefix = '', $id)
{
    return [
        $prefix . 'nombre' => 'required|string|max:255',
        $prefix . 'descripcion' => 'nullable|string',
        $prefix . 'correo' => 'required|email|unique:proveedores,correo,' . $id,
        $prefix . 'rfc' => ['required', 'string', 'unique:proveedores,rfc,' . $id, new ValidaRfc],
    ];
}

public static function messagesUpdate($prefix = '')
{
    return [
        $prefix . 'nombre.required' => 'Registrar nombre del proveedor es obligatorio.',
        $prefix . 'nombre.string' => 'El nombre debe ser un texto.',
        $prefix . 'nombre.max' => 'El nombre es demasiado largo.',
        $prefix . 'descripcion.string' => 'La descripción debe ser una cadena de texto.',
        $prefix . 'correo.required' => 'Registrar un correo electrónico es obligatorio.',
        $prefix . 'correo.email' => 'Debe proporcionar una dirección de correo electrónico válida.',
        $prefix . 'correo.unique' => 'Esta dirección de correo electrónico ya está registrada.',
        $prefix . 'rfc.required' => 'Registrar el RFC es obligatorio.',
        $prefix . 'rfc.string' => 'El RFC debe ser una cadena de texto.',
        $prefix . 'rfc.unique' => 'Este RFC ya está registrado.',
        $prefix . 'rfc.valid' => 'El RFC registrado no es válido, verificalo.',
    ];
}

}
