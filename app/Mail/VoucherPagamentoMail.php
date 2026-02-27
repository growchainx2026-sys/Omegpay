<?php

namespace App\Mail;

use App\Models\Voucher;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VoucherPagamentoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $voucher;

    /**
     * Create a new message instance.
     */
    public function __construct(Voucher $voucher)
    {
        $this->voucher = $voucher;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Seu Voucher X-Coin foi confirmado!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.voucher-pagamento',
            with: [
                'clientName' => $this->voucher->client_name ?? 'Cliente',
                'codigoVoucher' => $this->voucher->codigo_voucher,
                'valor' => number_format($this->voucher->valor, 2, ',', '.'),
                'status' => $this->getStatusFormatado($this->voucher->status),
            ],
        );
    }

    /**
     * Formata o status para exibição
     */
    private function getStatusFormatado($status)
    {
        $statusMap = [
            'pago' => 'Pago ✅',
            'pendente' => 'Pendente',
            'revisao' => 'Em Revisão',
        ];

        return $statusMap[$status] ?? ucfirst($status);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}