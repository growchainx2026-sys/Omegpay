@extends('layouts.aluno', [
    'produto' => null,
    'colors' => null
])

@section('title', 'Marketplace')

@section('content')
    <div class="header">
        <h1 class="header-title">
            Shop
        </h1>
    </div>

    <div class="row mb-3">
        @foreach ($produtos as $produto)
            <div class="col-sm-12 col-lg-4 col-xl-3">
                <div class="card produto">
                    <img loading="lazy"
                    class="card-img-top color-box"
                    alt="{{ $file->produto->name }}"
                    @if ($file->produto->image == "produtos/box_default.svg")
                        src="{{ url('/produto-image-default') }}"
                    @else
                        src="/storage/{{ $file->produto->image }}"
                    @endif
                >       
                    <div class="card-body border-lg" style="min-height: 200px;max-height:200px;height:100%;position: relative;">
                        <h5 class="card-title">{{ $produto->name }}</h5>
                        <p class="card-text descricao">{{ $produto->description }}</p>
                        <small class="text-muted" style="position: absolute;bottom: 90px;">Produtor: <span class="text-muted">{{ $produto->name_exibition ?? 'Sem nome' }}</span></small>
                        <div style="display: flex; justify-content: space-between; align-items: center;position: absolute;bottom: 60px;width:90%;">
                            <span class="card-text text-bold">R$ {{ number_format($produto->price, 2 , ',', '.') }}</span>
                            <a href="{{ url('produto/'.$produto->checkouts()->where('default', 1)->first()->uuid) }}" target="_blank" class="btn btn-primary">Comprar</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
