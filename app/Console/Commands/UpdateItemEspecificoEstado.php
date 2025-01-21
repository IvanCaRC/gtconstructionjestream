<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ItemEspecifico;
use App\Models\User; // Importar el modelo User
use App\Notifications\ItemEspecificoEstadoCambiado; // Importar la notificación

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
        ItemEspecifico::where('estado', true)->update(['estado' => false]);

        // Enviar notificación
        $user = User::first(); // O cualquier usuario al que quieras notificar
        $user->notify(new ItemEspecificoEstadoCambiado());

        $this->info('El estado items se ha desactualizado.');
    }
}

