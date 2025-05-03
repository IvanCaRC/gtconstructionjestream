<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class SolicitudCancelacion extends Notification
{
    use Queueable;

    protected $idProyecto;
    protected $nombreProyecto;
    protected $solicitante;

    public function __construct($idProyecto, $nombreProyecto, $solicitante)
    {
        $this->idProyecto = $idProyecto;
        $this->nombreProyecto = $nombreProyecto;
        $this->solicitante = $solicitante;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $url = config('app.url') . route('admin.cancelaciones', [], false);
        Log::info('Generando URL para SolicitudCancelacion: ' . $url);

        return [
            'type' => 'solicitud_cancelacion',
            'message' => 'El usuario: ' . $this->solicitante . ' ha solicitado la cancelación del proyecto: "' . $this->nombreProyecto . '".',
            'url' => $url,
        ];
    }
}
