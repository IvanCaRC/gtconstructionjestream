<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use App\Models\ListasCotizar;
use App\Models\Proyecto;

class SeleccionListaNotification extends Notification
{
    use Queueable;

    protected $lista;
    protected $nombreProyecto;

    public function __construct(ListasCotizar $lista, $nombreProyecto)
    {
        $this->lista = $lista;
        $this->nombreProyecto = $nombreProyecto;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $url = config('app.url') . route('compras.cotisaciones.verMisCotisaciones', [], false);
        Log::info('Generando URL para SeleccionListaNotification: ' . $url);

        return [
            'type' => 'seleccion_lista',
            'message' => 'Se te ha asignado la lista de cotización: "' . ($this->lista->nombre ?? 'Sin nombre') . '" para el proyecto "' . $this->nombreProyecto . '".',
            'url' => $url,
        ];
    }
}
