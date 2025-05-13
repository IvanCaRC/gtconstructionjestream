<?php

namespace App\Livewire\Cotisaciones\OrdeneCompra;

use App\Http\Controllers\Cotisaciones;
use App\Models\Cotizacion;
use App\Models\ListasCotizar;
use App\Models\ordenCompra;
use App\Models\Proyecto;
use Livewire\Component;

class VistaEspecificaDeOrden extends Component
{

    public $cotisacion;
    public $searchTerm = '';
    public $statusFiltro = 0; // Filtro de estado

    public function mount($idCotisaciones)
    {
        $this->cotisacion = Cotizacion::find($idCotisaciones);

        if (!$this->cotisacion) {
            abort(404, 'Cotización no encontrada');
        }
    }

    public function render()
    {

        $listas = ordenCompra::where('id_cotizacion', $this->cotisacion->id)
            ->when($this->searchTerm, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('estado', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->when($this->statusFiltro != 0, function ($query) {
                $query->where('estado', $this->statusFiltro);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Usar paginate() con el trait WithPagination

        return view('livewire.cotisaciones.ordene-compra.vista-especifica-de-orden', ['listas' => $listas]);
    }

    public function search() {}

    public function cancelarOrdenoOmpra($id)
    {

        $ordenVenta = ordenCompra::findOrFail($id);
        $ordenVenta->update([
            'estado' => 3, // 1 = Liquidada
        ]);

        $this->dispatch('refresh');
    }

    public function regresarGestionClientes()
    {
        return redirect()->route('compras.cotisaciones.verOrdenesCompra');
    }

    public function viewOrden($idProyecto)
    {
        $proyecto = ordenCompra::find($idProyecto);

        if ($proyecto === null) {
            abort(404, 'proyecto no encontrado');
        }

        return redirect()->route('finanzas.verPagos.verPagosOrdenCompra', ['id' => $idProyecto]);
    }
}
