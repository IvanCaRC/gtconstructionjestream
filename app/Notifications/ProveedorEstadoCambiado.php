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
        // Usar la ruta definida en web.php
        $url = config('app.url') . route('compras.proveedores.viewProveedores', [], false);
        Log::info('Generando URL para ProveedorEstadoCambiado: ' . $url);

        return [
            'message' => 'El proveedor "' . $this->nombreProveedor . '" se encuentra desactualizado',
            'url' => $url
        ];
    }
}



