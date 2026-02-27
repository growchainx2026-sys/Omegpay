<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\Link;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Exibe a listagem de vouchers gerados (pagos)
     */
    public function index(Request $request)
    {
        $periodo = $request->input('periodo', 'todos');
        
        // ✅ SOLUÇÃO ALTERNATIVA: Buscar IDs dos links do usuário primeiro
        $linkIds = Link::where('user_id', auth()->id())->pluck('id');
        
        // Query base: apenas vouchers pagos DOS LINKS DO USUÁRIO
        $query = Voucher::with(['link', 'transaction'])
            ->whereIn('link_id', $linkIds) // ✅ FILTRAR PELOS IDs DOS LINKS
            ->where('status', 'pago');

        // Filtro por período
        switch ($periodo) {
            case 'hoje':
                $query->whereDate('data_pagamento', now()->toDateString());
                break;
            case 'semana':
                $query->whereBetween('data_pagamento', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]);
                break;
            case 'mes':
                $query->whereBetween('data_pagamento', [
                    now()->startOfMonth(),
                    now()->endOfMonth()
                ]);
                break;
            case 'todos':
            default:
                // Sem filtro adicional
                break;
        }

        $vouchers = $query->orderBy('data_pagamento', 'desc')->get();

        // Estatísticas
        $totalVouchers = $vouchers->count();
        $valorTotal = $vouchers->sum('valor');

        return view('pages.vouchers', compact('vouchers', 'periodo', 'totalVouchers', 'valorTotal'));
    }

    /**
     * Armazena um novo voucher (quando o link é criado)
     */
    public function store(Request $request)
    {
        // Lógica para criar o voucher quando o link é criado
        // Será chamado automaticamente quando um link de pagamento for gerado
    }

    /**
     * Atualiza o voucher quando o pagamento é confirmado
     * Este método será chamado pelo webhook ou pela confirmação de pagamento
     */
    public function updateFromPayment($idTransaction, $transactionData)
    {
        $voucher = Voucher::where('codigo_voucher', $idTransaction)->first();

        if ($voucher) {
            $voucher->update([
                'status' => 'pago',
                'client_name' => $transactionData['client_name'],
                'client_cpf' => $transactionData['client_cpf'],
                'client_email' => $transactionData['client_email'] ?? null,
                'client_telefone' => $transactionData['client_telefone'] ?? null,
                'payment_method' => $transactionData['method'],
                'data_pagamento' => now(),
                'transaction_id' => $transactionData['transaction_id'] ?? null,
            ]);

            return $voucher;
        }

        return null;
    }

    public function validar($id)
    {
        // ✅ BUSCAR VOUCHER APENAS DOS LINKS DO USUÁRIO
        $linkIds = Link::where('user_id', auth()->id())->pluck('id');
        
        $voucher = Voucher::whereIn('link_id', $linkIds)
            ->where('id', $id)
            ->firstOrFail();

        // Só valida se ainda estiver pendente
        if ($voucher->ativacao === 'validar?') {
            $voucher->ativacao = 'Validado';
            $voucher->save();
        }

        return redirect()->back()->with('success', 'Voucher validado com sucesso!');
    }

    /**
     * Edita um voucher
     */
    public function edit(Request $request)
    {
        //
    }

    /**
     * Deleta um voucher
     */
    public function del(Request $request)
    {
        //
    }
}