@extends('layouts.app')

@section('content')
<style>
.dev-page-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
}

.dev-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.dev-page-header h1 {
    font-size: 1.875rem;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
}

.dev-page-back {
    color: #6b7280;
    text-decoration: none;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: color 0.2s;
}

.dev-page-back:hover {
    color: var(--gateway-primary-color);
    text-decoration: none;
}

.dev-content-section {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 1.5rem;
}

.dev-content-section:last-child {
    margin-bottom: 0;
}

.dev-section-header {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.dev-section-header i {
    color: var(--gateway-primary-color);
}

.dev-content-text {
    color: #6b7280;
    font-size: 0.9375rem;
    line-height: 1.7;
    margin: 0 0 1.5rem 0;
}

.dev-table {
    width: 100%;
    border-collapse: collapse;
}

.dev-table thead {
    background: #f9fafb;
    border-bottom: 2px solid #e5e7eb;
}

.dev-table th {
    padding: 1rem;
    text-align: left;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.dev-table td {
    padding: 1rem;
    border-bottom: 1px solid #f3f4f6;
    color: #374151;
    font-size: 0.9375rem;
}

.dev-table tbody tr:hover {
    background: #f9fafb;
}

.dev-table code {
    background: #f3f4f6;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
    color: var(--gateway-primary-color);
}

.dev-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.dev-badge-success {
    background: #d1fae5;
    color: #065f46;
}

.dev-tool-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.5rem;
    height: 100%;
    transition: all 0.2s;
}

.dev-tool-card:hover {
    border-color: var(--gateway-primary-color);
}

.dev-tool-title {
    font-size: 1rem;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.dev-tool-text {
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.dev-tool-list {
    list-style: none;
    padding: 0;
    margin: 0 0 1rem 0;
    font-size: 0.875rem;
    color: #374151;
}

.dev-tool-list li {
    padding: 0.25rem 0;
}

.dev-tool-link {
    display: block;
    width: 100%;
    padding: 0.625rem 1rem;
    background: var(--gateway-primary-color);
    color: white;
    text-align: center;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: opacity 0.2s;
}

.dev-tool-link:hover {
    opacity: 0.9;
    color: white;
    text-decoration: none;
}

.dev-tool-hint {
    font-size: 0.75rem;
    color: #9ca3af;
    margin-top: 0.5rem;
    display: block;
}

.dev-tabs {
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 1.5rem;
}

.dev-tabs .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    color: #6b7280;
    padding: 0.75rem 1.25rem;
    font-weight: 500;
    transition: all 0.2s;
}

.dev-tabs .nav-link:hover {
    color: var(--gateway-primary-color);
    border-bottom-color: var(--gateway-primary-color);
}

.dev-tabs .nav-link.active {
    color: var(--gateway-primary-color);
    border-bottom-color: var(--gateway-primary-color);
    background: transparent;
}

.dev-code-block {
    background: #1a1a1a;
    color: #e5e7eb;
    padding: 1.5rem;
    border-radius: 8px;
    overflow-x: auto;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 0.875rem;
    line-height: 1.6;
}

.dev-code-block code {
    background: transparent;
    padding: 0;
    color: inherit;
}

.dev-list-item {
    padding: 0.75rem 0;
    color: #374151;
    font-size: 0.9375rem;
    border-bottom: 1px solid #f3f4f6;
    padding-left: 1.5rem;
    position: relative;
}

.dev-list-item:last-child {
    border-bottom: none;
}

.dev-list-item:before {
    content: "→";
    position: absolute;
    left: 0;
    color: var(--gateway-primary-color);
    font-weight: 600;
}
</style>

<div class="dev-page-container">
    <div class="dev-page-header">
        <h1>Webhooks Utilizados</h1>
        <a href="{{ route('admin.dev.index') }}" class="dev-page-back">
            <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i>
            Voltar
        </a>
    </div>

    <!-- Eventos de Webhook Disponíveis -->
    <div class="dev-content-section">
        <h2 class="dev-section-header">
            <i data-lucide="zap" style="width: 20px; height: 20px;"></i>
            Eventos de Webhook Disponíveis
        </h2>
        <div style="overflow-x: auto;">
            <table class="dev-table">
                    <thead>
                        <tr>
                            <th>Evento</th>
                            <th>Quando é Disparado</th>
                            <th>Método HTTP</th>
                            <th>Headers</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>pix_in.paid</code></td>
                            <td>Quando um pagamento Pix-In é confirmado</td>
                            <td><span class="dev-badge dev-badge-success">POST</span></td>
                            <td><code>Content-Type: application/json</code><br><code>Accept: application/json</code></td>
                        </tr>
                        <tr>
                            <td><code>pix_out.paid</code></td>
                            <td>Quando um saque Pix-Out é processado com sucesso</td>
                            <td><span class="dev-badge dev-badge-success">POST</span></td>
                            <td><code>Content-Type: application/json</code><br><code>Accept: application/json</code></td>
                        </tr>
                        <tr>
                            <td><code>pix_out.failed</code></td>
                            <td>Quando um saque Pix-Out falha</td>
                            <td><span class="dev-badge dev-badge-success">POST</span></td>
                            <td><code>Content-Type: application/json</code><br><code>Accept: application/json</code></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Como Receber/Ouvir Webhooks -->
    <div class="dev-content-section">
        <h2 class="dev-section-header">
            <i data-lucide="headphones" style="width: 20px; height: 20px;"></i>
            Como Receber e Ouvir Webhooks
        </h2>
        <p class="dev-content-text">
                Para testar e desenvolver integrações com webhooks, você precisa de uma URL pública que possa receber requisições HTTP.
                Abaixo estão as principais ferramentas e métodos para receber webhooks:
            </p>

            <div class="row g-3">
                <!-- Webhook.site -->
                <div class="col-md-6">
                    <div class="dev-tool-card">
                        <h6 class="dev-tool-title">
                            <i data-lucide="globe" style="width: 18px; height: 18px; color: var(--gateway-primary-color);"></i>
                            Webhook.site <span class="dev-badge dev-badge-success" style="font-size: 0.625rem; padding: 0.125rem 0.5rem;">Recomendado</span>
                        </h6>
                        <p class="dev-tool-text">
                                Ferramenta online gratuita que gera uma URL única para receber webhooks.
                                Visualize requisições em tempo real com todos os detalhes.
                            </p>
                            <ul class="dev-tool-list">
                                <li>✅ Não precisa instalar nada</li>
                                <li>✅ Visualização em tempo real</li>
                                <li>✅ Histórico de requisições</li>
                                <li>✅ Headers, body e raw data</li>
                            </ul>
                            <a href="https://webhook.site" target="_blank" rel="noopener" class="dev-tool-link">
                                <i data-lucide="external-link" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 0.25rem;"></i>
                                Acessar Webhook.site
                            </a>
                            <span class="dev-tool-hint">
                                <strong>Como usar:</strong> Acesse o site, copie a URL única gerada e use no campo de webhook.
                            </span>
                        </div>
                    </div>
                </div>

                <!-- RequestBin -->
                <div class="col-md-6">
                    <div class="dev-tool-card">
                        <h6 class="dev-tool-title">
                            <i data-lucide="server" style="width: 18px; height: 18px; color: var(--gateway-primary-color);"></i>
                            RequestBin
                        </h6>
                        <p class="dev-tool-text">
                                Crie um "bin" temporário para receber e inspecionar requisições HTTP.
                                Ideal para testes rápidos e desenvolvimento.
                            </p>
                            <ul class="dev-tool-list">
                                <li>✅ Criação rápida de endpoints</li>
                                <li>✅ Dashboard para visualizar requisições</li>
                                <li>✅ Exportação de dados</li>
                                <li>✅ Suporte a diferentes métodos HTTP</li>
                            </ul>
                            <a href="https://requestbin.com" target="_blank" rel="noopener" class="dev-tool-link">
                                <i data-lucide="external-link" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 0.25rem;"></i>
                                Acessar RequestBin
                            </a>
                            <span class="dev-tool-hint">
                                <strong>Como usar:</strong> Crie um RequestBin, copie a URL e use para receber webhooks.
                            </span>
                        </div>
                    </div>
                </div>

                <!-- ngrok -->
                <div class="col-md-6">
                    <div class="dev-tool-card">
                        <h6 class="dev-tool-title">
                            <i data-lucide="network" style="width: 18px; height: 18px; color: var(--gateway-primary-color);"></i>
                            ngrok
                        </h6>
                        <p class="dev-tool-text">
                                Expõe seu servidor local na internet através de um túnel seguro.
                                Perfeito para testar webhooks em ambiente de desenvolvimento local.
                            </p>
                            <ul class="dev-tool-list">
                                <li>✅ Testa webhooks localmente</li>
                                <li>✅ Túnel HTTPS seguro</li>
                                <li>✅ Inspeção de requisições</li>
                                <li>✅ Replay de requisições</li>
                            </ul>
                            <a href="https://ngrok.com/download" target="_blank" rel="noopener" class="dev-tool-link">
                                <i data-lucide="download" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 0.25rem;"></i>
                                Baixar ngrok
                            </a>
                            <span class="dev-tool-hint">
                                <strong>Como usar:</strong> Execute <code style="background: #f3f4f6; padding: 0.125rem 0.375rem; border-radius: 4px;">ngrok http 8000</code> e use a URL HTTPS gerada.
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Área de Testes Interna -->
                <div class="col-md-6">
                    <div class="dev-tool-card">
                        <h6 class="dev-tool-title">
                            <i data-lucide="flask-conical" style="width: 18px; height: 18px; color: var(--gateway-primary-color);"></i>
                            Área de Testes (Sandbox)
                        </h6>
                        <p class="dev-tool-text">
                                Use a área de testes interna para disparar webhooks de teste
                                e validar integrações sem precisar de eventos reais.
                            </p>
                            <ul class="dev-tool-list">
                                <li>✅ Disparo manual de webhooks</li>
                                <li>✅ Payloads pré-configurados</li>
                                <li>✅ Teste de diferentes eventos</li>
                                <li>✅ Validação de resposta</li>
                            </ul>
                            <a href="{{ route('admin.dev.sandbox') }}" class="dev-tool-link">
                                <i data-lucide="rocket" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 0.25rem;"></i>
                                Ir para Sandbox
                            </a>
                            <span class="dev-tool-hint">
                                <strong>Como usar:</strong> Acesse a área de testes e envie webhooks para URLs de teste.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Exemplos de Payloads -->
    <div class="dev-content-section">
        <h2 class="dev-section-header">
            <i data-lucide="code" style="width: 20px; height: 20px;"></i>
            Exemplos de Payloads
        </h2>
        <ul class="nav dev-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pix-in-paid" type="button">
                        Pix-In Pago
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pix-out-paid" type="button">
                        Pix-Out Pago
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pix-out-failed" type="button">
                        Pix-Out Falhado
                    </button>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="pix-in-paid">
                    <pre class="dev-code-block"><code>{
  "status": "paid",
  "idTransaction": "TXN-123456789",
  "typeTransaction": "PIX",
  "product": {
    "name": "produto-exemplo",
    "price": 100.00
  },
  "client": {
    "cpf": "12345678900",
    "name": "João Silva",
    "email": "joao@exemplo.com",
    "phone": "5511999999999"
  }
}</code></pre>
                </div>
                <div class="tab-pane fade" id="pix-out-paid">
                    <pre class="dev-code-block"><code>{
  "status": "paid",
  "idTransaction": "TXN-OUT-987654321",
  "typeTransaction": "PIX_OUT",
  "amount": 50.00,
  "client": {
    "name": "Maria Santos",
    "cpf": "98765432100"
  }
}</code></pre>
                </div>
                <div class="tab-pane fade" id="pix-out-failed">
                    <pre class="dev-code-block"><code>{
  "status": "failed",
  "idTransaction": "TXN-OUT-987654321",
  "typeTransaction": "PIX_OUT",
  "amount": 50.00,
  "error": "Saldo insuficiente",
  "client": {
    "name": "Maria Santos",
    "cpf": "98765432100"
  }
}</code></pre>
                </div>
            </div>
        </div>
    </div>

    <!-- Boas Práticas -->
    <div class="dev-content-section">
        <h2 class="dev-section-header">
            <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
            Boas Práticas
        </h2>
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-primary">Para o Consumidor do Webhook:</h6>
                    <ul class="dev-content-list">
                        <li>Sempre retorne <code>200 OK</code> rapidamente (processe em background)</li>
                        <li>Valide a origem do webhook quando possível</li>
                        <li>Implemente idempotência (não processe o mesmo evento duas vezes)</li>
                        <li>Use HTTPS para todas as URLs de webhook</li>
                        <li>Implemente retry logic no seu lado se necessário</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="text-primary">Configurações do Sistema:</h6>
                    <ul class="dev-content-list">
                        <li><strong>Timeout:</strong> 30 segundos</li>
                        <li><strong>Retries:</strong> Não implementado automaticamente (processe idempotente)</li>
                        <li><strong>Logs:</strong> Todas as requisições são logadas em <code>storage/logs/laravel.log</code></li>
                        <li><strong>Headers:</strong> <code>Content-Type: application/json</code></li>
                        <li><strong>Métodos:</strong> GET, POST, PUT suportados</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Links Úteis -->
    <div class="dev-content-section">
        <h2 class="dev-section-header">
            <i data-lucide="link" style="width: 20px; height: 20px;"></i>
            Links Úteis
        </h2>
            <div class="row">
                <div class="col-md-6">
                    <h6>Ferramentas de Teste:</h6>
                    <ul class="dev-content-list">
                        <li><a href="https://webhook.site" target="_blank" rel="noopener" style="color: var(--gateway-primary-color); text-decoration: none;">Webhook.site</a> - Receba webhooks em tempo real</li>
                        <li><a href="https://requestbin.com" target="_blank" rel="noopener" style="color: var(--gateway-primary-color); text-decoration: none;">RequestBin</a> - Crie bins temporários</li>
                        <li><a href="https://ngrok.com" target="_blank" rel="noopener" style="color: var(--gateway-primary-color); text-decoration: none;">ngrok</a> - Exponha servidor local</li>
                        <li><a href="https://httpbin.org" target="_blank" rel="noopener" style="color: var(--gateway-primary-color); text-decoration: none;">HTTPBin</a> - Teste requisições HTTP</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6>Documentação e Recursos:</h6>
                    <ul class="dev-content-list">
                        <li><a href="{{ route('admin.dev.sandbox') }}" style="color: var(--gateway-primary-color); text-decoration: none;">Área de Testes (Sandbox)</a> - Disparar webhooks de teste</li>
                        <li><a href="{{ route('admin.dev.manual') }}" style="color: var(--gateway-primary-color); text-decoration: none;">Manual do Desenvolvedor</a> - Documentação técnica</li>
                        <li><a href="{{ route('admin.dev.pix-in') }}" style="color: var(--gateway-primary-color); text-decoration: none;">Pix-In</a> - Fluxo de entradas</li>
                        <li><a href="{{ route('admin.dev.pix-out') }}" style="color: var(--gateway-primary-color); text-decoration: none;">Pix-Out</a> - Fluxo de saídas</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection

