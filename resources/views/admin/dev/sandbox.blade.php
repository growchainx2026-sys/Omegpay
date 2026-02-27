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

.dev-section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 1rem;
}

.dev-section-text {
    color: #6b7280;
    font-size: 0.9375rem;
    line-height: 1.7;
    margin-bottom: 1.5rem;
}

.dev-info-box {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 8px;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
}

.dev-info-box strong {
    color: #1e40af;
    display: block;
    margin-bottom: 0.75rem;
    font-size: 0.875rem;
}

.dev-info-box ul {
    margin: 0;
    padding-left: 1.5rem;
    color: #1e40af;
    font-size: 0.875rem;
}

.dev-info-box a {
    color: var(--gateway-primary-color);
    text-decoration: none;
}

.dev-info-box a:hover {
    text-decoration: underline;
}

.dev-form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
    display: block;
}

.dev-form-input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.9375rem;
    transition: all 0.2s;
    background: #ffffff;
}

.dev-form-input:focus {
    outline: none;
    border-color: var(--gateway-primary-color);
    box-shadow: 0 0 0 3px rgba(var(--gateway-primary-color-rgb), 0.1);
}

.dev-form-textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.875rem;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    transition: all 0.2s;
    background: #ffffff;
    resize: vertical;
}

.dev-form-textarea:focus {
    outline: none;
    border-color: var(--gateway-primary-color);
    box-shadow: 0 0 0 3px rgba(var(--gateway-primary-color-rgb), 0.1);
}

.dev-btn-group {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.dev-btn-secondary {
    padding: 0.5rem 1rem;
    background: #ffffff;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    color: #374151;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.dev-btn-secondary:hover {
    background: #f9fafb;
    border-color: var(--gateway-primary-color);
    color: var(--gateway-primary-color);
}

.dev-btn-primary {
    padding: 0.75rem 1.5rem;
    background: var(--gateway-primary-color);
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 0.9375rem;
    font-weight: 500;
    cursor: pointer;
    transition: opacity 0.2s;
}

.dev-btn-primary:hover {
    opacity: 0.9;
}

.dev-btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.dev-result-box {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 1.5rem;
}

.dev-result-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.dev-result-header strong {
    font-size: 1rem;
    color: #1a1a1a;
}

.dev-result-close {
    background: none;
    border: none;
    color: #6b7280;
    cursor: pointer;
    padding: 0.25rem;
    line-height: 1;
}

.dev-result-close:hover {
    color: #374151;
}

.dev-alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.dev-alert-success {
    background: #d1fae5;
    border: 1px solid #86efac;
    color: #065f46;
}

.dev-alert-danger {
    background: #fee2e2;
    border: 1px solid #fca5a5;
    color: #991b1b;
}

.dev-code-result {
    background: #1a1a1a;
    color: #e5e7eb;
    padding: 1rem;
    border-radius: 8px;
    overflow-x: auto;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 0.875rem;
    max-height: 300px;
    overflow-y: auto;
}

.dev-sidebar-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.5rem;
    height: 100%;
}

.dev-sidebar-title {
    font-size: 1rem;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 0.75rem;
}

.dev-sidebar-text {
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.dev-sidebar-list {
    list-style: none;
    padding: 0;
    margin: 0;
    font-size: 0.875rem;
    color: #374151;
}

.dev-sidebar-list li {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.dev-sidebar-list li:last-child {
    border-bottom: none;
}
</style>

<div class="dev-page-container">
    <div class="dev-page-header">
        <h1>Área de Testes (Sandbox)</h1>
        <a href="{{ route('admin.dev.index') }}" class="dev-page-back">
            <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i>
            Voltar
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <div>
            <div class="dev-content-section">
                <h2 class="dev-section-title">Disparo de Webhook de Teste</h2>
                <p class="dev-section-text">
                    Envie webhooks simulando eventos reais (Pix-In, Pix-Out, etc.) para URLs de homologação.
                </p>
                
                <div class="dev-info-box">
                    <strong>Precisa de uma URL para testar?</strong>
                    <ul>
                        <li><strong>Webhook.site:</strong> <a href="https://webhook.site" target="_blank" rel="noopener">https://webhook.site</a> - Gere uma URL única e veja as requisições em tempo real</li>
                        <li><strong>RequestBin:</strong> <a href="https://requestbin.com" target="_blank" rel="noopener">https://requestbin.com</a> - Crie um bin e receba webhooks</li>
                        <li><strong>ngrok:</strong> Para expor servidor local: <code style="background: #dbeafe; padding: 0.125rem 0.375rem; border-radius: 4px;">ngrok http 8000</code></li>
                    </ul>
                </div>
                    
                <form id="webhookTestForm">
                    @csrf
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                        <div>
                            <label class="dev-form-label">URL de destino <span style="color: #ef4444;">*</span></label>
                            <input type="url" name="url" id="webhookUrl" class="dev-form-input" 
                                placeholder="https://meu-teste.ngrok.app/webhook" required>
                        </div>
                        <div>
                            <label class="dev-form-label">Método HTTP <span style="color: #ef4444;">*</span></label>
                            <select name="method" id="webhookMethod" class="dev-form-input" required>
                                <option value="POST" selected>POST</option>
                                <option value="GET">GET</option>
                                <option value="PUT">PUT</option>
                            </select>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 1.5rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                            <label class="dev-form-label" style="margin: 0;">Payload (JSON) <span style="color: #ef4444;">*</span></label>
                            <div class="dev-btn-group">
                                <button type="button" class="dev-btn-secondary" onclick="loadExample('pix_in_paid')">
                                    Pix-In Pago
                                </button>
                                <button type="button" class="dev-btn-secondary" onclick="loadExample('pix_out_paid')">
                                    Pix-Out Pago
                                </button>
                                <button type="button" class="dev-btn-secondary" onclick="loadExample('pix_out_failed')">
                                    Pix-Out Falhado
                                </button>
                            </div>
                        </div>
                        <textarea name="payload" id="webhookPayload" class="dev-form-textarea" 
                            rows="12" spellcheck="false" required></textarea>
                        <small style="color: #9ca3af; font-size: 0.75rem; display: block; margin-top: 0.5rem;">Use os botões acima para carregar exemplos de payloads.</small>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <button type="submit" class="dev-btn-primary" id="sendWebhookBtn">
                            <i data-lucide="send" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 0.5rem;"></i>
                            Enviar Webhook
                        </button>
                        <button type="button" class="dev-btn-secondary" onclick="formatJSON()">
                            <i data-lucide="code" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 0.25rem;"></i>
                            Formatar JSON
                        </button>
                    </div>
                </form>

                <!-- Resultado do envio -->
                <div id="webhookResult" style="display: none;">
                    <div class="dev-result-box">
                        <div class="dev-result-header">
                            <strong>Resultado do Envio</strong>
                            <button type="button" class="dev-result-close" onclick="closeResult()">
                                <i data-lucide="x" style="width: 18px; height: 18px;"></i>
                            </button>
                        </div>
                        <div id="resultContent"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div>
            <div class="dev-sidebar-card" style="margin-bottom: 1.5rem;">
                <h3 class="dev-sidebar-title">Teste de Token e Secret</h3>
                <p class="dev-sidebar-text">
                    Valide tokens e secrets de clientes para diagnosticar erros de autenticação na API.
                </p>
                
                <form id="tokenSecretTestForm">
                    @csrf
                    <div style="margin-bottom: 1rem;">
                        <label class="dev-form-label">Token (clientId) <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="token" id="tokenInput" class="dev-form-input" 
                            placeholder="ci__52f6a783-55d6-460a-bcd7-ef227b5c19a" required>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label class="dev-form-label">Secret <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="secret" id="secretInput" class="dev-form-input" 
                            placeholder="cs__7aa42076-a07c-4d8b-ae27-d5650ce61d2c" required>
                    </div>
                    <button type="submit" class="dev-btn-primary" style="width: 100%;" id="testTokenBtn">
                        <i data-lucide="search" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 0.5rem;"></i>
                        Testar Token/Secret
                    </button>
                </form>

                <!-- Resultado do teste -->
                <div id="tokenSecretResult" style="display: none; margin-top: 1rem;">
                    <div class="dev-result-box">
                        <div class="dev-result-header">
                            <strong>Resultado do Teste</strong>
                            <button type="button" class="dev-result-close" onclick="closeTokenResult()">
                                <i data-lucide="x" style="width: 18px; height: 18px;"></i>
                            </button>
                        </div>
                        <div id="tokenResultContent"></div>
                    </div>
                </div>
            </div>

            <div class="dev-sidebar-card">
                <h3 class="dev-sidebar-title">Simulação de Fluxos</h3>
                <p class="dev-sidebar-text">
                    Reserve este espaço para criar simuladores de fluxo ponta a ponta:
                    criação de cobrança, pagamento, liquidação, estorno, etc.
                </p>
                <ul class="dev-sidebar-list">
                    <li>Simulação de recebimento Pix completo</li>
                    <li>Simulação de saque com retorno de erro</li>
                    <li>Stress test em ambiente de homologação</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
// Exemplos de payloads baseados nos eventos reais do sistema
const payloadExamples = {
    pix_in_paid: {
        "status": "paid",
        "idTransaction": "TXN-" + Date.now(),
        "typeTransaction": "PIX",
        "product": {
            "name": "produto-teste",
            "price": 100.00
        },
        "client": {
            "cpf": "12345678900",
            "name": "Cliente Teste",
            "email": "cliente@teste.com",
            "phone": "5511999999999"
        }
    },
    pix_out_paid: {
        "status": "paid",
        "idTransaction": "TXN-OUT-" + Date.now(),
        "typeTransaction": "PIX_OUT",
        "amount": 50.00,
        "client": {
            "name": "Cliente Teste",
            "cpf": "12345678900"
        }
    },
    pix_out_failed: {
        "status": "failed",
        "idTransaction": "TXN-OUT-" + Date.now(),
        "typeTransaction": "PIX_OUT",
        "amount": 50.00,
        "error": "Saldo insuficiente",
        "client": {
            "name": "Cliente Teste",
            "cpf": "12345678900"
        }
    }
};

function loadExample(type) {
    const payload = payloadExamples[type];
    if (payload) {
        document.getElementById('webhookPayload').value = JSON.stringify(payload, null, 2);
    }
}

function formatJSON() {
    const textarea = document.getElementById('webhookPayload');
    try {
        const json = JSON.parse(textarea.value);
        textarea.value = JSON.stringify(json, null, 2);
        showToast('success', 'JSON formatado com sucesso!');
    } catch (e) {
        showToast('error', 'JSON inválido: ' + e.message);
    }
}

function closeResult() {
    document.getElementById('webhookResult').style.display = 'none';
}

document.getElementById('webhookTestForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('sendWebhookBtn');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Enviando...';
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route("admin.dev.sandbox.send-webhook") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        // Mostra o resultado
        const resultDiv = document.getElementById('webhookResult');
        const resultContent = document.getElementById('resultContent');
        
        let html = '';
        if (data.success) {
            html = `
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i><strong>Sucesso!</strong> ${data.message}
                </div>
                <div class="mb-2">
                    <strong>Status HTTP:</strong> <span class="badge bg-success">${data.status}</span>
                </div>
            `;
        } else {
            html = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i><strong>Erro!</strong> ${data.message}
                </div>
                ${data.status ? `<div class="mb-2"><strong>Status HTTP:</strong> <span class="badge bg-danger">${data.status}</span></div>` : ''}
            `;
        }
        
        if (data.body) {
            html += `
                <div style="margin-top: 1rem;">
                    <strong style="display: block; margin-bottom: 0.5rem;">Resposta do servidor:</strong>
                    <pre class="dev-code-result"><code>${escapeHtml(data.body)}</code></pre>
                </div>
            `;
        }
        
        resultContent.innerHTML = html;
        resultDiv.style.display = 'block';
        
        // Scroll para o resultado
        resultDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        
        showToast(data.success ? 'success' : 'error', data.message);
        
    } catch (error) {
        showToast('error', 'Erro ao enviar webhook: ' + error.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
});

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Carrega exemplo padrão ao abrir a página
document.addEventListener('DOMContentLoaded', function() {
    loadExample('pix_in_paid');
    lucide.createIcons();
});

// Teste de Token e Secret
document.getElementById('tokenSecretTestForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('testTokenBtn');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Testando...';
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route("admin.dev.sandbox.test-token-secret") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        // Mostra o resultado
        const resultDiv = document.getElementById('tokenSecretResult');
        const resultContent = document.getElementById('tokenResultContent');
        
        let html = '';
        
        // Status geral
        if (data.success) {
            html = `<div class="dev-alert dev-alert-success">
                <strong>Sucesso!</strong> ${data.message}
            </div>`;
        } else {
            html = `<div class="dev-alert dev-alert-danger">
                <strong>Erro!</strong> ${data.message || 'Token ou Secret inválidos'}
            </div>`;
        }
        
        // Passos de validação
        if (data.validation_steps && data.validation_steps.length > 0) {
            html += '<div style="margin-top: 1rem;"><strong style="display: block; margin-bottom: 0.75rem;">Passos de Validação:</strong><ul style="list-style: none; padding: 0; margin: 0;">';
            data.validation_steps.forEach(step => {
                const color = step.status === 'success' ? '#10b981' : 
                             step.status === 'error' ? '#ef4444' : '#3b82f6';
                html += `<li style="padding: 0.5rem 0; border-bottom: 1px solid #f3f4f6; padding-left: 1.5rem; position: relative;">
                    <span style="position: absolute; left: 0; color: ${color};">→</span>
                    <strong>${step.name}:</strong> ${step.message}
                </li>`;
            });
            html += '</ul></div>';
        }
        
        // Erros
        if (data.errors && data.errors.length > 0) {
            html += '<div style="margin-top: 1rem;"><strong style="color: #ef4444; display: block; margin-bottom: 0.75rem;">Erros encontrados:</strong><ul style="list-style: none; padding: 0; margin: 0;">';
            data.errors.forEach(error => {
                html += `<li style="padding: 0.5rem 0; color: #ef4444; border-bottom: 1px solid #f3f4f6; padding-left: 1.5rem; position: relative;">
                    <span style="position: absolute; left: 0;">→</span>
                    ${error}
                </li>`;
            });
            html += '</ul></div>';
        }
        
        // Avisos
        if (data.warnings && data.warnings.length > 0) {
            html += '<div style="margin-top: 1rem;"><strong style="color: #f59e0b; display: block; margin-bottom: 0.75rem;">Avisos:</strong><ul style="list-style: none; padding: 0; margin: 0;">';
            data.warnings.forEach(warning => {
                html += `<li style="padding: 0.5rem 0; color: #f59e0b; border-bottom: 1px solid #f3f4f6; padding-left: 1.5rem; position: relative;">
                    <span style="position: absolute; left: 0;">→</span>
                    ${warning}
                </li>`;
            });
            html += '</ul></div>';
        }
        
        // Dados do usuário (se encontrado)
        if (data.user_found && data.user_data) {
            html += `
                <div style="margin-top: 1rem;">
                    <strong style="display: block; margin-bottom: 0.75rem;">Informações do Usuário:</strong>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tbody>
                                <tr style="border-bottom: 1px solid #f3f4f6;"><td style="padding: 0.75rem 0.5rem; font-weight: 600; color: #374151;">ID:</td><td style="padding: 0.75rem 0.5rem; color: #6b7280;">${data.user_data.id}</td></tr>
                                <tr style="border-bottom: 1px solid #f3f4f6;"><td style="padding: 0.75rem 0.5rem; font-weight: 600; color: #374151;">Nome:</td><td style="padding: 0.75rem 0.5rem; color: #6b7280;">${data.user_data.name || 'N/A'}</td></tr>
                                <tr style="border-bottom: 1px solid #f3f4f6;"><td style="padding: 0.75rem 0.5rem; font-weight: 600; color: #374151;">Email:</td><td style="padding: 0.75rem 0.5rem; color: #6b7280;">${data.user_data.email || 'N/A'}</td></tr>
                                <tr style="border-bottom: 1px solid #f3f4f6;"><td style="padding: 0.75rem 0.5rem; font-weight: 600; color: #374151;">Username:</td><td style="padding: 0.75rem 0.5rem; color: #6b7280;">${data.user_data.username || 'N/A'}</td></tr>
                                <tr style="border-bottom: 1px solid #f3f4f6;"><td style="padding: 0.75rem 0.5rem; font-weight: 600; color: #374151;">Status:</td><td style="padding: 0.75rem 0.5rem;"><span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; background: ${data.user_data.status === 'aprovado' ? '#d1fae5' : '#fef3c7'}; color: ${data.user_data.status === 'aprovado' ? '#065f46' : '#92400e'};">${data.user_data.status}</span></td></tr>
                                <tr style="border-bottom: 1px solid #f3f4f6;"><td style="padding: 0.75rem 0.5rem; font-weight: 600; color: #374151;">Banido:</td><td style="padding: 0.75rem 0.5rem;"><span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; background: ${data.user_data.banido ? '#fee2e2' : '#d1fae5'}; color: ${data.user_data.banido ? '#991b1b' : '#065f46'};">${data.user_data.banido ? 'Sim' : 'Não'}</span></td></tr>
                                <tr style="border-bottom: 1px solid #f3f4f6;"><td style="padding: 0.75rem 0.5rem; font-weight: 600; color: #374151;">Permissão:</td><td style="padding: 0.75rem 0.5rem;"><span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; background: #dbeafe; color: #1e40af;">${data.user_data.permission}</span></td></tr>
                                <tr style="border-bottom: 1px solid #f3f4f6;"><td style="padding: 0.75rem 0.5rem; font-weight: 600; color: #374151;">Token no BD:</td><td style="padding: 0.75rem 0.5rem;"><code style="background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">${data.user_data.clientId || 'N/A'}</code></td></tr>
                                <tr style="border-bottom: 1px solid #f3f4f6;"><td style="padding: 0.75rem 0.5rem; font-weight: 600; color: #374151;">Tamanho Secret:</td><td style="padding: 0.75rem 0.5rem; color: #6b7280;">${data.user_data.secret_length} caracteres</td></tr>
                                <tr><td style="padding: 0.75rem 0.5rem; font-weight: 600; color: #374151;">Cadastrado em:</td><td style="padding: 0.75rem 0.5rem; color: #6b7280;">${data.user_data.created_at || 'N/A'}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        }
        
        resultContent.innerHTML = html;
        resultDiv.style.display = 'block';
        
        // Scroll para o resultado
        resultDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        
        showToast(data.success ? 'success' : 'error', data.message || 'Teste concluído');
        
    } catch (error) {
        showToast('error', 'Erro ao testar token/secret: ' + error.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
});

function closeTokenResult() {
    document.getElementById('tokenSecretResult').style.display = 'none';
}

function closeResult() {
    document.getElementById('webhookResult').style.display = 'none';
}
</script>
@endsection

