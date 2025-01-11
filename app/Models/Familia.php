<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    use HasFactory;

    protected $fillable = ['id_familia_padre', 'nombre', 'descripcion', 'estadoEliminacion'];

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
}
