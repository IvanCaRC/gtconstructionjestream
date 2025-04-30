<?php

namespace App\Livewire\Ventas\RecepsionCotizacio;

use App\Models\Cotizacion;
use App\Models\Proyecto;
use Livewire\Component;
use Livewire\WithPagination;

use Illuminate\Support\Facades\Auth;

class Recepcioncotiosacion extends Component
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
        $cotizacion = Cotizacion::find($id);
        if ($cotizacion) {
            $cotizacion->estado = 2; // Estado "Cancelada"
            $cotizacion->save();
            if (Auth::user()->cotizaciones == $id) {
                Auth::user()->update(['cotizaciones' => null]);
            }
        }
    }

    public function render()
    {


        if (Auth::user()->hasRole('Administrador')) {
            $query = Cotizacion::with(['proyecto', 'proyecto.cliente'])
                ->orderBy('created_at', 'desc')
                ->where('estado', 1);
        } else {
            $usuarioId = Auth::id();
        
            // Obtener cotizaciones donde el cliente del proyecto fue creado por el usuario actual
            $query = Cotizacion::with(['proyecto', 'proyecto.cliente'])
                ->whereHas('proyecto.cliente', function($q) use ($usuarioId) {
                    $q->where('user_id', $usuarioId);
                })
                ->orderBy('created_at', 'desc')
                ->where('estado', 1);
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

        return view('livewire.ventas.recepsion-cotizacio.recepcioncotiosacion', compact('listasCotizar'));
    }


    public function viewProyecto($idProyecto)
    {
        $proyecto = Proyecto::find($idProyecto);

        if ($proyecto === null) {
            abort(404, 'proyecto no encontrado');
        }

        return redirect()->route('ventas.clientes.vistaEspecProyecto', ['idProyecto' => $idProyecto]);
    }
}
