<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

//     <label>
//     {!! $proyecto->proceso == 0
//         ? '<span class="badge badge-primary">Creando lista a cotizar</span>'
//         : ($proyecto->proceso == 1
//             ? '<span class="badge badge-primary">Creando cotización</span>'
//             : ($proyecto->proceso == 2
//                 ? '<span class="badge badge-primary">Cotizado</span>'
//                 : ($proyecto->proceso == 3
//                     ? '<span class="badge badge-warning">Esperando pago</span>'
//                     : ($proyecto->proceso == 4
//                         ? '<span class="badge badge-primary">Pagado/span>'
//                         : ($proyecto->proceso == 5
//                             ? '<span class="badge badge-warning">Preparando</span>'
//                             : ($proyecto->proceso == 6
//                                 ? '<span class="badge badge-warning">En proceso de entrga</span>'
//                                 : ($proyecto->proceso == 7
//                                     ? '<span class="badge badge-success">Venta terminada</span>'
//                                     : ($proyecto->proceso == 8
//                                         ? '<span class="badge badge-danger">Cancelado</span>'
//                                         : '<span class="badge badge-secondary">Estado desconocido</span>')))))))) !!}
// </label>


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
        'culminacion',
        'motivo_finalizacion',
        'motivo_detallado',
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

    public static function rulesSolicitoCancelacion()
    {
        return [
            'motivo_finalizacion' => 'required|string|max:255',
            'motivo_detallado' => 'required|string|max:500',
            'motivo_finalizacion_alterno' => 'nullable|required_if:motivo_finalizacion,otro|string|max:255',
        ];
    }

    public static function messagesSolicitoCancelacion()
    {
        return [
            'motivo_finalizacion.required' => 'Registra el motivo de tu cancelacion.',
            'motivo_finalizacion.max' => 'Resume un poco tu motivo de finalizacion.',
            'motivo_detallado.required' => 'Describe los detalles de dicha cancelacion para poder enviarla.',
            'motivo_detallado.string' => 'Los detalles deben ser un texto.',
            'motivo_detallado.max' => 'Resume un poco la descripcion del motivo',
            'motivo_finalizacion_alterno.required_if' => 'Describe el motivo especifico alterno.',
        ];
    }
}
