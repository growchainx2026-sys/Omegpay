@extends('layouts.app')

@section('title', 'Edição de produto')

@section('content')
    <div class="header mb-3">
        <h1 class="header-title">
            {{ $produto->name }}
        </h1>
    </div>

    <form action="{{ route('produtos.edit', ['id' => $produto->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3 d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-link" onclick="window.history.back()"
                style="color: var(--gateway-primary-color)!important; text-decoration:none; font-weight:bold;">
                <i class="fa-solid fa-arrow-left" style="color: var(--gateway-primary-color)!important;"></i>&nbsp;Voltar
            </button>
            <button class="btn btn-primary" type="submit">
                <i class="fa-solid fa-save"></i>&nbsp;Salvar alterações
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                {{-- NAV TABS --}}
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="geral-tab" data-bs-toggle="tab" href="#geral"
                            role="tab">Geral</a></li>
                    <li class="nav-item"><a class="nav-link" id="configuracoes-tab" data-bs-toggle="tab"
                            href="#configuracoes" role="tab">Configurações</a></li>
                    <li class="nav-item"><a class="nav-link" id="orderbump-tab" data-bs-toggle="tab" href="#orderbump"
                            role="tab">Order Bump</a></li>
                    <li class="nav-item"><a class="nav-link" id="checkout-tab" data-bs-toggle="tab" href="#checkout"
                            role="tab">Checkout</a></li>
                    <li class="nav-item"><a class="nav-link" id="tracking-tab" data-bs-toggle="tab" href="#tracking"
                            role="tab">Trackeamento</a></li>
                    <li class="nav-item"><a class="nav-link" id="files-tab" data-bs-toggle="tab" href="#files"
                            role="tab">Área de membros</a></li>
                    <li class="nav-item"><a class="nav-link" id="files-tab" data-bs-toggle="tab" href="#coprodutor"
                            role="tab">Coprodutor</a></li>
                    <li class="nav-item"><a class="nav-link" id="files-tab" data-bs-toggle="tab" href="#affiliate"
                            role="tab">Afiliados</a></li>
                    <li class="nav-item"><a class="nav-link" id="files-tab" data-bs-toggle="tab" href="#cupons"
                            role="tab">Cupons</a></li>
                    <li class="nav-item"><a class="nav-link" id="links-tab" data-bs-toggle="tab" href="#links"
                            role="tab">Links</a></li>
                </ul>
            </div>
        </div>

        {{-- TAB CONTENT --}}
        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="geral" role="tabpanel">
                @include('pages.produtos.components.geral', compact('produto'))</div>
            <div class="tab-pane fade" id="configuracoes" role="tabpanel">
                @include('pages.produtos.components.configuracoes', compact('produto', 'produtos'))</div>
            <div class="tab-pane fade" id="orderbump" role="tabpanel">
                @include('pages.produtos.components.order-bump', compact('produtos', 'produto'))</div>
            <div class="tab-pane fade" id="checkout" role="tabpanel">
                @include('pages.produtos.components.checkout', compact('produtos', 'produto'))</div>
            <div class="tab-pane fade" id="tracking" role="tabpanel">
                @include('pages.produtos.components.tracking', compact('produtos', 'produto'))</div>
            <div class="tab-pane fade" id="files" role="tabpanel">
                @include('pages.produtos.components.files', compact('produtos', 'produto'))</div>
            <div class="tab-pane fade" id="affiliate" role="tabpanel">
                @include('pages.produtos.components.affiliate', compact('produtos', 'produto'))</div>
            <div class="tab-pane fade" id="coprodutor" role="tabpanel">
                @include('pages.produtos.components.coprodutor', compact('produtos', 'produto'))</div>
            <div class="tab-pane fade" id="cupons" role="tabpanel">
                @include('pages.produtos.components.cupom', compact('produtos', 'produto'))</div>
            <div class="tab-pane fade" id="links" role="tabpanel">
                @include('pages.produtos.components.links', compact('produtos', 'produto'))</div>
        </div>
    </form>
@endsection

@if (session('tab'))
<script>
    let tab = "{{ session('tab') }}";
    document.addEventListener('DOMContentLoaded', function(){
        tab = tab.slice('tab-');
        document.getElementById(tab).click();
    })
</script>

@endif

<div class="offcanvas offcanvas-end" tabindex="-1" id="addCupon" aria-labelledby="addCuponLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="addCuponLabel"> + Adcionar cupom</h5>
        <button id="close-canva-add-cupon" type="button" class="btn-close" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form action="{{ route('produtos.cupons.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="mb-3 col-12">
                    <label for="price">Código</label>
                    <input type="text" class="form-control form-control-md" id="codigo" name="codigo">
                </div>
                <div class="mb-3 col-12">
                    <label for="type-cupom-desconto">Tipo de desconto</label>
                    <select class="form-control form-control-md" id="type-cupom-desconto" name="type"
                        onchange="document.getElementById('lbl-desc').innerText = this.value === 'percent' ? 'Desconto (%)' : 'Desconto (R$)'">
                        <option value="fixed">Fixo (R$)</option>
                        <option value="percent">Porcentagem (%)</option>
                    </select>
                </div>

                <div class="mb-3 col-12">
                    <label id="lbl-desc" for="cupom-desconto">Desconto (R$)</label>
                    <input type="text" class="form-control form-control-md" id="cupom-desconto" name="desconto">
                </div>
                <div class="mb-3 col-12">
                    <label for="price">Data de início do cupom</label>
                    <input type="datetime-local" class="form-control form-control-md" id="inicio" name="inicio">
                </div>
                <div class="mb-3 col-12">
                    <label for="price">Data de fim do cupom</label>
                    <input type="datetime-local" class="form-control form-control-md" id="fim" name="fim">
                    <small>Deixe sem valor para a validade ser eterna</small>
                </div>
                <div class="mb-3 col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" name="aplicar_orderbumps"
                            id="aplicar_orderbumps">
                        <label class="form-check-label" for="aplicar_orderbumps">
                            Aplicar desconto ao Order Bumps
                        </label>
                    </div>
                </div>
                <input id="input-produto-id-form-cadastrar-cupom" name="produto_id" hidden>
                <div class="mb-3 col-12 d-flex align-items-center justify-content-end gs-3">
                    <button class="btn btn-secondary " type="button"
                        onclick="document.getElementById('close-canva-add-cupon').click()">Cancelar</button>
                    <button class="btn btn-primary mx-1" type="submit">Adcionar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modais Categorias -->
@foreach ($produto->cupons as $cupom)
    <!-- Modal delete categoria -->
    <div class="modal fade" id="delCupomModal{{ $cupom->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="delCupomModal{{ $cupom->id }}Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <form action="{{ route('produtos.cupons.del', ['id' => $cupom->id]) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="delCupomModal{{ $cupom->id }}Label">- Excluir cupom</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p> Tem certeza que deseja excluir o cupom <span class="text-danger">{{ $cupom->codigo }}</span>?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger text-white">Excluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="offcanvas offcanvas-end" tabindex="-1" id="editCupom{{ $cupom->id }}"
        aria-labelledby="editCupom{{ $cupom->id }}Label">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="editCupom{{ $cupom->id }}Label"> * Editar cupom</h5>
            <button id="close-canva-edit-cupon-{{ $cupom->id }}" type="button" class="btn-close" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('produtos.cupons.edit', ['id' => $cupom->id]) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="mb-3 col-12">
                        <label for="price">Código</label>
                        <input type="text" class="form-control form-control-md" id="codigo" name="codigo"
                            value="{{ $cupom->codigo }}">
                    </div>

                    <div class="mb-3 col-12">
                        <label for="type-cupom-desconto">Tipo de desconto</label>
                        <select class="form-control form-control-md" id="type-cupom-desconto-{{ $cupom->id }}" name="type"
                            onchange="document.getElementById('lbl-desc-{{ $cupom->id }}').innerText = this.value === 'percent' ? 'Desconto (%)' : 'Desconto (R$)'">
                             <option value="fixed" {{ $cupom->type == 'fixed' ? 'selected' : '' }}>Fixo (R$)</option>
                        <option value="percent" {{ $cupom->type == 'percent' ? 'selected' : '' }}>Porcentagem (%)</option>
                        </select>
                    </div>

                    <div class="mb-3 col-12">
                        <label id="lbl-desc-{{ $cupom->id }}" for="cupom-desconto">Desconto (R$)</label>
                        <input type="text" class="form-control form-control-md" id="cupom-desconto" name="desconto"
                        value="{{ $cupom->desconto }}">
                    </div>

                    <div class="mb-3 col-12">
                        <label for="price">Data de início do cupom</label>
                        <input type="datetime-local" class="form-control form-control-md" id="inicio" name="inicio"
                            value="{{ $cupom->data_inicio->format('Y-m-d H:i') }}">
                    </div>
                    <div class="mb-3 col-12">
                        <label for="price">Data de fim do cupom</label>
                        <input type="datetime-local" class="form-control form-control-md" id="fim" name="fim"
                            value="{{ $cupom->data_termino->format('Y-m-d H:i') }}">
                        <small>Deixe sem valor para a validade ser eterna</small>
                    </div>
                    <div class="mb-3 col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ $cupom->aplicar_orderbumps ? 1 : 0 }}"
                                name="aplicar_orderbumps" id="aplicar_orderbumps" {{ $cupom->aplicar_orderbumps ? 'checked' : '' }}>
                            <label class="form-check-label" for="aplicar_orderbumps">
                                Aplicar desconto ao Order Bumps
                            </label>
                        </div>
                    </div>
                    <input id="input-produto-id-form-cadastrar-cupom" name="produto_id" value="{{ $cupom->produto_id }}"
                        hidden>
                    <div class="mb-3 col-12 d-flex align-items-center justify-content-end gs-3">
                        <button class="btn btn-secondary " type="button"
                            onclick="document.getElementById('close-canva-edit-cupon-{{ $cupom->id }}').click()">Cancelar</button>
                        <button class="btn btn-primary mx-1" type="submit">Alterar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Ativa a tab correta com base no hash da URL
        const hash = window.location.hash;
        if (hash) {
            const trigger = document.querySelector(`a.nav-link[href="${hash}"]`);
            if (trigger) {
                new bootstrap.Tab(trigger).show();
            }
        }

        // Atualiza o hash ao mudar de tab
        const links = document.querySelectorAll('a[data-bs-toggle="tab"]');
        links.forEach(link => {
            link.addEventListener('shown.bs.tab', function (event) {
                const newHash = event.target.getAttribute('href');
                history.replaceState(null, null, newHash);
            });
        });
    });

    $(function () {
        // Se carregar a página com hash (ou quando hash mudar)
        function handleHash() {
            var hash = window.location.hash;
            if (hash) {
                var trigger = $('a[href="' + hash + '"][data-bs-toggle="tab"]');
                if (trigger.length) {
                    var tab = new bootstrap.Tab(trigger[0]);
                    tab.show();
                    setTimeout(function () {
                        $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
                    }, 60);
                }
            }
        }

        handleHash();
        $(window).on('hashchange', handleHash);
    });
</script>