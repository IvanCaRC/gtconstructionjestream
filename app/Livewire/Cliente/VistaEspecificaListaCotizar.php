<?php

namespace App\Livewire\Cliente;

use Livewire\Component;

use App\CustomClases\ConexionProveedorItemTemporal;
use App\Models\Item;
use App\Models\ItemEspecifico;
use App\Models\ItemEspecificoHasFamilia;
use App\Models\ItemEspecificoProveedor;

use App\Models\ListasCotizar;
use Illuminate\Support\Facades\Auth;

class VistaEspecificaListaCotizar extends Component
{
    public $listadeUsuarioActiva;
    public $usuarioActual;
    public $nombreProyecto;
    public $nombreCliente;
    public $idLista;
    public $idListaActual;
    public $itemsDeLaLista = [];

    public $cantidades = [];


    public function mount($idLista)
    {
        $lista = ListasCotizar::find($idLista);

        if (!$lista) {
            session()->flash('error', 'No se encontró la lista.');
            return;
        }

        $itemsData = json_decode($lista->items_cotizar, true) ?? [];

        $itemIds = array_column($itemsData, 'id');
        $items = ItemEspecifico::whereIn('id', $itemIds)->get();

        $this->itemsDeLaLista = $items->map(function ($item) use ($itemsData) {
            $itemData = collect($itemsData)->firstWhere('id', $item->id);
            $item->cantidad = $itemData['cantidad'];

            // Guardamos la cantidad en el array de cantidades
            $this->cantidades[$item->id] = $itemData['cantidad'];

            return $item;
        });

        $this->idListaActual = $idLista;
    }


    public function render()
    {
        return view('livewire.cliente.vista-especifica-lista-cotizar');
    }

    public function agregarItemLista($idItem)
    {
        $lista = ListasCotizar::find($this->idListaActual);

        if (!$lista) {
            session()->flash('error', 'No se encontró la lista.');
            return;
        }

        $items = json_decode($lista->items_cotizar, true) ?? [];

        $itemKey = array_search($idItem, array_column($items, 'id'));

        if ($itemKey !== false) {
            $items[$itemKey]['cantidad'] += 1;
        } else {
            $items[] = ['id' => $idItem, 'cantidad' => 1];
        }

        $lista->update(['items_cotizar' => json_encode($items)]);

        session()->flash('success', 'Item agregado a la lista.');
    }

    public function actualizarCantidad($idItem, $cambio)
    {
        $lista = ListasCotizar::find($this->idListaActual);

        if (!$lista) return;

        $items = json_decode($lista->items_cotizar, true) ?? [];

        // Buscar el item en la lista
        foreach ($items as $key => &$item) {
            if ($item['id'] == $idItem) {
                if ($cambio === 0) {
                    // Si cambio es 0, significa que se escribió manualmente en el input
                    $nuevaCantidad = $this->cantidades[$idItem] ?? 1;
                } else {
                    // Si se presionó + o -, se suma/resta
                    $nuevaCantidad = $item['cantidad'] + $cambio;
                }

                if ($nuevaCantidad <= 0) {
                    // Si la cantidad llega a 0, eliminar el item de la lista
                    unset($items[$key]);
                } else {
                    // Si no, actualizar la cantidad
                    $item['cantidad'] = $nuevaCantidad;
                }
            }
        }

        // Reindexar el array para evitar problemas con las claves eliminadas
        $items = array_values($items);

        // Guardar los cambios
        $lista->update(['items_cotizar' => json_encode($items)]);

        // Refrescar la lista en la vista
        $this->mount($this->idListaActual);
    }

    public function eliminarItemLista($idItem)
    {
        $lista = ListasCotizar::find($this->idListaActual);

        if (!$lista) return;

        // Obtener los items de la lista
        $items = json_decode($lista->items_cotizar, true) ?? [];

        // Filtrar los items para eliminar el que coincida con $idItem
        $items = array_filter($items, fn($item) => $item['id'] != $idItem);

        // Guardar los cambios en la base de datos
        $lista->update(['items_cotizar' => json_encode(array_values($items))]);

        // Refrescar la lista en la vista
        $this->mount($this->idListaActual);

        session()->flash('success', 'Item eliminado correctamente.');
    }

    public function viewItem($idItem)
    {
        $item = ItemEspecifico::find($idItem);

        if ($item === null) {
            abort(404, 'item no encontrada');
        }
        return redirect()->route('ventas.fichasTecnicas.fichaEspecificaItem', ['idItem' => $idItem]);
    }

    public $openModalItemPersonalisado = false;

    public function cancelar()
    {
        $this->reset('openModalItemPersonalisado');
        $this->dispatch('refresh');
    }
}
