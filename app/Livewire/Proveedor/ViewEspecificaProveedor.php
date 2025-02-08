<?php

namespace App\Livewire\Proveedor;

use App\Models\Proveedor;
use App\Models\ProveedorHasFamilia;
use App\Models\Telefono;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ViewEspecificaProveedor extends Component
{
    public $proveedor,$familiasSeleccionadas = [''], $telefonos = [''];
    
    public $searchTerm = '';
    public $sort = 'id';
    public $direction = 'desc';
    
    public function render()
    {
        if (!$this->proveedor->estado_eliminacion) {
            abort(404); // Lanza un error 404 si la familia está eliminada
        }

        $query = Proveedor::where('estado_eliminacion', 1)
        ->with(['familias', 'direcciones' => function ($query) {
            $query->whereNotNull('proveedor_id'); // Solo incluir direcciones donde proveedor_id no sea nulo
        }, 'telefonos' => function ($query) {
            $query->whereNotNull('proveedor_id'); // Solo incluir telefonos donde proveedor_id no sea nulo
        }]);
        $proveedores = $query->orderBy($this->sort, $this->direction)
            ->paginate(10);

        return view('livewire.proveedor.view-especifica-proveedor', [
            'proveedor' => $this->proveedor,
            'proveedores' => $proveedores
        ]);
    }

    public function mount($idproveedor)
    {
        // Obtiene los teléfonos del proveedor actual
        $this->telefonos = Telefono::where('proveedor_id', $idproveedor)->pluck('numero')->toArray();

        // Obtiene las familias seleccionadas del proveedor actual
        $this->familiasSeleccionadas = ProveedorHasFamilia::where('proveedor_id', $idproveedor)
            ->with('familia')
            ->get()
            ->pluck('familia')
            ->toArray();
        $this->proveedor = Proveedor::findOrFail($idproveedor);
    }

    public function eliminar($proveedorId)
    {
        ProveedorHasFamilia::where('proveedor_id', $proveedorId)->delete();
        $Proveedor = Proveedor::findOrFail($proveedorId);
        $Proveedor->update(['estado_eliminacion' => false]);
        $this->dispatch('renderVistaProv');
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
