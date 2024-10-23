<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    protected $fillable = [
        'name', 'guard_name', 'description'
    ];

    public static function rules($id = null)
    {
        return [
            'roleEdit.name' => 'required',
            'roleEdit.description' => 'required',
        ];
    }
}

