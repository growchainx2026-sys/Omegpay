<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendMessage extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // Propriedades públicas acessíveis na view
    public string $email;
    public string $name;
    public string $mensagem;
    public string $assunto;

    /**
     * Create a new message instance.
     */
    public function __construct(string $email, string $assunto, string $mensagem, string $name)
    {
        Log::debug("DADOS DA MENSAGEM: " . json_encode(compact('email','assunto','mensagem','name')));
        $this->email = $email;
        $this->name = $name;
        $this->assunto = $assunto;
        $this->mensagem = $mensagem;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->assunto,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.message', // Blade view
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
