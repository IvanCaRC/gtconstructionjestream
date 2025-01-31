<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proveedor;
use App\Models\User; // Importar el modelo User
use App\Notifications\ProveedorEstadoCambiado; // Importar la notificación

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
        Proveedor::where('estado', true)->update(['estado' => false]);

        // Enviar notificación a el usuario administrador y del departamento de compras
        $users = User::role(['Administrador','Compras'])->get();
        foreach ($users as $user) {
            $user->notify(new ProveedorEstadoCambiado());
            $this->info('Notificación enviada al usuario: ' . $user->id);
        }

        $this->info('Estado de proveedor desactualizado');
    }
}
