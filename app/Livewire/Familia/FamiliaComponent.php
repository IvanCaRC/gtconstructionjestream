<?php

namespace App\Livewire\Familia;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Familia;
use Illuminate\Support\Facades\DB;

class FamiliaComponent extends Component
{
    use WithPagination;

    public $searchTerm;
    public $familiaId;
    protected $listeners = ['FamiliaEll' => 'render'];
    public $subfamilias = [];

    public function search()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Familia::whereNull('id_familia_padre')
            ->where('estadoEliminacion', 0); // Filtrar por estado_eliminacion igual a 0

        if ($this->searchTerm) {
            $query = Familia::query();
            $query->where(function ($q) {
                $q->where('nombre', 'LIKE', "%{$this->searchTerm}%");
                $q->where('estadoEliminacion', 0);
            });
        }


        $familias = $query->with(['subfamiliasRecursivas' => function ($q) {
            $q->where('estadoEliminacion', 0); // Filtrar subfamilias por estado_eliminacion igual a 0
        }])->paginate(10);

        return view('livewire.familia.familia-component', [
            'familias' => $familias
        ]);
    }


    public function editCategory($idfamilia)
    {
        return redirect()->route('compras.familias.edicionFamilia', ['idfamilia' => $idfamilia]);
    }

    public function eliminar($familiaId)
    {
        $fammmm = Familia::findOrFail($familiaId);
        $fammmm->update(['estadoEliminacion' => 1]);
        $this->dispatch('FamiliaEll');
    }

    public function viewFamilia($idfamilia)
    {
        return redirect()->route('compras.familias.viewFamiliaEspecifica', ['idfamilia' => $idfamilia]);
    }

    public function obtenerSubfamiliasActivas($familiaId)
    {
        $this->familiaId = $familiaId; // Guardamos el id de la familia seleccionada
        $this->subfamilias = []; // Limpiar el arreglo de subfamilias antes de agregar nuevas

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
        $subfamilias = Familia::where('id_familia_padre', $familiaId)
            ->where('estadoEliminacion', 0)
            ->get();

        // Si hay subfamilias, las agregamos al arreglo
        foreach ($subfamilias as $subfamilia) {
            // Almacenar la subfamilia en el arreglo
            $this->subfamilias[] = $subfamilia;

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
        foreach ($this->subfamilias as $subfamilia) {
            $subfamilia->update(['estadoEliminacion' => 1]);
        }

        session()->flash('message', 'Familia y sus subfamilias eliminadas correctamente.');
        $this->dispatch('FamiliaEll');
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
        $subfamiliasAsignadas = $familia->subfamilias->contains(function ($subfamilia) {
            return DB::table('proveedor_has_familia')->where('familia_id', $subfamilia->id)->exists() ||
                DB::table('item_especifico_has_familia')->where('familia_id', $subfamilia->id)->exists();
        });

        // Si la familia o alguna subfamilia está asignada, retornar verdadero
        return $proveedorAsignado || $itemAsignado || $subfamiliasAsignadas;
    }
}
