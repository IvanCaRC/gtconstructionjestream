<?php

namespace App\Livewire\Cliente;

use App\Models\Cliente;
use Livewire\Component;

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

    public function mount($idcliente)
    {
        $this->clienteActual = Cliente::findOrFail($idcliente); //Obtener el cliente mediante su id
        $this->nombreClienteEdit = $this->clienteActual->nombre;
        //Asignacion de propiedades
        $this->clienteEdit['id'] = $this->clienteActual->id;
        $this->clienteEdit['nombre'] = $this->clienteActual->nombre;
        $this->clienteEdit['correo'] = $this->clienteActual->correo;
        $this->clienteEdit['rfc'] = $this->clienteActual->rfc;

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

    public function render()
    {
        return view('livewire.cliente.edit-cliente');
    }

    public function updateCliente()
    {
        // Obtener datos actuales del proveedor
        $clienteActual = Cliente::findOrFail($this->idDecliente);

        //Validaciones de actualizacion
        $this->validate(
            Cliente::rulesUpdate('', $this->idDecliente), 
            Cliente::messagesUpdate('')
        );

        // Actualizar campos básicos
        $clienteActual->update([
            'nombre' => $this->clienteEdit['nombre'],
            'correo' => $this->clienteEdit['correo'],
            'rfc' => $this->clienteEdit['rfc'],
        ]);

        // Feedback al usuario
        session()->flash('message', 'Proveedor actualizado exitosamente.');
        return ['cliente_id' => $clienteActual->id];
    }
}
