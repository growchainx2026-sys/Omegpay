<?php

namespace App\Jobs;

use App\Models\Coprodutor;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VerificaCoproducoesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // Busca todas as coproduções ativas
        $coproducoes = Coprodutor::where('status', '!=', 'expired')->get();

        foreach ($coproducoes as $coprodutor) {
            // Se período for "sempre", não expira nunca
            if ($coprodutor->periodo === 'sempre') {
                continue;
            }

            // Soma o período (em dias) ao created_at
            $dataExpiracao = Carbon::parse($coprodutor->created_at)->addDays((int) $coprodutor->periodo);

            // Se a data de expiração for hoje, expira a coprodução
            if ($dataExpiracao->isToday()) {
                $coprodutor->update(['status' => 'expired']);
            }
        }
    }
}
