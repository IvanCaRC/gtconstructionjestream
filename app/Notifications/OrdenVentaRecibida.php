<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrdenVentaRecibida extends Notification
{
    use Queueable;

    public $proyectoNombre;

    public function __construct($proyectoNombre)
    {
        $this->proyectoNombre = $proyectoNombre;
    }

    public function via($notifiable)
    {
        return ['database']; // Guardar en la base de datos
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $url = config('app.url') . route('finanzas.ordenesVenta.vistaOrdenVentaFin', [], false);

        return [
            'type' => 'orden_venta_recibida',
            'message' => 'Se ha generado la orden de venta correspondiente para el proyecto: "' . $this->proyectoNombre . '".',
            'url' => $url,
        ];
    }
}
