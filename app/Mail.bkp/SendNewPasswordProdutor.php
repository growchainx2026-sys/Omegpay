<?php

namespace App\Mail;

use App\Models\Aluno;
use App\Models\ProdutoFile;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendNewPasswordProdutor extends Mailable
{
    use Queueable, SerializesModels;

    // Torne as propriedades públicas
    public User $user;
    public string $newpassword;
    public string $assunto;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $newpassword, string $assunto)
    {
        $this->user = $user;
        $this->newpassword = $newpassword;
        $this->assunto = $assunto;
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
            view: 'emails.new-pasword-produtor', // crie a view nesse caminho
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
