@php
use App\Models\Setting;

$setting = Setting::first();

$isDeposito = $tipo == 'deposito';
$dataHora = $transaction->updated_at;

$valor = $transaction->amount;
$data = $dataHora->format('d/m/Y');
$hora = $dataHora->format('H:i:s');
$destino_nome = $isDeposito ? $transaction->client_name : $transaction->recebedor_name;
$destino_cpf = $isDeposito ? $transaction->client_cpf : $transaction->recebedor_cpf;
$destino_instituicao = $isDeposito ? env('APP_NAME') : 'INSTITUIÇÃO';
$destino_chave = $isDeposito ? null : "";
$origem_nome = $isDeposito ? $transaction->client_name : $transaction->recebedor_name;
$origem_cpf = $isDeposito ? $transaction->client_cpf : $transaction->recebedor_cpf;
$origem_instituicao = $isDeposito ? env('APP_NAME') : env('APP_NAME');
@endphp

<!doctype html>
<html lang="pt_BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }} - Comprovante PIX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: {{ $setting->software_color }};
        }

        body {
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }

        .comprovante {
            max-width: 400px;
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
        }

        .bg-gateway {
            background-color: var(--primary-color) !important;
        }

        .section-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: .5rem;
        }

        .info-item {
            font-size: 0.95rem;
            margin-bottom: .3rem;
        }

        .btn-compartilhar {
            border-radius: 8px;
            padding: .7rem;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="card shadow-sm border-0 comprovante">
        {{-- Topo --}}
        <div class="bg-gateway text-center text-white p-4">
            <i data-lucide="circle-check-big" class="mb-2" style="width: 48px; height: 48px;"></i>
            <h4 class="fw-bold mb-1">R$ {{ number_format($valor, 2, ',', '.') }}</h4>
            <p class="mb-1">{{ $isDeposito ? 'Pix Recebido' : 'Pix Enviado' }}</p>
            <small>{{ $data }} - {{ $hora }}</small>
        </div>

        {{-- Destino --}}
        <div class="p-3 border-bottom">
            <div class="section-title">Destino</div>
            <div class="info-item"><strong>Para:</strong> {{ $destino_nome }}</div>
            <div class="info-item"><strong>CPF/CNPJ:</strong> {{ $destino_cpf }}</div>
            <div class="info-item"><strong>Instituição:</strong> {{ $destino_instituicao }}</div>
            @if($destino_chave)
                <div class="info-item"><strong>Chave Pix:</strong> {{ $destino_chave }}</div>
            @endif
        </div>

        {{-- Origem --}}
        <div class="p-3 border-bottom">
            <div class="section-title">Origem</div>
            <div class="info-item"><strong>De:</strong> {{ $origem_nome }}</div>
            <div class="info-item"><strong>CPF/CNPJ:</strong> {{ $origem_cpf }}</div>
            <div class="info-item"><strong>Instituição:</strong> {{ $origem_instituicao }}</div>
        </div>

        {{-- Botão --}}
        <div class="p-3 text-start">
            <p style="font-size: 12px;font-weight: bold; color: rgba(61, 61, 61, 0.5)">Transação: {{ strtoupper($transaction->idTransaction) }}</p>
        </div>
    </div>

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        lucide.createIcons();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
