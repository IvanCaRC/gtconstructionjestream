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
use Livewire\WithPagination;

class OrdenVentaVista extends Component
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

    public $esVistaFinanzas = false;

    public function mount()
    {
        $this->esVistaFinanzas = \Route::currentRouteName() === 'finanzas.ordenesVenta.vistaOrdenVentaFin';
    }


    /**
     * Redirige a editar la lista de cotización
     * @param int $id ID de la cotización
     * @return \Illuminate\Http\RedirectResponse
     */


    /**
     * Cancela una cotización
     * @param int $id ID de la cotización
     */
    public function cancelar($id)
    {
        $cotizacion = Cotizacion::find($id);
        if ($cotizacion) {
            $cotizacion->estado = 2; // Estado "Cancelada"
            $cotizacion->save();

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
        // if (!empty($this->searchTerm)) {
        //     $query->where(function ($q) {
        //         $q->where('nombre', 'like', '%' . $this->searchTerm . '%')
        //             ->orWhereDate('created_at', 'like', '%' . $this->searchTerm . '%');
        //     });
        // }

        // Aplicar filtro de preferencia
        // if ($this->statusFiltro != 0) {
        //     $query->whereHas('proyecto', function ($q) {
        //         $q->where('preferencia', $this->statusFiltro);
        //     });
        // }

        $ordenesVenta = $query->paginate(10);

        return view('livewire.ventas.ordene-venta.orden-venta-vista', compact('ordenesVenta'));
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

        return OrdenVenta::query()
            ->where('id_usuario', $usuarioId) // Filtrar por usuario
            ->orderBy('created_at', 'desc')
            ->whereIn('estado', [1, 2]);
    }

    public $openModalPagar = false;
    public $ordenVentaSelecionada;
    public $cantidadPagar = 0;
    public $montoPagar = 0;

    public function abrirModalPagar($ordenVentaId)
    {
        $this->ordenVentaSelecionada = OrdenVenta::findOrFail($ordenVentaId);
        $this->montoPagar = $this->ordenVentaSelecionada->montoPagar;
        $this->openModalPagar = true;
    }

    public function cerrarModal()
    {
        $this->reset(['openModalPagar', 'ordenVentaSelecionada', 'cantidadPagar','montoPagar']);
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
                $this->liquidar();
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
            $this->reset(['openModalPagar', 'ordenVentaSelecionada', 'cantidadPagar','montoPagar']);
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('mostrarAlerta', [
                'icono' => 'error',
                'titulo' => 'Error',
                'texto' => 'Ocurrió un error: ' . $e->getMessage()
            ]);
            $this->reset(['openModalPagar', 'ordenVentaSelecionada', 'cantidadPagar','montoPagar']);
            return false;
        }
    }

    public function liquidar()
    {
        $this->ordenVentaSelecionada->update([
            'montoPagar' => 0,
            'estado' => 1, // 1 = Liquidada
        ]);

        $cotisacion = Cotizacion::findOrFail($this->ordenVentaSelecionada->id_cotizacion);
        $cotisacion->update([
            'estado' => 5, // 1 = Liquidada
        ]);

    }

    public function Abonar4cantidad($cantidad)
    {
        $nuevoMonto = $this->ordenVentaSelecionada->montoPagar - $cantidad;

        $this->ordenVentaSelecionada->update([
            'montoPagar' => $nuevoMonto,
            'estado' => 0, // 0 = Pendiente
        ]);
    }
}
