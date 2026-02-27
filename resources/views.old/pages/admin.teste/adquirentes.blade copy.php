@extends('layouts.app')

@section('title', 'Adquirentes')

@section('content')
    <div class="header mb-3">
        <h3 class="header-title">
            Adquirentes
        </h3>
    </div>
    <form class="row" method="POST" action="{{ route('admin.adquirentes.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Adquirente padrão</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <select class="form-control" id="adquirencia" name="adquirencia"
                                    value="{{ $adquirencia }}">
                                    <option value="cashtime" {{ $adquirencia == 'cashtime' ? 'selected' : '' }}>Cashtime</option>
                                    <option value="efi" {{ $adquirencia == 'efi' ? 'selected' : '' }}>Efí</option>
                                    <option value="witetec" {{ $adquirencia == 'witetec' ? 'selected' : '' }}>Witetec</option>
                                    <!-- <option value="transfeera" {{ $adquirencia == 'transfeera' ? 'selected' : '' }}>Transfeera</option> -->
                                </select>
                                <label for="adquirencia">Adquirente padrão</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- SIXXPAYMENTS --}}
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Sixxpayments</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="sixxpayments.secret"
                                    name="sixxpayments.secret" value="{{ $sixxpayments->secret }}">
                                <label for="sixxpayments.taxa_cash_in">Authorization Key</label>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="sixxpayments.taxa_cash_in"
                                    name="sixxpayments.taxa_cash_in" value="{{ $sixxpayments->taxa_cash_in }}">
                                <label for="sixxpayments.taxa_cash_in">Taxa de depósito (%)</label>
                                <small>Taxa paga ao adquirente em transações de entrada (pix-in)</small>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="sixxpayments.taxa_cash_out"
                                    name="sixxpayments.taxa_cash_out" value="{{ $sixxpayments->taxa_cash_out }}">
                                <label for="sixxpayments.taxa_cash_out">Taxa de depósito (%)</label>
                                <small>Taxa paga ao adquirente em transações de entrada (pix-in)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Transfeera</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="transfeera.token" name="transfeera.token"
                                    value="{{ $transfeera->token }}">
                                <label for="transfeera.token">Token</label>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="transfeera.secret" name="transfeera.secret"
                                    value="{{ $transfeera->secret }}">
                                <label for="transfeera.secret">Secret</label>
                            </div>
                        </div>

                        
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="transfeera.tenant_name" name="transfeera.tenant_name"
                                    value="{{ $transfeera->tenant_name }}">
                                <label for="transfeera.tenant_name">Tenant Name</label>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="transfeera.tenant_email" name="transfeera.tenant_email"
                                    value="{{ $transfeera->tenant_email }}">
                                <label for="transfeera.tenant_email">Tenant Email</label>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="transfeera.tenant_keypix" name="transfeera.tenant_keypix"
                                    value="{{ $transfeera->tenant_keypix }}">
                                <label for="transfeera.tenant_keypix">Tenant Keypix</label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div> -->

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Cashtime</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="cashtime.secret" name="cashtime.secret"
                                    value="{{ $cashtime->secret }}">
                                <label for="cashtime.taxa_cash_in">Authorization Key</label>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="cashtime.taxa_cash_in"
                                    name="cashtime.taxa_cash_in" value="{{ $cashtime->taxa_cash_in }}">
                                <label for="cashtime.taxa_cash_in">Taxa de depósito (%)</label>
                                <small>Taxa paga ao adquirente em transações de entrada (pix-in)</small>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="cashtime.taxa_cash_out"
                                    name="cashtime.taxa_cash_out" value="{{ $cashtime->taxa_cash_out }}">
                                <label for="cashtime.taxa_cash_out">Taxa de depósito (%)</label>
                                <small>Taxa paga ao adquirente em transações de entrada (pix-in)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Witetec</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="witetec.api_token" name="witetec.api_token"
                                    value="{{ $witetec->api_token }}">
                                <label for="witetec.api_token">API Key</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Efí</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="efi.client_id" name="efi.client_id"
                                    value="{{ $efi->client_id }}">
                                <label for="efi.client_id">Client ID</label>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="efi.client_secret"
                                    name="efi.client_secret" value="{{ $efi->client_secret }}">
                                <label for="efi.client_secret">Client secret</label>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="efi.chave_pix" name="efi.chave_pix"
                                    value="{{ $efi->chave_pix }}">
                                <label for="efi.chave_pix">Chave PIX</label>
                            </div>
                        </div>

                        <div class="col-xl-12">
                            <label for="input-cert" class="form-label">Certificado</label>
                            <input id="input-cert" type="file"
                                class="filepond form-control @error('efi.cert') is-invalid @enderror" name="efi.cert"
                                hidden value="{{ $efi->cert }}">
                            <br />
                            <button id="bt-add-cert" type="button" class="w-100 btn btn-success"
                                onclick="adicionarCertificado()">Selecionar certificado</button>
                            <small style="display: none;" class="text-success">Certificado selecionado</small>
                            @error('cert')
                                <span style="color: red;">{{ $message }}</span>
                            @enderror
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
                            <button type="submit" class="btn btn-primary"><i
                                    class="fa-solid fa-save"></i>&nbsp;Salvar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div>
                    <form method="POST" action="{{ route('admin.adquirentes.efi.regitrar') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-success">Registrar Webhooks Efí</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function adicionarCertificado() {
            document.getElementById('input-cert').click();
        }
        document.getElementById('input-cert').addEventListener('change', function(ev) {
            ev.preventDefault();
            document.getElementById('bt-add-cert').innerText = "Alterar Certificado";
            document.querySelector('#container-btn-cert small').style.display = 'block';
        })
    </script>
@endsection
