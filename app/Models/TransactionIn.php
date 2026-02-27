<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionIn extends Model
{
    public $table = "transactions_cash_in";

    protected $fillable = [
        'method',
        'external_id',
        'amount',
        'client_name',
        'client_cpf',
        'real_data',
        'status',
        'type',
        'idTransaction',
        'cash_in_liquido',
        'qrcode_pix',
        'paymentcode',
        'paymentCodeBase64',
        'adquirente_ref',
        'taxa_cash_in',
        'taxa_fixa',
        'executor_ordem',
        'descricao_transacao',
        'request_ip',
        'request_domain',
        'callbackUrl',
        'user_id',
        'dias_recebimento',
        'taxa_reserva',
        'dias_liberar_reserva',
        'taxa_reserva_resgatada'
    ];

    public const METHODS = [
        'pix' => 'Pix',
        'billet' => 'Boleto',
        'card' => 'Cartão',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function callback()
    {
        return $this->hasOne(Callback::class, 'transaction_cash_in_id');
    }


    protected static function booted()
    {
        static::created(function ($transaction) {
            $transaction->load('user'); // 🔁 força o carregamento do user



            if ($transaction->user->client_indication) {
                $indicador = \App\Models\User::where('codigo_referencia', $transaction->user->client_indication)->first();
                if ($indicador->ativar_split) {
                    $idTransaction = uniqid();
                    $external_id = $transaction->idTransaction;

                    if ($indicador->split_fixed > 0 && !str_contains($transaction->idTransaction, 'balance_in_')) {
                        $amount = $indicador->split_fixed;
                        $transaction->cash_in_liquido -= $amount;
                        $transaction->amount -= $amount;
                        $transaction->save();

                        $payload = [
                            'external_id' => $external_id,
                            'amount' => $amount,
                            'client_name' => $transaction->client_name,
                            'client_cpf' => $transaction->client_cpf,
                            'end2end' => $transaction->end2end,
                            'status' => 'pendente',
                            'idTransaction' => $idTransaction,
                            'cash_in_liquido' => $amount,
                            'qrcode_pix' => 'N/A',
                            'paymentcode' => 'N/A',
                            'paymentCodeBase64' => 'N/A',
                            'adquirente_ref' => $transaction->adquirente_ref,
                            'executor_ordem' => $transaction->adquirente_ref,
                            'taxa_cash_in' => 0,
                            'type' => 'split',
                            'descricao_transacao' => "SPLIT",
                            'plataforma' => 'web',
                            'callbackUrl' => 'web',
                            'user_id' => $indicador->id
                        ];

                        $affiliate = TransactionIn::create($payload);

                        $setting = Setting::first();
                        if ($setting->taxa_reserva > 0) {
                            $reserva = $affiliate->cash_in_liquido * $setting->taxa_reserva / 100;

                            $affiliate->taxa_reserva = $reserva;
                            $affiliate->dias_liberar_reserva = $setting->dias_liberar_reserva;
                            $affiliate->taxa_reserva_resgatada = false;
                            $affiliate->cash_in_liquido -= $reserva;
                            $affiliate->save();
                        }

                    } elseif ($indicador->split_percent > 0 && !str_contains($transaction->idTransaction, 'balance_in_')) {
                        $amount = $indicador->split_percent * $transaction->amount / 100;
                        $transaction->cash_in_liquido -= $amount;
                        $transaction->amount -= $amount;
                        $transaction->save();

                        $payload = [
                            'external_id' => $external_id,
                            'amount' => $amount,
                            'client_name' => $transaction->client_name,
                            'client_cpf' => $transaction->client_cpf,
                            'end2end' => $transaction->end2end,
                            'status' => 'pendente',
                            'idTransaction' => $idTransaction,
                            'cash_in_liquido' => $amount,
                            'qrcode_pix' => 'N/A',
                            'paymentcode' => 'N/A',
                            'paymentCodeBase64' => 'N/A',
                            'adquirente_ref' => $transaction->adquirente_ref,
                            'executor_ordem' => $transaction->adquirente_ref,
                            'taxa_cash_in' => 0,
                            'type' => 'split',
                            'descricao_transacao' => "SPLIT",
                            'plataforma' => 'web',
                            'callbackUrl' => 'web',
                            'user_id' => $indicador->id
                        ];

                        $affiliate = TransactionIn::create($payload);

                        $setting = Setting::first();
                        if ($setting->taxa_reserva > 0) {
                            $reserva = $affiliate->cash_in_liquido * $setting->taxa_reserva / 100;

                            $affiliate->taxa_reserva = $reserva;
                            $affiliate->dias_liberar_reserva = $setting->dias_liberar_reserva;
                            $affiliate->taxa_reserva_resgatada = false;
                            $affiliate->cash_in_liquido -= $reserva;
                            $affiliate->save();
                        }
                    }
                }
            }

            if (empty($transaction->taxa_reserva)) {
                $setting = Setting::first();
                if ($setting->taxa_reserva > 0 && !str_contains($transaction->idTransaction, 'balance_in_')) {
                    $reserva = $transaction->cash_in_liquido * $setting->taxa_reserva / 100;
                    $transaction->taxa_reserva = $reserva;
                    $transaction->dias_liberar_reserva = $setting->dias_liberar_reserva;
                    $transaction->taxa_reserva_resgatada = false;
                    $transaction->cash_in_liquido -= $reserva;
                    $transaction->save();
                }
            }

        });

        // ✅ CORREÇÃO: Atualizar saldo quando transação for atualizada
        static::updated(function ($transaction) {
            \Log::info("[SALDO] Evento updated disparado para user_id: {$transaction->user_id}");
            self::atualizarSaldoUsuario($transaction->user_id);
        });

        // ✅ CORREÇÃO: Atualizar saldo quando transação for deletada
        static::deleted(function ($transaction) {
            \Log::info("[SALDO] Evento deleted disparado para user_id: {$transaction->user_id}");
            self::atualizarSaldoUsuario($transaction->user_id);
        });
    }

    /**
     * ✅ CORREÇÃO: Atualizar saldo do usuário automaticamente
     * Este método recalcula o saldo sempre que uma transação é criada, atualizada ou deletada
     */
    protected static function atualizarSaldoUsuario($userId)
    {
        try {
            \Log::info("[SALDO] Iniciando atualização para user_id: {$userId}");
            
            $user = \App\Models\User::find($userId);
            if (!$user) {
                \Log::error("[SALDO] User {$userId} não encontrado!");
                return;
            }

            // Calcular saldo total (entradas - saídas)
            $totalEntradas = self::where('user_id', $userId)
                ->whereIn('status', ['pago', 'revisao'])
                ->sum('amount');

            $totalSaidas = \App\Models\TransactionOut::where('user_id', $userId)
                ->where('status', 'pago')
                ->sum('amount');

            $saldoCalculado = $totalEntradas - $totalSaidas;

            \Log::info("[SALDO] User {$userId} - Entradas: {$totalEntradas}, Saídas: {$totalSaidas}, Saldo calculado: {$saldoCalculado}");

            $user->saldo = $saldoCalculado;
            $user->save();

            \Log::info("[SALDO] Saldo do user {$userId} atualizado com sucesso para: {$saldoCalculado}");

        } catch (\Exception $e) {
            \Log::error("[SALDO] Erro ao atualizar saldo do user {$userId}: " . $e->getMessage());
            \Log::error("[SALDO] Stack trace: " . $e->getTraceAsString());
        }
    }
}