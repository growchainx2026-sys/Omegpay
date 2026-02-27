@extends('layouts.documentation')

@section('title', 'Enviar um pagamento')

@section('content')
@php
use Carbon\Carbon;
use App\Helpers\Helper;

    $setting = Helper::settings();

    $dataRequest = [
        'token' => 'TOKEN',
        'secret' => 'SECRET',
        'amount' => 10,
        'pixKey' => '12345678911',
        'pixKeyType' =>    'cpf',
        'baasPostbackUrl' => 'https://seudominio.com/callback/withdrawal'
    ];

    $dataResponseSuccess = [
        "id"                => "ID DA TRANSAÇÃO",
        "amount"            => 10,
        "pixKey"            => "12345678911",
        "pixKeyType"        => "cpf",
        "withdrawStatusId"  => "PendingProcessing",
        "createdAt"         => Carbon::now()->toIso8601String(),
        "updatedAt"         => Carbon::now()->toIso8601String()
    ];

    $dataResponseError400 = [
        'error' => 'Token ou Secret ausentes',
        'message' => 'Você precisa fornecer tanto o token quanto o secret.'
    ];

    $dataResponseError401 = [
        'status' => 'error',
        'message' => 'Saldo Insulficiente.'
    ];


    $dataResponseError422 = [
        'required' => 'Este campo é obrigatório',
        'string'   => 'Este campo deve ser uma string',
        'in'       => 'Valor inválido para o campo :attribute' //:attribute -> cpf|email|telefone|aleatoria
    ];

    $jsonDataRequest = json_encode($dataRequest, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $jsonDataResponseSuccess =json_encode($dataResponseSuccess, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $jsonDataResponseError400 =json_encode($dataResponseError400, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $jsonDataResponseError401 =json_encode($dataResponseError401, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $jsonDataResponseError422 =json_encode($dataResponseError422, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

@endphp

<div class="header">
    <h1 class="header-title">
        PIX-OUT
    </h1>
    <p class="header-subtitle">Enviar um pagamento</p>
</div>
<div class="row">
    <div class="col-12 mb-3">
        <div class="borda" >
            <h4 class="alert-heading">
                <i class="fa-solid fa-circle-info"></i>&nbsp;Importante
            </h4>
            <p>
                A requisição a seguir tem como objetivo enviar um pagamento via PIX para uma chave denominada pixKey com o tipo de chave denominada pixKeyType.
            </p>
        </div>
    </div>

    <div class="col-12 mb-3">
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <div class="method">
                        <span class="method-method">POST</span><span class="method-endpoint">{{ env('APP_URL') }}/api/pixout</span>
                    </div>
                </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="mb-3">
                            <p>Requisição</p>
                            <p>Tipos aceitos em pixKeyType: <span class="text-primary">cpf | email | telefone | aleatoria</span></p>
                            <pre><code class="language-json">{{ $jsonDataRequest }}</code></pre>
                        </div>
                        <div class="mb-3">
                            <p style="margin-bottom: -6px;">Resposta</p>
                            <small>As respostas abaixo representam Sucesso(200) e Falhas/erros do consumo.</small>
                            <ul class="nav nav-pills mt-1" id="responseTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link response-success d-flex align-items-center"
                                    id="success-tab" data-bs-toggle="tab" href="#success" role="tab"
                                    aria-controls="success" aria-selected="true">
                                        <div class="circle-green me-1"></div> 200
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link response-error d-flex align-items-center"
                                    id="error-tab-400" data-bs-toggle="tab" href="#error400" role="tab"
                                    aria-controls="error" aria-selected="false">
                                        <div class="circle-red me-1"></div> 400
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link response-error d-flex align-items-center"
                                    id="error-tab-401" data-bs-toggle="tab" href="#error401" role="tab"
                                    aria-controls="error" aria-selected="false">
                                        <div class="circle-red me-1"></div> 401
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link response-error d-flex align-items-center"
                                    id="error-tab-422" data-bs-toggle="tab" href="#error422" role="tab"
                                    aria-controls="error" aria-selected="false">
                                        <div class="circle-red me-1"></div> 422
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content mt-3" id="responseTabsContent">
                                <div class="tab-pane fade" id="success" role="tabpanel" aria-labelledby="success-tab">
                                <pre><code class="language-json">{{ $jsonDataResponseSuccess }}</code></pre>    
                                </div>
                                <div class="tab-pane fade" id="error400" role="tabpanel" aria-labelledby="error-tab-400">
                                    <pre><code class="language-json">{{ $jsonDataResponseError400 }}</code></pre>
                                </div>
                                <div class="tab-pane fade" id="error401" role="tabpanel" aria-labelledby="error-tab-401">
                                    <pre><code class="language-json">{{ $jsonDataResponseError401 }}</code></pre>
                                </div>
                                <div class="tab-pane fade" id="error422" role="tabpanel" aria-labelledby="error-tab-422">
                                    <pre><code class="language-json">{{ $jsonDataResponseError422 }}</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 mb-3 d-flex justify-content-between">
        <a href="/docs/api-pix/receive#success">
            <button class="btn btn-xs btn-outline-success">
                <i class="fa-solid fa-arrow-left" style="color: {{ $setting->software_color }} !important;"></i>&nbsp;Voltar
            </button>
        </a>
        <a href="{{ route('docs.webhooks') }}">
            <button class="btn btn-xs btn-outline-success">
                <i class="fa-solid fa-arrow-right" style="color: {{ $setting->software_color }} !important;"></i>&nbsp;Próximo
            </button>
        </a>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const hash = window.location.hash;

        if (hash) {
            const tabTrigger = document.querySelector(`a.nav-link[href="${hash}"]`);
            if (tabTrigger) {
                const tab = new bootstrap.Tab(tabTrigger);
                tab.show();
            }
        }

        // Atualiza a URL ao clicar em abas
        document.querySelectorAll('.nav-link[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function (e) {
                history.replaceState(null, null, e.target.getAttribute('href'));
            });
        });
    });
</script>

@endsection
