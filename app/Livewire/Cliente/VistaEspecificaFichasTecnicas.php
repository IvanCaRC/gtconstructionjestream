<?php

namespace App\Livewire\Cliente;


use App\CustomClases\ConexionProveedorItemTemporal;
use App\Models\Item;
use App\Models\ItemEspecifico;
use App\Models\ItemEspecificoHasFamilia;
use App\Models\ItemEspecificoProveedor;
use Livewire\Component;
use App\Models\ListasCotizar;
use Illuminate\Support\Facades\Auth;

class VistaEspecificaFichasTecnicas extends Component
{
    public $item, $itemEspecifico;
    public $imagenesCargadas;
    public $familiasSeleccionadas = [''];
    public $ProvedoresAsignados = [];
    public $especificaciones = [['enunciado' => '', 'concepto' => '']];
    public $ficha_Tecnica_pdf;

    public $listadeUsuarioActiva;
    public $usuarioActual;
    public $nombreProyecto;
    public $nombreCliente;
    public $idLista;

    public $cantidad = 1;


    public function incrementarCantidad()
    {
        if ($this->cantidad < 99) {
            $this->cantidad++;
        }
    }

    // Disminuye la cantidad (mínimo 1)
    public function decrementarCantidad()
    {
        if ($this->cantidad > 1) {
            $this->cantidad--;
        }
    }

    public function mount($idItem)
    {
        $this->usuarioActual = Auth::user();

        // Consultar el primer registro con estado 0 para el usuario actual
        $registro = ListasCotizar::where('usuario_id', $this->usuarioActual->id) // Verificar usuario actual
            ->where('estado', 1) // Estado igual a 0
            ->first(); // Obtener el primer registro (o null si no hay)

        if ($registro) {
            // Recuperamos el proyecto relacionado
            // Si se encuentra el registro, guardar el nombre; si no, asignar null
            $this->idLista = $registro->id;
            $this->listadeUsuarioActiva = $registro->nombre;
            $proyecto = $registro->proyecto; // Relación proyecto
            $cliente = $proyecto->cliente; // Relación cliente

            // Asignamos los valores
            $this->nombreProyecto = $proyecto->nombre ?? 'Sin nombre';
            $this->nombreCliente = $cliente->nombre ?? 'Sin cliente';
            $itemsData = json_decode($registro->items_cotizar, true) ?? [];
            $this->itemsEnLista = array_column($itemsData, 'id');
        } else {
            // Si no existe el registro
            $this->listadeUsuarioActiva = null;
            $this->nombreProyecto = null;
            $this->nombreCliente = null;
        }

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
        return view('livewire.cliente.vista-especifica-fichas-tecnicas');
    }

    public function verLista($idLista)
    {
        return redirect()->route('ventas.clientes.vistaEspecificaListaCotizar', ['idLista' => $idLista]);
    }

    public $itemsEnLista = [];

    public function agregarItemLista($idItem)
    {
        // Buscar la lista existente (asegúrate de tener el ID de la lista almacenado
        $lista = ListasCotizar::find($this->idLista);

        if (!$lista) {
            session()->flash('error', 'No se encontró la lista de cotización.');
            return;
        }

        // Decodificar los items ya guardados
        $items = json_decode($lista->items_cotizar, true) ?? [];

        // Buscar si el item ya está en la lista
        $itemKey = array_search($idItem, array_column($items, 'id'));

        if ($itemKey !== false) {
            // Si el item ya existe, aumentar la cantidad
              $items[$itemKey]['cantidad'] += $this->cantidad;
        } else {
            // Si el item no existe, agregarlo con cantidad inicial 1
            $items[] = [
                'id' => $idItem,
                'cantidad' => $this->cantidad
            ];
        }

        // Guardar la lista actualizada en la base de datos
        $lista->update([
            'items_cotizar' => json_encode($items)
        ]);

        $this->itemsEnLista = array_column($items, 'id');

        session()->flash('success', 'Item agregado a la lista de cotización.');
    }
}
