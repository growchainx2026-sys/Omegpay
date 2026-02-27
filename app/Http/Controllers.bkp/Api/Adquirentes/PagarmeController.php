<?php

namespace App\Http\Controllers\Api\Adquirentes;

use App\Models\Pagarme;
use App\Models\Voucher;
use App\Models\TransactionIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\ProcessaCallback;
use App\Mail\VoucherPagamentoMail;
use Illuminate\Support\Facades\Mail;

class PagarmeController extends Controller
{
    public function webhook(Request $request)
    {
        $data = $request->all();
        Log::debug("[+][CALLBACK][DEPOSIT][PAGARME] -> dados recebidos: " . json_encode($data));

        $type = $data["type"] ?? null;
        
        // ✅ SUPORTA AMBOS OS TIPOS DE WEBHOOK
        if ($type == "charge.paid") {
            // ✅ NOVO FORMATO (Links de Pagamento)
            $status = $data['data']['last_transaction']['status'] ?? null;
            $idTransaction = $data['data']['order']['id'] ?? null;
            $paymentMethod = $data['data']['payment_method'] ?? null;
            
            if ($status == "captured" && $idTransaction) {
                $novostatus = 'pago';
                if ($paymentMethod == 'credit_card') {
                    $novostatus = 'revisao';
                }
                if ($paymentMethod == 'boleto') {
                    $novostatus = 'revisao';
                }

                $processa = new ProcessaCallback();
                $processa->deposit($idTransaction, $novostatus, null, 'PAGARME');

                // ✅ Atualizar voucher (se existir)
                $this->atualizarVoucher($idTransaction);
            }
        } 
        elseif ($type == "order.paid") {
            // ✅ FORMATO ANTIGO (Produtos)
            $status = $data['data']['charges'][0]['last_transaction']['status'] ?? null;
            $idTransaction = $data['data']['id'] ?? null;
            $paymentMethod = $data['data']['charges'][0]['payment_method'] ?? null;
            
            if ($status == "paid" && $idTransaction) {
                $novostatus = 'pago';
                if ($paymentMethod == 'credit_card') {
                    $novostatus = 'revisao';
                }
                if ($paymentMethod == 'boleto') {
                    $novostatus = 'revisao';
                }

                $processa = new ProcessaCallback();
                $processa->deposit($idTransaction, $novostatus, null, 'PAGARME');

                // ✅ Atualizar voucher (se existir)
                $this->atualizarVoucher($idTransaction);
            }
        }
        
        return response()->json(['success' => true]);
    }

    /**
     * Atualiza o voucher quando o pagamento é confirmado
     */
    private function atualizarVoucher($idTransaction)
    {
        try {
            Log::info("[VOUCHER] Iniciando atualização para: {$idTransaction}");
            
            // ✅ CORREÇÃO: Buscar link pelo idTransaction OU pelo external_id da transação
            $link = \App\Models\Link::where('idTransaction', $idTransaction)->first();
            
            if (!$link) {
                // Tentar buscar pela tabela transactions_cash_in usando external_id
                $transaction = TransactionIn::where('external_id', $idTransaction)->first();
                
                if ($transaction) {
                    // Encontrou pela external_id, agora busca o link pelo idTransaction da transação
                    $link = \App\Models\Link::where('idTransaction', $transaction->idTransaction)->first();
                    
                    if ($link) {
                        Log::info("[VOUCHER] Link encontrado via external_id da transação");
                    }
                }
                
                if (!$link) {
                    Log::info("[VOUCHER] Link não encontrado (não é um link de pagamento, ignorando)");
                    return;
                }
            }

            // ✅ Busca o voucher pelo link_id
            $voucher = Voucher::where('link_id', $link->id)->first();

            if (!$voucher) {
                Log::warning("[VOUCHER] Voucher não encontrado para link_id: {$link->id}");
                return;
            }

            Log::info("[VOUCHER] Voucher encontrado: {$voucher->id}");

            // ✅ Busca a transação para pegar os dados do cliente
            if (!isset($transaction)) {
                $transaction = TransactionIn::where('idTransaction', $link->idTransaction)
                    ->orWhere('external_id', $idTransaction)
                    ->first();
            }
            
            if (!$transaction) {
                Log::warning("[VOUCHER] Transação não encontrada");
                
                $voucher->update([
                    'status' => 'pago',
                    'data_pagamento' => now(),
                ]);
                
                // Tentar enviar e-mail mesmo sem transação
                if (($voucher->status === 'pago' || $voucher->status === 'revisao') && $voucher->client_email) {
                    try {
                        Mail::to($voucher->client_email)->send(new VoucherPagamentoMail($voucher));
                        Log::info("[VOUCHER] E-mail enviado com sucesso para: {$voucher->client_email}");
                    } catch (\Exception $emailError) {
                        Log::error("[VOUCHER] Erro ao enviar e-mail: " . $emailError->getMessage());
                    }
                }
                
                return;
            }

            // ✅ Atualiza o voucher com os dados completos
            $voucher->update([
                'status' => 'pago',
                'client_name' => $transaction->client_name ?? null,
                'client_cpf' => $transaction->client_cpf ?? null,
                'client_email' => $voucher->client_email ?? $transaction->client_email ?? null,
                'client_telefone' => $voucher->client_telefone ?? $transaction->client_telefone ?? null,
                'payment_method' => $transaction->method ?? 'card',
                'data_pagamento' => now(),
                'transaction_id' => $transaction->id,
            ]);

            Log::info("[VOUCHER] Voucher atualizado com sucesso");

            // ✅ ENVIAR E-MAIL
            if (($voucher->status === 'pago' || $voucher->status === 'revisao') && $voucher->client_email) {
                try {
                    Mail::to($voucher->client_email)->send(new VoucherPagamentoMail($voucher));
                    Log::info("[VOUCHER] E-mail enviado com sucesso para: {$voucher->client_email}");
                } catch (\Exception $emailError) {
                    Log::error("[VOUCHER] Erro ao enviar e-mail: " . $emailError->getMessage());
                }
            }

        } catch (\Exception $e) {
            Log::error("[VOUCHER] Erro ao atualizar voucher: " . $e->getMessage());
        }
    }

    public function parcels(Request $request, $amount)
    {
        $valor = (float) $amount;
        $pagarme = Pagarme::first();

        $parcelas = [];
        for ($i = 1; $i <= 12; $i++) {
            $campo = "{$i}x";
            $prefix = $i === 1 ? 'À vista - R$ ' : "{$i}x R$ ";
            $percentual = $pagarme->$campo ?? 0;

            $valorCalculado = $i === 1
                ? $valor
                : $this->calculaParcela($valor, $percentual, $i);

            $parcelas[] = [
                'label' => $prefix . number_format($valorCalculado, 2, ',', '.'),
                'value' => $i
            ];
        }

        return response()->json($parcelas);
    }

    private function calculaParcela($valor, $percentual, $parcelas)
    {
        $total = $valor + ($valor * ($percentual / 100));
        return $total / $parcelas;
    }

    private function formatarValorDecimal($valorBruto)
    {
        $valor = str_replace(['R$', ' '], '', $valorBruto);
        $valor = str_replace('.', '', $valor);
        $valor = str_replace(',', '.', $valor);
        return (float) $valor;
    }
}