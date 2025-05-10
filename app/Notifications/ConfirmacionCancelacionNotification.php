<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ConfirmacionCancelacionNotification extends Notification
{
    use Queueable;

    protected $idProyecto;
    protected $nombreProyecto;
    protected $idCliente;

    public function __construct($idProyecto, $nombreProyecto, $idCliente)
    {
        $this->idProyecto = $idProyecto;
        $this->nombreProyecto = $nombreProyecto;
        $this->idCliente = $idCliente;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $url = config('app.url') . route('ventas.clientes.vistaEspecificaCliente', ['idCliente' => $this->idCliente], false);
        Log::info('Generando URL para ConfirmacionCancelacionNotification: ' . $url);

        return [
            'type' => 'confirmacion_cancelacion',
            'message' => 'Tu solicitud de cancelación para el proyecto "' . $this->nombreProyecto . '" ha sido aceptada.',
            'url' => $url,
        ];
    }
}
