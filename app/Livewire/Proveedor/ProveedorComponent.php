<?php

namespace App\Livewire\Proveedor;

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

    public function search()
    {
        $this->resetPage();
    }

    public function render()
    {
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

        $proveedores = $query->orderBy($this->sort, $this->direction)
            ->paginate(10);

        return view('livewire.proveedor.proveedor-component', [
            'proveedores' => $proveedores
        ]);
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
    
    public function editProveedor($proveedorId){
        return redirect()->route('compras.proveedores.editProveedores', ['idproveedor' => $proveedorId]);
    }
}

