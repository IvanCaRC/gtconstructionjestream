<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CotizacionEnviada extends Notification
{
    use Queueable;

    public $proyectoNombre;
    public $listaNombre;

    public function __construct($proyectoNombre, $listaNombre)
    {
        $this->proyectoNombre = $proyectoNombre;
        $this->listaNombre = $listaNombre;
    }

    public function via($notifiable)
    {
        return ['database']; // Guardar en la base de datos
    }

    public function toArray($notifiable)
    {
        $url = config('app.url') . route('ventas.recepcionCotizaciones.recepcionCotizacion', [], false);

        return [
            'type' => 'cotizacion_enviada',
            'message' => 'Haz recibido la cotizacion de la lista: "' . $this->listaNombre . '" correspondiente al proyecto: "' . $this->proyectoNombre . '".',
            'url' => $url,
        ];
    }
}
