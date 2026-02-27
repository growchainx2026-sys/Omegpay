@extends('layouts.app')

@section('title', 'Vitrine')

@section('content')
    <div class="header">
        <h1 class="header-title">
            Vitrine
        </h1>
    </div> 

    <div class="row mb-3">
        @if($produtos->count() > 0)
        @foreach ($produtos as $produto)
            <div class="col-12 col-md-6 col-xl-4 col-xxl-3 mb-4">
                <div class="card h-100 produto" style="min-height: 100%">
                    <img 
                        loading="lazy"
                        class="card-img-top color-box img-fluid"
                        style="max-height: 250px;width:auto;object-fit:cover;"
                        alt="{{ $produto->name }}"
                        @if ($produto->image == "produtos/box_default.svg")
                            src="{{ url('/produto-image-default') }}"
                        @else
                            src="/storage/{{ $produto->image }}"
                        @endif
                    >

                    <div class="card-body d-flex flex-column" style="line-height: 3px;">
                        <h5 class="card-title text-truncate">{{ $produto->name }}</h5>
                        {{-- <p class="card-text descricao text-truncate">{{ $produto->description }}</p> --}}
                        
                        <small class="text-muted mb-2">
                            Produtor: 
                            <span class="text-muted">
                                {{ $produto->name_exibition ?? explode(' ', $produto->user->name)[0] }}
                            </span>
                        </small>

                        <div class="mt-auto">
                            <div class="row text-center">
                                <div class="col-12 col-md-6 mb-2">
                                    <h6 class="card-text fw-bold" style="line-height: 3px">
                                        <small>Valor</small>
                                        <h6>R$ {{ number_format($produto->price, 2 , ',', '.') }}</h6>
                                    </h6>
                                </div>
                                <div class="col-12 col-md-6 mb-2">
                                    <h6 class="card-text fw-bold" style="line-height: 3px">
                                        <small>Comissão</small>
                                        <h6>{{ number_format($produto->affiliate_percentage, 2) }}%</h6>
                                    </h6>
                                </div>
                                <div class="col-12 mb-3">
                                    @php
                                        $comissao = number_format((float) $produto->price * (float) $produto->affiliate_percentage / 100, 2, ',', '.');
                                    @endphp
                                    <h6 class="card-text fw-bold" style="font-size: 12px;">
                                        Comissão de R$ {{ $comissao }} por venda realizada.
                                    </h6>
                                </div>
                            </div>

                            <div class="d-grid">
                                @if ($produto->user_id == auth()->user()->id)
                                    <button class="btn btn-primary" disabled>Sou o produtor</button>
                                
                                @elseif (in_array($produto->id, $afiliacoesIds, true))
                                    <a href="/affiliates/my-affiliates" class="btn btn-secondary" disabled>Sou afiliado</a>
                                
                                @else
                                    <a href="#"
                                    type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#affiliateModal{{ $produto->id }}"
                                    class="btn btn-primary btn-produto">
                                    Afiliar-me
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="affiliateModal{{ $produto->id }}" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="affiliateModal{{ $produto->id }}Label"
                            aria-hidden="true">
                            <div class="modal-dialog modal-md modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="affiliateModal{{ $produto->id }}Label">Afiliar-me
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h6>Você tem certeza que deseja afiliar-se a este produto?</h6>
                                        <h6>Produto: {{ $produto->name }}</h6>
                                        <h6>Produtor: {{ $produto->name_exibition ?? explode(' ', $produto->user->name)[0] }}</h6>
                                        <hr/>
                                        <h6>Valor do produto: R$ {{ number_format($produto->price, 2, ',', '.') }}</h6>
                                        <h6>Comissão: {{ number_format($produto->affiliate_percentage, '2') }}%</h6>
                                        <h6>A cada venda realizada você receberá R$ {{ $comissao }}</h6>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancelar</button>
                                            <button type="button" class="btn btn-success text-white" onclick="afiliarMe('{{ $produto->id }}')">Afiliar-me</button>
                                    </div>
                                </div>
                            </div>
                        </div>
        @endforeach
        @else
        <div class="w-100 d-flex align-items-center justify-content-center" style="height:70vh;">
        <div class="row">
            <div class="col-12">    
                <h5 class="text-center">Nenhum produto</h5>
            </div>
            <div class="col-12 text-center">
                <a href="/produtos">
                    <button class="btn btn-sm btn-primary">
                        Criar um produto
                    </button>
                </a>
            </div>
        </div>
        @endif
    </div>

    <script>
        function afiliarMe(id){
            const form = document.createElement('form');
            form.method = 'POST'; // ou 'GET'
            form.action = `{{ route('affiliates.affiliate.me') }}`; // coloque a rota de envio

            // Adicionar token CSRF
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            const inputid = document.createElement('input');
            inputid.type = 'hidden';
            inputid.name = 'produto_id';
            inputid.value = id;
            form.appendChild(inputid);

            document.body.appendChild(form);

            form.submit();
        }
    </script>
@endsection
