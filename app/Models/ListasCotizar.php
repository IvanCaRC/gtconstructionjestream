<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListasCotizar extends Model
{

    // $estados = [
    //     1 => ['label' => 'Creando Lista', 'class' => 'badge-success'],
    //     2 => ['label' => 'Creando cotizacion', 'class' => 'badge-primary'],
    //     3 => ['label' => 'Cotizado', 'class' => 'badge-primary'],
    //     4 => ['label' => 'Esperando pago', 'class' => 'badge-warning'],
    //     5 => ['label' => 'Pagado', 'class' => 'badge-primary'],
    //     6 => ['label' => 'Preparando', 'class' => 'badge-warning'],
    //     7 => ['label' => 'En proceso de entraga', 'class' => 'badge-warning'],
    //     8 => ['label' => 'Terminado', 'class' => 'badge-success'],
    //     9 => ['label' => 'Cancelado', 'class' => 'badge-danger'],
    // ];

    use HasFactory;
    protected $fillable = [
        'proyecto_id',
        'usuario_id',
        'nombre',
        'estado',
        'items_cotizar',
        'items_cotizar_temporales',
    ];




    // Relación con Proyecto
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }


    public function cliente()
    {
        return $this->hasOneThrough(Cliente::class, Proyecto::class, 'id', 'id', 'proyecto_id', 'cliente_id');
    }



}
