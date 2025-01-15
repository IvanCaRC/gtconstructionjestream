<?php

namespace App\Livewire\Proveedor;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Proveedor;

class ProveedorComponent extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $sort = 'id';
    public $direction = 'desc';
    protected $listeners = ['renderVistaProv' => 'render'];

    public function search()
    {
        $this->resetPage();
    }

    public function render()
    {

        $query = Proveedor::where('estado_eliminacion', 1);

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('nombre', 'LIKE', "%{$this->searchTerm}%")
                    ->orWhere('correo', 'LIKE', "%{$this->searchTerm}%")
                    ->orWhere('rfc', 'LIKE', "%{$this->searchTerm}%");
            });
        }

        $proveedor = $query->orderBy($this->sort, $this->direction)
            ->paginate(10);

        return view('livewire.proveedor.proveedor-component', [
            'proveedores' => $proveedor
        ]);
        
    }

    public function eliminar($proveedroId)
    {
        $Proveedor = Proveedor::findOrFail($proveedroId);
        $Proveedor->update(['estado_eliminacion' => false]);
        $this->dispatch('renderVistaProv');
    }

    public function viewProveedor($idproveedor)
    {
        return redirect()->route('compras.proveedores.viewProveedorEspecifico', ['idproveedor' => $idproveedor]);
    }
}
