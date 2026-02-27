@php
    /*
"id" => 7
      "name" => "Produto para entrega 1"
      "description" => "Descrição de produto para entrega 1"
      "file" => "https://google.com"
      "produto_id" => 1
      "created_at" => "2025-08-18 16:37:50"
      "updated_at" => "2025-08-18 16:37:50"
      "type" => "link"

*/
@endphp

@extends('layouts.aluno', [
    'produto' => null,
    'colors' => null
])

@section('title', 'Meus produtos')

@section('content')
    <div class="header mb-3">
        <h1 class="header-title">
            Minhas Compras
        </h1>
    </div>
    <div class="row g-3 mb-3">
       
        @foreach ($files as $file)
            <div class="col-sm-12 col-lg-4 col-xl-3">
                <div class="card produto">
            
                    <img loading="lazy"
                        class="card-img-top color-box img-fluid"
                        style="max-height: 250px;width:auto;object-fit:cover;"
                    alt="{{ $file->produto?->name }}"
                    @if ($file->produto->image == "produtos/box_default.svg")
                        src="{{ url('/produto-image-default') }}"
                    @else
                        src="/storage/{{ $file->produto->image ?? "Produto" }}"
                    @endif
                >       
                    <div class="card-body"
                        style="position: relative;">
                        <h5 class="card-title">{{ $file->produto->name }}</h5>
                        <p class="card-text descricao">{{ $file->produto->description ?? '' }}</p>

                        <small class="text-gateway" style="position: absolute;bottom: 50px;">Produtor:
                            <a href="{{ $file->produto->email_support ? "mail:".$file->produto->email_support :  "#" }}" target="{{ is_null($file->produto->email_support) ? "" : "_blank" }}">
                                <span >{{ $file->produto->name_exibition ?? 'Sem nome' }}</span>
                            </a>
                        </small>

                        <div class="card-link">
                            <a href="{{ route('aluno.produto.id', ["id" => $file->produto->id]) }}"
                                class="btn btn-primary w-100">Acessar</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection
