<?php

namespace App\Livewire\Ventas\RecepsionCotizacio;


use App\Models\Cotizacion;
use App\Models\ItemEspecifico;
use App\Models\ListasCotizar;
use App\Models\ordenVenta;
use App\Models\Proyecto;
use App\Models\Role;
use App\Notifications\OrdenVentaRecibida;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class Recepcioncotiosacion extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $statusFiltro = 0;
    public $precioStock = 0;
    public $precioProveedor = 0;
    public $precioTotal = 0;

    public function search()
    {
        $this->resetPage();
    }

    /**
     * Redirige a la vista de detalles de la cotización
     * @param int $id ID de la cotización
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verDetalles($id)
    {
        $cotizacion = Cotizacion::find($id);
        if ($cotizacion) {
            Auth::user()->update(['cotizaciones' => $id]);
            return redirect()->route('compras.cotisaciones.verCarritoCotisaciones', ['idCotisacion' => $id]);
        }
    }

    /**
     * Redirige a editar la lista de cotización
     * @param int $id ID de la cotización
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editarlista($id)
    {
        $cotizacion = Cotizacion::find($id);
        if ($cotizacion) {
            Auth::user()->update(['cotizaciones' => $id]);
            return redirect()->route('compras.catalogoCotisacion.catalogoItem');
        }
    }

    /**
     * Cancela una cotización
     * @param int $id ID de la cotización
     */


    public function cancelar($id)
    {

        if (Auth::user()->cotizaciones == $id) {
            Auth::user()->update(['cotizaciones' => null]);
        }
        $cotisacion = Cotizacion::findOrFail($id);
        $cotisacion->update([
            'estado' => 7, // 1 = Liquidada
        ]);
        $ListaCotisar = ListasCotizar::findOrFail($cotisacion->lista_cotizar_id);
        $ListaCotisar->update([
            'estado' => 9, // 1 = Liquidada
        ]);
        $proyecto = Proyecto::findOrFail($ListaCotisar->proyecto_id);
        $proyecto->update([
            'proceso' => 10, // 1 = Liquidada
        ]);
        $this->dispatch('refresh');
    }
    //Funcion para abrir el PDF de la cotizacion generada.
    public function generarPDFCotizacion($id)
    {
        // ✅ Recupera la cotización desde la BD con relaciones necesarias
        $cotizacion = Cotizacion::with('usuario', 'proyecto', 'listaCotizar')->findOrFail($id);

        // ✅ Organiza los datos para el PDF
        $data = [
            'title' => 'Cotización',
            'cliente' => $cotizacion->usuario->name ?? 'Sin asignar',
            'proyecto' => $cotizacion->proyecto->nombre ?? 'No asignado',
            'estado' => $cotizacion->estado,
            'usuario_atendio' => "{$cotizacion->usuario->name} {$cotizacion->usuario->first_last_name} {$cotizacion->usuario->second_last_name}",
            'items_stock' => json_decode($cotizacion->items_cotizar_stock, true),
            'items_proveedor' => json_decode($cotizacion->items_cotizar_proveedor, true),
        ];

        // ✅ Generar el PDF con los datos reales
        $pdf = Pdf::loadView('pdf.cotizacion', $data)->setPaper('a4', 'portrait');

        return $pdf->stream('cotizacion.pdf'); // Muestra el PDF en el navegador
    }

    /**
     * Redirige a la vista de detalles del proyecto
     * @param int $idProyecto ID del proyecto
     * @return \Illuminate\Http\RedirectResponse
     */
    public function viewProyecto($idProyecto)
    {
        $proyecto = Proyecto::find($idProyecto);
        if (!$proyecto) {
            abort(404, 'Proyecto no encontrado');
        }
        return redirect()->route('ventas.clientes.vistaEspecProyecto', ['idProyecto' => $idProyecto]);
    }





    /**
     * Renderiza la vista principal del componente
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $query = Auth::user()->hasRole('Administrador')
            ? $this->getQueryForAdmin()
            : $this->getQueryForRegularUser();

        // Aplicar búsqueda
        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->searchTerm . '%')
                    ->orWhereDate('created_at', 'like', '%' . $this->searchTerm . '%');
            });
        }

        // Aplicar filtro de preferencia
        if ($this->statusFiltro != 0) {
            $query->whereHas('proyecto', function ($q) {
                $q->where('preferencia', $this->statusFiltro);
            });
        }

        $listasCotizar = $query->paginate(10);

        return view('livewire.ventas.recepsion-cotizacio.recepcioncotiosacion', compact('listasCotizar'));
    }

    /**
     * Construye la consulta para administradores
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getQueryForAdmin()
    {
        return Cotizacion::with(['proyecto', 'proyecto.cliente'])
            ->orderBy('created_at', 'desc')
            ->whereIn('estado', [1, 2, 3, 4, 5, 6,]);
    }

    /**
     * Construye la consulta para usuarios regulares
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getQueryForRegularUser()
    {
        $usuarioId = Auth::id();

        return Cotizacion::with(['proyecto', 'proyecto.cliente'])
            ->whereHas('proyecto.cliente', function ($q) use ($usuarioId) {
                $q->where('user_id', $usuarioId);
            })
            ->orderBy('created_at', 'desc')
            ->whereIn('estado', [1, 2, 3, 4, 5, 6,]);
    }


    public $openModalOrdenVenta = false;
    public $cotisacionSelecionada;
    public $metodoPago = 1;
    public $formaPago = 1;

    public function abrirModal($id)
    {
        $cotisacion = Cotizacion::find($id);
        $this->openModalOrdenVenta = true;
        $this->cotisacionSelecionada = $cotisacion;
    }

    public function cerrarModal()
    {
        $this->reset('openModalOrdenVenta', 'cotisacionSelecionada', 'metodoPago', 'formaPago');
    }

    public function asignarMetodoPago($valor)
    {
        $this->metodoPago = $valor;
    }

    public function asignarFormaPago($valor)
    {
        $this->formaPago = $valor;
    }

    private function calcularPrecioTotal($id)
    {
        $cotizacion = Cotizacion::find($id);
        if (!$cotizacion) return;

        // Calcular precio de items en stock
        $itemsStock = json_decode($cotizacion->items_cotizar_stock, true) ?? [];
        $this->precioStock = array_reduce($itemsStock, function ($carry, $item) {
            $itemEspecifico = ItemEspecifico::find($item['id']);
            if (!$itemEspecifico) return $carry;

            $precio = ($item['cantidad'] < $itemEspecifico->cantidad_piezas_mayoreo)
                ? $itemEspecifico->precio_venta_minorista
                : $itemEspecifico->precio_venta_mayorista;

            return $carry + ($precio * $item['cantidad']);
        }, 0);

        // Calcular precio de items con proveedor
        $itemsProveedor = json_decode($cotizacion->items_cotizar_proveedor, true) ?? [];
        $this->precioProveedor = array_reduce($itemsProveedor, function ($carry, $item) {
            return $carry + ($item['precio'] * $item['cantidad']);
        }, 0);

        $subtotal = $this->precioStock + $this->precioProveedor;
        $iva = $subtotal * 0.16;
        $totalConIva = $subtotal + $iva;

        // Redondear al próximo múltiplo de 0.10 hacia arriba
        $this->precioTotal = ceil($totalConIva * 10) / 10;
    }
    /**
     * Acepta una cotización y crea una orden de venta
     * @param int $id ID de la cotización
     * @return bool
     */
    public function aceptar()
    {
        $cotizacion = Cotizacion::find($this->cotisacionSelecionada->id);
        if (!$cotizacion) {
            abort(404, 'Cotización no encontrada');
        }

        $lista = ListasCotizar::find($cotizacion->lista_cotizar_id);
        $proyecto = Proyecto::find($lista->proyecto_id ?? null);

        $this->calcularPrecioTotal($this->cotisacionSelecionada->id);

        // Actualizar estados
        $cotizacion->update(['estado' => 2]);
        if ($lista) $lista->update(['estado' => 4]);
        if ($proyecto) $proyecto->update(['proceso' => 3]);


        $proyectoConsulta = Proyecto::findOrFail($lista->proyecto_id ?? null);
        $nombre = strtoupper(substr(strval($proyectoConsulta->nombre), 0, 1)) . 'ODV' . strval($proyectoConsulta->ordenes + 1);

        $proyecto = Proyecto::find($lista->proyecto_id);
        // Crear orden de venta
        $ordenVenta = OrdenVenta::create([
            'id_cliente' => $proyecto->cliente_id,
            'id_usuario' => $cotizacion->usuario_id,
            'id_cotizacion' => $cotizacion->id,
            'nombre' => $nombre,
            'direccion_id' => $proyecto->direccion_id,
            'monto' => $this->precioTotal,
            'montoPagar' => $this->precioTotal,
            'formaPago' => $this->formaPago,
            'metodoPago' => $this->metodoPago, // Estado inicial de la cotización
            'estado' => 0, // Estado inicial de la cotización
        ]);
        //Incrementar ordenes del proyecto
        $proyecto->increment('ordenes');

        // Crear notificación para Administrador y Finanzas
        $usuariosDestino = Role::whereIn('name', ['Administrador', 'Finanzas'])->first()->users;
        Notification::send($usuariosDestino, new OrdenVentaRecibida($proyecto->nombre));
        //Restablecer valores del modal
        $this->reset('openModalOrdenVenta', 'cotisacionSelecionada', 'metodoPago', 'formaPago');
        return true;
    }
}
