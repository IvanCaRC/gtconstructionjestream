<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proveedor;

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
        $this->info('Estado de proveedor desactualizado');
    }
}
