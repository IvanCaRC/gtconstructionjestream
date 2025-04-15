<?php

namespace App\Livewire\ItemsCotizar;

use Livewire\Component;

use App\CustomClases\ConexionProveedorItemTemporal;
use App\Models\Cotizacion;
use App\Models\Item;
use App\Models\ItemEspecifico;
use App\Models\ItemEspecificoHasFamilia;
use App\Models\ItemEspecificoProveedor;
use App\Models\ListasCotizar;
use Illuminate\Support\Facades\Auth;

class VistaEspecificaItem extends Component
{
    public $item, $itemEspecifico;
    public $imagenesCargadas;
    public $familiasSeleccionadas = [''];
    public $ProvedoresAsignados = [];
    public $especificaciones = [['enunciado' => '', 'concepto' => '']];

    public $proveedorSeleccionadoId = null;
    public $precioMinimoSeleccionado = null;
    public $precioMaximoSeleccionado = null;

    public $tipoCotisacion = null;

    public $nombreProyecto;
    public $nombreCliente;
    public $idLista;
    public $usuarioActual;
    public $listadeUsuarioActiva;

    public $itemsEnLista = [];

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
        $especificaciones = array_filter($especificaciones, function ($especificacion) {
            return !empty($especificacion['enunciado']) || !empty($especificacion['concepto']);
        });

        if (!empty($especificaciones)) {
            $this->especificaciones = $especificaciones;
        } else {
            $this->especificaciones = null;
        }

        // Obtener el usuario actual
        $this->usuarioActual = Auth::user();

        // Consultar el primer registro con estado 0 para el usuario actual
        $registro = Cotizacion::where('id_usuario_compras', $this->usuarioActual->id) // Verificar usuario actual
            ->where('estado', 1) // Estado igual a 0
            ->first(); // Obtener el primer registro (o null si no hay)

        if ($registro) {
            // Recuperamos el proyecto relacionado
            // Si se encuentra el registro, guardar el nombre; si no, asignar null
            $this->idLista = $registro->id;
            $this->listadeUsuarioActiva = $registro->nombre ?? 'Sin nombre';
            $proyecto = $registro->proyecto ?? 'Sin proyecto';
            $cliente = $proyecto->cliente ?? 'Sin cliente    ';

            // Asignamos los valores
            $this->nombreProyecto = $proyecto->nombre ?? 'Sin nombre';

            $this->nombreCliente = $cliente->nombre ?? 'Sin cliente';

            // Obtener los IDs de los items en la lista
            $itemsData = json_decode($registro->items_cotizar, true) ?? [];
            $this->itemsEnLista = array_column($itemsData, 'id');
        } else {
            // Si no existe el registro
            $this->idLista = null;
            $this->listadeUsuarioActiva = null;
            $this->nombreProyecto = null;
            $this->nombreCliente = null;
        }
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

    public function seleccionarProveedor($id)
    {
        if ($this->proveedorSeleccionadoId === $id) {
            // Si el mismo proveedor está seleccionado, desmarcarlo
            $this->proveedorSeleccionadoId = null;
        } else {
            // Seleccionar un nuevo proveedor
            $this->proveedorSeleccionadoId = $id;
        }
    }

    public function cambiarProveedor()
    {
        // Seleccionar un nuevo proveedor
        $this->tipoCotisacion = 1;
    }

    public function cambiarStock()
    {
        $this->tipoCotisacion = 2;
    }


    public function render()
    {
        return view('livewire.items-cotizar.vista-especifica-item');
    }
}
