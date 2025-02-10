<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proveedor;
use App\Models\User;
use App\Notifications\ProveedorEstadoCambiado;

class UpdateProveedorEstado extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proveedor:update-estado';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Desactualizar el estado de un proveedor';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Obtener la lista de proveedores desactualizados
        $proveedores = Proveedor::where('estado', true)->get();
        $this->info('Número de proveedores a desactualizar: ' . $proveedores->count()); // Mensaje de depuración

        foreach ($proveedores as $proveedor) {
            $proveedor->update(['estado' => false]);
            $this->info('Proveedor desactualizado: ' . $proveedor->nombre); // Mensaje de depuración

            // Enviar notificación a los usuarios con roles 'Administrador' y 'Compras'
            $users = User::role(['Administrador', 'Compras'])->get();
            $this->info('Número de usuarios a notificar: ' . $users->count()); // Mensaje de depuración
            foreach ($users as $user) {
                $user->notify(new ProveedorEstadoCambiado($proveedor->id, $proveedor->nombre));
                $this->info('Notificación enviada al usuario: ' . $user->id . ' para el proveedor: ' . $proveedor->nombre);
            }
        }

        $this->info('Estado de proveedores desactualizado');
    }
}

