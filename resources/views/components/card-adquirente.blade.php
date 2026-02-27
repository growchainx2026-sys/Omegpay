@props([
    'image' => null,
    'data' => null,
    'name' => null,
    'id' => uniqid(),
    'title' => '',
    'background' => 'black',
])

<div class=" card mb-3 w-100" style="border: 10px solid 'gray !important'">
    <div class="row g-0 w-100">
        <div class="col-12 d-flex align-items-center justify-content-center p-2 girar"
            style="background: {{ $background }}; border-radius: 6px;height: 80px;">
            <img src="{{ $image }}" class="img-fluid " alt="Integração {{ $title }}"
                style="height: 40px; width:auto;object-fit:contain;">
        </div>
        <div class="col-12 w-100">
            <div class="card-body w-100 p-0 pt-2">
                @if ($name == 'efi')
                    <div class="d-flex align-items justify-content-between gap-1">
                        <a class="text-primary w-100" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#menu-{{ $id }}" aria-controls="menu-{{ $id }}">
                            <button class="btn btn-primary w-100" style="width:100%;color: white !important;"
                                type="submit">Editar</button>
                        </a>
                        <button class="btn btn-primary w-100" {{ $data->client_id ? '' : 'disabled' }}
                            style="width:100%;color: white !important;" type="button" id="register-webhook-efi"
                            onclick="registerWebhookEfi()">Registrar webhook</button>
                    </div>
                @elseif ($name == 'witetec')
                    <div class="d-flex align-items justify-content-between gap-1">
                        <a class="text-primary w-100" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#menu-{{ $id }}" aria-controls="menu-{{ $id }}">
                            <button class="btn btn-primary w-100" style="width:100%;color: white !important;"
                                type="submit">Editar</button>
                        </a>
                        <button class="btn btn-primary w-100" {{ $data->api_token ? '' : 'disabled' }}
                            style="width:100%;color: white !important;" type="button" id="register-webhook-witetec"
                            onclick="registerWebhookWitetec()">Registrar webhook</button>
                    </div>
                @else
                    <a class="text-primary w-100" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#menu-{{ $id }}" aria-controls="menu-{{ $id }}">
                        <button class="btn btn-primary w-100" style="width:100%;color: white !important;"
                            type="submit">Editar</button>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="menu-{{ $id }}"
    aria-labelledby="menu-{{ $id }}Label">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="menu-{{ $id }}Label">AJUSTES - {{ $title }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        @if ($id == 'sixxpayments')
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="sixxpayments.secret" name="sixxpayments.secret"
                        value="{{ $data->secret }}">
                    <label for="sixxpayments.taxa_cash_in">Authorization Key</label>
                </div>
            </div>

            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="sixxpayments.taxa_cash_in"
                        name="sixxpayments.taxa_cash_in" value="{{ $data->taxa_cash_in }}">
                    <label for="sixxpayments.taxa_cash_in">Taxa de depósito (%)</label>
                    <small>Taxa paga ao adquirente em transações de entrada (pix-in)</small>
                </div>
            </div>

            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="sixxpayments.taxa_cash_out"
                        name="sixxpayments.taxa_cash_out" value="{{ $data->taxa_cash_out }}">
                    <label for="sixxpayments.taxa_cash_out">Taxa de saque (%)</label>
                    <small>Taxa paga ao adquirente em transações de entrada (pix-out)</small>
                </div>
            </div>
        @elseif ($id == 'transfeera')
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="transfeera.token" name="transfeera.token"
                        value="{{ $data->token }}">
                    <label for="transfeera.token">Token</label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="transfeera.secret" name="transfeera.secret"
                        value="{{ $data->secret }}">
                    <label for="transfeera.secret">Secret</label>
                </div>
            </div>


            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="transfeera.tenant_name"
                        name="transfeera.tenant_name" value="{{ $data->tenant_name }}">
                    <label for="transfeera.tenant_name">Tenant Name</label>
                </div>
            </div>

            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="transfeera.tenant_email"
                        name="transfeera.tenant_email" value="{{ $data->tenant_email }}">
                    <label for="transfeera.tenant_email">Tenant Email</label>
                </div>
            </div>

            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="transfeera.tenant_keypix"
                        name="transfeera.tenant_keypix" value="{{ $data->tenant_keypix }}">
                    <label for="transfeera.tenant_keypix">Tenant Keypix</label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="transfeera.taxa_cash_in"
                        name="transfeera.taxa_cash_in" value="{{ $data->taxa_cash_in }}">
                    <label for="transfeera.taxa_cash_in">Taxa de depósito (%)</label>
                    <small>Taxa paga ao adquirente em transações de entrada (pix-in)</small>
                </div>
            </div>

            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="transfeera.taxa_cash_out"
                        name="transfeera.taxa_cash_out" value="{{ $data->taxa_cash_out }}">
                    <label for="transfeera.taxa_cash_out">Taxa de saque (%)</label>
                    <small>Taxa paga ao adquirente em transações de entrada (pix-out)</small>
                </div>
            </div>
        @elseif ($id == 'cashtime')
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="cashtime.secret" name="cashtime.secret"
                        value="{{ $data->secret }}">
                    <label for="cashtime.taxa_cash_in">Authorization Key</label>
                </div>
            </div>

            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="cashtime.taxa_cash_in"
                        name="cashtime.taxa_cash_in" value="{{ $data->taxa_cash_in }}">
                    <label for="cashtime.taxa_cash_in">Taxa de depósito (%)</label>
                    <small>Taxa paga ao adquirente em transações de entrada (pix-in)</small>
                </div>
            </div>

            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="cashtime.taxa_cash_out"
                        name="cashtime.taxa_cash_out" value="{{ $data->taxa_cash_out }}">
                    <label for="cashtime.taxa_cash_out">Taxa de saque (%)</label>
                    <small>Taxa paga ao adquirente em transações de saída (pix-out)</small>
                </div>
            </div>
        @elseif ($id == 'efi')
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="efi.client_id" name="efi.client_id"
                        value="{{ $data->client_id }}">
                    <label for="efi.client_id">Client ID</label>
                </div>
            </div>

            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="efi.client_secret" name="efi.client_secret"
                        value="{{ $data->client_secret }}">
                    <label for="efi.client_secret">Client secret</label>
                </div>
            </div>

            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="efi.chave_pix" name="efi.chave_pix"
                        value="{{ $data->chave_pix }}">
                    <label for="efi.chave_pix">Chave PIX</label>
                </div>
            </div>

            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="efi.identificador_conta" name="efi.identificador_conta"
                        value="{{ $data->identificador_conta }}">
                    <label for="efi.identificador_conta">Identificador de conta</label>
                </div>
            </div>

            <div class="col-xl-12">
                <label for="input-cert" class="form-label">Certificado</label>
                <input id="input-cert" type="file"
                    class="filepond form-control @error('efi.cert') is-invalid @enderror" name="efi.cert" hidden
                    value="{{ $data->cert }}">
                <br />
                <button id="bt-add-cert" type="button" class="w-100 btn btn-success mb-3"
                    onclick="adcionarCertificado()">Selecionar certificado</button>
                <small style="display: none;" class="text-success">Certificado selecionado</small>
                @error('cert')
                    <span style="color: red;">{{ $message }}</span>
                @enderror
            </div>

            <div class="row g-3 my-3 mx-1" style="border: 1px solid #cececeff;border-radius: 8px;">
                <div class="col-12">
                    Taxas (PIX)
                </div>
                <div class="col-12">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="efi.taxa_pix_cash_in" name="efi.taxa_pix_cash_in"
                            value="{{ $data->taxa_pix_cash_in }}">
                        <label for="efi.taxa_pix_cash_in">Taxa de depósito Pix (%)</label>
                        <small>Taxa paga ao adquirente em transações de entrada (pix-in)</small>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="efi.taxa_pix_cash_out"
                            name="efi.taxa_pix_cash_out" value="{{ $data->taxa_pix_cash_out }}">
                        <label for="efi.taxa_pix_cash_out">Taxa de saque (%)</label>
                        <small>Taxa paga ao adquirente em transações de saída (pix-out)</small>
                    </div>
                </div>
            </div>

            <div class="row g-3 my-3 mx-1" style="border: 1px solid #cececeff;border-radius: 8px;">
                <div class="col-12">
                    Taxas (Boleto)
                </div>
                <div class="col-12">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="efi.billet_tx_percent" name="efi.billet_tx_percent"
                            value="{{ $data->billet_tx_percent }}">
                        <label for="efi.billet_tx_percent">Taxa (%)</label>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="efi.billet_tx_fixed"
                            name="efi.billet_tx_fixed" value="{{ $data->billet_tx_fixed }}">
                        <label for="efi.billet_tx_fixed">Taxa Fixa (R$)</label>
                    </div>
                </div>
            </div>
            <div class="row g-3 my-3 mx-1" style="border: 1px solid #cececeff;border-radius: 8px;">
                <div class="col-12">
                    Taxas (Cartão)
                </div>
                <div class="col-12">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="efi.card_tx_percent" name="efi.card_tx_percent"
                            value="{{ $data->card_tx_percent }}">
                        <label for="efi.card_tx_percent">Taxa (%)</label>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="efi.card_tx_fixed"
                            name="efi.card_tx_fixed" value="{{ $data->card_tx_fixed }}">
                        <label for="efi.card_tx_fixed">Taxa Fixa (R$)</label>
                    </div>
                </div>
            </div>
        @elseif ($id == 'witetec')
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="witetec.api_token" name="witetec.api_token"
                        value="{{ $data->api_token }}">
                    <label for="witetec.api_token">API Token</label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="witetec.taxa_cash_in" name="witetec.taxa_cash_in"
                        value="{{ $data->taxa_cash_in }}">
                    <label for="witetec.taxa_cash_in">Taxa de depósito Pix (%)</label>
                    <small>Taxa paga ao adquirente em transações de entrada (pix-in)</small>
                </div>
            </div>

            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="witetec.taxa_cash_out"
                        name="witetec.taxa_cash_out" value="{{ $data->taxa_cash_out }}">
                    <label for="witetec.taxa_cash_out">Taxa de saque (%)</label>
                    <small>Taxa paga ao adquirente em transações de saída (pix-out)</small>
                </div>
            </div>
        @elseif ($id == 'xgate')
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="xgate.email" name="xgate_email"
                        value="{{ $data->email ?? '' }}" placeholder="email@exemplo.com">
                    <label for="xgate.email">E-mail</label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="xgate.senha" name="xgate_senha"
                        value="" placeholder="Deixe em branco para não alterar">
                    <label for="xgate.senha">Senha</label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="xgate.taxa_cash_in" name="xgate_taxa_cash_in"
                        value="{{ $data->taxa_cash_in ?? '' }}">
                    <label for="xgate.taxa_cash_in">Taxa de depósito (%)</label>
                    <small>Taxa paga ao adquirente em transações de entrada (pix-in)</small>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="xgate.taxa_cash_out" name="xgate_taxa_cash_out"
                        value="{{ $data->taxa_cash_out ?? '' }}">
                    <label for="xgate.taxa_cash_out">Taxa de saque (%)</label>
                    <small>Taxa paga ao adquirente em transações de saída (pix-out)</small>
                </div>
            </div>
        @elseif ($id == 'getpay')
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="getpay.url_base" name="getpay_url_base"
                        value="{{ $data->url_base ?? 'https://api.getpay.one/api' }}">
                    <label for="getpay.url_base">URL Base</label>
                    <small>Ex: https://api.getpay.one/api</small>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="getpay.client_id" name="getpay_client_id"
                        value="{{ $data->client_id ?? '' }}">
                    <label for="getpay.client_id">Client ID (API V2)</label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="getpay.client_secret" name="getpay_client_secret"
                        value="{{ $data->client_secret ?? '' }}" placeholder="Deixe em branco para não alterar">
                    <label for="getpay.client_secret">Client Secret (API V2)</label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="getpay.webhook_token_deposit" name="getpay_webhook_token_deposit"
                        value="{{ $data->webhook_token_deposit ?? '' }}">
                    <label for="getpay.webhook_token_deposit">Token Secreto (Webhook IN - Depósitos)</label>
                    <small>Configure no painel GetPay o callback: {{ url('api/getpay/callback/deposit') }}</small>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="getpay.webhook_token_withdraw" name="getpay_webhook_token_withdraw"
                        value="{{ $data->webhook_token_withdraw ?? '' }}">
                    <label for="getpay.webhook_token_withdraw">Token Secreto (Webhook OUT - Saques)</label>
                    <small>Configure no painel GetPay o callback: {{ url('api/getpay/callback/withdraw') }}</small>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="getpay.taxa_cash_in" name="getpay_taxa_cash_in"
                        value="{{ $data->taxa_cash_in ?? '' }}">
                    <label for="getpay.taxa_cash_in">Taxa de depósito (%)</label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="getpay.taxa_cash_out" name="getpay_taxa_cash_out"
                        value="{{ $data->taxa_cash_out ?? '' }}">
                    <label for="getpay.taxa_cash_out">Taxa de saque (%)</label>
                </div>
            </div>
        @elseif ($id == 'rapdyn')
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="rapdyn.url_base" name="rapdyn_url_base"
                        value="{{ $data->url_base ?? 'https://app.rapdyn.io/api' }}">
                    <label for="rapdyn.url_base">URL Base</label>
                    <small>Padrão: https://app.rapdyn.io/api</small>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="rapdyn.api_token" name="rapdyn_api_token"
                        value="{{ $data->api_token ?? '' }}" placeholder="Token da Integração">
                    <label for="rapdyn.api_token">Token (Integrações)</label>
                    <small>Cole o token gerado em Integrações → Nova integração no painel Rapdyn</small>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="rapdyn.webhook_token_deposit" name="rapdyn_webhook_token_deposit"
                        value="{{ $data->webhook_token_deposit ?? '' }}">
                    <label for="rapdyn.webhook_token_deposit">Token Secreto (Webhook IN - Depósitos)</label>
                    <small>Configure no painel Rapdyn o callback: {{ url('api/rapdyn/callback/deposit') }}</small>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="rapdyn.webhook_token_withdraw" name="rapdyn_webhook_token_withdraw"
                        value="{{ $data->webhook_token_withdraw ?? '' }}">
                    <label for="rapdyn.webhook_token_withdraw">Token Secreto (Webhook OUT - Saques)</label>
                    <small>Configure no painel Rapdyn o callback: {{ url('api/rapdyn/callback/withdraw') }}</small>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="rapdyn.taxa_cash_in" name="rapdyn_taxa_cash_in"
                        value="{{ $data->taxa_cash_in ?? '' }}">
                    <label for="rapdyn.taxa_cash_in">Taxa de depósito (%)</label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="rapdyn.taxa_cash_out" name="rapdyn_taxa_cash_out"
                        value="{{ $data->taxa_cash_out ?? '' }}">
                    <label for="rapdyn.taxa_cash_out">Taxa de saque (%)</label>
                </div>
            </div>
        @elseif ($id == 'getpay2')
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="getpay2.url_base" name="getpay2_url_base"
                        value="{{ $data->url_base ?? 'https://api.getpay.one/api' }}">
                    <label for="getpay2.url_base">URL Base</label>
                    <small>Ex: https://api.getpay.one/api</small>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="getpay2.client_id" name="getpay2_client_id"
                        value="{{ $data->client_id ?? '' }}">
                    <label for="getpay2.client_id">Client ID (API V2)</label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="getpay2.client_secret" name="getpay2_client_secret"
                        value="{{ $data->client_secret ?? '' }}" placeholder="Deixe em branco para não alterar">
                    <label for="getpay2.client_secret">Client Secret (API V2)</label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="getpay2.webhook_token_deposit" name="getpay2_webhook_token_deposit"
                        value="{{ $data->webhook_token_deposit ?? '' }}">
                    <label for="getpay2.webhook_token_deposit">Token Secreto (Webhook IN - Depósitos)</label>
                    <small>Configure no painel GetPay o callback: {{ url('api/getpay2/callback/deposit') }}</small>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="getpay2.webhook_token_withdraw" name="getpay2_webhook_token_withdraw"
                        value="{{ $data->webhook_token_withdraw ?? '' }}">
                    <label for="getpay2.webhook_token_withdraw">Token Secreto (Webhook OUT - Saques)</label>
                    <small>Configure no painel GetPay o callback: {{ url('api/getpay2/callback/withdraw') }}</small>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="getpay2.taxa_cash_in" name="getpay2_taxa_cash_in"
                        value="{{ $data->taxa_cash_in ?? '' }}">
                    <label for="getpay2.taxa_cash_in">Taxa de depósito (%)</label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="getpay2.taxa_cash_out" name="getpay2_taxa_cash_out"
                        value="{{ $data->taxa_cash_out ?? '' }}">
                    <label for="getpay2.taxa_cash_out">Taxa de saque (%)</label>
                </div>
            </div>
        @elseif ($id == 'pagarme')
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="pagarme.secret" name="pagarme.secret"
                        value="{{ $data->secret }}">
                    <label for="pagarme.secret">Chave Secreta</label>
                </div>
            </div>
            <div class="row g-3 my-3 mx-1" style="border: 1px solid #cececeff;border-radius: 8px;">
                <div class="col-12">
                    Taxas (PIX)
                </div>
                <div class="col-12">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="pagarme.tx_pix_cash_in" name="pagarme.tx_pix_cash_in"
                            value="{{ $data->tx_pix_cash_in }}">
                        <label for="pagarme.tx_pix_cash_in">Taxa de depósito Pix (%)</label>
                        <small>Taxa paga ao adquirente em transações de entrada (pix-in)</small>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="pagarme.tx_pix_cash_out"
                            name="pagarme.tx_pix_cash_out" value="{{ $data->tx_pix_cash_out }}">
                        <label for="pagarme.tx_pix_cash_out">Taxa de saque (%)</label>
                        <small>Taxa paga ao adquirente em transações de saída (pix-out)</small>
                    </div>
                </div>
            </div>
            <div class="row g-3 my-3 mx-1" style="border: 1px solid #cececeff;border-radius: 8px;">
                <div class="col-12">
                    Taxas (Cartão)
                </div>
                @foreach (['1x','2x','3x','4x','5x','6x','7x','8x','9x','10x','11x','12x'] as $parcela)
                    <div class="col-4">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="pagarme.{{ $parcela }}"
                                name="pagarme.{{ $parcela }}" value="{{ $data->$parcela }}">
                            <label for="pagarme.{{ $parcela }}">{{ $parcela }} (%)</label>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row g-3 my-3 mx-1" style="border: 1px solid #cececeff;border-radius: 8px;">
                <div class="col-12">
                    Taxas (Boleto)
                </div>
                <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="pagarme.tx_billet_percent"
                        name="pagarme.tx_billet_percent" value="{{ $data->tx_billet_percent }}">
                    <label for="pagarme.tx_billet_percent">Porcentagem (%)</label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="pagarme.tx_billet_fixed"
                        name="pagarme.tx_billet_fixed" value="{{ $data->tx_billet_fixed }}">
                    <label for="pagarme.tx_billet_fixed">Fixa (R$)</label>
                </div>
            </div>
            </div>
        @elseif ($id == 'stripe')
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="stripe.public_key" name="stripe.public_key"
                        value="{{ $data->public_key }}">
                    <label for="stripe.public_key">Chave pública</label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="stripe.secret_key" name="stripe.secret_key"
                        value="{{ $data->secret_key }}">
                    <label for="stripe.secret_key">Chave secreta</label>
                </div>
            </div>

            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="stripe.tx_card_percent"
                        name="stripe.tx_card_percent" value="{{ $data->tx_card_percent }}">
                    <label for="stripe.tx_card_percent">Taxa de Cartão (%)</label>
                </div>
            </div>

            <div class="col-12">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="stripe.tx_card_fixed"
                        name="stripe.tx_card_fixed" value="{{ $data->tx_card_fixed }}">
                    <label for="stripe.tx_card_fixed">Taxa de Cartão (R$)</label>
                </div>
            </div>
        @endif
        <button type="submit" class="btn btn-primary w-100">Atualizar</button>
    </div>
</div>
<script>
    function registerWebhookEfi() {
        let btnRegisterWebhookEfi = document.getElementById('register-webhook-efi');
        if (btnRegisterWebhookEfi) {
            btnRegisterWebhookEfi.setAttribute('disabled', true);
            btnRegisterWebhookEfi.innerHTML = `
                <div class="spinner-border text-white" style="width:12px;height:12px;" role="status">
                <span class="visually-hidden">Loading...</span>
                </div>&nbsp;Registrando...
            `;
        }

        fetch('/api/efi/register-webhook', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content // se for Laravel
                },
                body: JSON.stringify({})
            })
            .then((res) => res.json())
            .then((res) => {
                if (res.status) {
                    showToast('success', 'Webhook registrado com sucesso');
                } else {
                    showToast('error', 'Falha ao registrar webhook');
                }
            })
            .catch(() => {
                showToast('error', 'Erro na comunicação com o servidor');
            })
            .finally(() => {
                btnRegisterWebhookEfi.innerHTML = "Registrar webhook";
                btnRegisterWebhookEfi.removeAttribute('disabled');
            });
    }

    function registerWebhookWitetec() {
        let btnRegisterWebhookWitetec = document.getElementById('register-webhook-witetec');
        if (btnRegisterWebhookWitetec) {
            btnRegisterWebhookWitetec.setAttribute('disabled', true);
            btnRegisterWebhookWitetec.innerHTML = `
                <div class="spinner-border text-white" style="width:12px;height:12px;" role="status">
                <span class="visually-hidden">Loading...</span>
                </div>&nbsp;Registrando...
            `;
        }

        fetch('/api/witetec/register-webhook', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content // se for Laravel
                },
                body: JSON.stringify({})
            })
            .then((res) => res.json())
            .then((res) => {
                if (res.status) {
                    showToast('success', 'Webhook registrado com sucesso');
                } else {
                    showToast('error', 'Falha ao registrar webhook');
                }
            })
            .catch(() => {
                showToast('error', 'Erro na comunicação com o servidor');
            })
            .finally(() => {
                btnRegisterWebhookWitetec.innerHTML = "Registrar webhook";
                btnRegisterWebhookWitetec.removeAttribute('disabled');
            });
    }
</script>
@if ($name == 'efi')
<script>
    function adcionarCertificado() {
        const el = document.getElementById('input-cert');
        if (el) el.click();
    }
    (function() {
        const inputCert = document.getElementById('input-cert');
        if (inputCert) {
            inputCert.addEventListener('change', function(ev) {
                ev.preventDefault();
                const btAddCert = document.getElementById('bt-add-cert');
                if (btAddCert) btAddCert.innerText = "Alterar Certificado";
                const containerSmall = document.querySelector('#container-btn-cert small');
                if (containerSmall) containerSmall.style.display = 'block';
            });
        }
    })();
</script>
@endif
