@php
use App\Helpers\Helper;
    $setting = Helper::settings();

    $dataRequest = [
        'token' => 'TOKEN',
        'secret' => 'SECRET',
        'amount' => 10,
        'debtor_name' => 'Fulano de tal',
        'email' => 'fulano@gmail.com',
        'debtor_document_number' => '12345678911',
        'phone' => '11900000000',
        'method_pay' => 'pix',
        'postback' => 'https://seudominio.com/callback/deposit',
    ];

    $dataResponseSuccess = [
        "idTransaction" => "ID DA TRANSAÇÃO",
        "qrcode" => "00020126330014br.gov.bcb.pix01111335366962052040000530398654040.805802BR5919NOME6014CIDADE62580520LKH2021102118215467250300017br.gov.bcb.brcode01051.0.063044D24",
        "qr_code_image_url" => "base64data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAApgAAAKYB3X3..."    
    ];

    $dataResponseError400 = [
        'error' => 'Token ou Secret ausentes',
        'message' => 'Você precisa fornecer tanto o token quanto o secret.'
    ];
    

    $dataResponseError422 = [
        'required' => 'Este campo é obrigatório',
        'string'   => 'Este campo deve ser uma string',
        'email'    => 'O campo deve ser um email válido'
    ];

    $jsonDataRequest = json_encode($dataRequest, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $jsonDataResponseSuccess =json_encode($dataResponseSuccess, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $jsonDataResponseError400 =json_encode($dataResponseError400, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $jsonDataResponseError422 =json_encode($dataResponseError422, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

@endphp

@extends('layouts.documentation')

@section('title', 'Gerar QrCode')

@section('content')
<div class="header">
    <h1 class="header-title">
        PIX-IN
    </h1>
    <p class="header-subtitle">Receber um pagamento</p>
</div>

<div class="row">
    <div class="col-12 mb-3">
        <div class="borda" >
            <h4 class="alert-heading">
                <i class="fa-solid fa-circle-info"></i>&nbsp;Importante
            </h4>
            <p>
                A requisição a seguir tem como objetivo gerar um QrCode juntamente com Pix copia e cola.
            </p>
        </div>
    </div>

    <div class="col-12 mb-3">
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <div class="method">
                        <span class="method-method">POST</span><span class="method-endpoint">{{ env('APP_URL') }}/api/wallet/deposit/payment</span>
                    </div>
                </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="mb-3">
                            <p>Requisição</p> <small class="text-danger">* Todos os campos são obrigatórios</small>
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
        <a href="{{ route('docs.index') }}">
            <button class="btn btn-xs btn-outline-success">
                <i class="fa-solid fa-arrow-left" style="color: {{ $setting->software_color }} !important;"></i>&nbsp;Voltar
            </button>
        </a>
        <a href="/docs/api-pix/send#success">
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
