<?php

namespace App\Livewire\Familia;

use App\Models\Familia;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class FamiliaVistaEspecifica extends Component
{
    public $familia;
    public $nivel;
    public $familiaPadre = null;
    public $subfamilias2 = [];
    public $familiaId;
    protected $listeners = ['FamEspe' => 'render'];

    public function render()
    {
        if ($this->familia->estadoEliminacion) {
            abort(404); // Lanza un error 404 si la familia está eliminada
        }

        $query = Familia::where('id_familia_padre', $this->familia->id) ->where('estadoEliminacion', 0);

        $subfamilias = $query->paginate(10);

        return view('livewire.familia.familia-vista-especifica', [
            'familia' => $this->familia,
            'familiaPadre' => $this->familiaPadre,
            'subfamilias' => $subfamilias,
            'nivel' => $this->nivel
        ]);
    }

    


    public function mount($idfamilia)
    {
        $this->familia = Familia::findOrFail($idfamilia);
        $this->buscarFamiliaPadreDirecta($idfamilia);
        $this->nivel = $this->familia->nivel;
    }

    public function buscarFamiliaPadreDirecta($idfamilia)
    {
        // Buscar la familia con el ID especificado
        $familia = Familia::find($idfamilia);

        // Verificar si la familia existe
        if ($familia) {
            // Buscar la familia padre directa utilizando el campo 'id_familia_padre'
            $familiaPadre = Familia::find($familia->id_familia_padre);

            // Retornar la familia padre directa si existe
            if ($familiaPadre) {
                $this->familiaPadre = $familiaPadre;
            } else {
                $this->familiaPadre = null;
            }
        }
    }

    public function editCategory($id)
    {
        // Lógica para editar una categoría
    }

    public function eliminar($familiaId)
    {
        $fammmm = Familia::findOrFail($familiaId);
        $fammmm->update(['estadoEliminacion' => 1]);
        $this->dispatch('FamEspe');
    }

    public function viewFamilia($idfamilia)
    {
        return redirect()->route('compras.familias.viewFamiliaEspecifica', ['idfamilia' => $idfamilia]);
    }
    
    public function obtenerSubfamiliasActivas($familiaId)
    {
        $this->familiaId = $familiaId; // Guardamos el id de la familia seleccionada
        $this->subfamilias2 = []; // Limpiar el arreglo de subfamilias antes de agregar nuevas

        // Llamada recursiva para obtener las subfamilias activas
        $this->recuperarSubfamiliasRecursivas($familiaId);
    }

    /**
     * Función recursiva para obtener las subfamilias activas de manera anidada.
     * 
     * @param int $familiaId
     */
    private function recuperarSubfamiliasRecursivas($familiaId)
    {
        // Obtener las subfamilias activas (estadoEliminacion = 0) de una familia
        $subfamilias2 = Familia::where('id_familia_padre', $familiaId)
            ->where('estadoEliminacion', 0)
            ->get();

        // Si hay subfamilias, las agregamos al arreglo
        foreach ($subfamilias2 as $subfamilia) {
            // Almacenar la subfamilia en el arreglo
            $this->subfamilias2[] = $subfamilia;

            // Llamada recursiva para obtener las subfamilias de la subfamilia actual
            $this->recuperarSubfamiliasRecursivas($subfamilia->id);
        }
    }

    public function eliminarFamiliaConSubfamilias($familiaId)
    {
        // Obtener la familia principal
        $familia = Familia::findOrFail($familiaId);

        // Cambiar estado de eliminación a 1 (eliminar)
        $familia->estadoEliminacion = 1;
        $familia->save();

        // Si tiene subfamilias, también cambiar su estado
        foreach ($this->subfamilias2 as $subfamilia) {
            $subfamilia->update(['estadoEliminacion' => 1]);
        }

        session()->flash('message', 'Familia y sus subfamilias eliminadas correctamente.');
    }

    public function verificarAsignacion($familiaId)
    {
        // Verificar si la familia está asignada en 'proveedor_has_familia' o 'item_especifico_has_familia'
        $familia = Familia::find($familiaId);

        // Verificar en la tabla proveedor_has_familia
        $proveedorAsignado = DB::table('proveedor_has_familia')
            ->where('familia_id', $familiaId)
            ->exists();

        // Verificar en la tabla item_especifico_has_familia
        $itemAsignado = DB::table('item_especifico_has_familia')
            ->where('familia_id', $familiaId)
            ->exists();

        // Verificar si alguna de las subfamilias está asignada
        $subfamiliasAsignadas = $familia->subfamilias2->contains(function ($subfamilia) {
            return DB::table('proveedor_has_familia')->where('familia_id', $subfamilia->id)->exists() ||
                DB::table('item_especifico_has_familia')->where('familia_id', $subfamilia->id)->exists();
        });

        // Si la familia o alguna subfamilia está asignada, retornar verdadero
        return $proveedorAsignado || $itemAsignado || $subfamiliasAsignadas;
    }
}


