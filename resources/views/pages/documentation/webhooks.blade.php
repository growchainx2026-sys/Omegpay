@extends('layouts.documentation')

@section('title', 'Webhooks')

@section('content')
@php
use Carbon\Carbon;
use App\Helpers\Helper;

    $setting = Helper::settings();

    $dataRequestDeposit = [
        "status"            => "paid", // "paid" | "canceled"
        "idTransaction"     => "ID DA TRANSAÇÃO",
        "typeTransaction"   => "PIX"
    ];

     $dataRequestWithdrawal = [
        "status"            => "paid", // "paid" | "canceled" | "rejected"
        "idTransaction"     => "ID DA TRANSAÇÃO",
        "typeTransaction"   => "PAYMENT"
    ];

    $jsonDataRequestDeposit = json_encode($dataRequestDeposit, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $jsonDataRequestWithdrawal = json_encode($dataRequestWithdrawal, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
@endphp

<div class="header mb-3">
    <h1 class="header-title">
        Webhooks
    </h1>
</div>
<div class="row">
    <div class="col-12 mb-3">
        <div class="borda" >
            <h4 class="alert-heading">
                <i class="fa-solid fa-circle-info"></i>&nbsp;Importante
            </h4>
            <p>
                Os dados abaixo é recebido através das URL de callback informados em: 
            </p>
            <div style="line-height:10px;">
                <p class="text-danger">
                    Em PIX-IN a url informada em postbackUrl.
                </p>
                <p class="text-danger">
                    Em PIX-OUT a url informada em baasPostbackUrl.
                </p>
            </div>
            
        </div>
    </div>

    <div class="col-12 mb-3">
        <div class="accordion mb-3" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <div class="method">
                        <span class="method-method">POST</span><span class="method-endpoint">https://seudominio.com/callback/deposit</span>
                    </div>
                </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="mb-3">
                            <p>(PIX-IN) Requisição </p>
                            <pre><code class="language-json">{{ $jsonDataRequestDeposit }}</code></pre>
                        </div>
                        <div class="mb-3">
                            <p style="margin-bottom: -6px;">Resposta</p>
                            <p class="text-warning">Após o tratamento dos dados retornar o statusCode 200</p>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <div class="method">
                        <span class="method-method">POST</span><span class="method-endpoint">https://seudominio.com/callback/withdrawal</span>
                    </div>
                </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo" data-bs-parent="#accordionTwo">
                    <div class="accordion-body">
                        <div class="mb-3">
                            <p>(PIX-OUT) Requisição </p>
                            <pre><code class="language-json">{{ $jsonDataRequestWithdrawal }}</code></pre>
                        </div>
                        <div class="mb-3">
                            <p style="margin-bottom: -6px;">Resposta</p>
                            <p class="text-warning">Após o tratamento dos dados retornar o statusCode 200</p>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 mb-3 d-flex justify-content-between">
        <a href="/docs/api-pix/send#success">
            <button class="btn btn-xs btn-outline-success">
                <i class="fa-solid fa-arrow-left" style="color: {{ $setting->software_color }} !important;"></i>&nbsp;Voltar
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
