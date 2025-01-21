<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ItemEspecifico;

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

        $this->info('El estado de los items específicos se ha actualizado a false');
    }
}

