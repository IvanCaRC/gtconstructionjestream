<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTemporal extends Model
{
    use HasFactory;

    protected $table = 'item_temporal'; // Nombre de la tabla

    protected $fillable = [
        'item_id',
    ];

    // Definir relación con el modelo Item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}



