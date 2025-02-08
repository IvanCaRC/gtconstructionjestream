<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ProveedorEstadoCambiado extends Notification
{
    use Queueable;

    protected $nombreProveedor;

    public function __construct($nombreProveedor)
    {
        $this->nombreProveedor = $nombreProveedor;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        // Generar la URL manualmente con 127.0.0.1
        $url = 'http://127.0.0.1:8000/compras/proveedores/viewProveedores';
        Log::info('Generando URL para ProveedorEstadoCambiado: ' . $url);

        return [
            'message' => 'El proveedor "' . $this->nombreProveedor . '" se encuentra desactualizado',
            'url' => $url
        ];
    }
}



