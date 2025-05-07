<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;

    protected $table = 'cotizaciones';
    
    // <label>
    //                                         {!! $lista->estado == 0
    //                                             ? '<span class="badge badge-success">Activa</span>'zzzzz
    //                                             : ($lista->estado == 1
    //                                                 ? '<span class="badge badge-secondary">Enviada</span>'
    //                                                 : ($lista->estado == 2
    //                                                     ? '<span class="badge badge-warning">Aceptada pendiente de pago</span>'
    //                                                     : ($lista->estado == 3
    //                                                         ? '<span class="badge badge-danger">Pagado</span>'
    //                                                         : ($lista->estado == 4
    //                                                             ? '<span class="badge badge-success">Comprando</span>'
    //                                                             : ($lista->estado == 5
    //                                                                 ? '<span class="badge badge-primary">En proceso de entrega</span>'
    //                                                                 : ($lista->estado == 6
    //                                                                     ? '<span class="badge badge-primary">Terminado</span>'
    //                                                                     : ($lista->estado == 7
    //                                                                     ? '<span class="badge badge-primary">Cancelado</span>'
    //                                                                     : '<span class="badge badge-secondary">Estado desconocido</span>'))))))) !!}
    //                                     </label>


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
