@extends('layouts.app')

@section('content')
<style>
.dev-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem;
}

.dev-header {
    margin-bottom: 3rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid rgba(0,0,0,0.08);
}

.dev-header h1 {
    font-size: 2rem;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 0.5rem;
}

.dev-header p {
    color: #6b7280;
    font-size: 1rem;
    margin: 0;
}

.dev-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1.5rem;
}

.dev-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 2rem;
    transition: all 0.2s ease;
    text-decoration: none;
    display: block;
    color: inherit;
}

.dev-card:hover {
    border-color: var(--gateway-primary-color);
    transform: translateY(-2px);
    color: inherit;
    text-decoration: none;
}

.dev-card-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--gateway-primary-color)15, var(--gateway-primary-color));
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.25rem;
    color: var(--gateway-primary-color);
}

.dev-card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 0.5rem;
}

.dev-card-description {
    color: #6b7280;
    font-size: 0.9375rem;
    line-height: 1.6;
    margin: 0;
}
</style>

<div class="dev-container">
    <div class="dev-header">
        <h1>Área de Desenvolvedor</h1>
        <p>Documentação técnica, ferramentas de teste e guias de integração para desenvolvimento</p>
    </div>

    <div class="dev-grid">
        <a href="{{ route('admin.dev.manual') }}" class="dev-card">
            <div class="dev-card-icon">
                <i data-lucide="book-open" style="width: 24px; height: 24px;"></i>
            </div>
            <h3 class="dev-card-title">Manual do Desenvolvedor</h3>
            <p class="dev-card-description">Guia técnico completo com autenticação, endpoints, exemplos e boas práticas</p>
        </a>

        <a href="{{ route('admin.dev.pix-in') }}" class="dev-card">
            <div class="dev-card-icon">
                <i data-lucide="arrow-down-circle" style="width: 24px; height: 24px;"></i>
            </div>
            <h3 class="dev-card-title">Pix-In</h3>
            <p class="dev-card-description">Fluxo completo de recebimento: criação de cobranças, callbacks e conciliação</p>
        </a>

        <a href="{{ route('admin.dev.pix-out') }}" class="dev-card">
            <div class="dev-card-icon">
                <i data-lucide="arrow-up-circle" style="width: 24px; height: 24px;"></i>
            </div>
            <h3 class="dev-card-title">Pix-Out</h3>
            <p class="dev-card-description">Processo de saques, validações, filas de processamento e tratamento de erros</p>
        </a>

        <a href="{{ route('admin.dev.webhooks') }}" class="dev-card">
            <div class="dev-card-icon">
                <i data-lucide="webhook" style="width: 24px; height: 24px;"></i>
            </div>
            <h3 class="dev-card-title">Webhooks</h3>
            <p class="dev-card-description">Eventos disponíveis, formatos de payload, autenticação e ferramentas de teste</p>
        </a>

        <a href="{{ route('admin.dev.adquirentes') }}" class="dev-card">
            <div class="dev-card-icon">
                <i data-lucide="building-2" style="width: 24px; height: 24px;"></i>
            </div>
            <h3 class="dev-card-title">Adquirentes</h3>
            <p class="dev-card-description">Padrões e guias para implementação de novos adquirentes de pagamento</p>
        </a>

        <a href="{{ route('admin.dev.sandbox') }}" class="dev-card">
            <div class="dev-card-icon">
                <i data-lucide="flask-conical" style="width: 24px; height: 24px;"></i>
            </div>
            <h3 class="dev-card-title">Área de Testes</h3>
            <p class="dev-card-description">Ferramentas para testar webhooks, validar tokens e simular fluxos completos</p>
        </a>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection

