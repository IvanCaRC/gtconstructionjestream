<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;

    protected $table = 'cotizaciones';

    protected $fillable = [
        'lista_cotizar_id',
        'proyecto_id',
        'usuario_id',
        'id_usuario_compras',
        'nombre',
        'estado',
        'items_cotizar_stock',
        'items_cotizar_proveedor',
    ];

    /**
     * Relación con el modelo Proyecto.
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    /**
     * Relación con el modelo User para el creador de la cotización.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Relación con el modelo User para el usuario de compras.
     */
    public function usuarioCompras()
    {
        return $this->belongsTo(User::class, 'id_usuario_compras');
    }

    public function listaCotizar()
    {
        return $this->belongsTo(ListasCotizar::class, 'lista_cotizar_id');
    }
}
