<?php

namespace App\Livewire\Cliente;

use App\Models\Cliente;
use Livewire\Component;
use App\Models\User;

class EditCliente extends Component
{
    public $idDecliente;

    public $telefonos = [['nombre' => '', 'numero' => '']];
    public $bancarios = [['banco' => '', 'titular' => '', 'cuenta' => '', 'clave' => '']];
    public $clienteActual;
    public $nombreClienteEdit;
    public $rfcDuplicado = false;
    public $clienteDuplicadoId = null;
    public $clienteEdit = [
        'id' => '', // Identificador del cliente
        'nombre' => '', // Nombre del cliente
        'correo' => '', // Correo del cliente
        'rfc' => '', // RFC del cliente
        'bancarios' => '', // Datos bancarios del cliente
        'proyectos' => '', // Proyectos registrados del cliente
        'proyectos_activos' => '', //Total de proyectos activos del cliente
        'user_id' => '', //Usuario asociado al cliente
    ];
    public $selectedUser;
    public $userRole; //Variable local para poder usar roles del sistema

    public function mount($idcliente)
    {
        $this->clienteActual = Cliente::findOrFail($idcliente); //Obtener el cliente mediante su id
        $this->nombreClienteEdit = $this->clienteActual->nombre;
        //Asignacion de propiedades
        $this->clienteEdit['id'] = $this->clienteActual->id;
        $this->clienteEdit['nombre'] = $this->clienteActual->nombre;
        $this->clienteEdit['correo'] = $this->clienteActual->correo;
        $this->clienteEdit['rfc'] = $this->clienteActual->rfc;

        //Resguardar el usuario asignado actualmente al cliente
        $this->selectedUser = $this->clienteActual->user_id;

        // Obtener el rol del usuario desde la sesion en lugar del metodo Auth:user()
        $this->userRole = session('user_role');

        // Cargar los teléfonos del cliente en el formato correcto
        $telefonosGuardados = json_decode($this->clienteActual->telefono, true);
        // Verificar si existen teléfonos en la base de datos y asignarlos
        if (!empty($telefonosGuardados)) {
            $this->telefonos = $telefonosGuardados;
        }

        //Cargas los datos bancarios del cliente segun el formato correcto
        $bancariosGuardados = json_decode($this->clienteActual->bancarios, true);
        //Verificar si existen datos bancarios en la base de datos y asignarlos
        if (!empty($bancariosGuardados)) {
            $this->bancarios = $bancariosGuardados;
        }

        $this->idDecliente = $idcliente;
    }

    public function getUsuariosVentas()
    {
        return User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Ventas', 'Administrador']);
        })->select('id', 'name', 'first_last_name', 'second_last_name')->get();
    }

    public function render()
    {
        return view('livewire.cliente.edit-cliente');
    }

    //Funciones para validacion en tiempo real
    public function updated($propertyName)
    {
        //Implementar mensajes personalizados
        $this->validateOnly($propertyName, Cliente::rulesUpdate('', $this->idDecliente), Cliente::messagesUpdate());
    }

    public function validateField($field)
    {
        $this->validateOnly($field);
    }

    //Mandar a llamar las reglas del modelo de manera local
    protected function rules()
    {
        return Cliente::rulesUpdate('', $this->idDecliente);
    }
    //Llamar los mensajes personalizados de manera local
    protected function messages()
    {
        return Cliente::messagesUpdate('');
    }

    public function updateCliente()
{
    // Obtener datos actuales del cliente
    $clienteActual = Cliente::findOrFail($this->idDecliente);

    // Validaciones de actualización
    $this->validate([
        'selectedUser' => 'required|exists:users,id', // Asegura que el usuario asignado sea válido
    ] + Cliente::rulesUpdate('', $this->idDecliente), Cliente::messagesUpdate(''));

    // Determinar qué ID de usuario asignar
    $idUser = $this->selectedUser;

    // Actualizar los datos del cliente, incluyendo el usuario asignado
    $clienteActual->update([
        'nombre' => $this->clienteEdit['nombre'],
        'correo' => $this->clienteEdit['correo'],
        'rfc' => $this->clienteEdit['rfc'],
        'user_id' => $idUser, // Se actualiza el usuario asignado correctamente
    ]);

    return ['cliente_id' => $clienteActual->id];
}
}
