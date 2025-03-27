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

    public $itemsDeLaLista;

    public function mount($idLista)
    {

        $lista = ListasCotizar::find($idLista);
        $itemsData = json_decode($lista->items_cotizar, true) ?? [];

        $itemIds = array_column($itemsData, 'id'); // Extraer solo los IDs
        $items = Item::whereIn('id', $itemIds)->get();

        // Asociamos los items con su cantidad
        $this->itemsDeLaLista = $items->map(function ($item) use ($itemsData) {
            $itemData = collect($itemsData)->firstWhere('id', $item->id);
            $item->cantidad = $itemData['cantidad'];
            return $item;
        });

        $this->idListaActual = $idLista;
        // Obtener el usuario actual
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
        } else {
            // Si no existe el registro
            $this->listadeUsuarioActiva = null;
            $this->nombreProyecto = null;
            $this->nombreCliente = null;
        }
    }

    public function render()
    {
        return view('livewire.cliente.vista-especifica-lista-cotizar');
    }
}
