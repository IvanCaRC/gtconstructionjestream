<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class SolicitudCotizacionNotification extends Notification
{
    use Queueable;

    protected $idProyecto;
    protected $nombreProyecto;

    public function __construct($idProyecto, $nombreProyecto)
    {
        $this->idProyecto = $idProyecto;
        $this->nombreProyecto = $nombreProyecto;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $url = config('app.url') . route('compras.cotisaciones.verCotisaciones', [], false);
        Log::info('Generando URL para SolicitudCancelacion: ' . $url);

        return [
            'type' => 'solicitudCotizacion_notificacion',
            'message' => 'El departamento de Ventas ha solicitado una cotización para el proyecto "' . $this->nombreProyecto . '".',
            'url' => $url,
        ];
    }
}
