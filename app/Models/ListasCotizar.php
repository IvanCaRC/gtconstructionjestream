<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListasCotizar extends Model
{
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


}
