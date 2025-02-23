<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemEspecificoProveedor extends Model
{
    use HasFactory;

    protected $table = 'item_especifico_proveedor';

    protected $fillable = [
        'item_especifico_id',
        'proveedor_id',
        'tiempo_max_entrega',
        'tiempo_min_entrega',
        'precio_compra',
        'estado',
    ];

    public function itemEspecifico()
    {
        return $this->belongsTo(ItemEspecifico::class); // Relacion con items registrados en el sistema
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class); //Relacion con proveedores registrados en el sistema
    }

    //Reglas especiales en caso de haber proveedor asignado
    public static function rules()
    {
        return [
            'ProvedoresAsignados.*.tiempo_maximo_entrega' => [
                'required',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    if (request()->input("ProvedoresAsignados.$index.tiempo_minimo_entrega") > $value) {
                        $fail('El tiempo máximo de entrega no puede ser menor que el tiempo mínimo de entrega.');
                    }
                },
            ],
            'ProvedoresAsignados.*.tiempo_minimo_entrega' => 'required|integer|min:0',
            'ProvedoresAsignados.*.unidad' => 'required|string|max:255',
            'ProvedoresAsignados.*.precio_compra' => 'required|numeric|min:0',
        ];
    }

    public static function messages()
    {
        return [
            'ProvedoresAsignados.*.tiempo_maximo_entrega.required' => 'Debes registrar el tiempo de entrega máximo.',
            'ProvedoresAsignados.*.tiempo_maximo_entrega.integer' => 'Este dato no es válido.',
            'ProvedoresAsignados.*.tiempo_maximo_entrega.min' => 'El tiempo máximo de entrega no puede ser tan corto.',
            'ProvedoresAsignados.*.tiempo_maximo_entrega.not_smaller' => 'El tiempo máximo de entrega no puede ser menor que el tiempo mínimo de entrega.',
            'ProvedoresAsignados.*.tiempo_minimo_entrega.required' => 'Debes registrar el tiempo de entrega mínimo.',
            'ProvedoresAsignados.*.tiempo_minimo_entrega.integer' => 'Este dato no es válido.',
            'ProvedoresAsignados.*.tiempo_minimo_entrega.min' => 'El tiempo mínimo de entrega no puede ser menor a 0.',
            'ProvedoresAsignados.*.unidad.string' => 'La unidad debe ser una cadena de texto.',
            'ProvedoresAsignados.*.unidad.max' => 'La unidad es demasiado larga.',
            'ProvedoresAsignados.*.precio_compra.required' => 'El campo precio de compra es obligatorio.',
            'ProvedoresAsignados.*.precio_compra.numeric' => 'El precio de compra debe ser un número.',
            'ProvedoresAsignados.*.precio_compra.min' => 'El precio de compra no puede ser negativo.',
        ];
    }

    public static function rulesUpdate()
    {
        return [
            'ProvedoresAsignados.*.tiempo_maximo_entrega' => [
                'required',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    if (request()->input("ProvedoresAsignados.$index.tiempo_minimo_entrega") > $value) {
                        $fail('El tiempo máximo de entrega no puede ser menor que el tiempo mínimo de entrega.');
                    }
                },
            ],
            'ProvedoresAsignados.*.tiempo_minimo_entrega' => 'required|integer|min:0',
            'ProvedoresAsignados.*.unidad' => 'required|string|max:255',
            'ProvedoresAsignados.*.precio_compra' => 'required|numeric|min:0',
        ];
    }

    public static function messagesUpdate()
    {
        return [
            'ProvedoresAsignados.*.tiempo_maximo_entrega.required' => 'Debes registrar el tiempo de entrega máximo.',
            'ProvedoresAsignados.*.tiempo_maximo_entrega.integer' => 'Este dato no es válido.',
            'ProvedoresAsignados.*.tiempo_maximo_entrega.min' => 'El tiempo máximo de entrega no puede ser tan corto.',
            'ProvedoresAsignados.*.tiempo_maximo_entrega.not_smaller' => 'El tiempo máximo de entrega no puede ser menor que el tiempo mínimo de entrega.',
            'ProvedoresAsignados.*.tiempo_minimo_entrega.required' => 'Debes registrar el tiempo de entrega mínimo.',
            'ProvedoresAsignados.*.tiempo_minimo_entrega.integer' => 'Este dato no es válido.',
            'ProvedoresAsignados.*.tiempo_minimo_entrega.min' => 'El tiempo mínimo de entrega no puede ser menor a 0.',
            'ProvedoresAsignados.*.unidad.string' => 'La unidad debe ser una cadena de texto.',
            'ProvedoresAsignados.*.unidad.max' => 'La unidad es demasiado larga.',
            'ProvedoresAsignados.*.precio_compra.required' => 'El campo precio de compra es obligatorio.',
            'ProvedoresAsignados.*.precio_compra.numeric' => 'El precio de compra debe ser un número.',
            'ProvedoresAsignados.*.precio_compra.min' => 'El precio de compra no puede ser negativo.',
        ];
    }
}
