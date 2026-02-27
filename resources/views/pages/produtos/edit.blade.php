@extends('layouts.app')

@section('title', 'Edição de produto')

@section('content')
<div class="produto-edit">
    <form action="{{ route('produtos.edit', ['id' => $produto->id]) }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')

        {{-- Header compacto --}}
        <header class="produto-edit-header">
            <button type="button" class="produto-edit-back" onclick="window.location.href='{{ route('produtos.index') }}'" aria-label="Voltar">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Voltar</span>
            </button>
            <h1 class="produto-edit-title">{{ $produto->name }}</h1>
            <button class="produto-edit-save btn btn-primary" type="submit">
                <i class="fa-solid fa-check"></i>
                <span>Salvar</span>
            </button>
        </header>

        {{-- Layout principal com sidebar e conteúdo --}}
        <div class="produto-edit-container">
            {{-- Sidebar de navegação vertical --}}
            <div class="produto-edit-sidebar">
                <nav class="produto-edit-nav">
                    <ul class="nav produto-edit-tabs-vertical" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="configuracoes-tab" data-bs-toggle="tab" href="#geral" role="tab">
                                <i class="fa-solid fa-gear"></i>
                                <span>Configurações</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link produto-edit-tab-external" href="{{ route('produtos.area-membros', ['uuid' => $produto->uuid]) }}" role="tab">
                                <i class="fa-solid fa-users"></i>
                                <span>Área de Membros</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="alunos-tab" data-bs-toggle="tab" href="#alunos" role="tab">
                                <i class="fa-solid fa-graduation-cap"></i>
                                <span>Alunos</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="checkout-tab" data-bs-toggle="tab" href="#checkout" role="tab">
                                <i class="fa-solid fa-shopping-cart"></i>
                                <span>Checkout</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pagamento-tab" data-bs-toggle="tab" href="#configuracoes" role="tab">
                                <i class="fa-solid fa-credit-card"></i>
                                <span>Pagamentos</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="orderbump-tab" data-bs-toggle="tab" href="#orderbump" role="tab">
                                <i class="fa-solid fa-plus-circle"></i>
                                <span>Order Bump</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="tracking-tab" data-bs-toggle="tab" href="#tracking" role="tab">
                                <i class="fa-solid fa-chart-line"></i>
                                <span>Trackeamento</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="coprodutor-tab" data-bs-toggle="tab" href="#coprodutor" role="tab">
                                <i class="fa-solid fa-handshake"></i>
                                <span>Coprodutor</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="affiliate-tab" data-bs-toggle="tab" href="#affiliate" role="tab">
                                <i class="fa-solid fa-share-nodes"></i>
                                <span>Afiliados</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="cupons-tab" data-bs-toggle="tab" href="#cupons" role="tab">
                                <i class="fa-solid fa-ticket"></i>
                                <span>Cupons</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="links-tab" data-bs-toggle="tab" href="#links" role="tab">
                                <i class="fa-solid fa-link"></i>
                                <span>Links</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            
            {{-- Conteúdo principal --}}
            <div class="produto-edit-main-content">

            {{-- Conteúdo das abas --}}
            <div class="tab-content produto-edit-content" id="myTabContent">
                <div class="tab-pane fade show active" id="geral" role="tabpanel">
                    @include('pages.produtos.components.geral', compact('produto', 'produtos'))
                </div>
                <div class="tab-pane fade" id="configuracoes" role="tabpanel">
                    @include('pages.produtos.components.configuracoes', compact('produto', 'produtos'))
                </div>
                <div class="tab-pane fade" id="orderbump" role="tabpanel">
                    @include('pages.produtos.components.order-bump', compact('produtos', 'produto'))
                </div>
                <div class="tab-pane fade" id="checkout" role="tabpanel">
                    @include('pages.produtos.components.checkout', compact('produtos', 'produto'))
                </div>
                <div class="tab-pane fade" id="tracking" role="tabpanel">
                    @include('pages.produtos.components.tracking', compact('produtos', 'produto'))
                </div>
                <div class="tab-pane fade" id="alunos" role="tabpanel">
                    @include('pages.produtos.components.alunos', compact('produto'))
                </div>
                <div class="tab-pane fade" id="affiliate" role="tabpanel">
                    @include('pages.produtos.components.affiliate', compact('produtos', 'produto'))
                </div>
                <div class="tab-pane fade" id="coprodutor" role="tabpanel">
                    @include('pages.produtos.components.coprodutor', compact('produtos', 'produto'))
                </div>
                <div class="tab-pane fade" id="cupons" role="tabpanel">
                    @include('pages.produtos.components.cupom', compact('produtos', 'produto'))
                </div>
                <div class="tab-pane fade" id="links" role="tabpanel">
                    @include('pages.produtos.components.links', compact('produtos', 'produto'))
                </div>
            </div>
            </div>
        </div>
    </form>
</div>

<style>
/* ========== Produto Edit - Layout com Sidebar Vertical ========== */
.produto-edit {
    --pe-bg: var(--gateway-background-color);
    --pe-surface: var(--gateway-sidebar-color);
    --pe-border: rgba(165, 170, 177, 0.2);
    --pe-text: var(--gateway-text-color);
    --pe-text-muted: #94a3b8;
    --pe-accent: var(--gateway-primary-color);
    --pe-radius: 12px;
    --pe-space: 1rem;
    padding-bottom: 2rem;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

body.dark-mode .produto-edit {
    --pe-bg: #0f172a;
    --pe-surface: #0f172a;
    --pe-border: rgba(30, 41, 59, 0.8);
    --pe-text: #e2e8f0;
    --pe-text-muted: #94a3b8;
    background-color: #020617;
}

body.dark-mode .produto-edit-container {
    background-color: #020617;
}

body.dark-mode .produto-edit-main-content {
    background-color: #020617;
}

body.dark-mode .produto-edit-content .tab-pane {
    background-color: #0f172a;
    border: 1px solid #1e293b;
}

/* Texto claro em todo o bloco de edição no dark mode */
body.dark-mode .produto-edit,
body.dark-mode .produto-edit .produto-edit-header,
body.dark-mode .produto-edit .produto-edit-title,
body.dark-mode .produto-edit .produto-edit-back,
body.dark-mode .produto-edit .nav-link,
body.dark-mode .produto-edit .nav-link span,
body.dark-mode .produto-edit .nav-link i,
body.dark-mode .produto-edit h1,
body.dark-mode .produto-edit h2,
body.dark-mode .produto-edit h3,
body.dark-mode .produto-edit h4,
body.dark-mode .produto-edit h5,
body.dark-mode .produto-edit h6,
body.dark-mode .produto-edit label,
body.dark-mode .produto-edit .form-label,
body.dark-mode .produto-edit p,
body.dark-mode .produto-edit span,
body.dark-mode .produto-edit small,
body.dark-mode .produto-edit .text-muted,
body.dark-mode .produto-edit .pe-section-title,
body.dark-mode .produto-edit .pe-section-desc,
body.dark-mode .produto-edit .pe-field label,
body.dark-mode .produto-edit .pe-hint,
body.dark-mode .produto-edit .form-control,
body.dark-mode .produto-edit .form-select,
body.dark-mode .produto-edit input,
body.dark-mode .produto-edit select,
body.dark-mode .produto-edit textarea,
body.dark-mode .produto-edit td,
body.dark-mode .produto-edit th,
body.dark-mode .produto-edit .card-title,
body.dark-mode .produto-edit .table,
body.dark-mode .produto-edit .offcanvas-title,
body.dark-mode .produto-edit .modal-title,
body.dark-mode .produto-edit .modal-body,
body.dark-mode .produto-edit .offcanvas-body {
    color: #e2e8f0 !important;
}

body.dark-mode .produto-edit .text-muted,
body.dark-mode .produto-edit .pe-section-desc,
body.dark-mode .produto-edit .pe-hint {
    color: #94a3b8 !important;
}

body.dark-mode .produto-edit .produto-edit-back,
body.dark-mode .produto-edit .nav-link.active,
body.dark-mode .produto-edit .nav-link:hover {
    color: var(--gateway-primary-color) !important;
}

body.dark-mode .produto-edit .nav-link i {
    color: inherit !important;
}

body.dark-mode .produto-edit .text-danger {
    color: #f87171 !important;
}

body.dark-mode .produto-edit .form-control::placeholder,
body.dark-mode .produto-edit .form-control::-webkit-input-placeholder {
    color: #64748b !important;
}

.produto-edit .pe-section-body,
.produto-edit .pe-ob-body { border-color: var(--pe-border); }

.produto-edit-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.25rem;
    flex-wrap: wrap;
    padding: 0 1.5rem;
    padding-top: 1.5rem;
}

.produto-edit-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    background: transparent;
    border: 1px solid var(--pe-border);
    border-radius: var(--pe-radius);
    color: var(--pe-accent);
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}
.produto-edit-back:hover { 
    background: var(--gateway-primary-opacity2, rgba(0,0,0,0.05)); 
    border-color: var(--pe-accent); 
    color: var(--pe-accent);
    transform: translateY(-1px);
}

.produto-edit-title {
    flex: 1;
    min-width: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--pe-text);
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.produto-edit-save {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.25rem;
    font-size: 0.875rem;
    font-weight: 600;
    border-radius: var(--pe-radius);
    border: none;
    background: var(--pe-accent) !important;
    color: #fff !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    transition: all 0.2s ease;
}
.produto-edit-save:hover {
    color: #fff !important;
    background: var(--pe-accent) !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}
.produto-edit-save i { color: #fff !important; }

/* ========== Container Principal ========== */
.produto-edit-container {
    display: flex;
    flex: 1;
    overflow: hidden;
}

/* ========== Sidebar Vertical ========== */
.produto-edit-sidebar {
    width: 220px;
    background: var(--pe-surface);
    border-right: 1px solid var(--pe-border);
    padding: 1.5rem 0;
    flex-shrink: 0;
}

.produto-edit-nav {
    position: sticky;
    top: 1.5rem;
}

.produto-edit-tabs-vertical {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.produto-edit-tabs-vertical .nav-item {
    margin: 0;
}

.produto-edit-tabs-vertical .nav-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.25rem;
    color: var(--pe-text-muted);
    text-decoration: none;
    border-radius: 0;
    border: none;
    background: transparent;
    font-size: 0.8rem;
    font-weight: 500;
    transition: all 0.2s ease;
    position: relative;
    white-space: nowrap;
}

.produto-edit-tabs-vertical .nav-link i {
    width: 16px;
    text-align: center;
    font-size: 0.8rem;
    opacity: 0.7;
    transition: opacity 0.2s ease;
}

.produto-edit-tabs-vertical .nav-link span {
    flex: 1;
}

.produto-edit-tabs-vertical .nav-link:hover {
    color: var(--pe-accent);
    background: var(--gateway-primary-opacity2, rgba(0,0,0,0.04));
}

.produto-edit-tabs-vertical .nav-link:hover i {
    opacity: 1;
}

.produto-edit-tabs-vertical .nav-link.active {
    color: var(--pe-accent);
    background: var(--gateway-primary-opacity2, rgba(0,0,0,0.08));
    border-left: 3px solid var(--pe-accent);
}

.produto-edit-tabs-vertical .nav-link.active i {
    opacity: 1;
}

.produto-edit-tab-external:hover { color: var(--pe-accent); }

/* ========== Conteúdo Principal ========== */
.produto-edit-main-content {
    flex: 1;
    padding: 1.5rem;
    overflow-y: auto;
}

.produto-edit-content {
    animation: produtoEditFade 0.3s ease;
}

@keyframes produtoEditFade {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}

.produto-edit-content .tab-pane { 
    padding-top: 0.25rem;
    background: var(--pe-bg);
    border-radius: var(--pe-radius);
    padding: 1.5rem;
}

.produto-edit-content .tab-pane.fade:not(.show) { display: none; }
.produto-edit-content .tab-pane.fade.show { animation: produtoEditFade 0.3s ease; }

/* ========== Responsivo ========== */
@media (max-width: 768px) {
    .produto-edit-container {
        flex-direction: column;
    }
    
    .produto-edit-header {
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .produto-edit-sidebar {
        width: 100%;
        border-right: none;
        border-bottom: 1px solid var(--pe-border);
        padding: 1rem 0;
    }
    
    .produto-edit-nav {
        position: static;
    }
    
    .produto-edit-tabs-vertical {
        flex-direction: row;
        overflow-x: auto;
        gap: 0.5rem;
        padding: 0 1rem;
        justify-content: center;
    }
    
    .produto-edit-tabs-vertical .nav-link {
        flex-shrink: 0;
        padding: 0.75rem 1rem;
        border-radius: var(--pe-radius);
        border: 1px solid var(--pe-border);
        min-width: fit-content;
        justify-content: center;
        text-align: center;
    }
    
    .produto-edit-tabs-vertical .nav-link.active {
        border-left: none;
        border-color: var(--pe-accent);
        background: var(--pe-accent);
        color: white;
    }
    
    .produto-edit-tabs-vertical .nav-link i {
        width: auto;
        margin: 0;
        font-size: 1rem;
    }
    
    .produto-edit-tabs-vertical .nav-link span {
        display: none;
    }
    
    .produto-edit-main-content {
        padding: 1rem;
    }
    
    .produto-edit-content .tab-pane {
        padding: 1rem;
    }
}

@media (max-width: 480px) {
    .produto-edit-header {
        flex-direction: column;
        align-items: stretch;
        gap: 0.75rem;
    }
    
    .produto-edit-title {
        text-align: center;
        font-size: 1.1rem;
    }
    
    .produto-edit-back,
    .produto-edit-save {
        justify-content: center;
    }
}
</style>
@endsection

<script>
    (function() {
        history.scrollRestoration = 'manual';
        function scrollContentToTop() {
            var main = document.querySelector('main.content');
            if (main) main.scrollTop = 0;
            window.scrollTo(0, 0);
        }
        scrollContentToTop();
        document.addEventListener('DOMContentLoaded', function() {
            scrollContentToTop();
            setTimeout(scrollContentToTop, 0);
            setTimeout(scrollContentToTop, 50);
            setTimeout(scrollContentToTop, 150);
        });
        window.addEventListener('load', function() {
            scrollContentToTop();
            setTimeout(scrollContentToTop, 0);
        });
    })();
</script>

@if (session('tab'))
<script>
    document.addEventListener('DOMContentLoaded', function(){
        let tab = "{{ session('tab') }}";
        if (tab) {
            tab = tab.replace('tab-', '');
            const tabElement = document.getElementById(tab + '-tab');
            if (tabElement) {
                const tabTrigger = new bootstrap.Tab(tabElement);
                tabTrigger.show();
            }
        }
    })
</script>
@endif

<div class="offcanvas offcanvas-end" tabindex="-1" id="addCupon" aria-labelledby="addCuponLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="addCuponLabel">Adicionar cupom</h5>
        <button id="close-canva-add-cupon" type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form action="{{ route('produtos.cupons.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label for="price" class="form-label">Código</label>
                    <input type="text" class="form-control form-control-md" id="codigo" name="codigo">
                </div>
                <div class="col-12">
                    <label for="type-cupom-desconto" class="form-label">Tipo de desconto</label>
                    <select class="form-select form-control-md" id="type-cupom-desconto" name="type"
                        onchange="document.getElementById('lbl-desc').innerText = this.value === 'percent' ? 'Desconto (%)' : 'Desconto (R$)'">
                        <option value="fixed">Fixo (R$)</option>
                        <option value="percent">Porcentagem (%)</option>
                    </select>
                </div>
                <div class="col-12">
                    <label id="lbl-desc" for="cupom-desconto" class="form-label">Desconto (R$)</label>
                    <input type="text" class="form-control form-control-md" id="cupom-desconto" name="desconto">
                </div>
                <div class="col-12">
                    <label for="inicio" class="form-label">Data de início</label>
                    <input type="datetime-local" class="form-control form-control-md" id="inicio" name="inicio">
                </div>
                <div class="col-12">
                    <label for="fim" class="form-label">Data de fim</label>
                    <input type="datetime-local" class="form-control form-control-md" id="fim" name="fim">
                    <small class="text-muted">Vazio = validade eterna</small>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" name="aplicar_orderbumps" id="aplicar_orderbumps">
                        <label class="form-check-label" for="aplicar_orderbumps">Aplicar desconto ao Order Bumps</label>
                    </div>
                </div>
                <input id="input-produto-id-form-cadastrar-cupom" name="produto_id" type="hidden">
                <div class="col-12 d-flex gap-2 justify-content-end">
                    <button class="btn btn-secondary" type="button" onclick="document.getElementById('close-canva-add-cupon').click()">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Adicionar</button>
                </div>
            </div>
        </form>
    </div>
</div>

@foreach ($produto->cupons as $cupom)
    <div class="modal fade" id="delCupomModal{{ $cupom->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="delCupomModal{{ $cupom->id }}Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <form action="{{ route('produtos.cupons.del', ['id' => $cupom->id]) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fs-5" id="delCupomModal{{ $cupom->id }}Label">Excluir cupom</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Excluir o cupom <strong class="text-danger">{{ $cupom->codigo }}</strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Excluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="editCupom{{ $cupom->id }}" aria-labelledby="editCupom{{ $cupom->id }}Label">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="editCupom{{ $cupom->id }}Label">Editar cupom</h5>
            <button id="close-canva-edit-cupon-{{ $cupom->id }}" type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('produtos.cupons.edit', ['id' => $cupom->id]) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Código</label>
                        <input type="text" class="form-control form-control-md" name="codigo" value="{{ $cupom->codigo }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Tipo de desconto</label>
                        <select class="form-select form-control-md" id="type-cupom-desconto-{{ $cupom->id }}" name="type"
                            onchange="document.getElementById('lbl-desc-{{ $cupom->id }}').innerText = this.value === 'percent' ? 'Desconto (%)' : 'Desconto (R$)'">
                            <option value="fixed" {{ $cupom->type == 'fixed' ? 'selected' : '' }}>Fixo (R$)</option>
                            <option value="percent" {{ $cupom->type == 'percent' ? 'selected' : '' }}>Porcentagem (%)</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label id="lbl-desc-{{ $cupom->id }}" class="form-label">Desconto (R$)</label>
                        <input type="text" class="form-control form-control-md" name="desconto" value="{{ $cupom->desconto }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Data de início</label>
                        <input type="datetime-local" class="form-control form-control-md" name="inicio" value="{{ $cupom->data_inicio->format('Y-m-d\TH:i') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Data de fim</label>
                        <input type="datetime-local" class="form-control form-control-md" name="fim" value="{{ $cupom->data_termino ? $cupom->data_termino->format('Y-m-d\TH:i') : '' }}">
                        <small class="text-muted">Vazio = validade eterna</small>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" name="aplicar_orderbumps" id="aplicar_orderbumps_edit_{{ $cupom->id }}" {{ $cupom->aplicar_orderbumps ? 'checked' : '' }}>
                            <label class="form-check-label" for="aplicar_orderbumps_edit_{{ $cupom->id }}">Aplicar ao Order Bumps</label>
                        </div>
                    </div>
                    <input name="produto_id" type="hidden" value="{{ $cupom->produto_id }}">
                    <div class="col-12 d-flex gap-2 justify-content-end">
                        <button class="btn btn-secondary" type="button" onclick="document.getElementById('close-canva-edit-cupon-{{ $cupom->id }}').click()">Cancelar</button>
                        <button class="btn btn-primary" type="submit">Alterar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

<script>
document.addEventListener('DOMContentLoaded', function () {
    var hash = window.location.hash;
    if (hash) {
        var trigger = document.querySelector('a.nav-link[href="' + hash + '"]');
        if (trigger && trigger.getAttribute('data-bs-toggle') === 'tab') {
            new bootstrap.Tab(trigger).show();
        }
    }
    document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(function(link) {
        link.addEventListener('shown.bs.tab', function (e) {
            history.replaceState(null, null, e.target.getAttribute('href'));
        });
    });
    $(function () {
        function handleHash() {
            var h = window.location.hash;
            if (h) {
                var trigger = document.querySelector('a.nav-link[href="' + h + '"][data-bs-toggle="tab"]');
                if (trigger) {
                    new bootstrap.Tab(trigger).show();
                }
                setTimeout(function () {
                    if (typeof $ !== 'undefined' && $.fn.DataTable && $.fn.dataTable.tables) {
                        var t = $($.fn.dataTable.tables(true));
                        if (t.length && t.DataTable) t.DataTable().columns.adjust().responsive.recalc();
                    }
                }, 60);
            }
        }
        handleHash();
        window.addEventListener('hashchange', handleHash);
    });
});
</script>
