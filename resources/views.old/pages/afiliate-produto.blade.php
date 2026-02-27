@php
    use App\Helpers\Helper;
    $setting = Helper::settings();
    $link = env('APP_URL') . '/register?ref=' . auth()->user()->codigo_referencia;

@endphp
@extends('layouts.app')

@section('title', 'Indicações')

@section('content')
    <div class="header mb-3 d-flex align-items-center justify-content-between">
        <div>
            <h1 class="header-title">
                Afiliação
            </h1>
            <small>
                {{ $afiliacao->produto->name }}
            </small>
        </div>
    </div>
    <div class="row g-3 mb-0">
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small>Vendas pagas</small>
                        <h4 class="mb-0 value-visible">{{ auth()->user()
                            ->historico_afiliado()
                            ->whereHas('pedido', function ($q) use ($afiliacao) {
                                $q->where('produto_id', $afiliacao->produto->id);
                            })->where('status', 'pago')->count() }}
                        </h4>
                        <h4 class="mb-0 value-visible d-none">R$ ---</h4>
                    </div>
                    <i class="bi bi-eye cursor-pointer toggle-visibility "></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small>Vendas pendentes</small>
                        <h4 class="mb-0 value-visible">{{ auth()->user()
                            ->historico_afiliado()
                            ->whereHas('pedido', function ($q) use ($afiliacao) {
                                $q->where('produto_id', $afiliacao->produto->id);
                            })->where('status', 'pendente')->count() }}</h4>
                        <h4 class="mb-0 value-visible d-none">R$ ---</h4>
                    </div>
                    <i class="bi bi-eye cursor-pointer toggle-visibility "></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small>Vendas canceladas</small>
                        <h4 class="mb-0 value-visible">{{ auth()->user()
                            ->historico_afiliado()
                            ->whereHas('pedido', function ($q) use ($afiliacao) {
                                $q->where('produto_id', $afiliacao->produto->id);
                            })->where('status', 'cancelado')->count() }}</h4>
                        <h4 class="mb-0 value-visible d-none">R$ ---</h4>
                    </div>
                    <i class="bi bi-eye cursor-pointer toggle-visibility "></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small>Lucro total</small>
                        <h4 class="mb-0 value-visible">{{ "R$ ".number_format(auth()->user()
                            ->historico_afiliado()
                            ->whereHas('pedido', function ($q) use ($afiliacao) {
                                $q->where('produto_id', $afiliacao->produto->id);
                            })->where('status', 'pago')->sum('amount'), 2, ',', '.') }}</h4>
                        <h4 class="mb-0 value-visible d-none">---%</h4>
                    </div>
                    <i class="bi bi-eye cursor-pointer toggle-visibility "></i>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3 mb-3">
    <div class="col-12" style="min-height: 150px;">
        <div class="card h-100">
            <div class="card-body h-100 relative">
                <div class="row align-items-center">
                    
                    <!-- Coluna da imagem -->
                    <div class="col-12 col-md-3 col-lg-2 text-center">
                        <img loading="lazy"
                             class="img-fluid mt-4"
                             alt="{{ $afiliacao->produto->name }}"
                             @if ($afiliacao->produto->image == "produtos/box_default.svg")
                                 src="{{ url('/produto-image-default/140/140') }}"
                             @else
                                 src="/storage/{{ $afiliacao->produto->image }}"
                             @endif >
                    </div>

                    <!-- Coluna do texto -->
                    <div class="col-12 col-md-6 col-lg-6">
                        <h4 class="mt-2 mb-0 value-visible fs-6">{{ $afiliacao->produto->name }}</h4>
                        <small class="d-block">{{ $afiliacao->produto->description }}</small>
                        <h4 class="mt-2 mb-0 value-visible fs-6">Valor: {{ "R$ ".number_format($afiliacao->produto->price, 2, ',', '.') }}</h4>
                        <h4 class="mt-2 mb-0 value-visible fs-6">Comissão: {{ "R$ ".number_format($afiliacao->produto->price * $afiliacao->percentage / 100, 2, ',', '.') }}</h4>
                    </div>
                    <div class="m-3 col-12 col-md-3 col-lg-3">
                        <h6>Meu link de afiliado</h6>
                        <input id="refLink" 
                            class="form-control mb-3" 
                            readonly 
                            value="{{ url('produto/'.$afiliacao->produto->checkouts[0]->uuid.'?ref='.$afiliacao->ref) }}">

                        <div class="row g-2">
                            <div class="col-6">
                                <button id="btnCopy" class="btn btn-sm btn-primary w-100 text-white">
                                    <i data-lucide="copy" class="me-2" style="width: 15px;stroke:white!important;"></i>
                                    Copiar
                                </button>
                            </div>
                            <div class="col-6">
                                <button id="btnShare" class="btn btn-sm btn-primary w-100 text-white">
                                    <i data-lucide="share-2" class="me-2" style="width: 15px;stroke:white!important;"></i>
                                    Compartilhar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>               
            </div>
        </div>
    </div>
</div>
<script>
        function shareLink() {
            const input = document.getElementById('refLink');
            input.select();
            input.setSelectionRange(0, 99999); // para mobile
            document.execCommand('copy');

            // Alerta visual opcional
            showToast('success', 'Link copiado para a área de transferência!');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Verifica suporte ao Web Share API
            if (!navigator.share) {
                const btnShare = document.getElementById('btnShare');
                if (btnShare) {
                    btnShare.style.display = 'none';
                }
            }

            // Copiar link
            document.getElementById('btnCopy').addEventListener('click', function() {
                const input = document.getElementById('refLink');
                input.select();
                input.setSelectionRange(0, 99999); // para mobile
                document.execCommand('copy');
                showToast('success', 'Link copiado para a área de transferência!');
            });

            // Compartilhar link
            document.getElementById('btnShare')?.addEventListener('click', async function() {
                const text = document.getElementById('refLink').value;

                try {
                    await navigator.share({
                        title: "Compre agora {{ $afiliacao->produto->name }}. Por apenas {{ "R$ ".number_format($afiliacao->produto->price, 2, ',', '.') }}",
                        text: "Cadastre-se agora na {{ env('APP_NAME') }} e alavanque suas vendas!",
                        url: text,
                    });
                } catch (error) {
                    alert('Erro ao compartilhar: ' + error.message);
                }
            });
        });
    </script>
@endsection