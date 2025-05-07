<?php

namespace App\Livewire\Cotisaciones;

use App\Models\Cotizacion;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ListasCotizar;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Auth;

class VerMisCotisaciones extends Component
{
    use WithPagination;

    public $searchTerm = ''; // Término de búsqueda
    public $statusFiltro = 0; // Filtro de preferencia: 0 = Todos, 1 = Precio, 2 = Tiempo de entrega
    public $estado;

    // Método que se ejecuta al actualizar 'searchTerm'
    public function search()
    {
        $this->resetPage(); // Reinicia la paginación al realizar una búsqueda
    }

    // Método que se ejecuta al actualizar 'statusFiltroDeBusqueda'
    public function filter()
    {
        $this->resetPage(); // Reinicia la paginación al cambiar el filtro
    }

    public function render()
    {
        if (Auth::user()->hasRole('Administrador')) {
            $query = Cotizacion::with('proyecto')
                ->orderBy('created_at', 'desc')
                ->when($this->estado, function ($q) {
                    $q->where('estado', $this->estado);
                });
        } else {
            $usuarioId = Auth::id();

            // Obtener las listas a cotizar con estado igual a 3
            $query = Cotizacion::where('id_usuario_compras', $usuarioId)
                ->with('proyecto')
                ->orderBy('created_at', 'desc')
                ->when($this->estado, function ($q) {
                    $q->where('estado', $this->estado);
                });

        }

        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->searchTerm . '%')
                    ->orWhereDate('created_at', 'like', '%' . $this->searchTerm . '%');
            });
        }



        // Aplicar filtro de preferencia basado en la relación con 'proyecto'
        if ($this->statusFiltro != 0) {
            $query->whereHas('proyecto', function ($q) {
                $q->where('preferencia', $this->statusFiltro);
            });
        }


        // Paginar los resultados
        $listasCotizar = $query->paginate(10);

        return view('livewire.cotisaciones.ver-mis-cotisaciones', compact('listasCotizar'));
    }


    public function verDetalles($id)
    {
        // Redirigir a la ruta especificada con el ID de la cotización
        $cotizacion = Cotizacion::find($id);
        if ($cotizacion) {
            Auth::user()->update(['cotizaciones' => $id]);
        return redirect()->route('compras.cotisaciones.verCarritoCotisaciones', ['idCotisacion' => $id]);
        }
    }

    public function editarlista($id)
    {
        $cotizacion = Cotizacion::find($id);
        if ($cotizacion) {
            Auth::user()->update(['cotizaciones' => $id]);
            return redirect()->route('compras.catalogoCotisacion.catalogoItem');
        }
        
    }

    public function cancelar($id)
    {
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
}
