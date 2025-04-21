<?php

namespace App\Livewire\Cotisaciones;

use App\Models\Cotizacion;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ListasCotizar;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VerCotisaciones extends Component
{
    use WithPagination;


    public $searchTerm = ''; // Término de búsqueda
    public $statusFiltro = 0; // Filtro de preferencia: 0 = Todos, 1 = Precio, 2 = Tiempo de entrega

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
        // Obtener las listas a cotizar con estado igual a 3
        $query = ListasCotizar::where('estado', 2)
            ->whereNull('id_usuario_compras')
            ->with('proyecto') // Cargar la relación con el proyecto
            ->orderBy('created_at', 'desc'); // Ordenar por fecha de creación descendente

        // Aplicar búsqueda por nombre o fecha de creación
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

        return view('livewire.cotisaciones.ver-cotisaciones', compact('listasCotizar'));
    }

    public $openModalAsignarUsuario = false;
    public $selecionarOtroUsuario = false;

    public $idListaSelecionada;
    
    public function seleccionar($id)
    {
        $this->idListaSelecionada = $id;
        if (Auth::user()->hasRole('Administrador')) {
            
            $this->openModalAsignarUsuario = true;

            // $this->obtenerUsuarios();
        } else {
            $this->seleccionarususarioactual($id,Auth::id());
        }
    }

    public function selecionactivadeotrousuario(){
        $this->selecionarOtroUsuario = true;
    }

    
    public $searchTearmUsuario = '';
    public $usuariosAsignables = [];
    public $usuarioSeleccionadoId = null;

    

    public function cancelarAsignacion()
    {
        $this->reset(['selecionarOtroUsuario','idListaSelecionada','openModalAsignarUsuario', 'searchTearmUsuario', 'usuariosAsignables', 'usuarioSeleccionadoId', 'selecionarOtroUsuario']);
    }

    public function obtenerUsuarios()
    {
        if ($this->searchTearmUsuario) {
            $this->usuariosAsignables = User::where(function ($query) {
                $query->where('name', 'LIKE', '%' . $this->searchTearmUsuario . '%')
                    ->orWhere('first_last_name', 'LIKE', '%' . $this->searchTearmUsuario . '%')
                    ->orWhere('second_last_name', 'LIKE', '%' . $this->searchTearmUsuario . '%')
                    ->orWhere(DB::raw("CONCAT(first_last_name, ' ', second_last_name, ' ', name)"), 'LIKE', '%' . $this->searchTearmUsuario . '%')
                    ->orWhere('email', 'LIKE', '%' . $this->searchTearmUsuario . '%')
                    ->orWhere('number', 'LIKE', '%' . $this->searchTearmUsuario . '%');
            })->get();
        } else {
            $this->usuariosAsignables = [];
        }
    }

    public function seleccionarususarioactual($idUsuario)
    {
        // Obtener la lista de cotización por su ID
        $lista = ListasCotizar::findOrFail($this->idListaSelecionada);
        $usuarioVentas = $lista->usuario_id;

        // Asignar el ID del usuario autenticado al campo id_usuario_compras
        $lista->id_usuario_compras = $idUsuario;

        // Transformar y añadir el campo 'estado' en 'items_cotizar'
        $itemsCotizar = json_decode($lista->items_cotizar, true);
        if ($itemsCotizar) {
            foreach ($itemsCotizar as &$item) {
                $item['estado'] = 0; // Estado por defecto
            }
        }
        $lista->items_cotizar = json_encode($itemsCotizar);

        // Transformar y añadir el campo 'estado' en 'items_cotizar_temporales'
        $itemsCotizarTemporales = json_decode($lista->items_cotizar_temporales, true);
        if ($itemsCotizarTemporales) {
            foreach ($itemsCotizarTemporales as &$itemTemp) {
                $itemTemp['estado'] = 0; // Estado por defecto
            }
        }
        $lista->items_cotizar_temporales = json_encode($itemsCotizarTemporales);

        // Guardar los cambios en la base de datos
        $lista->save();

        // Crear la cotización basada en la lista a cotizar
        $cotizacion = Cotizacion::create([
            'lista_cotizar_id' => $lista->id,
            'proyecto_id' => $lista->proyecto_id,
            'usuario_id' => $usuarioVentas,
            'id_usuario_compras' => $idUsuario,
            'nombre' => $lista->nombre,
            'estado' => 0, // Estado inicial de la cotización
        ]);
        $this->cancelarAsignacion();
        return redirect()->route('compras.cotisaciones.verMisCotisaciones');

        // Emitir un evento o redirigir según sea necesario
        // $this->emit('listaSeleccionada', $lista->id);
    }
}
