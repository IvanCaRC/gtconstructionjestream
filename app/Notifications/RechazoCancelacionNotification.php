<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RechazoCancelacionNotification extends Notification
{
    use Queueable;

    protected $idProyecto;
    protected $nombreProyecto;
    protected $usuarioAsociado;

    /**
     * Create a new notification instance.
     */
    public function __construct($idProyecto, $nombreProyecto, $usuarioAsociado)
    {
        $this->idProyecto = $idProyecto;
        $this->nombreProyecto = $nombreProyecto;
        $this->usuarioAsociado = $usuarioAsociado;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
