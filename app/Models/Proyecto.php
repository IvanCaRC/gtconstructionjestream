<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'direccion_id',
        'proceso',
        'nombre',
        'preferencia',
        'listas',
        'cotisaciones',
        'ordenes',
        'tipo',
        'estado',
        'archivo',
        'items_cotizar',
        'datos_medidas',
        'datos_adicionales',
        'fecha'
    ];
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function direccion()
    {
        return $this->belongsTo(Direccion::class, 'direccion_id');
    }

    public static function rules()
    {
        return [
            // 'cliente_id' => 'required|exists:clientes,id',
            // 'direccion_id' => 'nullable|exists:direcciones,id',
            // 'proceso' => 'required|integer',
            'nombreProyecto' => 'required|string|max:255',
            'preferencia' => 'nullable|integer',
            // 'listas' => 'required|integer',
            // 'cotisaciones' => 'required|integer',
            // 'ordenes' => 'required|integer',
            'tipoDeProyectoSelecionado' => 'required|integer|in:0,1',
            // 'estado' => 'required|integer',
            // 'archivo' => 'nullable|string|max:255',
            // 'items_cotizar' => 'nullable|string',
            // 'datos_medidas' => 'nullable|string',
            // 'datos_adicionales' => 'nullable|string',
            // 'fecha' => 'required|date',
        ];
    }

    public static function messages()
    {
        return [
            // 'cliente_id.required' => 'El cliente es obligatorio.',
            // 'cliente_id.exists' => 'El cliente seleccionado no es válido.',
            // 'direccion_id.exists' => 'La dirección seleccionada no es válida.',
            // 'proceso.required' => 'El proceso es obligatorio.',
            'nombreProyecto.required' => 'Asigna un nombre para registrar el proyecto.',
            'nombreProyecto.max' => 'El nombre excede el limite de caracteres.',
            'tipoDeProyectoSelecionado.required' => 'Porfavor, selecciona el tipo de proyecto del que se trata.',
            'tipoDeProyectoSelecionado.in' => 'El tipo de proyecto seleccionado no es valido.',
            // 'fecha.required' => 'La fecha es obligatoria.',
            // 'fecha.date' => 'La fecha no tiene un formato válido.',
        ];
    }
}
