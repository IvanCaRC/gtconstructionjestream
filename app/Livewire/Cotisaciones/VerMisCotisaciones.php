<?php

namespace App\Livewire\Cotisaciones;

use App\Models\Cotizacion;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ListasCotizar;

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

            // Resto del código...


            // Aplicar búsqueda por nombre o fecha de creación

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


    public function seleccionar($id)
    {
        // Obtener la lista de cotización por su ID
        $lista = ListasCotizar::findOrFail($id);

        // Asignar el ID del usuario autenticado al campo id_usuario_compras
        $lista->id_usuario_compras = Auth::id();

        // Guardar los cambios en la base de datos
        $lista->save();

        // Emitir un evento o redirigir según sea necesario
        // Por ejemplo, para actualizar otra parte de la interfaz:
        // $this->emit('listaSeleccionada', $lista->id);
    }
    public function verDetalles($id)
    {
        // Redirigir a la ruta especificada con el ID de la cotización
        return redirect()->route('compras.cotisaciones.verCarritoCotisaciones', ['idCotisacion' => $id]);
    }


    public function activar($id)
    {
        $cotizacion = Cotizacion::find($id);
        if ($cotizacion) {
            $cotizacion->estado = 1; // Estado "Activa"
            $cotizacion->save();
        }
    }

    public function desactivar($id)
    {
        $cotizacion = Cotizacion::find($id);
        if ($cotizacion) {
            $cotizacion->estado = 0; // Estado "Inactiva"
            $cotizacion->save();
        }
    }

    public function cancelar($id)
    {
        $cotizacion = Cotizacion::find($id);
        if ($cotizacion) {
            $cotizacion->estado = 3; // Estado "Cancelada"
            $cotizacion->save();
        }
    }
}
