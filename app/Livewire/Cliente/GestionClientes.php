<?php

namespace App\Livewire\Cliente;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cliente; // Asegúrate de importar el modelo Cliente
use Illuminate\Support\Facades\Auth; // Para obtener el usuario autenticado
use Spatie\Permission\Traits\HasRoles; // Para verificar roles

class GestionClientes extends Component
{
    use WithPagination;

    public $searchTerm = ''; // Para la búsqueda
    public $statusFiltroDeBusqueda = 2; // Para el filtro de estado


    public function search()
    {
        $this->resetPage();
    }

    public function filter()
    {
        $this->resetPage();  // Asegura que la paginación se restablezca
    }
    // Método para obtener los clientes según el rol del usuario
    public function getClientesProperty()
    {
        $query = Cliente::query();

        // Si el usuario no es administrador, filtrar por su ID
        if (!Auth::user()->hasRole('Administrador')) {  
            $query->where('user_id', Auth::id());
        }

        // Aplicar búsqueda
        $query->when($this->searchTerm, function ($query) {
            $query->where('nombre', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('correo', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('rfc', 'like', '%' . $this->searchTerm . '%');
        });

        // Aplicar filtro de estado
        $query->when($this->statusFiltroDeBusqueda != 2, function ($query) {
            if ($this->statusFiltroDeBusqueda == 1) {
                $query->where('proyectos_activos', '>', 0); // Proyectos activos mayores a 0
            } elseif ($this->statusFiltroDeBusqueda == 0) {
                $query->where('proyectos_activos', 0); // Proyectos activos iguales a 0
            }
        });

        return $query->paginate(10); // Paginación
    }

    public function render()
    {
        return view('livewire.cliente.gestion-clientes', [
            'clientes' => $this->clientes, // Pasamos los clientes a la vista
        ]);
    }

    public function viewCliente($idCliente)
    {
        $cliente = Cliente::find($idCliente);

        if ($cliente === null) {
            abort(404, 'proveedor no encontrado');
        }

        return redirect()->route('ventas.clientes.vistaEspecificaCliente', ['idCliente' => $idCliente]);
    }
}