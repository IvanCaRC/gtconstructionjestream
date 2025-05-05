<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CotizacionEnviadaNotification extends Notification
{
    use Queueable;

    protected $nombreProyecto;
    protected $nombreLista;

    public function __construct($nombreProyecto, $nombreLista)
    {
        $this->nombreProyecto = $nombreProyecto;
        $this->nombreLista = $nombreLista;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $url = config('app.url') . route('ventas.recepcionCotizaciones.recepcionCotizacion', [], false);
        return [
            'type' => 'cotizacion_enviada',
            'message' => "Has recibido respuesta para tu solicitud de: '" . ($this->nombreLista ?? 'Lista desconocida') . "' correspondiente al proyecto: '" . ($this->nombreProyecto ?? 'Proyecto desconocido') . "'.",
            'url' => $url,
        ];
    }
}
