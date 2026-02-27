@extends('layouts.app')

@section('title', 'Área de Membros - ' . $produto->name)

@section('content')
<div class="area-membros-page">
    {{-- Header compacto e sofisticado --}}
    <header class="am-header">
        <a href="{{ route('produtos.index.edit', ['uuid' => $produto->uuid]) }}" class="am-back" aria-label="Voltar para editar produto">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Voltar</span>
        </a>
        <div class="am-title-wrap">
            <h1 class="am-title">Área de Membros</h1>
            <p class="am-subtitle">{{ $produto->name }}</p>
        </div>
        <a href="{{ route('produtos.index.edit', ['uuid' => $produto->uuid]) }}" class="am-btn-edit">
            <i class="fa-solid fa-pen"></i>
            <span>Editar produto</span>
        </a>
    </header>

    {{-- Abas compactas --}}
    <nav class="am-tabs-wrap">
        <ul class="nav am-tabs" id="areaMembrosTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="customizacao-tab" data-bs-toggle="tab" data-bs-target="#customizacao" type="button" role="tab" aria-controls="customizacao" aria-selected="true">Customização</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="alunos-am-tab" data-bs-toggle="tab" data-bs-target="#alunos" type="button" role="tab" aria-controls="alunos" aria-selected="false">Alunos</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="modulos-tab" data-bs-toggle="tab" data-bs-target="#modulos" type="button" role="tab" aria-controls="modulos" aria-selected="false">Módulos</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="chat-tab" data-bs-toggle="tab" data-bs-target="#chat" type="button" role="tab" aria-controls="chat" aria-selected="false">Chat</button>
            </li>
        </ul>
    </nav>

    {{-- Conteúdo com animação --}}
    <div class="tab-content am-content" id="areaMembrosTabContent">
        <div class="tab-pane fade show active" id="customizacao" role="tabpanel" aria-labelledby="customizacao-tab">
            @include('pages.produtos.components.area-membros-customizacao', compact('produto'))
        </div>
        <div class="tab-pane fade" id="alunos" role="tabpanel" aria-labelledby="alunos-am-tab">
            @include('pages.produtos.components.alunos', compact('produto'))
        </div>
        <div class="tab-pane fade" id="modulos" role="tabpanel" aria-labelledby="modulos-tab">
            @include('pages.produtos.components.area-membros-modulos', compact('produto'))
        </div>
        <div class="tab-pane fade" id="chat" role="tabpanel" aria-labelledby="chat-tab">
            @include('pages.produtos.components.area-membros-chat', compact('produto', 'alunos', 'lastMessagesByAluno'))
        </div>
    </div>
</div>

<style>
/* ========== Área de Membros - Minimalista, sem sombra ========== */
.area-membros-page {
    --am-border: rgba(165, 170, 177, 0.18);
    --am-text: var(--gateway-text-color);
    --am-muted: #64748b;
    --am-accent: var(--gateway-primary-color);
    --am-radius: 8px;
    padding-bottom: 2rem;
    background: transparent;
}
body.dark-mode .area-membros-page {
    --am-border: rgba(30, 41, 59, 0.6);
    --am-muted: #94a3b8;
}

.am-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}
.am-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.45rem 0.7rem;
    border: 1px solid var(--am-border);
    border-radius: var(--am-radius);
    color: var(--am-accent);
    font-size: 0.8125rem;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.2s ease, border-color 0.2s ease;
}
.am-back:hover { background: var(--gateway-primary-opacity2, rgba(0,0,0,0.04)); border-color: var(--am-accent); color: var(--am-accent); }
.am-title-wrap { flex: 1; min-width: 0; }
.am-title { font-size: 1.125rem; font-weight: 600; color: var(--am-text); margin: 0 0 0.1rem 0; }
.am-subtitle { font-size: 0.75rem; color: var(--am-muted); margin: 0; }
.am-btn-edit {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.45rem 0.85rem;
    font-size: 0.8125rem;
    font-weight: 500;
    border: 1px solid var(--am-border);
    border-radius: var(--am-radius);
    color: var(--am-accent);
    text-decoration: none;
    transition: background 0.2s ease, border-color 0.2s ease;
}
.am-btn-edit:hover { background: var(--gateway-primary-opacity2, rgba(0,0,0,0.04)); border-color: var(--am-accent); color: var(--am-accent); }

.am-tabs-wrap {
    margin-bottom: 1rem;
    border-bottom: 1px solid var(--am-border);
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.am-tabs {
    display: flex;
    flex-wrap: nowrap;
    gap: 0.1rem;
    padding: 0;
    margin: 0;
    list-style: none;
    min-width: min-content;
}
.am-tabs .nav-item { flex-shrink: 0; }
.am-tabs .nav-link {
    padding: 0.45rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--am-muted);
    border: none;
    border-bottom: 2px solid transparent;
    background: transparent;
    border-radius: 6px 6px 0 0;
    transition: color 0.2s ease, border-color 0.2s ease, background 0.2s ease;
    white-space: nowrap;
}
.am-tabs .nav-link:hover { color: var(--am-accent); background: var(--gateway-primary-opacity2, rgba(0,0,0,0.03)); }
.am-tabs .nav-link.active {
    color: var(--am-accent);
    border-bottom-color: var(--am-accent);
    background: transparent;
}

.am-content { position: relative; }
.am-content .tab-pane { padding-top: 0.5rem; }
.am-content .tab-pane.fade:not(.show) { display: none; }
.am-content .tab-pane.fade.show { animation: amFadeIn 0.3s ease; }
@keyframes amFadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Cards internos: sem sombra, só borda fina */
.area-membros-page .card {
    border: 1px solid var(--am-border);
    border-radius: var(--am-radius);
    margin-bottom: 1rem;
    overflow: visible;
    background: transparent;
    box-shadow: none;
}
body.dark-mode .area-membros-page .card {
    border-color: rgba(30, 41, 59, 0.6);
    background: transparent;
    box-shadow: none;
}
.area-membros-page .card-header {
    padding: 0.65rem 1rem;
    border-bottom: 1px solid var(--am-border);
    background: transparent;
    border-radius: var(--am-radius) var(--am-radius) 0 0;
}
body.dark-mode .area-membros-page .card-header { border-bottom-color: rgba(30, 41, 59, 0.5); }
.area-membros-page .card-body { padding: 1rem 1.25rem; }

@media (max-width: 576px) {
    .am-header { gap: 0.75rem; }
    .am-back span, .am-btn-edit span { display: none; }
    .am-title { font-size: 1rem; }
    .am-tabs .nav-link { padding: 0.4rem 0.6rem; font-size: 0.7rem; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var hash = window.location.hash;
    var urlParams = new URLSearchParams(window.location.search);
    var tabParam = urlParams.get('tab');
    var targetTab = hash || (tabParam ? '#' + tabParam : null);
    if (targetTab) {
        var trigger = document.querySelector('button.nav-link[data-bs-target="' + targetTab + '"]');
        if (trigger) new bootstrap.Tab(trigger).show();
    }
    document.querySelectorAll('#areaMembrosTab button[data-bs-toggle="tab"]').forEach(function(btn) {
        btn.addEventListener('shown.bs.tab', function(e) {
            history.replaceState(null, null, e.target.getAttribute('data-bs-target'));
        });
    });
    @if(session('success'))
        if (typeof showToast === 'function') showToast('success', '{{ addslashes(session('success')) }}'); else alert('{{ addslashes(session('success')) }}');
    @endif
    @if(session('error'))
        if (typeof showToast === 'function') showToast('error', '{{ addslashes(session('error')) }}'); else alert('{{ addslashes(session('error')) }}');
    @endif
});
</script>
@endsection
