<?php

namespace App\Livewire\Proveedor;

use App\Models\Familia;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Proveedor;
use App\Models\ProveedorHasFamilia;
use Illuminate\Support\Facades\DB;

class ProveedorComponent extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $sort = 'id';
    public $direction = 'desc';
    protected $listeners = ['renderVistaProv' => 'render'];
    public $statusFiltroDeBusqueda;
    public $familiasSeleccionadas = [];
    public $desplegables = [];



    public function search()
    {
        $this->resetPage();
    }

    public function filter()
    {
        $this->resetPage();  // Asegura que la paginación se restablezca
    }
    public function render()
    {

        $queryfamilia = Familia::whereNull('id_familia_padre')
            ->where('estadoEliminacion', 0);

        $familias = $queryfamilia->with(['subfamiliasRecursivas' => function ($q) {
            $q->where('estadoEliminacion', 0); // Filtrar subfamilias por estado_eliminacion igual a 0
        }])->get();
        // Consulta para el modelo Proveedor
        $query = Proveedor::where('estado_eliminacion', 1)
            ->with(['familias', 'direcciones' => function ($query) {
                $query->whereNotNull('proveedor_id'); // Solo incluir direcciones donde proveedor_id no sea nulo
            }, 'telefonos' => function ($query) {
                $query->whereNotNull('proveedor_id'); // Solo incluir telefonos donde proveedor_id no sea nulo
            }]);

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('nombre', 'LIKE', "%{$this->searchTerm}%")
                    ->orWhere('correo', 'LIKE', "%{$this->searchTerm}%")
                    ->orWhere('rfc', 'LIKE', "%{$this->searchTerm}%");
            });
        }

        if (!empty($this->familiasSeleccionadas)) {
            $query->whereHas('familias', function ($q) {
                $q->whereIn('familia_id', $this->familiasSeleccionadas);
            });
        }

        if ($this->statusFiltroDeBusqueda !== "2" && $this->statusFiltroDeBusqueda !== null) {
            $query->where('estado', $this->statusFiltroDeBusqueda);
        }

        $proveedores = $query->orderBy($this->sort, $this->direction)
            ->paginate(10);

        return view('livewire.proveedor.proveedor-component', [
            'proveedores' => $proveedores,
            'familias' => $familias,
        ]);
    }
    public function seleccionarFamilia($familiaId)
    {
        $familia = Familia::with('subfamiliasRecursivas')->find($familiaId);

        if (!$familia) {
            return;
        }

        // Obtener todos los IDs de la familia y sus subfamilias
        $idsFamilia = $this->obtenerTodosLosIds($familia);

        if (in_array($familiaId, $this->familiasSeleccionadas)) {
            // Si ya estaba seleccionada, eliminar todas las familias relacionadas
            $this->familiasSeleccionadas = array_diff($this->familiasSeleccionadas, $idsFamilia);
        } else {
            // Agregar todas las familias relacionadas
            $this->familiasSeleccionadas = array_merge($this->familiasSeleccionadas, $idsFamilia);
        }

        // Eliminar duplicados
        $this->familiasSeleccionadas = array_unique($this->familiasSeleccionadas);
    }

    /**
     * Función recursiva para obtener todos los IDs de una familia y sus subfamilias
     */
    private function obtenerTodosLosIds($familia)
    {
        $ids = [$familia->id];

        foreach ($familia->subfamiliasRecursivas as $subfamilia) {
            $ids = array_merge($ids, $this->obtenerTodosLosIds($subfamilia));
        }

        return $ids;
    }
    public function eliminar($proveedorId)
    {
        ProveedorHasFamilia::where('proveedor_id', $proveedorId)->delete();
        $Proveedor = Proveedor::findOrFail($proveedorId);
        $Proveedor->update(['estado_eliminacion' => false]);
        $this->dispatch('renderVistaProv');
    }

    public function viewProveedor($idproveedor)
    {
        $proveedor = Proveedor::find($idproveedor);

        if ($proveedor === null) {
            abort(404, 'proveedor no encontrada');
        }

        return redirect()->route('compras.proveedores.viewProveedorEspecifico', ['idproveedor' => $idproveedor]);
    }

    public function verificarAsignacionProvedor($proveedorId)
    {
        // Verificar si la familia está asignada en 'proveedor_has_familia' o 'item_especifico_has_familia'
        $proveedor = Proveedor::find($proveedorId);
        // Verificar en la tabla proveedor_has_familia
        $proveedorAsignado = DB::table('item_especifico_proveedor')
            ->where('proveedor_id', $proveedorId)
            ->exists();
        // Verificar si alguna de las subfamilias está asignada
        // Si la familia o alguna subfamilia está asignada, retornar verdadero
        return $proveedorAsignado;
    }

    public function editProveedor($proveedorId)
    {
        $proveedor = Proveedor::find($proveedorId);

        if ($proveedor === null) {
            abort(404, 'proveedor no encontrada');
        }

        return redirect()->route('compras.proveedores.editProveedores', ['idproveedor' => $proveedorId]);
    }

    public function toggleDesplegable($idfamilia)
    {
        if (isset($this->desplegables[$idfamilia])) {
            $this->desplegables[$idfamilia] = !$this->desplegables[$idfamilia];
        } else {
            $this->desplegables[$idfamilia] = true;
        }
    }
}
