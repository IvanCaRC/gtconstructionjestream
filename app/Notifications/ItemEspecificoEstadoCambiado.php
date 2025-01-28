<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ItemEspecificoEstadoCambiado extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['database']; // Usamos 'database' para almacenar la notificación
    }

    public function toArray($notifiable)
    {
        // Generar la URL manualmente con 127.0.0.1
        $url = 'http://127.0.0.1:8000/compras/items/viewItems';
        Log::info('Generando URL para ItemEspecificoEstadoCambiado: ' . $url);

        return [
            'message' => 'El estado de un item se encuentra desactualizado',
            'url' => $url
        ];
    }
}


