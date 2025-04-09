<?php

namespace App\Livewire\Cotisaciones;

use App\Models\Cotizacion;
use App\Models\ItemEspecifico;
use App\Models\ItemTemporal;
use App\Models\ListasCotizar;
use Livewire\Component;

class VerCarritoCotisaciones extends Component
{

    public $idCotisacion;
    public $cotisacion;
    public $itemsDeLaLista = [];

    public $listadeUsuarioActiva;
    public $usuarioActual;
    public $nombreProyecto;
    public $nombreCliente;
    public $preferenciaProeycto;
    public $idLista;
    public $idListaActual;

    public $itemsTemporalesDeLaLista = [];

    public $cantidades = [];
    public $estados = [];

    public $cantidadItemsDiferentes = 0;

    public $cantidadItemsTemporaesDiferentes = 0;

    public $idProyectoActual;

    public function mount($idCotisacion)
    {

        $this->idCotisacion = $idCotisacion ;
        $this->cotisacion = Cotizacion::findOrFail($idCotisacion);
        
        $idLista = $this->cotisacion->lista_cotizar_id;

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
            $item->estado = $itemData['estado'];

            // Guardamos la cantidad en el array de cantidades
            $this->cantidades[$item->id] = $itemData['cantidad'];
            $this->estados[$item->id] = $itemData['estado'];

            return $item;
        });

        $this->idListaActual = $idLista;
        $this->cantidadItemsDiferentes = count($itemsData);
        /////

        $itemsDataTemporales = json_decode($lista->items_cotizar_temporales, true) ?? [];
        $itemIdsTemporales = array_column($itemsDataTemporales, 'id');
        $itemsTemporales = ItemTemporal::whereIn('id', $itemIdsTemporales)->get();

        $this->itemsTemporalesDeLaLista = $itemsTemporales->map(function ($itemTemporal) use ($itemsDataTemporales) {
            $itemDataTemporal = collect($itemsDataTemporales)->firstWhere('id', $itemTemporal->id);
            $itemTemporal->cantidad = $itemDataTemporal['cantidad'];

            // Guardamos la cantidad en el array de cantidades
            $this->cantidades[$itemTemporal->id] = $itemDataTemporal['cantidad'];

            return $itemTemporal;
        });

        $this->actualizarCantidadItemsTemporalesDiferentes();

        if ($lista) {
            // Recuperamos el proyecto relacionado
            // Si se encuentra el registro, guardar el nombre; si no, asignar  null

            $this->listadeUsuarioActiva = $lista->nombre ?? 'Sin nombre';
            $proyecto = $lista->proyecto ?? 'Sin proyecto';
            $cliente = $lista->cliente ?? 'Sin cliente    ';
            $idProyecto = $lista->proyecto_id ?? 'Sin proyecto';
            // Asignamos los valores
            $this->nombreProyecto = $proyecto->nombre ?? 'Sin nombre';
            $this->preferenciaProeycto = $proyecto->preferencia ?? 'Sin preferencia';
            $this->idProyectoActual = $idProyecto;
            $this->nombreCliente = $cliente->nombre ?? 'Sin cliente';

            // Obtener los IDs de los items en la lista
            $itemsData = json_decode($lista->items_cotizar, true) ?? [];
        } else {
            // Si no existe el registro
            $this->idLista = null;
            $this->listadeUsuarioActiva = null;
            $this->nombreProyecto = null;
            $this->nombreCliente = null;
        }
    }

    public function render()
    {
        return view('livewire.cotisaciones.ver-carrito-cotisaciones');
    }

    public function actualizarCantidadItemsTemporalesDiferentes()
    {
        $lista = ListasCotizar::find($this->idListaActual);

        if (!$lista) {
            $this->cantidadItemsDiferentes = 0;
            return;
        }

        $items = json_decode($lista->items_cotizar_temporales, true) ?? [];

        // Contar los ítems únicos en la lista
        $this->cantidadItemsTemporaesDiferentes = count($items);
    }
}
