<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

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
        return [
            'message' => 'Un item se encuentra desactualizado',
            'url' => url('/'), // Puedes agregar más detalles si es necesario
        ];
    }
}

