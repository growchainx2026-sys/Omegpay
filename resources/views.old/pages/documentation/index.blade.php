@extends('layouts.documentation')

@section('title', 'Bem vindo')

@section('content')
@php
use App\Helpers\Helper;
    $setting = Helper::settings();
@endphp
<div class="header">
    <h1 class="header-title">
        Documentação API PIX
    </h1>
    <p class="header-subtitle">Introdução</p>
</div>

<div class="row">
    <div class="col-12 mb-3">
        <div class="borda" >
            <h4 class="alert-heading">
                <i class="fa-solid fa-circle-info"></i>&nbsp;Importante
            </h4>
            <p>
                O conjunto de endpoints a seguir é responsável pela gestão de cobranças imediatas. As cobranças, no contexto da API Pix representam uma transação financeira entre um pagador e um recebedor, cuja forma de pagamento é o Pix.
            </p>
        </div>
    </div>

    <div class="col-12 mb-3 d-flex justify-content-between">
        <div></div>
        <a href="/docs/api-pix/receive#success">
            <button class="btn btn-xs btn-outline-success">
                <i class="fa-solid fa-arrow-right" style="color: {{ $setting->software_color }} !important;"></i>&nbsp;
                <span>
                    Próximo
                </span>
            </button>
        </a>
    </div>
</div>

@endsection
