<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Rules\ValidaRFC; // Regla de validacion personalizada
use App\Rules\ValidaClaveBancaria; // Regla de validacion personalizada
use App\Rules\ValidaCuentaBancaria; // Regla de validacion personalizada
use App\Rules\ValidaNombreBancario; // Regla de validacion personalizada
use SebastianBergmann\Type\NullType;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes'; // Nombre de la tabla

    protected $fillable = [
        'nombre',
        'correo',
        'rfc',
        'bancarios',
        'proyectos',
        'proyectos_activos',
        'telefono',
        'fecha',
        'user_id',
    ];

    // Definir relación con el modelo User

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'cliente_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Definir relación con el modelo Direccion
    public function direcciones()
    {
        return $this->hasMany(Direccion::class);
    }

    //Reglas de validacion
    public static function rules($prefix = '', $id = null)
    {
        return [
            $prefix . 'nombre' => 'required|string|max:255',
            $prefix . 'correo' => 'required|email|unique:clientes,correo,' . $id,
            $prefix . 'rfc' => ['required', 'string', 'unique:clientes,rfc,' . $id, new ValidaRfc],
            $prefix . 'bancarios.*.titular' => [new ValidaNombreBancario],
            $prefix . 'bancarios.*.cuenta' => [new ValidaCuentaBancaria],
            $prefix . 'bancarios.*.clave' => [new ValidaClaveBancaria],
        ];
    }

    public static function messages($prefix = '')
    {
        return [
            $prefix . 'nombre.required' => 'Para registrar un cliente es obligatorio el nombre.',
            $prefix . 'nombre.string' => 'El nombre de cliente no es valido.',
            $prefix . 'nombre.max' => 'Este nombre es demasiado largo.',

            $prefix . 'correo.required' => 'Registrar un correo electronico es obligatorio.',
            $prefix . 'correo.email' => 'La direccion de correo registrada no es valida.',
            $prefix . 'correo.unique' => 'Este correo ya esta registrado en un cliente actual.',

            $prefix . 'rfc.required' => 'Registrar el RFC es obligatorio.',
            $prefix . 'rfc.string' => 'El campo RFC debe ser una cadena de texto.',
            $prefix . 'rfc.unique' => 'Este RFC ya se encuentra registrado con un cliente actual.',
            $prefix . 'rfc.valid' => 'El RFC registrado no es valido.',

            $prefix . 'telefono.required' => 'El campo teléfono es obligatorio.',
            $prefix . 'telefono.numeric' => 'El campo teléfono debe ser un número.',
            $prefix . 'telefono.regex' => 'El campo teléfono debe tener exactamente 12 dígitos.',
   
            $prefix . 'bancarios.*.titular.valid' => 'Asegurate de introducir correctamente el nombre del titular', 
            $prefix . 'bancarios.*.cuenta.valid' => 'El numero de cuenta registrado no es valido.',
            $prefix . 'bancarios.*.clave.valid' => 'La clave registrada no es valida.',
        ];
    }

    public static function rulesUpdate($prefix = '', $id)
    {
        return [
            $prefix . 'clienteEdit.nombre' => 'required|string|max:255',
            $prefix . 'clienteEdit.correo' => 'required|email|unique:clientes,correo,' . $id,
            $prefix . 'clienteEdit.rfc' => ['required', 'string', 'unique:clientes,rfc,' . $id, new ValidaRfc],
            $prefix . 'bancarios.*.titular' => [new ValidaNombreBancario],
            $prefix . 'bancarios.*.cuenta' => [new ValidaCuentaBancaria],
            $prefix . 'bancarios.*.clave' => [new ValidaClaveBancaria],
        ];
    }

    public static function messagesUpdate($prefix = '')
    {
        return [
            $prefix . 'clienteEdit.nombre.required' => 'Para actualizar los datos del cliente es requerido un nombre.',
            $prefix . 'clienteEdit.nombre.string' => 'El nombre de cliente no es valido.',
            $prefix . 'clienteEdit.nombre.max' => 'Este nombre es demasiado largo.',

            $prefix . 'clienteEdit.correo.required' => 'Registrar un correo electronico es obligatorio.',
            $prefix . 'clienteEdit.correo.email' => 'La direccion de correo registrada no es valida.',
            $prefix . 'clienteEdit.correo.unique' => 'Este correo ya esta registrado en un cliente actual.',

            $prefix . 'clienteEdit.rfc.required' => 'Registrar el RFC es obligatorio.',
            $prefix . 'clienteEdit.rfc.string' => 'El campo RFC debe ser una cadena de texto.',
            $prefix . 'clienteEdit.rfc.unique' => 'Este RFC ya se encuentra registrado con un cliente actual.',
            $prefix . 'clienteEdit.rfc.valid' => 'El RFC registrado no es valido.',

            $prefix . 'clienteEdit.telefono.required' => 'El campo teléfono es obligatorio.',
            $prefix . 'clienteEdit.telefono.numeric' => 'El campo teléfono debe ser un número.',
            $prefix . 'clienteEdit.telefono.regex' => 'El campo teléfono debe tener exactamente 12 dígitos.',

            $prefix . 'bancarios.*.titular.valid' => 'Asegurate de introducir correctamente el nombre del titular', 
            $prefix . 'bancarios.*.cuenta.valid' => 'El numero de cuenta registrado no es valido.',
            $prefix . 'bancarios.*.clave.valid' => 'La clave registrada no es valida.',
        ];
    }
}
