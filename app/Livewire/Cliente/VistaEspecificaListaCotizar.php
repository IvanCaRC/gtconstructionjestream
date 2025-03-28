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
        
        $this->usuarioActual = Auth::user();

        $registro = ListasCotizar::where('usuario_id', $this->usuarioActual->id)
            ->where('estado', 1)
            ->first();

        if ($registro) {
            $this->idLista = $registro->id;
            $this->listadeUsuarioActiva = $registro->nombre;
            $proyecto = $registro->proyecto;
            $cliente = $proyecto->cliente;

            $this->nombreProyecto = $proyecto->nombre ?? 'Sin nombre';
            $this->nombreCliente = $cliente->nombre ?? 'Sin cliente';
        } else {
            $this->listadeUsuarioActiva = null;
            $this->nombreProyecto = null;
            $this->nombreCliente = null;
        }
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
}
