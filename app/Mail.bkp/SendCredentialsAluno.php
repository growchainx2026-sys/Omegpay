<?php

namespace App\Mail;

use App\Models\Aluno;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendCredentialsAluno extends Mailable
{
    use Queueable, SerializesModels;

    // Torne as propriedades públicas
    public Aluno $aluno;
    public string $senha; 
    public string $assunto; 

    /**
     * Create a new message instance.
     */
    public function __construct(Aluno $aluno, string $senha, string $assunto)
    {
        $this->aluno = $aluno;
        $this->senha = $senha;
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
            view: 'emails.cadastro-aluno', // crie a view nesse caminho
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
