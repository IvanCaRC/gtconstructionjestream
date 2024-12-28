<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemEspecificoHasFamilia extends Model
{
    use HasFactory;

    protected $table = 'item_especifico_has_familia';

    protected $fillable = [
        'item_especifico_id',
        'familia_id',
    ];

    public function itemEspecifico()
    {
        return $this->belongsTo(ItemEspecifico::class); //Relacion con un item registrado en el sistema
    }

    public function familia()
    {
        return $this->belongsTo(Familia::class); //Relacion de una familia registrada en el sistema
    }
}

