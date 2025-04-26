<?php

namespace App\Livewire\ItemsCotizar;

use Livewire\Component;
use App\CustomClases\ConexionProveedorItemTemporal;
use App\Models\{Cotizacion, Item, ItemEspecifico, ItemEspecificoHasFamilia, ItemEspecificoProveedor, Proveedor, Proyecto};
use Illuminate\Support\Facades\Auth;

class VistaEspecificaItem extends Component
{
    // Propiedades públicas del componente
    public $item;                        // Modelo Item relacionado
    public $itemEspecifico;              // Modelo ItemEspecifico principal
    public $imagenesCargadas;            // Array de imágenes del item
    public $familiasSeleccionadas = [];  // Familias asociadas al item
    public $proveedoresAsignados = [];   // Proveedores disponibles para el item
    public $especificaciones = [];       // Especificaciones técnicas del item

    public $proveedorSeleccionadoId;     // ID del proveedor seleccionado
    public $tipoCotizacion;              // Tipo de cotización (1: proveedor, 2: stock)

    public $usuarioActual;               // Usuario autenticado
    public $listaUsuarioActiva;          // Nombre de la lista activa del usuario
    public $itemsEnLista = [];           // IDs de items en lista (stock)
    public $itemsEnListaProveedores = []; // IDs de items en lista (proveedores)

    public $idCotizaciones;              // ID de la cotización activa
    public $proyecto;                    // Modelo Proyecto relacionado
    public $cantidad = 1;                // Cantidad para cotizar (valor mínimo 1)
    public $preferenciaProyecto; // 1 = Tiempo, 2 = Precio, null = Sin preferencia
    public $proveedorRecomendadoId = null; // ID del proveedor recomendado

    /**
     * Inicializa el componente con el item específico
     * 
     * @param int $idItem ID del item específico a mostrar
     */
    public function mount($idItem)
    {
        $this->cargarItemEspecifico($idItem);
        $this->cargarImagenes();
        $this->cargarProveedores($idItem);
        $this->cargarFamilias($idItem);
        $this->cargarEspecificaciones();
        $this->cargarDatosUsuario();
        $this->ordenarProveedoresSegunPreferencia(); // Ordenar al cargar
    }

    /**
     * Carga los datos principales del item específico
     */
    private function cargarItemEspecifico($idItem)
    {
        $this->itemEspecifico = ItemEspecifico::findOrFail($idItem);
        $this->item = Item::findOrFail($this->itemEspecifico->item_id);
    }

    /**
     * Procesa las imágenes del item
     */
    private function cargarImagenes()
    {
        $this->imagenesCargadas = $this->itemEspecifico->image
            ? explode(',', $this->itemEspecifico->image)
            : null;
    }

    /**
     * Carga los proveedores asociados al item
     */
    private function cargarProveedores($idItem)
    {
        $proveedores = ItemEspecificoProveedor::where('item_especifico_id', $idItem)->get();

        foreach ($proveedores as $proveedor) {
            $this->proveedoresAsignados[] = (array) new ConexionProveedorItemTemporal(
                $proveedor->proveedor_id,
                $proveedor->proveedor->nombre,
                $proveedor->tiempo_min_entrega,
                $proveedor->tiempo_max_entrega,
                $proveedor->precio_compra,
                $proveedor->unidad,
                $proveedor->estado
            );
        }
    }

    /**
     * Ordena los proveedores según la preferencia del proyecto
     * y establece el proveedor recomendado.
     */
    public function ordenarProveedoresSegunPreferencia()
    {
        if (empty($this->proveedoresAsignados)) {
            return;
        }

        switch ($this->preferenciaProyecto) {
            case 1: // Tiempo de entrega (más rápido primero)
                $this->proveedoresAsignados = $this->quicksort(
                    $this->proveedoresAsignados,
                    'tiempo_maximo_entrega',
                    'asc'
                );
                $this->proveedorRecomendadoId = $this->proveedoresAsignados[0]['proveedor_id'];
                break;

            case 2: // Precio (más barato primero)
                $this->proveedoresAsignados = $this->quicksort(
                    $this->proveedoresAsignados,
                    'precio_compra',
                    'asc'
                );
                $this->proveedorRecomendadoId = $this->proveedoresAsignados[0]['proveedor_id'];
                break;

            default: // Sin preferencia
                $this->proveedorRecomendadoId = null;
                break;
        }
    }


    /**
     * Carga las familias asociadas al item
     */
    private function cargarFamilias($idItem)
    {
        $this->familiasSeleccionadas = ItemEspecificoHasFamilia::where('item_especifico_id', $idItem)
            ->with('familia')
            ->get()
            ->pluck('familia')
            ->toArray();
    }

    /**
     * Procesa las especificaciones técnicas del item
     */
    private function cargarEspecificaciones()
    {
        $especificaciones = json_decode($this->itemEspecifico->especificaciones, true) ?: [];

        $this->especificaciones = array_filter($especificaciones, function ($espec) {
            return !empty($espec['enunciado']) || !empty($espec['concepto']);
        }) ?: [];
    }

    /**
     * Carga los datos relacionados al usuario y su cotización activa
     */
    private function cargarDatosUsuario()
    {
        $this->usuarioActual = Auth::user();

        if (!$this->usuarioActual->cotizaciones) {
            $this->resetearDatosCotizacion();
            return;
        }

        $cotizacion = Cotizacion::find($this->usuarioActual->cotizaciones);

        if (!$cotizacion) {
            $this->resetearDatosCotizacion();
            return;
        }

        $this->proyecto = Proyecto::find($cotizacion->proyecto_id);
        $this->preferenciaProyecto = $this->proyecto->preferencia;
        $this->idCotizaciones = $cotizacion->id;
        $this->listaUsuarioActiva = $cotizacion->nombre ?? 'Sin nombre';

        // Cargar items en lista de stock
        $itemsStock = json_decode($cotizacion->items_cotizar_stock, true) ?: [];
        $this->itemsEnLista = array_column($itemsStock, 'id');

        // Cargar items en lista de proveedores
        $itemsProveedores = json_decode($cotizacion->items_cotizar_proveedor, true) ?: [];
        $this->itemsEnListaProveedores = array_column($itemsProveedores, 'id');
    }

    /**
     * Resetea los datos de cotización cuando no hay una activa
     */
    private function resetearDatosCotizacion()
    {
        $this->idCotizaciones = null;
        $this->listaUsuarioActiva = null;
        $this->itemsEnLista = [];
        $this->itemsEnListaProveedores = [];
    }

    /**
     * Alterna la selección de un proveedor
     */
    public function seleccionarProveedor($id)
    {
        $this->proveedorSeleccionadoId = $this->proveedorSeleccionadoId === $id ? null : $id;
    }

    /**
     * Establece el tipo de cotización como proveedor
     */
    public function cambiarProveedor()
    {
        $this->tipoCotizacion = 1;
    }

    /**
     * Establece el tipo de cotización como stock (si hay stock disponible)
     */
    public function cambiarStock()
    {
        if (!$this->itemEspecifico->stock) {
            session()->flash('error', 'No hay un stock asignado en el item que se pueda usar');
            return;
        }
        $this->tipoCotizacion = 2;
    }

    /**
     * Incrementa la cantidad para cotizar
     */
    public function incrementarCantidad()
    {
        $this->cantidad++;
    }

    /**
     * Decrementa la cantidad para cotizar (mínimo 1)
     */
    public function decrementarCantidad()
    {
        $this->cantidad > 1 && $this->cantidad--;
    }

    /**
     * Valida que la cantidad no sea menor a 1
     */
    public function updatedCantidad($value)
    {
        $this->cantidad = $value < 1 ? 1 : $value;
    }

    /**
     * Agrega un item a la lista de cotización por stock
     */
    public function agregarItemStockLista($data)
    {

        [$idItem, $nombreItem] = explode('|', $data);
        $cotizacion = Cotizacion::find($this->idCotizaciones);

        if (!$cotizacion) {
            return;
        }

        if ($this->itemEspecifico->stock < $this->cantidad) {
            session()->flash('error', 'La cantidad solicitada excede el stock actual');
            return;
        }

        if ($this->itemEspecifico->moc > $this->cantidad) {
            session()->flash('error', 'La cantidad solicitada debe ser mayor al minimo de venta permitidol');
            return;
        }

        if ($this->cantidad >= $this->itemEspecifico->cantidad_piezas_mayoreo) {
            $precio =  $this->itemEspecifico->precio_venta_mayorista;
        } else {
            $precio =  $this->itemEspecifico->precio_venta_minorista;
        }


        $items = json_decode($cotizacion->items_cotizar_stock, true) ?: [];
        $items[] = [
            'id' => $idItem,
            'nombreDeItem' => $nombreItem,
            'precio' => $precio,
            'cantidad' => $this->cantidad,
            'estado' => 0,
        ];

        $cotizacion->update(['items_cotizar_stock' => json_encode($items)]);

        return redirect()->route('compras.cotisaciones.verCarritoCotisaciones', [
            'idCotisacion' => $this->idCotizaciones
        ]);
    }

    public function agregarItemProveedorLista($data)
    {

        [$itemId, $proveedorIdRegistro, $nombreItem] = explode('|', $data);
        $proveedorItem = ItemEspecificoProveedor::where('item_especifico_id', $itemId)
            ->where('proveedor_id', $proveedorIdRegistro)
            ->first();
        $proveedorId = $proveedorItem->id;
        $unidad = $proveedorItem->unidad;
        $precioSelecionado = $proveedorItem->precio_compra;


        $proveedor = Proveedor::find($proveedorIdRegistro);

        $cotizacion = Cotizacion::find($this->idCotizaciones);

        if (!$cotizacion) {
            return $this->emit('mostrarAlerta', 'error', 'No se encontró la cotización');
        }

        if ($this->itemEspecifico->moc > $this->cantidad) {
            session()->flash('error', 'La cantidad solicitada debe ser mayor al minimo de venta permitidol');
            return;
        }

        if ($this->cantidad >= $this->itemEspecifico->cantidad_piezas_mayoreo) {
            $precio = round($precioSelecionado * (1 + $this->itemEspecifico->porcentaje_venta_mayorista / 100), 2);
        } else {
            $precio = round($precioSelecionado * (1 + $this->itemEspecifico->porcentaje_venta_minorista / 100), 2);
        }



        $items = json_decode($cotizacion->items_cotizar_proveedor, true) ?: [];

        $items[] = [
            'id' => $itemId,
            'nombreDeItem' => $nombreItem,
            'proveedor_id' => $proveedorId,
            'nombreProveedor' => $proveedor->nombre,
            'unidad' => $unidad,
            'precio' => $precio,
            'cantidad' => $this->cantidad,
            'estado' => 0,
        ];

        $cotizacion->update(['items_cotizar_proveedor' => json_encode($items)]);

        return redirect()->route('compras.cotisaciones.verCarritoCotisaciones', [
            'idCotisacion' => $this->idCotizaciones
        ]);
    }

    /**
     * Implementación de Quicksort para arrays multidimensionales
     */
    private function quicksort($array, $key, $order = 'asc')
    {
        if (count($array) <= 1) {
            return $array;
        }

        $pivot = $array[0][$key];
        $left = [];
        $right = [];

        for ($i = 1; $i < count($array); $i++) {
            if ($order === 'asc') {
                if ($array[$i][$key] < $pivot) {
                    $left[] = $array[$i];
                } else {
                    $right[] = $array[$i];
                }
            } else {
                if ($array[$i][$key] > $pivot) {
                    $left[] = $array[$i];
                } else {
                    $right[] = $array[$i];
                }
            }
        }

        return array_merge(
            $this->quicksort($left, $key, $order),
            [$array[0]], // Pivote
            $this->quicksort($right, $key, $order)
        );
    }

    public function verLista($idLista)
    {

        return redirect()->route('compras.cotisaciones.verCarritoCotisaciones', ['idCotisacion' => $idLista]);
    }

    /**
     * Renderiza la vista del componente
     */
    public function render()
    {
        return view('livewire.items-cotizar.vista-especifica-item');
    }
}
