<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ProveedorEstadoCambiado extends Notification
{
    use Queueable;

    protected $nombreProveedor;
    protected $idProveedor;

    public function __construct($idProveedor, $nombreProveedor)
    {
        $this->idProveedor = $idProveedor;
        $this->nombreProveedor = $nombreProveedor;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        // Usar la ruta definida en web.php
        $url = config('app.url') . route('compras.proveedores.viewProveedorEspecifico', ['idproveedor' => $this->idProveedor], false);
        Log::info('Generando URL para ProveedorEstadoCambiado: ' . $url);

        return [
            'type' => 'proveedor_desactualizado',
            'message' => 'El proveedor "' . $this->nombreProveedor . '" se encuentra desactualizado.',
            'url' => $url
        ];
    }
}



