<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GeralNotification extends Notification
{
    use Queueable;

    public $assunto;
    public $mensagem;
    public $pagina;
    /**
     * Create a new notification instance.
     */
    public function __construct($assunto, $mensagem, $pagina)
    {
        $this->assunto = $assunto;
        $this->mensagem = $mensagem;
        $this->pagina = $pagina;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'assunto'   => $this->assunto,
            'mensagem'  => $this->mensagem,
            'pagina'  => $this->pagina,
        ];
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
