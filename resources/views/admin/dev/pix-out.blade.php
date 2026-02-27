@extends('layouts.app')

@section('content')
<style>
.dev-page-container {
    max-width: 1200px;
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

.dev-content-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 1rem;
}

.dev-content-text {
    color: #6b7280;
    font-size: 0.9375rem;
    line-height: 1.7;
    margin: 0;
}

.dev-content-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.dev-content-list li {
    padding: 0.75rem 0;
    color: #374151;
    font-size: 0.9375rem;
    border-bottom: 1px solid #f3f4f6;
    padding-left: 1.5rem;
    position: relative;
}

.dev-content-list li:last-child {
    border-bottom: none;
}

.dev-content-list li:before {
    content: "→";
    position: absolute;
    left: 0;
    color: var(--gateway-primary-color);
    font-weight: 600;
}

.dev-section-label {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #9ca3af;
    margin-bottom: 1rem;
    display: block;
}
</style>

<div class="dev-page-container">
    <div class="dev-page-header">
        <h1>Pix-Out (Saídas)</h1>
        <a href="{{ route('admin.dev.index') }}" class="dev-page-back">
            <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i>
            Voltar
        </a>
    </div>

    <div class="dev-content-section">
        <h2 class="dev-content-title">Fluxo de Saída</h2>
        <p class="dev-content-text">
            Detalhe aqui o fluxo de saques, filas de processamento, validações de KYC/KYB,
            limites, antifraude e reconciliação com o provedor de Pix-out.
        </p>
    </div>

    <div class="dev-content-section">
        <span class="dev-section-label">Sugestões de Conteúdo</span>
        <ul class="dev-content-list">
            <li>Como a solicitação de saque é criada e enviada ao provedor</li>
            <li>Estados possíveis de uma saída (pendente, processando, pago, falhado...)</li>
            <li>Regras de reprocessamento e retentativas</li>
            <li>Logs importantes para monitoramento</li>
        </ul>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection

