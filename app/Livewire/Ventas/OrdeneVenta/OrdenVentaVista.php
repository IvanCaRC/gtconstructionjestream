<?php

namespace App\Livewire\Ventas\OrdeneVenta;

use App\Models\Cotizacion;
use App\Models\ItemEspecifico;
use App\Models\ListasCotizar;
use App\Models\ordenVenta;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class OrdenVentaVista extends Component
{

    use WithPagination;
    use WithFileUploads;

    public $searchTerm = '';
    public $statusFiltro = 0;
    public $statusFiltro2 = 0;
    public $precioStock = 0;
    public $precioProveedor = 0;
    public $precioTotal = 0;
    public $fileNamePdf;


    public function updatedArchivoSubido()
    {
        $this->fileNamePdf = $this->archivoSubido ? $this->archivoSubido->getClientOriginalName() : '';
    }

    public function search()
    {
        $this->resetPage();
    }

    public $esVistaFinanzas = false;

    public function mount()
    {
        $this->esVistaFinanzas = \Route::currentRouteName() === 'finanzas.ordenesVenta.vistaOrdenVentaFin';
    }

    /**
     * Cancela una cotización
     * @param int $id ID de la cotización
     */
    public function cancelar($id)
    {
        $cotizacion = ordenVenta::find($id);
        if ($cotizacion) {
            $cotizacion->estado = 3; // Estado "Cancelada"
            $cotizacion->save();
            $cotizacionCons = Cotizacion::find($cotizacion->id_cotizacion);
            $cotizacionCons->estado = 7; // Estado "Cancelada"
            $cotizacionCons->save();
            if (Auth::user()->cotizaciones == $id) {
                Auth::user()->update(['cotizaciones' => null]);
            }
        }
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

    public function render()
    {
        $query = Auth::user()->hasAnyRole(['Administrador', 'Finanzas'])
            ? $this->getQueryForAdmin()
            : $this->getQueryForRegularUser();

            if (!empty($this->searchTerm)) {
                $query->where(function($q) {
                    $q->where('nombre', 'like', '%' . $this->searchTerm . '%')
                      ->orWhereHas('cliente', function ($subQuery) {
                          $subQuery->where('nombre', 'like', '%' . $this->searchTerm . '%');
                      });
                      
                });
            }

        if ($this->statusFiltro) {
            $query->where('estado', $this->statusFiltro);
        }

        if ($this->statusFiltro2) {
            $query->where('metodoPago', $this->statusFiltro2);
        }

        $ordenesVenta = $query->paginate(10);

        return view('livewire.ventas.ordene-venta.orden-venta-vista', compact('ordenesVenta'));
    }

    public function viewOrden($idProyecto)
    {
        $proyecto = ordenVenta::find($idProyecto);

        if ($proyecto === null) {
            abort(404, 'proyecto no encontrado');
        }

        return redirect()->route('finanzas.verPagos.verPagosOrdenVenta', ['id' => $idProyecto]);
    }

    /**
     * Construye la consulta para administradores
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getQueryForAdmin()
    {
        return OrdenVenta::query()
            ->orderBy('created_at', 'desc')
            ->whereIn('estado', [0, 1]);
    }

    /**
     * Construye la consulta para usuarios regulares
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getQueryForRegularUser()
    {
        $usuarioId = Auth::id();

        return ordenVenta::query()
            ->where('id_usuario', $usuarioId) // Filtrar por usuario
            ->orderBy('created_at', 'desc')
            ->whereIn('estado', [0,1, 2]);
    }

    public $openModalPagar = false;
    public $ordenVentaSelecionada;
    public $cantidadPagar = 0;
    public $montoPagar = 0;
    public $archivoSubido;
    public $datosParaPagra;

    public function abrirModalPagar($ordenVentaId)
    {
        $this->ordenVentaSelecionada = ordenVenta::findOrFail($ordenVentaId);
        $this->montoPagar = $this->ordenVentaSelecionada->montoPagar;
        $this->openModalPagar = true;
        $this->datosParaPagra = json_decode($this->ordenVentaSelecionada->historial, true) ?: [];


    }

    public function cerrarModal()
    {
        $this->reset(['openModalPagar', 'ordenVentaSelecionada', 'cantidadPagar', 'montoPagar','archivoSubido','datosParaPagra','fileNamePdf']);
    }

    public function aceptar()
    {
        $this->validate([
            'cantidadPagar' => 'required|numeric|min:0.01|max:' . $this->ordenVentaSelecionada->montoPagar
        ]);

        try {
            DB::beginTransaction();

            if ($this->cantidadPagar < $this->ordenVentaSelecionada->montoPagar) {
                $this->Abonar4cantidad($this->cantidadPagar);
                $mensaje = "Abono registrado correctamente";
            } else {
                $this->liquidar($this->cantidadPagar);
                $mensaje = "Orden liquidada completamente";
            }

            DB::commit();

            $this->dispatch('mostrarAlerta', [
                'icono' => 'success',
                'titulo' => 'Éxito',
                'texto' => $mensaje
            ]);


            $this->cerrarModal();
            $this->emit('pagoRealizado'); // Para actualizar listas si es necesario
            $this->reset(['openModalPagar', 'ordenVentaSelecionada', 'cantidadPagar', 'montoPagar','archivoSubido','datosParaPagra']);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('mostrarAlerta', [
                'icono' => 'error',
                'titulo' => 'Error',
                'texto' => 'Ocurrió un error: ' . $e->getMessage()
            ]);
            $this->reset(['openModalPagar', 'ordenVentaSelecionada', 'cantidadPagar', 'montoPagar','archivoSubido','datosParaPagra']);
            return false;
        }
    }

    public function liquidar($cantidad)
    {

        $archivoSubido = $this->archivoSubido ? $this->archivoSubido->store('archivosFacturacionProveedores', 'public') : null;


        $this->datosParaPagra[] = [
            'monto' => number_format($this->ordenVentaSelecionada->montoPagar, 2, '.', ''), // genera "1.30" como string
            'Archivo' => $archivoSubido,
        ];
        
        $this->ordenVentaSelecionada->update([
            'montoPagar' => 0,
            'estado' => 1, // 1 = Liquidada
            'historial' => json_encode($this->datosParaPagra),
        ]);

        $cotisacion = Cotizacion::findOrFail($this->ordenVentaSelecionada->id_cotizacion);

        $itemsDeProveedor = json_decode($cotisacion->items_cotizar_proveedor, true) ?? [];
        $cantidadDeItemsCotizacionProveedor = count($itemsDeProveedor);

        if ($cantidadDeItemsCotizacionProveedor == 0) {
            $cotisacion->update([
                'estado' => 5, // 1 = Liquidada
            ]);
            $ListaCotisar = ListasCotizar::findOrFail($cotisacion->lista_cotizar_id);
            $ListaCotisar->update([
                'estado' => 7, // 1 = Liquidada
            ]);
            $proyecto = Proyecto::findOrFail($ListaCotisar->proyecto_id);
            $proyecto->update([
                'proceso' => 6, // 1 = Liquidada
            ]);
        } else {
            $cotisacion->update([
                'estado' => 3, // 1 = Liquidada
            ]);
            $ListaCotisar = ListasCotizar::findOrFail($cotisacion->lista_cotizar_id);
            $ListaCotisar->update([
                'estado' => 5, // 1 = Liquidada
            ]);
            $proyecto = Proyecto::findOrFail($ListaCotisar->proyecto_id);
            $proyecto->update([
                'proceso' => 4, // 1 = Liquidada
            ]);
        }
    }

    public function Abonar4cantidad($cantidad)
    {
        $cantidad2 = round(floatval($cantidad), 2);
        $montoActual = round(floatval($this->ordenVentaSelecionada->montoPagar), 2);
        $nuevoMonto = round($montoActual - $cantidad2, 2);

       
        if (abs($nuevoMonto) < 0.01) {
            $nuevoMonto = 0.00;
        }
        $archivoSubido = $this->archivoSubido ? $this->archivoSubido->store('archivosFacturacionProveedores', 'public') : null;
        $this->datosParaPagra[] = [
            'monto' => number_format($cantidad2, 2, '.', ''), // genera "1.30" como string
            'Archivo' => $archivoSubido,
        ];

        $this->ordenVentaSelecionada->update([
            'montoPagar' => $nuevoMonto,
            'estado' => 0, // 0 = Pendiente
            'historial' => json_encode($this->datosParaPagra),
        ]);
    }
}
