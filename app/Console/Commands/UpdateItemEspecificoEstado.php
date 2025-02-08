<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ItemEspecifico;
use App\Models\User;
use App\Notifications\ItemEspecificoEstadoCambiado;

class UpdateItemEspecificoEstado extends Command
{
    protected $signature = 'itemespecifico:update-estado';
    protected $description = 'Actualizar el estado de los items específicos a false';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Obtener la lista de ítems específicos desactualizados
        $itemsEspecificos = ItemEspecifico::where('estado', true)->get();
        $this->info('Número de ítems a desactualizar: ' . $itemsEspecificos->count()); // Mensaje de depuración

        foreach ($itemsEspecificos as $itemEspecifico) {
            $itemEspecifico->update(['estado' => false]);
            $this->info('Ítem desactualizado: ' . $itemEspecifico->item->nombre); // Mensaje de depuración

            // Enviar notificación a los usuarios con roles 'Administrador' y 'Compras'
            $users = User::role(['Administrador', 'Compras'])->get();
            $this->info('Número de usuarios a notificar: ' . $users->count()); // Mensaje de depuración
            foreach ($users as $user) {
                $user->notify(new ItemEspecificoEstadoCambiado($itemEspecifico->item->nombre)); // Usar la relación para obtener el nombre del ítem
                $this->info('Notificación enviada al usuario: ' . $user->id . ' para el ítem: ' . $itemEspecifico->item->nombre);
            }
        }

        $this->info('Estado de ítems desactualizado');
    }
}


