@extends('layouts.app')

@section('title', 'ConfigTaxas e Valoresurações')

@section('content')
    <div class="header mb-3">
        <h1 class="header-title">
            Taxas e Valores
        </h1>
    </div>
    <form class="row" method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body m-0 p-1">
                    <ul class="nav nav-pills nav-config-taxas" id="configTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pix-tab" data-bs-toggle="tab" data-bs-target="#pix"
                                type="button" role="tab">PIX</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="boleto-tab" data-bs-toggle="tab" data-bs-target="#boleto"
                                type="button" role="tab">Boleto</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="cartao-tab" data-bs-toggle="tab" data-bs-target="#cartao"
                                type="button" role="tab">Cartão</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reserva-tab" data-bs-toggle="tab" data-bs-target="#reserva"
                                type="button" role="tab">Reserva financeira</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="igaming-tab" data-bs-toggle="tab" data-bs-target="#igaming"
                                type="button" role="tab">Igaming</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button"
                                role="tab">Info produto</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>


        <div class="tab-content" id="tabConfigContent">
            <!-- PIX -->
            <div class="tab-pane fade show active" id="pix" role="tabpanel" aria-labelledby="pix-tab">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title fs-3">Alterar taxas de PIX</h5>
                        <div class="badge w-100 py-3 fs-5"
                            style="background: var(--gateway-opacity);color:var(--gateway-primary-color);">
                            Esses valores serão aplicados para todos os clientes que não tem uma taxa específica (você pode
                            alterar a taxa especifica no perfil do cliente)
                        </div>
                    </div>
                    <div class="card-body">

                        <table class="table table-config-taxas">
                            <thead>
                                <tr>
                                    <th scope="col">Depósitos</th>
                                    <th scope="col">Gerais</th>
                                    <th scope="col">Saques</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="form-floating mb-3">
                                            <input autofocus type="text" class="form-control percent-input"
                                                id="taxa_cash_in" name="taxa_cash_in" value="{{ $settings->taxa_cash_in }}">
                                            <label for="taxa_cash_in">Taxa %</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control money-input text-end" id="baseline"
                                                name="baseline" value="{{ $settings->baseline }}" data-symbol="R$ "
                                                data-thousands="." data-decimal=",">
                                            <label for="baseline">Baseline</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control percent-input" id="taxa_cash_out"
                                                name="taxa_cash_out" value="{{ $settings->taxa_cash_out }}">
                                            <label for="taxa_cash_out">Taxa %</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control money-input text-end"
                                                id="taxa_cash_in_fixa" name="taxa_cash_in_fixa"
                                                value="{{ number_format($settings->taxa_cash_in_fixa, 2) }}"
                                                data-symbol="R$ " data-thousands="." data-decimal=",">
                                            <label for="taxa_cash_in_fixa">Taxa R$</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control percent-input" id="taxa_reserva"
                                                name="taxa_reserva"
                                                value="{{ number_format($settings->taxa_reserva, 2) ?? 0.00 }}">
                                            <label for="taxa_reserva">Reserva</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control money-input text-end"
                                                id="taxa_cash_out_fixa" name="taxa_cash_out_fixa"
                                                value="{{ $settings->taxa_cash_out_fixa ?? 0.00 }}" data-symbol="R$ "
                                                data-thousands="." data-decimal=",">
                                            <label for="taxa_cash_out_fixa">Taxa R$</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control money-input text-end"
                                                id="deposito_maximo" name="deposito_maximo"
                                                value="{{ $settings->deposito_maximo ?? 0.00 }}" data-symbol="R$ "
                                                data-thousands="." data-decimal=",">
                                            <label for="deposito_maximo">Deposito máximo</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control number-input" id="saques_dia"
                                                name="saques_dia" value="{{ $settings->saques_dia ?? 0.00 }}">
                                            <label for="saques_dia">Quantidade de saques dia</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control money-input text-end" id="saque_maximo"
                                                name="saque_maximo" value="{{ $settings->saque_maximo ?? 0.00 }}"
                                                data-symbol="R$ " data-thousands="." data-decimal=",">
                                            <label for="saque_maximo">Saque máximo</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control money-input text-end"
                                                id="deposito_minimo" name="deposito_minimo"
                                                value="{{ $settings->deposito_minimo ?? 0.00 }}" data-symbol="R$ "
                                                data-thousands="." data-decimal=",">
                                            <label for="deposito_minimo">Deposito mínimo</label>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control money-input text-end" id="saque_minimo"
                                                name="saque_minimo" value="{{ $settings->saque_minimo ?? 0.00 }}"
                                                data-symbol="R$ " data-thousands="." data-decimal=",">
                                            <label for="saque_minimo">Saque mínimo</label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Boleto -->
            <div class="tab-pane fade" id="boleto" role="tabpanel" aria-labelledby="boleto-tab">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title fs-3">Alterar taxas de boleto</h5>
                        <!-- <div class="badge w-100 py-3 fs-5"
                                        style="background: var(--gateway-opacity);color:var(--gateway-primary-color);">
                                        Esses valores serão aplicados para todos os clientes que não tem uma taxa específica (você pode
                                        alterar a taxa especifica no perfil do cliente)
                                    </div> -->
                    </div>
                    <div class="card-body">
                        <div class="row mb-3" style="border-bottom: 1px solid rgba(0,0,0,0.1)">
                            <div class="col-12 mb-3">
                                <h5 class="card-title fs-4">Liberação e Retenção</h5>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="form-floating">
                                    <input autofocus type="text" class="form-control dias-input text-start"
                                        id="boleto_liberacao_dias" name="billet_days_to_release"
                                        value="{{ $settings->billet_days_to_release ?? 0 }}">
                                    <label for="billet_days_to_release">Quantidade de dias para liberação do valor</label>
                                </div>
                                <small>Quantidade de dias que a venda ficará pendente</small>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="form-floating">
                                    <input autofocus type="text" class="form-control percent-input text-start"
                                        id="billet_taxa_percent" name="billet_taxa_percent"
                                        value="{{ $settings->billet_taxa_percent ?? 0 }}">
                                    <label for="billet_taxa_percent">Taxa em porcentagem</label>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="form-floating">
                                    <input autofocus type="text" class="form-control money-input text-start"
                                        id="billet_taxa_fixed" name="billet_taxa_fixed"
                                        value="{{ $settings->billet_taxa_fixed ?? 0 }}">
                                    <label for="billet_taxa_fixed">Taxa fixa</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cartao -->
            <div class="tab-pane fade" id="cartao" role="tabpanel" aria-labelledby="cartao-tab">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title fs-3">Alterar taxas de cartão</h5>
                        <!-- <div class="badge w-100 py-3 fs-5"
                                        style="background: var(--gateway-opacity);color:var(--gateway-primary-color);">
                                        Esses valores serão aplicados para todos os clientes que não tem uma taxa específica (você pode
                                        alterar a taxa especifica no perfil do cliente)
                                    </div> -->
                    </div>
                    <div class="card-body">

                        <div class="row mb-3" style="border-bottom: 1px solid rgba(0,0,0,0.1)">
                            <div class="col-12 mb-3">
                                <h5 class="card-title fs-4">Liberação e Retenção</h5>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="form-floating">
                                    <input autofocus type="text" class="form-control dias-input text-start"
                                        id="cartao_liberacao_dias" name="card_days_to_release"
                                        value="{{ $settings->card_days_to_release ?? 0 }}">
                                    <label for="card_days_to_release">Quantidade de dias para liberação do valor</label>
                                </div>
                                <small>Quantidade de dias que a venda ficará pendente</small>
                            </div>

                        </div>
                        <div class="row mb-3" style="border-bottom: 1px solid rgba(0,0,0,0.1)">
                            <div class="col-12 mb-3">
                                <h5 class="card-title fs-4">Opções de antecipação</h5>
                            </div>

                            <div class="col-6 mb-3">
                                <div class="form-floating">
                                    <input autofocus type="text" class="form-control dias-input text-start"
                                        id="card_days_to_anticipation_opt1" name="card_days_to_anticipation_opt1"
                                        value="{{ $settings->card_days_to_anticipation_opt1 ?? 0 }}">
                                    <label for="card_days_to_anticipation_opt1">Dias de antecipação</label>
                                </div>
                                <small>Quantidade de dias que a venda ficará pendente</small>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="form-floating">
                                    <input autofocus type="text" class="form-control percent-input text-start"
                                        id="card_tx_to_anticipation_opt1" name="card_tx_to_anticipation_opt1"
                                        value="{{ $settings->card_tx_to_anticipation_opt1 ?? 0 }}">
                                    <label for="card_tx_to_anticipation_opt1">Taxa de antecipação D+ <span
                                            class="days-anticipation-opt1">0</span></label>
                                </div>
                                <small>Taxa cobrada para antecipação com <span class="days-anticipation-opt1">0</span>
                                    dias</small><br>
                                <small class="text-warning">Se definido como 0 desativa a opção</small>
                            </div>

                            <div class="col-6 mb-3">
                                <div class="form-floating">
                                    <input autofocus type="text" class="form-control dias-input text-start"
                                        id="card_days_to_anticipation_opt2" name="card_days_to_anticipation_opt2"
                                        value="{{ $settings->card_days_to_anticipation_opt2 ?? 0 }}">
                                    <label for="card_days_to_anticipation_opt2">Dias de antecipação</label>
                                </div>
                                <small>Quantidade de dias que a venda ficará pendente</small>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="form-floating">
                                    <input autofocus type="text" class="form-control percent-input text-start"
                                        id="card_tx_to_anticipation_opt2" name="card_tx_to_anticipation_opt2"
                                        value="{{ $settings->card_tx_to_anticipation_opt2 ?? 0 }}">
                                    <label for="card_tx_to_anticipation_opt2">Taxa de antecipação D+ <span
                                            class="days-anticipation-opt2">0</span></label>
                                </div>
                                <small>Taxa cobrada para antecipação com <span class="days-anticipation-opt2">0</span>
                                    dia</small><br>
                                <small class="text-warning">Se definido como 0 desativa a opção</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reserva financeira -->
            <div class="tab-pane fade" id="reserva" role="tabpanel" aria-labelledby="reserva-tab">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title fs-3">Alterar taxa da reserva financeira</h5>
                        <div class="badge w-100 py-3 fs-5"
                            style="background: var(--gateway-opacity);color:var(--gateway-primary-color);">
                            Esses valores serão aplicados para todos os clientes.
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3" style="border-bottom: 1px solid rgba(0,0,0,0.1)">
                            <div class="col-12 col-lg-6 mb-3">
                                <div class="form-floating">
                                    <input autofocus type="text" class="form-control dias-input text-start"
                                        id="dias_liberar_reserva" name="dias_liberar_reserva"
                                        value="{{ $settings->dias_liberar_reserva ?? 0 }}">
                                    <label for="dias_liberar_reserva">Quantidade de dias para liberação do valor</label>
                                </div>
                                <small>Quantidade de dias que ficará retido</small>
                            </div>
                            <div class="col-12 col-lg-6 mb-3">
                                <div class="form-floating">
                                    <input autofocus type="text" class="form-control percent-input text-start"
                                        id="taxa_reserva" name="taxa_reserva" value="{{ $settings->taxa_reserva ?? 0 }}">
                                    <label for="taxa_reserva">Taxa reserva</label>
                                </div>
                                <small class="text-warning">Se definido como 0 desativa a opção</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Igaming -->
            <div class="tab-pane fade" id="igaming" role="tabpanel" aria-labelledby="igaming-tab">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title fs-3">Alterar taxas de igaming</h5>
                        <div class="badge w-100 py-3 fs-5"
                            style="background: var(--gateway-opacity);color:var(--gateway-primary-color);">
                            Esses valores serão aplicados para todos os clientes que não tem uma taxa específica (você
                            pode alterar a taxa especifica no perfil do cliente)
                        </div>
                    </div>
                    <div class="card-body">

                    </div>
                </div>
            </div>

            <!-- Info Produtos -->
            <div class="tab-pane fade" id="info" role="tabpanel" aria-labelledby="info-tab">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title fs-3">Alterar taxas de info produtos</h5>
                        <div class="badge w-100 py-3 fs-5"
                            style="background: var(--gateway-opacity);color:var(--gateway-primary-color);">
                            Esses valores serão aplicados para todos os clientes que não tem uma taxa específica (você
                            pode alterar a taxa especifica no perfil do cliente)
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-xl-4">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control money-input text-end" id="valor_minimo_produto"
                                        name="valor_minimo_produto" value="{{ $settings->valor_minimo_produto ?? 0.00 }}" data-symbol="R$ "
                                        data-thousands="." data-decimal=",">
                                    <label for="valor_minimo_produto">Valor mínimo para produtos</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-end">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#salvarModal"><i class="fa-solid fa-save"></i>&nbsp;Salvar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="salvarModal" tabindex="-1" aria-labelledby="salvarModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="salvarModalLabel">Salvar Taxas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-danger">Caso clique em 'Salvar', as taxas só serão aplicadas a novos registros!
                        </p>
                        <p class="text-danger">Clicando em 'Salvar e aplicar a todos os usuários', as taxas serão
                            alteradas e aplicadas a todos os usuários cadastrados!</p>
                    </div>
                    <div class="modal-footer">
                        <input id="type" hidden name="type" value="salvar">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i>&nbsp;Salvar</button>
                        <button type="submit" class="btn btn-primary"
                            onclick="document.getElementById('type').value = 'aplicar';this.form.submit();"><i
                                class="fa-solid fa-rotate"></i>&nbsp;Salvar e aplicar a todos os usuários</button>
                    </div>
                </div>
            </div>
        </div>

    </form>

    <script>
        $(".money-input").maskMoney('mask');
        $(function () {
            $(".money-input").maskMoney({
                prefix: 'R$ ',
                allowNegative: true,
                thousands: '.',
                decimal: ',',
                affixesStay: false
            });
        });

        // Máscara para campos em % (percentual)
        $(".percent-input").inputmask({
            alias: "decimal",
            suffix: " %",
            digits: 2,
            max: 100,
            allowMinus: false,
            digitsOptional: true,
            radixPoint: ".",
            placeholder: "",
            removeMaskOnSubmit: true
        });

        $(".dias-input").inputmask({
            alias: "decimal",
            prefix: "D+ ",
            digits: 3,
            max: 100,
            allowMinus: false,
            digitsOptional: true,
            radixPoint: ".",
            placeholder: "",
            removeMaskOnSubmit: true
        });

        // Máscara para campos apenas com números inteiros
        $(".number-input").inputmask({
            alias: "integer",
            allowMinus: false,
            placeholder: "",
            removeMaskOnSubmit: true
        });

        $('form').on('submit', function () {
            $('.money-input').each(function () {
                const unmaskedValue = $(this).maskMoney('unmasked')[0]; // retorna número como float
                $(this).val(unmaskedValue);
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            function bindInputMask(inputSelector, targetClass) {
                $(inputSelector).each(function () {
                    const $input = $(this);
                    const targets = document.querySelectorAll('.' + targetClass);

                    const updateText = () => {
                        const value = $input.inputmask('unmaskedvalue') || 0;
                        targets.forEach(el => el.innerText = value);
                    };

                    // Inicializa com valor atual
                    updateText();

                    // Evento do Inputmask
                    $input.on('inputmaskcomplete', updateText);
                    $input.on('keyup', updateText); // opcional para atualizar em tempo real enquanto digita
                });
            }

            bindInputMask('#card_days_to_anticipation_opt1', 'days-anticipation-opt1');
            bindInputMask('#card_days_to_anticipation_opt2', 'days-anticipation-opt2');
        });
    </script>
@endsection