<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ItemEspecificoEstadoCambiado extends Notification
{
    use Queueable;

    protected $nombreItem;
    protected $idItem;

    public function __construct($idItem, $nombreItem)
    {
        $this->idItem = $idItem;
        $this->nombreItem = $nombreItem;
    }

    public function via($notifiable)
    {
        return ['database']; // Usamos 'database' para almacenar la notificación
    }

    public function toArray($notifiable)
    {
        // Generar la URL manualmente con 127.0.0.1
        $url = config('app.url') . route('compras.items.vistaEspecificaItem', ['idItem' => $this->idItem], false);
        Log::info('Generando URL para ItemEspecificoEstadoCambiado: ' . $url);

        return [
            'message' => 'El ítem: "' . $this->nombreItem . '" se encuentra desactualizado',
            'url' => $url
        ];
    }
}



