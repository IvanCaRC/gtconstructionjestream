<?php

namespace App\Livewire\Cotisaciones;

use App\Models\Cotizacion;
use App\Models\ItemEspecifico;
use App\Models\ItemEspecificoProveedor;
use App\Models\ItemTemporal;
use App\Models\ListasCotizar;
use Livewire\Component;
use PHPUnit\Framework\Constraint\IsTrue;

class VerCarritoCotisaciones extends Component
{
    // Propiedades principales
    public $idCotisacion;
    public $itemsDeLaLista = [];
    public $itemsTemporalesDeLaLista = [];
    public $cotisacion;
    public $itemCotisacionStock = [];
    public $itemsCotisacionProveedor = [];

    // Propiedades de la lista/cotización
    public $idListaActual;
    public $nombreLista = 'Sin nombre';
    public $nombreProyecto = 'Sin nombre';
    public $nombreCliente = 'Sin cliente';
    public $preferenciaProyecto = 'Sin preferencia';
    public $idProyectoActual;


    // Propiedades para gestión de cantidades y estados
    public $cantidades = [];
    public $estados = [];
    public $nombresDeItems = [];
    public $precios = [];
    public $unidads = [];
    public $proveedorIds = [];
    public $nombreProveedors = [];

    // Contadores
    public $cantidadItemsDiferentes = 0;
    public $cantidadItemsTemporalesDiferentes = 0;

    /**
     * Inicializa el componente con los datos de la cotización
     * 
     * @param int $idCotisacion ID de la cotización a visualizar
     */
    public function mount($idCotisacion)
    {
        $this->idCotisacion = $idCotisacion;
        $this->cargarDatosCotizacion();
        $this->cargarItemsNormales();
        $this->cargarItemsTemporales();
        $this->cargarItemsCotizacionStock();
        $this->cargarItemsCotizacionProveedor();
        $this->cargarResumen();
        $this->calcularPrecioTotal();
    }

    public $cantidadDeItemsLista;
    public $cantidadDeItemsCotizacionStock;
    public $cantidadDeItemsCotizacionProveedor;
    public $cantidadDeItemsCotizacion;
    public $cantidadItemsActivos;
    public $cantidadItemsInactivos;

    public $precioStock;
    public $precioProveedor;
    public $precioTotal;

    private function calcularPrecioTotal()
    {
        $cotisacion = Cotizacion::find($this->idCotisacion);
        if (!$cotisacion) {
            $this->cantidadDeItemsLista = 0;
            return;
        }

        $itemsDeStock = json_decode($cotisacion->items_cotizar_stock, true) ?? [];
        $precioAcumuladoStock = 0;
        foreach ($itemsDeStock as $key => &$item) {
            $itemEspecifico = ItemEspecifico::findOrFail($item['id']);
            if ($item['cantidad'] < $itemEspecifico->cantidad_piezas_mayoreo) {
                $precioAcumuladoStock = $precioAcumuladoStock + ($itemEspecifico->precio_venta_minorista * $item['cantidad']);
            } else {
                $precioAcumuladoStock = $precioAcumuladoStock + ($itemEspecifico->precio_venta_mayorista * $item['cantidad']);
            }
        }
        $this->precioStock = $precioAcumuladoStock;

        $itemsDeProveedor = json_decode($cotisacion->items_cotizar_proveedor, true) ?? [];
        $precioAcumuladoProveedor = 0;
        foreach ($itemsDeProveedor as $key => &$item) {
                $precioAcumuladoProveedor = $precioAcumuladoProveedor + ($item['precio'] * $item['cantidad']);
        }
        $this->precioProveedor = $precioAcumuladoProveedor;
    }

    private function cargarResumen()
    {
        $cotisacion = Cotizacion::find($this->idCotisacion);
        $lista = ListasCotizar::find($cotisacion->lista_cotizar_id);

        if (!$cotisacion) {
            $this->cantidadDeItemsLista = 0;
            return;
        }

        if (!$lista) {
            $this->cantidadDeItemsLista = 0;
            return;
        }

        // Decodificar los elementos de ambas propiedades JSON
        $itemsTemporales = json_decode($lista->items_cotizar_temporales, true) ?? [];
        $items = json_decode($lista->items_cotizar, true) ?? [];

        // Sumar la cantidad de elementos de ambas listas
        $this->cantidadDeItemsLista = count($items) + count($itemsTemporales);

        $itemsDeStock = json_decode($cotisacion->items_cotizar_stock, true) ?? [];
        $itemsDeProveedor = json_decode($cotisacion->items_cotizar_proveedor, true) ?? [];

        // Sumar la cantidad de elementos de ambas listas
        $this->cantidadDeItemsCotizacionStock = count($itemsDeStock);
        $this->cantidadDeItemsCotizacionProveedor = count($itemsDeProveedor);
        $this->cantidadDeItemsCotizacion = count($itemsDeStock) + count($itemsDeProveedor);

        $this->cantidadItemsActivos = count(array_filter($items, function ($item) {
            return ($item['estado'] ?? 0) == 1;
        }));

        $this->cantidadItemsInactivos = count(array_filter($items, function ($item) {
            return ($item['estado'] ?? 0) == 0;
        }));
    }

    /**
     * Carga los datos básicos de la cotización
     */
    private function cargarDatosCotizacion()
    {
        $this->cotisacion = Cotizacion::findOrFail($this->idCotisacion);
        $this->idListaActual = $this->cotisacion->lista_cotizar_id;
    }


    /**
     * Carga los items Stock Del las lisata
     * 
     * [{"id":"2","nombreDeItem":"Item de prueba","proveedor_id":11,"nombreProveedor":"Construrama 2","precio":1100,"cantidad":21,"estado":0}]
     */
    private function cargarItemsCotizacionProveedor()
    {
        $cotizacion = Cotizacion::findOrFail($this->idCotisacion);

        if (!$cotizacion) {
            session()->flash('error', 'No se encontró la cotisacion.');
            return;
        }

        $itemsData = json_decode($cotizacion->items_cotizar_proveedor, true) ?? [];
        $itemIds = array_column($itemsData, 'id');
        $items = ItemEspecifico::whereIn('id', $itemIds)->get();

        $this->itemsCotisacionProveedor = $items->each(function ($itemProveedor) use ($itemsData) {
            $itemData = collect($itemsData)->firstWhere('id', $itemProveedor->id);
            $itemProveedor->nombreDeItem = $itemData['nombreDeItem'];
            $itemProveedor->proveedorId = $itemData['proveedor_id'];
            $itemProveedor->nombreProveedor = $itemData['nombreProveedor'];
            $itemProveedor->precio = $itemData['precio'];
            $itemProveedor->cantidad = $itemData['cantidad'];
            $itemProveedor->estado = $itemData['estado'];
            $itemProveedor->unidad = $itemData['unidad'];


            $this->proveedorIds[$itemProveedor->id] = $itemData['proveedor_id'];
            $this->nombreProveedors[$itemProveedor->id] = $itemData['nombreProveedor'];
            $this->cantidades[$itemProveedor->id] = $itemData['cantidad'];
            $this->estados[$itemProveedor->id] = $itemData['estado'];
            $this->nombresDeItems[$itemProveedor->id] = $itemData['nombreDeItem'];
            $this->precios[$itemProveedor->id] = $itemData['precio'];
            $this->unidads[$itemProveedor->id] = $itemData['unidad'];


            return $itemProveedor;
        })->values();
    }

    /**
     * Actualiza la cantidad de un item en la lista de stock

     */
    public function actualizarCantidadProveedor($idItem, $cambio)
    {
        $lista = Cotizacion::find($this->idCotisacion);
        $itemEspecifico = ItemEspecifico::findOrFail($idItem);

        if (!$lista) return;
        $items = json_decode($lista->items_cotizar_proveedor, true) ?? [];
        // Buscar el item en la lista
        foreach ($items as $key => &$item) {
            if ($item['id'] == $idItem) {
                $proveedorItem = ItemEspecificoProveedor::findOrFail($item['proveedor_id']);
                $precioSelecionado = $proveedorItem->precio_compra;
                if ($cambio === 0) {
                    // Si cambio es 0, significa que se escribió manualmente en el input
                    $nuevaCantidad = $this->cantidades[$idItem] ?? 1;
                    if (!$nuevaCantidad) {
                        session()->flash('error', 'No puedes dejar vacio el campo');
                        $nuevaCantidad = 1;
                    }

                    if ($nuevaCantidad >= $itemEspecifico->cantidad_piezas_mayoreo) {
                        $precio = round($precioSelecionado * (1 + $itemEspecifico->porcentaje_venta_mayorista / 100), 2);
                        $item['precio'] = $precio;
                    } else {
                        $precio = round($precioSelecionado * (1 + $itemEspecifico->porcentaje_venta_minorista / 100), 2);
                        $item['precio'] = $precio;
                    }
                } else {
                    // Si se presionó + o -, se suma/resta
                    $nuevaCantidad = $this->cantidades[$idItem] ?? 1;
                    if (!$item['cantidad']) {
                        $nuevaCantidad = 1;
                    } else {
                        $nuevaCantidad = $item['cantidad'] + $cambio;

                        if ($nuevaCantidad >= $itemEspecifico->cantidad_piezas_mayoreo) {
                            $precio = round($precioSelecionado * (1 + $itemEspecifico->porcentaje_venta_mayorista / 100), 2);
                            $item['precio'] = $precio;
                        } else {
                            $precio = round($precioSelecionado * (1 + $itemEspecifico->porcentaje_venta_minorista / 100), 2);
                            $item['precio'] = $precio;
                        }
                    }
                }

                $item['cantidad'] = $nuevaCantidad;
            }
        }
        // Reindexar el array para evitar problemas con las claves eliminadas
        $items = array_values($items);
        // Guardar los cambios
        $lista->update(['items_cotizar_proveedor' => json_encode($items)]);
        // Refrescar la lista en la vista
        $this->mount($this->idCotisacion);
    }


    /**
     * Carga los items Stock Del las lisata
     * 
     * [{"id":"2","nombreDeItem":"Item de prueba","precio":"1100.00","cantidad":"21","estado":0}]
     */
    private function cargarItemsCotizacionStock()
    {
        $cotizacion = Cotizacion::findOrFail($this->idCotisacion);

        if (!$cotizacion) {
            session()->flash('error', 'No se encontró la cotisacion.');
            return;
        }

        $itemsData = json_decode($cotizacion->items_cotizar_stock, true) ?? [];
        $itemIds = array_column($itemsData, 'id');
        $items = ItemEspecifico::whereIn('id', $itemIds)->get();

        $this->itemCotisacionStock = $items->each(function ($itemStock) use ($itemsData) {
            $itemData = collect($itemsData)->firstWhere('id', $itemStock->id);
            $itemStock->nombreDeItem = $itemData['nombreDeItem'];
            $itemStock->precio = $itemData['precio'];
            $itemStock->cantidad = $itemData['cantidad'];
            $itemStock->estado = $itemData['estado'];

            $this->cantidades[$itemStock->id] = $itemData['cantidad'];
            $this->estados[$itemStock->id] = $itemData['estado'];
            $this->nombresDeItems[$itemStock->id] = $itemData['nombreDeItem'];
            $this->precios[$itemStock->id] = $itemData['precio'];


            return $itemStock;
        })->values();
    }

    /**
     * Actualiza la cantidad de un item en la lista de stock

     */
    public function actualizarCantidadStock($idItem, $cambio)
    {
        $lista = Cotizacion::find($this->idCotisacion);
        $itemEspecifico = ItemEspecifico::findOrFail($idItem);
        if (!$lista) return;
        $items = json_decode($lista->items_cotizar_stock, true) ?? [];
        // Buscar el item en la lista
        foreach ($items as $key => &$item) {
            if ($item['id'] == $idItem) {
                if ($cambio === 0) {
                    // Si cambio es 0, significa que se escribió manualmente en el input
                    $nuevaCantidad = $this->cantidades[$idItem] ?? 1;

                    if (!$nuevaCantidad) {
                        session()->flash('error', 'No puedes dejar vacio el campo');
                        $nuevaCantidad = $itemEspecifico->moc;
                    }
                    if ($itemEspecifico->stock < $nuevaCantidad) {
                        session()->flash('error', 'La cantidad solicitada excede el stock actual');
                        $nuevaCantidad = $itemEspecifico->stock;
                    } else if ($itemEspecifico->moc > $nuevaCantidad) {
                        session()->flash('error', 'La cantidad solicitada debe ser mayor al minimo de venta permitidol');
                        $nuevaCantidad = $itemEspecifico->moc;
                    } else {
                        if ($nuevaCantidad >= $itemEspecifico->cantidad_piezas_mayoreo) {
                            $precio = round((1 + $itemEspecifico->precio_venta_mayorista), 2);
                            $item['precio'] = $precio;
                        } else {
                            $precio = round((1 + $itemEspecifico->precio_venta_minorista), 2);
                            $item['precio'] = $precio;
                        }
                    }
                } else {
                    // Si se presionó + o -, se suma/resta
                    if ($itemEspecifico->stock < ($item['cantidad'] + $cambio)) {
                        session()->flash('error', 'La cantidad solicitada excede el stock actual');
                        $nuevaCantidad = $item['cantidad'];
                    } else if ($itemEspecifico->moc > ($item['cantidad'] + $cambio)) {
                        session()->flash('error', 'La cantidad solicitada debe ser mayor al minimo de venta permitidol');
                        $nuevaCantidad = $item['cantidad'];
                    } else {
                        $nuevaCantidad = $item['cantidad'] + $cambio;
                    }
                }

                $item['cantidad'] = $nuevaCantidad;
            }
        }
        // Reindexar el array para evitar problemas con las claves eliminadas
        $items = array_values($items);
        // Guardar los cambios
        $lista->update(['items_cotizar_stock' => json_encode($items)]);
        // Refrescar la lista en la vista
        $this->mount($this->idCotisacion);
    }

    public function eliminarItemLista($idItem)
    {
        $lista =  Cotizacion::find($this->idCotisacion);

        if (!$lista) return;

        // Obtener los items de la lista
        $items = json_decode($lista->items_cotizar_stock, true) ?? [];

        // Filtrar los items para eliminar el que coincida con $idItem
        $items = array_filter($items, fn($item) => $item['id'] != $idItem);

        // Guardar los cambios en la base de datos
        $lista->update(['items_cotizar_stock' => json_encode(array_values($items))]);

        session()->flash('success', 'Item eliminado correctamente.');

        $listaCotizar = ListasCotizar::find($lista->lista_cotizar_id);

        $items = json_decode($listaCotizar->items_cotizar, true) ?: [];
        foreach ($items as $key => &$item) {
            if ($item['id'] == $idItem) {
                $item['estado'] = 0;
            }
            $items = array_values($items);
            // Guardar los cambios
            $listaCotizar->update(['items_cotizar' => json_encode($items)]);
        }
        // Refrescar la lista en la vista
        $this->mount($this->idCotisacion);
    }

    public function eliminarItemListaCoti($idItem)
    {
        $lista =  Cotizacion::find($this->idCotisacion);

        if (!$lista) return;

        // Obtener los items de la lista
        $items = json_decode($lista->items_cotizar_proveedor, true) ?? [];

        // Filtrar los items para eliminar el que coincida con $idItem
        $items = array_filter($items, fn($item) => $item['id'] != $idItem);

        // Guardar los cambios en la base de datos
        $lista->update(['items_cotizar_proveedor' => json_encode(array_values($items))]);

        // Refrescar la lista en la vista


        session()->flash('success', 'Item eliminado correctamente.');

        $listaCotizar = ListasCotizar::find($lista->lista_cotizar_id);

        $items = json_decode($listaCotizar->items_cotizar, true) ?: [];
        foreach ($items as $key => &$item) {
            if ($item['id'] == $idItem) {
                $item['estado'] = 0;
            }
            $items = array_values($items);
            // Guardar los cambios
            $listaCotizar->update(['items_cotizar' => json_encode($items)]);
        }
        $this->mount($this->idCotisacion);
    }


    /**
     * Carga los items normales de la lista de cotización
     */
    private function cargarItemsNormales()
    {
        $lista = ListasCotizar::find($this->idListaActual);

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

            $this->cantidades[$item->id] = $itemData['cantidad'];
            $this->estados[$item->id] = $itemData['estado'];

            return $item;
        });

        $this->cantidadItemsDiferentes = count($itemsData);
    }

    /**
     * Carga los items temporales de la lista de cotización
     */
    private function cargarItemsTemporales()
    {
        $lista = ListasCotizar::find($this->idListaActual);
        $itemsDataTemporales = json_decode($lista->items_cotizar_temporales, true) ?? [];

        $itemIdsTemporales = array_column($itemsDataTemporales, 'id');
        $itemsTemporales = ItemTemporal::whereIn('id', $itemIdsTemporales)->get();

        $this->itemsTemporalesDeLaLista = $itemsTemporales->map(function ($itemTemporal) use ($itemsDataTemporales) {
            $itemDataTemporal = collect($itemsDataTemporales)->firstWhere('id', $itemTemporal->id);
            $itemTemporal->cantidad = $itemDataTemporal['cantidad'];
            $this->cantidades[$itemTemporal->id] = $itemDataTemporal['cantidad'];
            return $itemTemporal;
        });
    }


    public function viewItem($idItem)
    {
        $item = ItemEspecifico::find($idItem);

        if ($item === null) {
            abort(404, 'item no encontrada');
        }
        return redirect()->route('compras.catalogoCotisacion.vistaEspecificaItemCotizar', ['idItem' => $idItem]);
    }

    public function enviarListaCliente(){
        return true;
    }

    public function desactvarLista(){
        return true;
    }



    /**
     * Renderiza la vista del componente
     */
    public function render()
    {
        return view('livewire.cotisaciones.ver-carrito-cotisaciones');
    }
}
