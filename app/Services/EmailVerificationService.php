<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class EmailVerificationService
{
    /**
     * Verifica se o e-mail é real/existente usando serviço externo.
     *
     * Retorna true quando:
     * - a verificação estiver desativada
     * - não houver API key configurada
     * - a API externa falhar ou estiver indisponível
     *
     * Somente retorna false quando a API responder claramente
     * que o e-mail é inválido ou inexistente.
     */
    public function verify(string $email): bool
    {
        // Permite desligar a verificação via env
        if (!config('services.email_verification.enabled', false)) {
            return true;
        }

        $apiKey = config('services.email_verification.key');

        if (empty($apiKey)) {
            return true;
        }

        try {
            // Exemplo usando Mailboxlayer (você pode trocar o provider se quiser)
            $response = Http::timeout(5)->get('https://apilayer.net/api/check', [
                'access_key' => $apiKey,
                'email'      => $email,
                'smtp'       => 1,
                'format'     => 1,
            ]);

            if (!$response->ok()) {
                return true;
            }

            $data = $response->json();

            // Campos padrão da Mailboxlayer
            $formatValid = $data['format_valid'] ?? false;
            $smtpCheck   = $data['smtp_check'] ?? false;

            // Só consideramos inválido se a API disser com clareza
            // que o formato é inválido ou o servidor não aceita o e-mail.
            return $formatValid && $smtpCheck;
        } catch (\Throwable $e) {
            // Em caso de erro na API, não travar o cadastro.
            return true;
        }
    }
}

