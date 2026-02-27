@php 
    $setting = \App\Helpers\Helper::settings();
    $user = auth()->user();

    $taxa_fixa = (float) $setting->taxa_fixa;
    $valor_deposito = (float) $setting->valor_min_deposito + $taxa_fixa;
    $taxa_percentual = (float) $setting->taxa_cash_in;
    $taxa_fixa_ativa = $taxa_fixa > 0;

    // Calcula a taxa percentual
    $taxa_cash_in = $valor_deposito * $taxa_percentual / 100;

    // Se houver taxa fixa, somar ao total de taxas
    if ($taxa_fixa_ativa) {
        $taxa_cash_in += $taxa_fixa;
    }

    // Subtrai o total das taxas do valor de depósito
    $valor_total_deposito = $valor_deposito - $taxa_cash_in;

    if ($user->client_indication) {
        $indicador = $user->indicadoPor;

        if ($indicador && $indicador->ativar_split) {
            $taxa_split = 0;

            if (!empty($indicador->split_fixed)) {
                $taxa_split += (float) $indicador->split_fixed;
            }

            if (!empty($indicador->split_percent)) {
                $taxa_split += $valor_deposito * ((float) $indicador->split_percent / 100);
            }

            $taxa_cash_in += $taxa_split;
            $valor_total_deposito -= $taxa_split;
        }
    }

    // Formata o valor final com 2 casas decimais, vírgula decimal e ponto de milhar
    $valor_total_deposito = number_format($valor_total_deposito, 2, ',', '.');

    $split_ativo = !is_null($user->indicadoPor) && $user->indicadoPor->ativar_split;
@endphp

@extends('layouts.app')

@section('title', 'Depósito via PIX')

@section('content')
<div class="header mb-3">
    <h1 class="header-title">
        Depósito via PIX
    </h1>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Dados do Pagamento</h5>
            </div>
            <div class="card-body">
                <form class="row row-cols-md-auto align-items-center" method="POST" action="{{ route('deposito.web') }}">
                   @csrf
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label>CPF Pagador</label>
                            <input type="text" class="form-control" name="cliente_cpf" value="{{ auth()->user()->cpf_cnpj }}" id="cliente-cpf" readonly>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="mb-3">
                            <label>Nome Pagador</label>
                            <input type="text" class="form-control" name="client_name"  value="{{ auth()->user()->name }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label>Valor</label>
                            <input type="number" min="{{ $valor_deposito }}" value="{{ $valor_deposito }}" class="form-control" name="amount" id="amount" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label>Taxa</label>
                            <input type="text" class="form-control" readonly id="taxa" value="{{ 'R$ ' . number_format($taxa_cash_in, 2, ',', '.') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label>Valor Total</label>
                            <input type="text" class="form-control" name="cash_in_liquido" value="{{ 'R$ '.$valor_total_deposito }}" readonly id="valor-total">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100 mt-1 mb-0"><i class="fa-solid fa-download"></i>&nbsp;Depositar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    // Máscara dinâmica CPF
    $("input[id*='cliente-cpf']").inputmask({
        mask: ['999.999.999-99'],
        keepStatic: true
    });

    // Atualiza o valor total e taxa em tempo real
    $('#amount').on('input', function () {
    let valor = parseFloat($(this).val().replace(',', '.'));

    let valorMinimo = Number("{{ $setting->valor_min_deposito }}");
    let taxaPercentual = Number("{{ $setting->taxa_cash_in }}"); // Ex: 5 para 5%
    let taxaFixa = Number("{{ $setting->taxa_fixa }}");
    let taxaFixaAtiva = taxaFixa > 0;
    let splitAtivo = "{{ $split_ativo }}";
    let taxaSplit = 0;

    // Corrigir valor abaixo do mínimo
    if (valor < valorMinimo || isNaN(valor)) {
        valor = valorMinimo;
        $(this).val(valor.toFixed(2).replace('.', ','));
    }

    // Calcular taxa total
    let taxaTotal = (valor * taxaPercentual / 100);
    if (taxaFixaAtiva) {
        taxaTotal += taxaFixa;
    }

    // Calcular taxa de split se ativo
    if (splitAtivo == "1") {
        let indicadoPor = @json(auth()->user()->indicadoPor);

        if (indicadoPor) {
            if (indicadoPor.split_fixed) {
                taxaSplit += Number(indicadoPor.split_fixed);
            }
            if (indicadoPor.split_percent) {
                let valorSplit = valor * Number(indicadoPor.split_percent) / 100;
                taxaSplit += valorSplit;
            }
        }
    }

    taxaTotal += taxaSplit;

    // Valor final com desconto
    let valorFinal = valor - taxaTotal;

    // Formata os valores para exibição
    let taxaFormatada = 'R$ ' + taxaTotal.toFixed(2).replace('.', ',');
    let valorFinalFormatado = 'R$ ' + valorFinal.toFixed(2).replace('.', ',');

    // Atualiza os campos na tela
    $('#taxa').val(taxaFormatada);
    $('#valor-total').val(valorFinalFormatado);
});

});
</script>


@if (session('qrcode'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = new bootstrap.Modal(document.getElementById('modal-qrcode'));
            modal.show();

            // Função para copiar o valor da chave
            document.getElementById('copy-btn').addEventListener('click', function() {
                const input = document.getElementById('qrcode-input');
                input.select(); // Seleciona o conteúdo do input
                document.execCommand('copy'); // Copia para o clipboard

                // Alerta opcional para confirmar que a chave foi copiada
                showToast('success','Chave copiada para a área de transferência!');
            });
        });
    </script>

    <div class="modal fade" id="modal-qrcode" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pagamento PIX</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ session('qr_code_image_url') }}" alt="QR Code" class="img-fluid mb-3">
                    <p style="font-size: 36px">Valor: <strong style="color:green;">{{ session('amount') }}</strong></p>
                    
                    <!-- Campo de input para a chave QR Code -->
                    <input id="qrcode-input" value="{{ session('qrcode') }}" class="form-control mb-3" readonly>

                    <!-- Botão para copiar a chave -->
                    <button id="copy-btn" class="btn btn-primary w-100">Copiar chave</button>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

