<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_familia_padre',
        'nombre',
        'descripcion',
        'estadoEliminacion',
        'nivel'
    ];

    public function subfamilias()
    {
        // Filtrar las subfamilias por estado_eliminacion = 0
        return $this->hasMany(Familia::class, 'id_familia_padre')->where('estadoEliminacion', 0);
    }

    public function subfamiliasRecursivas()
    {
        // Aplicar el filtro también en la relación recursiva
        return $this->subfamilias()->with('subfamiliasRecursivas');
    }

    public function padre()
    {
        return $this->belongsTo(Familia::class, 'id_familia_padre');
    }

    public function itemEspecificos()
    {
        return $this->belongsToMany(ItemEspecifico::class, 'item_especifico_has_familia', 'familia_id', 'item_especifico_id');
    }

    public function proveedores()
    {
        return $this->belongsToMany(Proveedor::class, 'proveedor_has_familia', 'familia_id', 'proveedor_id');
    }

    /**
     * Obtiene las subfamilias de un nivel dado y limpia los niveles profundos si es necesario.
     *
     * @param int $idFamiliaSeleccionada ID de la familia seleccionada.
     * @param int $nivel Nivel actual.
     * @param array $niveles Niveles existentes.
     * @param array $seleccionadas Selecciones actuales.
     * @return array Devuelve el array actualizado de niveles y seleccionadas.
     */
    public static function calcularSubfamilias($idFamiliaSeleccionada, $nivel, $niveles, $seleccionadas)
    {
        // Guardar la selección actual en el nivel correspondiente
        $seleccionadas[$nivel] = $idFamiliaSeleccionada;

        // Limpiar los niveles más profundos que este
        foreach ($niveles as $key => $value) {
            if ($key > $nivel) {
                unset($niveles[$key]);
                unset($seleccionadas[$key]);
            }
        }

        // Cargar subfamilias solo si hay una selección válida
        if ($idFamiliaSeleccionada != 0) {
            $familia = self::find($idFamiliaSeleccionada);
            if ($familia) {
                $niveles[$nivel + 1] = $familia->subfamilias()->get();
                $seleccionadas[$nivel + 1] = 0; // Restablecer el siguiente nivel a "Seleccione una familia"
            }
        } else {
            // Si no hay selección válida, elimina el nivel siguiente
            unset($niveles[$nivel + 1]);
        }

        return compact('niveles', 'seleccionadas');
    }

    public static function rules($prefix = '', $id = null)
    {
        return [
            $prefix . 'nombre' => 'required|string|max:255',
            $prefix . 'descripcion' => 'nullable|string',
        ];
    }

    public static function messages($prefix = '')
    {
        return [
            $prefix . 'nombre.required' => 'Asigne nombre para crear una familia',
            $prefix . 'nombre.string' => 'El nombre debe ser un texto',
            $prefix . 'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            $prefix . 'descripcion.string' => 'La descripción debe ser una cadena de texto.',
        ];
    }

    public static function updateRules($prefix = '', $id = null)
    {
        return [
            $prefix . 'nombre' => 'required|string|max:255',
            $prefix . 'descripcion' => 'nullable|string',
        ];
    }

    public static function updateMessages($prefix = '')
    {
        return [
            $prefix . 'nombre.required' => 'Asigne nombre para actualizar la familia.',
            $prefix . 'nombre.string' => 'El nombre debe ser un texto.',
            $prefix . 'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            $prefix . 'descripcion.string' => 'La descripción debe ser una cadena de texto.',
        ];
    }
}
