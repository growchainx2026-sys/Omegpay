{{-- SAQUE --}}
@php
    $setting = \App\Helpers\Helper::settings();

    $valor_minimo = auth()->user()->saldo;
    $taxa_cash_out = ($valor_minimo * $setting->taxa_cash_out) / 100; //$setting->taxa_fixa;
    $valor_total_saque = number_format(auth()->user()->saldo - $taxa_cash_out, '2', ',', '.');
    $taxa_fixa_ativa = $setting->taxa_cash_out > 0; //$setting->active_taxa_fixa_web;
@endphp
{{-- DEPOSITO --}}
@php
    $setting = \App\Helpers\Helper::settings();
    $user = auth()->user();

    $taxa_fixa = (float) $setting->taxa_fixa;
    $valor_deposito = (float) $setting->deposito_minimo;
    $taxa_percentual = (float) $setting->taxa_cash_in; 
    $taxa_fixa_ativa = $taxa_fixa > 0;

    // Calcula a taxa percentual
    $taxa_cash_in = ($valor_deposito * $taxa_percentual) / 100;

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

@section('title', 'Financeiro')

@section('content')
    <div class="header mb-3">
        <h1 class="header-title">
            Financeiro
        </h1>
    </div>
    <div class="row g-3">
        <div class="col-12 mb-3">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach ($banners as $banner)
                        <div class="swiper-slide">
                            <img src="/storage/{{ $banner->image }}" class="img-fluid w-100" style="border-radius:6px;"
                                alt="Imagem">
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>

        <div class="col-12 col-lg-6 col-xl-3">
            <div class="card card-dash" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="card-body text-start p-4">
                    <h6 class="text-muted mb-3">Saldo Disponível (PIX)&nbsp;<i data-lucide="wallet" class="me-1"
                            style="width: 20px;"></i></h6>
                    <h4 class="fw-bold fs-3 mb-3">R$ {{ number_format(auth()->user()->saldo, '2', ',', '.') }}</h4>
                    <button data-bs-toggle="modal" data-bs-target="#modalSaque"
                        class="btn btn-md btn-primary d-flex align-items-end justify-content-center w-100">
                        <i data-lucide="qr-code" class="me-1" style="width: 16px; stroke: white !important;"></i>
                        Realizar Saque
                    </button>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 col-xl-3">
            <div class="card card-dash" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="card-body text-start p-4">
                    <h6 class="text-muted mb-3">Saldo Disponível (Cartão)&nbsp;<i data-lucide="credit-card" class="me-1"
                            style="width: 20px;"></i></h6>
                    <h4 class="fw-bold fs-3 mb-3">R$0,00</h4>
                    <button class="btn btn-md btn-primary d-flex align-items-center justify-content-center w-100" disabled>
                        <i data-lucide="qr-code" class="me-1" style="width: 16px; stroke: white !important;"></i>
                        Realizar Saque
                    </button>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 col-xl-3">
            <div class="card card-dash" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="card-body text-start p-4">
                    <h6 class="text-muted mb-3">Depositar&nbsp;<i data-lucide="wallet" class="me-1"
                            style="width: 20px;"></i></h6>
                    <h4 class="fw-bold fs-3 mb-3">R$0,00</h4>
                    <button data-bs-toggle="modal" data-bs-target="#modalDeposito"
                        class="btn btn-md btn-primary d-flex align-items-center justify-content-center w-100">
                        <i data-lucide="qr-code" class="me-1" style="width: 16px; stroke: white !important;"></i>
                        Realizar Depósito
                    </button>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 col-xl-3">
            <div class="card card-dash" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="card-body text-start p-4">
                    <h6 class="text-muted mb-3 d-flex justify-content-start align-items-center gap-1">
                        Reserva Financeira <i data-lucide="shield" class="text-secondary"
                            style="width:16px;height:16px"></i>
                    </h6>
                    <h4 class="fw-bold fs-3 mb-1">{{ 'R$ '.number_format(auth()->user()->taxa_reserva ?? 0, 2, ',', '.') }}</h4>
                    <small class="text-muted">Valor retido para garantir a segurança das suas transações</small>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalSaque" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="modalSaqueLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <form method="POST" action="{{ route('saquePix') }}">
                        @csrf
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="modalSaqueLabel">Realizar saque</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label>Tipo de chave</label>
                                    <select name="pixKeyType" id="pixKeyType" class="form-control"
                                        {{ auth()->user()->saldo <= 0 ? 'disabled' : '' }} required>
                                        <option value="cpf" selected>CPF</option>
                                        <option value="cnpj">CNPJ</option>
                                        <option value="email">E-Mail</option>
                                        <option value="telefone">Celular</option>
                                        <option value="aleatoria">Aleatória (EVP)</option>
                                    </select>
                                    @error('pixKeyType')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label>Chave</label>
                                    <input type="text" class="form-control" name="pixKey" id="pixKey"
                                        value="{{ auth()->user()->cpf_cnpj }}"
                                        {{ auth()->user()->saldo <= 0 ? 'disabled' : '' }} required>
                                    <div id="pixKey-error" class="text-danger" style="display: none; font-size: 0.875rem; margin-top: 0.25rem;"></div>
                                </div>
                                @error('pixKey')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label>Valor</label>
                                    {{-- Este é o campo que o usuário vê. Note que o 'name' foi removido para não ser enviado. --}}
                                    <input type="text" max="{{ auth()->user()->saldo }}" class="form-control"
                                           id="amount-saque"
                                           value="R$ {{ number_format($valor_minimo, 2, ',', '.') }}"
                                           {{ auth()->user()->saldo <= 0 ? 'disabled' : '' }} required>
                                    
                                    {{-- Este campo oculto enviará o valor numérico limpo para o backend --}}
                                    <input type="hidden" name="amount" id="amount-clean" value="{{ $valor_minimo }}">
                                </div>
                                @error('amount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12" hidden>
                                <div class="mb-3">
                                    <label>Taxas</label>
                                    <input type="text" class="form-control" name="taxa_cash_out" readonly
                                        value="{{ $taxa_cash_out }}" {{ auth()->user()->saldo <= 0 ? 'disabled' : '' }}>
                                </div>
                            </div>
                            <div class="col-12" hidden>
                                <div class="mb-3">
                                    <label>Valor Total</label>
                                    <input type="text" class="form-control" name="cash_out_liquido"
                                        value="{{ $valor_total_saque }}" id="valor-total"
                                        {{ auth()->user()->saldo <= 0 ? 'disabled' : '' }}>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" id="btn-submit-saque" class="btn btn-primary">Realizar Saque</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalDeposito" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="modalDepositoLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <form method="POST" action="{{ route('deposito.web') }}">
                        @csrf
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="modalDepositoLabel">Realizar depósito</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="col-12" hidden>
                                <div class="mb-3">
                                    <label>CPF Pagador</label>
                                    <input type="text" class="form-control" name="cliente_cpf"
                                        value="{{ auth()->user()->cpf_cnpj }}" id="cliente-cpf" readonly>
                                </div>
                            </div>
                            <div class="col-12" hidden>
                                <div class="mb-3">
                                    <label>Nome Pagador</label>
                                    <input type="text" class="form-control" name="client_name"
                                        value="{{ auth()->user()->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label>Valor</label>
                                    <input type="number" min="{{ $valor_deposito }}" value="{{ $valor_deposito }}"
                                        class="form-control" name="amount" id="amount-deposit" required>
                                </div>
                            </div>
                            <div class="col-12" hidden>
                                <div class="mb-3">
                                    <label>Taxa</label>
                                    <input type="text" class="form-control" readonly id="taxa-deposito"
                                        value="{{ 'R$ ' . number_format($taxa_cash_in, 2, ',', '.') }}">
                                </div>
                            </div>

                            <div class="col-12" hidden>
                                <div class="mb-3">
                                    <label>Valor Total</label>
                                    <input type="text" class="form-control" name="cash_in_liquido"
                                        value="{{ 'R$ ' . $valor_total_deposito }}" readonly id="valor-total-deposito">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Realizar depósito</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT CORRIGIDO E UNIFICADO --}}
    <script>
        $(document).ready(function() {
            
            // --- LÓGICA DO MODAL DE SAQUE ---

            // Máscara dinâmica CPF/CNPJ para a Chave PIX
            function applyMask() {
                var selectedOption = $('#pixKeyType').val();
                var maskOptions = '';

                switch (selectedOption) {
                    case 'cpf':
                        maskOptions = '999.999.999-99';
                        break;
                    case 'cnpj':
                        maskOptions = '99.999.999/9999-99';
                        break;
                    case 'email':
                        $('#pixKey').inputmask('remove');
                        return;
                    case 'celular':
                        maskOptions = '(99) 99999-9999';
                        break;
                    case 'aleatoria':
                        $('#pixKey').inputmask('remove');
                        return;
                    default:
                        $('#pixKey').inputmask('remove');
                        return;
                }
                $('#pixKey').inputmask(maskOptions);
            }
            
            // Funções de validação
            function validarCPF(cpf) {
                cpf = cpf.replace(/\D/g, '');
                if (cpf.length !== 11) return false;
                if (/^(\d)\1{10}$/.test(cpf)) return false;
                
                let soma = 0;
                for (let i = 0; i < 9; i++) {
                    soma += parseInt(cpf.charAt(i)) * (10 - i);
                }
                let resto = 11 - (soma % 11);
                if (resto === 10 || resto === 11) resto = 0;
                if (resto !== parseInt(cpf.charAt(9))) return false;
                
                soma = 0;
                for (let i = 0; i < 10; i++) {
                    soma += parseInt(cpf.charAt(i)) * (11 - i);
                }
                resto = 11 - (soma % 11);
                if (resto === 10 || resto === 11) resto = 0;
                if (resto !== parseInt(cpf.charAt(10))) return false;
                
                return true;
            }

            function validarCNPJ(cnpj) {
                cnpj = cnpj.replace(/\D/g, '');
                if (cnpj.length !== 14) return false;
                if (/^(\d)\1{13}$/.test(cnpj)) return false;
                
                let tamanho = cnpj.length - 2;
                let numeros = cnpj.substring(0, tamanho);
                let digitos = cnpj.substring(tamanho);
                let soma = 0;
                let pos = tamanho - 7;
                
                for (let i = tamanho; i >= 1; i--) {
                    soma += numeros.charAt(tamanho - i) * pos--;
                    if (pos < 2) pos = 9;
                }
                
                let resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
                if (resultado !== parseInt(digitos.charAt(0))) return false;
                
                tamanho = tamanho + 1;
                numeros = cnpj.substring(0, tamanho);
                soma = 0;
                pos = tamanho - 7;
                
                for (let i = tamanho; i >= 1; i--) {
                    soma += numeros.charAt(tamanho - i) * pos--;
                    if (pos < 2) pos = 9;
                }
                
                resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
                if (resultado !== parseInt(digitos.charAt(1))) return false;
                
                return true;
            }

            function validarEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }

            function validarCelular(celular) {
                celular = celular.replace(/\D/g, '');
                return celular.length === 11 && /^[1-9]\d{10}$/.test(celular);
            }

            function validarChavePix(valor, tipo) {
                switch(tipo) {
                    case 'cpf':
                        return validarCPF(valor);
                    case 'cnpj':
                        return validarCNPJ(valor);
                    case 'email':
                        return validarEmail(valor);
                    case 'telefone':
                        return validarCelular(valor);
                    case 'aleatoria':
                        return valor.length === 36 && /^[a-zA-Z0-9]{36}$/.test(valor);
                    default:
                        return false;
                }
            }

            function mostrarErro(campo, mensagem) {
                $(campo).addClass('is-invalid');
                $('#pixKey-error').text(mensagem).show();
            }

            function limparErro() {
                $('#pixKey').removeClass('is-invalid');
                $('#pixKey-error').hide();
            }

            // Inicializar e monitorar a máscara da chave PIX
            applyMask();
            $('#pixKeyType').change(function() {
                applyMask();
                limparErro();
                $('#pixKey').trigger('input');
            });

            // Validação em tempo real da chave PIX
            $('#pixKey').on('input blur', function() {
                const valor = $(this).val();
                const tipo = $('#pixKeyType').val();
                
                if (!valor) {
                    limparErro();
                    $('#btn-submit-saque').prop('disabled', false);
                    return;
                }

                if (!validarChavePix(valor, tipo)) {
                    let mensagem = '';
                    switch(tipo) {
                        case 'cpf':
                            mensagem = 'CPF inválido';
                            break;
                        case 'cnpj':
                            mensagem = 'CNPJ inválido';
                            break;
                        case 'email':
                            mensagem = 'Email inválido';
                            break;
                        case 'telefone':
                            mensagem = 'Celular inválido';
                            break;
                        case 'aleatoria':
                            mensagem = 'Chave aleatória inválida (deve ter 36 caracteres)';
                            break;
                    }
                    mostrarErro('#pixKey', mensagem);
                    $('#btn-submit-saque').prop('disabled', true);
                } else {
                    limparErro();
                    $('#btn-submit-saque').prop('disabled', false);
                }
            });

            // Validação antes de enviar o formulário
            $('form[action="{{ route('saquePix') }}"]').on('submit', function(e) {
                const valor = $('#pixKey').val();
                const tipo = $('#pixKeyType').val();
                
                if (!validarChavePix(valor, tipo)) {
                    e.preventDefault();
                    $('#pixKey').focus();
                    return false;
                }
            });

            // Função para limpar o valor monetário
            function cleanCurrency(value) {
                if (!value) return 0;
                // Remove "R$ ", remove os pontos de milhar, e troca a vírgula decimal por ponto
                let cleanedValue = value.toString()
                    .replace('R$ ', '')
                    .replace(/\./g, '')  // Regex para remover TODOS os pontos
                    .replace(',', '.');
                return parseFloat(cleanedValue) || 0;
            }

            // Atualiza o valor total em tempo real (Saque)
            $('#amount-saque').on('input', function() {
                let valorDigitado = cleanCurrency($(this).val());
                let saldo = parseFloat("{{ auth()->user()->saldo }}");
                
                let taxaPercentual = parseFloat("{{ $setting->taxa_cash_out }}") / 100;
                
                if (valorDigitado > saldo) {
                    valorDigitado = saldo;
                    let valorFormatado = valorDigitado.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                    $(this).val(valorFormatado);
                }
                
                let taxaCalculada = valorDigitado * taxaPercentual;
                let valorLiquido = valorDigitado - taxaCalculada;

                // Atualiza o campo oculto que será enviado ao backend com o valor bruto
                $('#amount-clean').val(valorDigitado.toFixed(2)); 

                // Atualiza o campo de valor total (líquido) apenas para visualização se necessário
                $('#valor-total').val(valorLiquido.toFixed(2));
            });
            
            // Dispara o evento 'input' no carregamento para garantir que o valor inicial seja processado
            $('#amount-saque').trigger('input');


            // --- LÓGICA DO MODAL DE DEPÓSITO ---

            // Máscara dinâmica CPF
            $("input[id*='cliente-cpf']").inputmask({
                mask: ['999.999.999-99'],
                keepStatic: true
            });

            // Atualiza o valor total e taxa em tempo real (Depósito)
            $('#amount-deposit').on('input', function() {
                let valor = parseFloat($(this).val().replace(',', '.'));

                let valorMinimo = Number("{{ $setting->valor_min_deposito }}");
                let taxaPercentual = Number("{{ $setting->taxa_cash_in }}");
                let taxaFixa = Number("{{ $setting->taxa_fixa }}");
                let taxaFixaAtiva = taxaFixa > 0;
                let splitAtivo = "{{ $split_ativo }}";
                let taxaSplit = 0;

                if (valor < valorMinimo || isNaN(valor)) {
                    valor = valorMinimo;
                    $(this).val(valor.toFixed(2).replace('.', ','));
                }

                let taxaTotal = (valor * taxaPercentual / 100);
                if (taxaFixaAtiva) {
                    taxaTotal += taxaFixa;
                }

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
                let valorFinal = valor - taxaTotal;

                $('#taxa-deposito').val('R$ ' + taxaTotal.toFixed(2).replace('.', ','));
                $('#valor-total-deposito').val('R$ ' + valorFinal.toFixed(2).replace('.', ','));
            });

        });
    </script>

    @if (session('qrcode'))
        @push('modals')
        <div class="modal fade" id="modal-qrcode" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
            data-bs-keyboard="false" style="z-index: 1060;">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pagamento PIX</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ session('qr_code_image_url') }}" alt="QR Code" class="img-fluid mb-3">
                        <p style="font-size: 36px">Valor: <strong style="color:green;">{{ session('amount') }}</strong>
                        </p>

                        <input id="qrcode-input" value="{{ session('qrcode') }}" class="form-control mb-3" readonly>

                        <button id="copy-btn" class="btn btn-primary w-100">Copiar chave</button>
                    </div>
                </div>
            </div>
        </div>
        @endpush

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = new bootstrap.Modal(document.getElementById('modal-qrcode'));
                modal.show();

                document.getElementById('copy-btn').addEventListener('click', function() {
                    const input = document.getElementById('qrcode-input');
                    input.select();
                    document.execCommand('copy');
                    showToast('success', 'Chave copiada para a área de transferência!');
                });
            });
        </script>
    @endif
@endsection