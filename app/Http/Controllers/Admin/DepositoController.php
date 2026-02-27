<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{TransactionIn, User};
use App\Models\Pedido;
use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DepositoController extends Controller
{
    public function index(Request $request)
    {

        $user = auth()->user();
        if (!$user || !in_array($user->permission, ['admin', 'dev'], true)) {
            return redirect()->route('dashboard');
        }
        $periodo = $request->input('periodo', 'dia');
        $start   = $request->input('start');
        $end     = $request->input('end');

        // --- define janelas ---
        switch ($periodo) {
            case 'dia':
                $inicioAtual   = Carbon::now()->startOfDay();
                $fimAtual      = Carbon::now()->endOfDay();
                $inicioAnterior = Carbon::yesterday()->startOfDay();
                $fimAnterior    = Carbon::yesterday()->endOfDay();
                break;

            case 'semana':
                $inicioAtual   = Carbon::now()->startOfWeek();
                $fimAtual      = Carbon::now()->endOfWeek();
                $inicioAnterior = Carbon::now()->subWeek()->startOfWeek();
                $fimAnterior    = Carbon::now()->subWeek()->endOfWeek();
                break;

            case 'mes':
                $inicioAtual   = Carbon::now()->startOfMonth();
                $fimAtual      = Carbon::now()->endOfMonth();
                $inicioAnterior = Carbon::now()->subMonth()->startOfMonth();
                $fimAnterior    = Carbon::now()->subMonth()->endOfMonth();
                break;

            case 'tudo':
                $inicioAtual   = Carbon::create(2020, 1, 1);
                $fimAtual      = Carbon::now();
                $inicioAnterior = Carbon::create(2019, 1, 1);
                $fimAnterior    = Carbon::create(2019, 12, 31);
                break;

            case 'custom':
                $inicioAtual   = Carbon::parse($start)->startOfDay();
                $fimAtual      = Carbon::parse($end)->endOfDay();
                // anterior = mesmo intervalo retrocedido
                $diff = $inicioAtual->diffInDays($fimAtual) + 1;
                $inicioAnterior = (clone $inicioAtual)->subDays($diff);
                $fimAnterior    = (clone $fimAtual)->subDays($diff);
                break;

            default:
                $inicioAtual   = Carbon::now()->startOfDay();
                $fimAtual      = Carbon::now()->endOfDay();
                $inicioAnterior = Carbon::yesterday()->startOfDay();
                $fimAnterior    = Carbon::yesterday()->endOfDay();
        }

        // --- coleção usada na tabela ---
        $depositos = TransactionIn::with('user')
            ->whereBetween('created_at', [$inicioAtual, $fimAtual])
            ->get();

        // --- helpers ---
        $sum = fn($status, $campo, $metodo = null, $inicio = null, $fim = null, $status2 = '')
        => TransactionIn::when($metodo, fn($q) => $q->where('method', $metodo))
            ->whereIn('status', [$status, $status2])
            ->whereBetween('created_at', [$inicio ?? $inicioAtual, $fim ?? $fimAtual])
            ->sum($campo) ?? 0.0;

        // --- vendas ---
        $vendasAnterior = $sum('pago', 'amount', null, $inicioAnterior, $fimAnterior);
        $vendasAtual    = $sum('pago', 'amount', null, null, null,'revisao');
        $crescimento    = $this->calcularCrescimento($vendasAnterior, $vendasAtual);

        $vendasCard   = $sum('pago', 'amount', 'card', null, null, 'revisao');
        $vendasPix    = $sum('pago', 'amount', 'pix');
        $vendasBoleto = $sum('pago', 'amount', 'billet', null, null, 'revisao');

        // --- abandono ---
        $abandonoAnterior = $sum('pendente', 'amount', null, $inicioAnterior, $fimAnterior);
        $abandonoAtual    = $sum('pendente', 'amount');
        $abandono         = $this->calcularCrescimento($abandonoAnterior, $abandonoAtual);

        $abandonoCard   = $sum('pendente', 'amount', 'card');
        $abandonoPix    = $sum('pendente', 'amount', 'pix');
        $abandonoBoleto = $sum('pendente', 'amount', 'billet');

        // --- lucro ---
        $lucroAnterior = $sum('pago', 'taxa_cash_in', null, $inicioAnterior, $fimAnterior, 'revisao');
        $lucroAtual    = $sum('pago', 'taxa_cash_in', null, null, null,'revisao');
        $lucro         = $this->calcularCrescimento($lucroAnterior, $lucroAtual);

        $lucroCard   = $sum('pago', 'taxa_cash_in', 'card', null, null, 'revisao');
        $lucroPix    = $sum('pago', 'taxa_cash_in', 'pix');
        $lucroBoleto = $sum('pago', 'taxa_cash_in', 'billet', null, null, 'revisao');

        return view('pages.admin.depositos', compact(
            'depositos',
            'periodo',
            // vendas
            'vendasAnterior',
            'vendasAtual',
            'crescimento',
            'vendasCard',
            'vendasPix',
            'vendasBoleto',
            // abandono
            'abandonoAnterior',
            'abandonoAtual',
            'abandono',
            'abandonoCard',
            'abandonoPix',
            'abandonoBoleto',
            // lucro
            'lucroAnterior',
            'lucroAtual',
            'lucro',
            'lucroCard',
            'lucroPix',
            'lucroBoleto'
        ));
    }

    private function calcularCrescimento($anterior, $atual)
    {
        if ($anterior > 0) {
            return (($atual - $anterior) / $anterior) * 100;
        } elseif ($atual > 0 && $anterior == 0) {
            return 100;
        }
        return 0;
    }


    public function addentrada(Request $request)
    {
        $request->validate([
            'deposito_id'       => 'required|exists:users,id',
            'deposito_amount'   => 'required|numeric|min:0.01',
            'deposito_descricao'=> 'required|string|max:191',
            'deposito_taxa'     => 'nullable|numeric|min:0',
            'master_password'  => 'required|string',
        ]);

        $masterPassword = config('admin.balance_master_password');
        if (empty($masterPassword) || $request->master_password !== $masterPassword) {
            return redirect()->back()->with('error', 'Senha mestre inválida.')->withInput($request->except('master_password'));
        }

        $data = $request->all();
        $amount = (float) $data['deposito_amount'];
        $taxa = (float) ($data['deposito_taxa'] ?? 0);
        $liquido = $amount - $taxa;
        if ($liquido < 0) {
            return redirect()->back()->with('error', 'A taxa não pode ser maior que o valor.')->withInput($request->except('master_password'));
        }

        $idTransaction = uniqid('balance_in_');
        $user = User::find($data['deposito_id']);

        $cashin = [
            "external_id"                   => $idTransaction,
            "amount"                        => $amount,
            "client_name"                   => $user->name,
            "client_cpf"                    => $user->cpf_cnpj,
            "client_email"                  => $user->email,
            "status"                        => "pago",
            "idTransaction"                 => $idTransaction,
            "cash_in_liquido"               => $liquido,
            "qrcode_pix"                    => 'balance',
            "paymentcode"                   => 'balance',
            "paymentCodeBase64"             => 'balance',
            "adquirente_ref"                => env('APP_NAME') ?? "SpacePag",
            "taxa_cash_in"                  => $taxa,
            "executor_ordem"                => env('APP_NAME') ?? "SpacePag",
            "request_ip"                    => 'web',
            "request_domain"                => 'web',
            "type"                          => 'cash',
            "plataforma"                    => 'web',
            "user_id"                       => $data['deposito_id'],
            "descricao_transacao"           => $data['deposito_descricao'],
            "callbackUrl"                   => 'web',
        ];

        TransactionIn::create($cashin);

        Helper::calculaSaldoLiquido($user->id);

        return redirect()->back()->with('success', 'Entrada adicionada com sucesso!');
    }

    public function antecipar(Request $request, $id)
    {
        $deposito = TransactionIn::where('id', $id)->first();
        if($deposito) {
            $deposito->update(['status' => 'pago']);
            $pedido = Pedido::where('idTransaction', $deposito->idTransaction)->first();
            if($pedido){
                $pedido->update(['status' => 'pago']);
            }
        }
        return back()->with('success', 'Antecipação realizada com sucesso!');
    }
}
