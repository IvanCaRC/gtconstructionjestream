<?php

namespace App\Livewire\Item;

use App\CustomClases\ConexionProveedorItemTemporal;
use App\Models\Item;
use App\Models\ItemEspecifico;
use App\Models\ItemEspecificoHasFamilia;
use App\Models\ItemEspecificoProveedor;
use Livewire\Component;

class VistaEspecifica extends Component
{
    public $item, $itemEspecifico;
    public $imagenesCargadas;
    public $familiasSeleccionadas = [''];
    public $ProvedoresAsignados = [];
    public $especificaciones = [['enunciado' => '', 'concepto' => '']];
    public $ficha_Tecnica_pdf;

    public function mount($idItem)
    {
        // Buscar el registro de ItemEspecifico
        $this->itemEspecifico = ItemEspecifico::findOrFail($idItem);
        // Buscar el registro de Item relacionado
        $this->item = Item::findOrFail($this->itemEspecifico->item_id);
        if ($this->itemEspecifico->image === null) {
            $this->imagenesCargadas = null;
        } else {
            $this->imagenesCargadas = explode(',', $this->itemEspecifico->image);
        }
        $this->cargarProvedoresParaEditar($idItem);
        $this->familiasSeleccionadas = ItemEspecificoHasFamilia::where('item_especifico_id', $idItem)
            ->with('familia')
            ->get()
            ->pluck('familia')
            ->toArray();

            $especificaciones = json_decode($this->itemEspecifico->especificaciones, true);
    
            // Filtrar especificaciones vacías
            $especificaciones = array_filter($especificaciones, function($especificacion) {
                return !empty($especificacion['enunciado']) || !empty($especificacion['concepto']);
            });
        
            if (!empty($especificaciones)) {
                $this->especificaciones = $especificaciones;
            } else {
                $this->especificaciones = null;
            }
        $this->ficha_Tecnica_pdf = $this->itemEspecifico->ficha_tecnica_pdf;
    }

    public function cargarProvedoresParaEditar($idItem)
    {
        $proveedores = ItemEspecificoProveedor::where('item_especifico_id', $idItem)->get();

        foreach ($proveedores as $proveedor) {
            $conexion = new ConexionProveedorItemTemporal(
                $proveedor->proveedor_id,
                $proveedor->proveedor->nombre,
                $proveedor->tiempo_min_entrega,
                $proveedor->tiempo_max_entrega,
                $proveedor->precio_compra,
                $proveedor->unidad,
                $proveedor->estado
            );

            $this->ProvedoresAsignados[] = (array) $conexion; // Convertir el objeto a un array y agregarlo al arreglo
        }
    }

    public function eliminar($itemId)
    {
        ItemEspecificoHasFamilia::where('item_especifico_id', $itemId)->delete();
        ItemEspecificoProveedor::where('item_especifico_id', $itemId)->delete();
        $ItemEspecifico = ItemEspecifico::findOrFail($itemId);
        $ItemEspecifico->update(['estado_eliminacion' => false]);
        $this->dispatch('renderVistaProv');
    }
    
    public function render()
    {
        return view('livewire.item.vista-especifica');
    }

    public function editItem($idItem)
    {
        return redirect()->route('compras.items.edicionItem', ['idItem' => $idItem]);
    }
}
