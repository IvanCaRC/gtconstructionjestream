<?php
//Ya se hicieron priebas
namespace App\Livewire\Cliente;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class RecepcionLlamada extends Component
{

    public $nombre, $correo, $rfc;
    public $telefonos = [['nombre' => '', 'numero' => '']];
    public $bancarios = [['banco' => '', 'titular' => '', 'cuenta' => '', 'clave' => '']];
    public $proyectos = 0;
    public $proyectosActivos = 0;
    public $rfcDuplicado = false;
    public $clienteDuplicadoId = null;
    public $clienteUsuarioId = null;
    public $selectedUser;

    public function verificarClienteDuplicado()
    {
        $cliente = Cliente::where('rfc', $this->rfc)->first();

        if ($cliente) {
            $this->clienteDuplicadoId = $cliente->id;
            $this->clienteUsuarioId = $cliente->user_id; // 🔹 Guardamos el propietario del cliente
        } else {
            $this->clienteDuplicadoId = null;
            $this->clienteUsuarioId = null; // Reiniciamos el valor si no hay duplicado
        }
    }

    public function mount()
    {
        // Asigna el usuario autenticado por defecto
        $this->selectedUser = Auth::user()->id;
    }

    public function getUsuariosVentas()
    {
        $usuariosVentas = User::whereHas('roles', function ($query) {
            $query->where('name', 'Ventas');
        })->select('id', 'name', 'first_last_name', 'second_last_name')->get(); // Asegúrate de incluir 'id'

        // Agregar el usuario autenticado a la lista
        $usuariosVentas->push([
            'id' => Auth::user()->id, // Agrega el ID del usuario autenticado
            'name' => Auth::user()->name,
            'first_last_name' => Auth::user()->first_last_name,
            'second_last_name' => Auth::user()->second_last_name
        ]);

        return $usuariosVentas;
    }

    public function render()
    {
        return view('livewire.cliente.recepcion-llamada');
    }

    public function addTelefono()
    {
        $this->telefonos[] = ['nombre' => '', 'numero' => ''];
    }

    public function removeTelefono($index)
    {
        unset($this->telefonos[$index]);
        $this->telefonos = array_values($this->telefonos); // Reindexar el array
    }

    public function addBancarios()
    {
        $this->bancarios[] = ['banco' => '', 'titular' => '', 'cuenta' => '', 'clave' => ''];
    }

    public function removeBancarios($index)
    {
        unset($this->bancarios[$index]);
        $this->bancarios = array_values($this->bancarios); // Reindexar el array
    }

    //Funcion de validacion en tiempo real
    public function updated($propertyName)
    {
        //Implementar mensajes personalizados
        $this->validateOnly($propertyName, Cliente::rules(), Cliente::messages());
    }

    public function validateField($field)
    {
        try {
            $this->validateOnly($field, Cliente::rules());

            // Si no hay errores, restablecemos
            if ($field === 'rfc') {
                $this->rfcDuplicado = false;
                $this->clienteDuplicadoId = null;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($field === 'rfc' && isset($e->validator->failed()['rfc']['Unique'])) {
                $this->rfcDuplicado = true;
                $this->verificarClienteDuplicado(); // Recuperar ID del cliente duplicado
            }
            throw $e;
        }
    }

    public function viewCliente($id)
    {
        $cliente = Cliente::find($id);

        if ($cliente && ($cliente->user_id === Auth::id() || Auth::user()->hasRole('Administrador'))) {
            return redirect()->route('ventas.clientes.vistaEspecificaCliente', ['idCliente' => $id]);
        } else {
            $this->dispatch('mostrarAlertaSeguridad'); // 🔹 Dispara evento para mostrar la alerta en la vista
        }
    }

    //Mandar a llamar las reglas del modelo de manera local
    protected function rules()
    {
        return Cliente::rules();
    }

    protected function messages()
    {
        return Cliente::messages();
    }

    public function save()
    {
        //Reglas de validacion
        $this->validate([
            'selectedUser' => 'required|exists:users,id', // Se valida que el usuario seleccionado exista
        ] + Cliente::rules(), Cliente::messages());
        // $this->validate(Cliente::rules(), Cliente::messages());
        //Obtener el usuario autenticado
        $user = Auth::user();
        //Determinar el id de usuario a asignar en funcion de su rol
        $idUser = $user->hasRole('Administrador') ? $this->selectedUser : $user->id;

        $cliente = Cliente::create([
            'nombre' => $this->nombre,
            'correo' => $this->correo,
            'rfc' => $this->rfc,
            'bancarios' => json_encode($this->bancarios), // Convertir a JSON
            'proyectos' => $this->proyectos,
            'proyectos_activos' => $this->proyectosActivos,
            'telefono' => json_encode($this->telefonos), // Convertir a JSON
            'user_id' => $idUser,
            'fecha' => now(),
        ]);



        $this->reset('nombre', 'correo', 'rfc', 'bancarios', 'proyectos', 'telefonos', 'proyectosActivos');


        return ['cliente_id' => $cliente->id];
    }
}
