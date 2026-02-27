@extends('layouts.app')

@section('title', 'Testar Webhooks')

@section('content')
@php
    $setting = \App\Helpers\Helper::settings();
    $payloads = [
        'pix_in_paid' => [
            'name' => 'PIX recebido (pago)',
            'desc' => 'Enviado para a URL informada em postbackUrl quando um PIX de entrada é confirmado.',
            'method' => 'POST',
            'body' => [
                'status' => 'paid',
                'idTransaction' => 'ID_DA_TRANSACAO',
                'typeTransaction' => 'PIX',
            ],
        ],
        'pix_in_canceled' => [
            'name' => 'PIX recebido (cancelado)',
            'desc' => 'Enviado quando um PIX de entrada é cancelado.',
            'method' => 'POST',
            'body' => [
                'status' => 'canceled',
                'idTransaction' => 'ID_DA_TRANSACAO',
                'typeTransaction' => 'PIX',
            ],
        ],
        'pix_out_paid' => [
            'name' => 'Saque/PIX enviado (pago)',
            'desc' => 'Enviado para a URL informada em baasPostbackUrl quando um saque é processado.',
            'method' => 'POST',
            'body' => [
                'status' => 'paid',
                'idTransaction' => 'ID_DA_TRANSACAO',
                'typeTransaction' => 'PAYMENT',
            ],
        ],
        'pix_out_canceled' => [
            'name' => 'Saque/PIX enviado (cancelado)',
            'desc' => 'Enviado quando um saque é cancelado ou não realizado.',
            'method' => 'POST',
            'body' => [
                'status' => 'canceled',
                'idTransaction' => 'ID_DA_TRANSACAO',
                'typeTransaction' => 'PAYMENT',
            ],
        ],
        'webhook_user' => [
            'name' => 'Webhook usuário (depósito)',
            'desc' => 'Payload enviado aos webhooks gerais do usuário (inclui dados do cliente).',
            'method' => 'POST',
            'body' => [
                'status' => 'paid',
                'typeTransaction' => 'PIX',
                'idTransaction' => 'ID_DA_TRANSACAO',
                'client' => [
                    'name' => 'Nome do cliente',
                    'cpf' => '00000000000',
                ],
            ],
        ],
        'webhook_produto' => [
            'name' => 'Webhook produto (venda)',
            'desc' => 'Payload enviado aos webhooks de produto quando uma venda é confirmada.',
            'method' => 'POST',
            'body' => [
                'product' => [
                    'name' => 'nome-do-produto',
                    'price' => 97.00,
                ],
                'status' => 'paid',
                'typeTransaction' => 'PIX',
                'idTransaction' => 'ID_DA_TRANSACAO',
                'client' => [
                    'cpf' => '00000000000',
                    'name' => 'Nome do comprador',
                    'email' => 'email@exemplo.com',
                    'phone' => '5511999999999',
                ],
            ],
        ],
    ];
@endphp

<style>
    .webhook-tests-page { max-width: 960px; margin: 0 auto; }
    .webhook-tests-page .page-header { margin-bottom: 1.75rem; }
    .webhook-tests-page .page-title { font-size: 1.5rem; font-weight: 600; margin: 0 0 0.35rem 0; }
    .webhook-tests-page .page-subtitle { color: var(--bs-secondary-color); font-size: 0.9rem; margin: 0; }
    .webhook-tests-page .url-section {
        background: var(--bs-body-bg);
        border: 1px solid var(--bs-border-color);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
    }
    .webhook-tests-page .url-section label { font-weight: 500; font-size: 0.875rem; }
    .webhook-tests-page .url-section input { border-radius: 8px; }
    .webhook-tests-page .card-test {
        border: 1px solid var(--bs-border-color);
        border-radius: 12px;
        margin-bottom: 1rem;
        overflow: hidden;
    }
    .webhook-tests-page .card-test .card-header-test {
        padding: 1rem 1.25rem;
        font-weight: 600;
        font-size: 0.95rem;
        border-bottom: 1px solid var(--bs-border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .webhook-tests-page .card-test .card-header-test .badge-method {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        background: var(--gateway-sidebar-color, #0d6efd);
        color: #fff;
    }
    .webhook-tests-page .card-test .card-body-test { padding: 1rem 1.25rem; }
    .webhook-tests-page .card-test .card-body-test .test-desc { font-size: 0.8rem; color: var(--bs-secondary-color); margin-bottom: 0.75rem; }
    .webhook-tests-page .payload-wrap {
        background: #1e1e1e;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        margin-bottom: 0.75rem;
        position: relative;
    }
    body.dark-mode .webhook-tests-page .payload-wrap { background: #0d1117; }
    .webhook-tests-page .payload-wrap pre {
        margin: 0;
        font-size: 0.8rem;
        color: #d4d4d4;
        overflow-x: auto;
        white-space: pre-wrap;
        word-break: break-word;
    }
    .webhook-tests-page .btn-copy-payload {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
        border-radius: 6px;
        opacity: 0.9;
    }
    .webhook-tests-page .result-panel {
        border: 1px solid var(--bs-border-color);
        border-radius: 12px;
        padding: 1.25rem;
        margin-top: 1.5rem;
        min-height: 80px;
    }
    .webhook-tests-page .result-panel .result-status { font-weight: 600; font-size: 0.95rem; margin-bottom: 0.5rem; }
    .webhook-tests-page .result-panel .result-status.success { color: var(--bs-success); }
    .webhook-tests-page .result-panel .result-status.error { color: var(--bs-danger); }
    .webhook-tests-page .result-panel .result-body {
        font-size: 0.8rem;
        font-family: ui-monospace, monospace;
        background: var(--bs-tertiary-bg);
        padding: 0.75rem;
        border-radius: 8px;
        max-height: 200px;
        overflow-y: auto;
        white-space: pre-wrap;
        word-break: break-word;
    }
    .webhook-tests-page .result-panel.empty { color: var(--bs-secondary-color); font-size: 0.9rem; }
</style>

<div class="webhook-tests-page">
    @csrf
    <div class="page-header">
        <h1 class="page-title">Testar Webhooks</h1>
        <p class="page-subtitle">Simule as requisições que enviamos para sua URL e valide a integração da sua API.</p>
    </div>

    <div class="url-section">
        <label for="webhookUrl">URL do seu endpoint</label>
        <input type="url" id="webhookUrl" class="form-control mt-1" placeholder="https://seusite.com/callback/webhook" />
    </div>

    @foreach ($payloads as $key => $payload)
    <div class="card card-test">
        <div class="card-header-test">
            <span>{{ $payload['name'] }}</span>
            <span class="badge badge-method">{{ $payload['method'] }}</span>
        </div>
        <div class="card-body-test">
            <p class="test-desc">{{ $payload['desc'] }}</p>
            <div class="payload-wrap">
                <button type="button" class="btn btn-sm btn-outline-light btn-copy-payload" data-copy="{{ $key }}">Copiar JSON</button>
                <pre data-payload="{{ $key }}">{{ json_encode($payload['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
            <button type="button" class="btn btn-primary btn-sm btn-send-test" data-key="{{ $key }}" data-method="{{ $payload['method'] }}">
                Enviar teste
            </button>
        </div>
    </div>
    @endforeach

    <div class="result-panel empty" id="resultPanel">
        O resultado da última requisição aparecerá aqui.
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var payloads = @json($payloads);
    var urlInput = document.getElementById('webhookUrl');
    var resultPanel = document.getElementById('resultPanel');
    var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    function showResult(success, status, body, message) {
        resultPanel.classList.remove('empty');
        resultPanel.classList.add(success ? 'success' : 'error');
        var statusHtml = status != null
            ? '<span class="result-status ' + (success ? 'success' : 'error') + '">HTTP ' + status + '</span>'
            : '<span class="result-status error">' + (message || 'Erro') + '</span>';
        var bodyHtml = body != null ? '<div class="result-body">' + escapeHtml(body) + '</div>' : '';
        resultPanel.innerHTML = statusHtml + bodyHtml;
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    document.querySelectorAll('.btn-copy-payload').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var key = this.getAttribute('data-copy');
            var pre = document.querySelector('pre[data-payload="' + key + '"]');
            if (pre) {
                navigator.clipboard.writeText(pre.textContent).then(function() {
                    var t = btn.textContent;
                    btn.textContent = 'Copiado!';
                    setTimeout(function() { btn.textContent = t; }, 1500);
                });
            }
        });
    });

    document.querySelectorAll('.btn-send-test').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var url = urlInput.value.trim();
            if (!url) {
                alert('Informe a URL do seu endpoint.');
                urlInput.focus();
                return;
            }
            var key = this.getAttribute('data-key');
            var method = this.getAttribute('data-method');
            var body = payloads[key].body;
            var payloadJson = JSON.stringify(body);

            btn.disabled = true;
            resultPanel.innerHTML = 'Enviando...';
            resultPanel.classList.remove('empty');

            var token = csrf || document.querySelector('input[name="_token"]')?.value;
            fetch('{{ route("webhook-tests.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    url: url,
                    payload: payloadJson,
                    method: method,
                    _token: token
                })
            }).then(function(r) { return r.json(); }).then(function(data) {
                if (data.success !== undefined) {
                    showResult(data.success, data.status, data.body, data.message);
                } else {
                    showResult(false, null, null, data.message || 'Resposta inválida');
                }
            }).catch(function(err) {
                showResult(false, null, null, err.message || 'Falha na requisição');
            }).finally(function() {
                btn.disabled = false;
            });
        });
    });
});
</script>
@endsection
