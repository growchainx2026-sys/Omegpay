@extends('layouts.app')

@section('title', 'Minhas taxas')

@section('content')
    <div class="header mb-3">
        <h1 class="header-title">
            Minhas taxas
        </h1>
    </div>

    <div class="row mb-3 w-100">
        <div class="col-12 col-md-3 w-100">
            <div class="card w-100">
                <div class="card-body w-100">
                    {{-- NAV TABS --}}
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="taxas-tab" data-bs-toggle="tab" href="#taxas" role="tab">
                                Minhas taxas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="antecipacao-tab" data-bs-toggle="tab" href="#antecipacao" role="tab">
                                Antecipação de valores
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="taxas" role="tabpanel">
                    <div class="row g-4">

                    @php
                        $plano_atual = $setting->card_days_to_release;
                        if(auth()->user()->plan_card == 'opt1'){
                            $plano_atual = $setting->card_days_to_anticipation_opt1;
                        } elseif(auth()->user()->plan_card == 'opt2'){
                            $plano_atual = $setting->card_days_to_anticipation_opt2;
                        }
                    @endphp

                        <!-- Cartão de crédito -->
                        <div class="col-md-6">
                            <div class="card payment-card p-3 h-100">
                                <i class="fa-solid fa-credit-card"></i>
                                <h5 class="text-default" >Cartão de crédito D+{{ $plano_atual }}</h5>
                                <small>Receba pagamentos com cartão de crédito de forma segura e rápida.</small>
                                <p class="mt-2 mb-3 payment-rate">{{ $taxa_cartao_percent }}% + R$ {{ number_format($taxa_cartao_fixa, 2, ',', '.') }} <small>/ transação</small></p>
                                
                            </div>
                        </div>

                        <!-- Boleto -->
                        <div class="col-md-6">
                            <div class="card payment-card p-3 h-100">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                <h5 class="text-default">Boleto D+{{ $setting->billet_days_to_release }}</h5>
                                <small>Boletos emitidos não são cobrados, apenas os pagos.</small>
                                <p class="mt-2 mb-0 payment-rate">{{ $setting->billet_taxa_percent ?? 0 }}% + R$ {{ number_format($setting->billet_taxa_fixed ?? 0,2,',','.') }} <small>/ boleto</small></p>
                            </div>
                        </div>

                        <!-- Pix -->
                        <div class="col-md-6">
                            <div class="card payment-card p-3 h-100">
                                <i class="fa-brands fa-pix"></i>
                                <h5 class="text-default">Pix D+0</h5>
                                <small>PIX é o novo meio de pagamento instantâneo da plataforma.</small>
                                <p class="mt-2 mb-0 payment-rate">Pix-in: {{ auth()->user()->taxa_cash_in ?? 0 }}% + R$ {{ number_format(auth()->user()->taxa_cash_in_fixa ?? 0, 2, ',', '.') }} <small >/ transação</small></p>
                                <p class="mt-n2 mb-0 payment-rate">Pix-out: {{ auth()->user()->taxa_cash_out ?? 0 }}% + R$ {{ number_format(auth()->user()->taxa_cash_out_fixa ?? 0, 2, ',', '.') }} <small>/ transação</small></p>
                            </div>
                        </div>

                        <!-- NeonPay -->
                        <div class="col-md-6">
                            <div class="card payment-card p-3 h-100">
                                <img src="{{ '/storage/'.$setting->favicon_light }}" width="40px" height="40px"/>
                                <h5 class="text-default">{{ $setting->software_name }}</h5>
                                <small>O melhor gateway de pagamentos</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="antecipacao" role="tabpanel">
                    <div class="card p-3">
                       <div class="row">
                        <div class="col-12 mb-3">
                            <h6>Antecipe o recebimento das próximas vendas feitas por cartão de crédito!</h6>
                        </div>
                        @foreach ($antecipacoes as $antecipacao)
                        @php
                        $button_text = 'Selecionar';
                        $disabled = false;
                        if (auth()->user()->plan_card == $antecipacao['value']){
                            $button_text = 'Plano atual';
                            $disabled = true;
                        }
                                   
                        @endphp
                        <div class="col-12 col-lg-4">
                            <div class="d-block text-center card-plano-recebimento  {{ $disabled ? 'selecionado' : '' }}">
                                <h6 class="text-muted mb-3">Quero receber em</h6>
                                <h1>{{ $antecipacao['label'] }}</h1>
                                <div class="d-block text-center py-5">
                                    {{ $antecipacao['taxa'] }}<br>
                                    <small>Sobre cada venda</small>
                                </div>
                                <form action="{{ route('minhas-taxas.update') }}" method="POST">
                                    @csrf
                                    <input name="plan_card" value="{{ $antecipacao['value'] }}" hidden>
                                    <button type="submit" class="btn btn-primary btn-xs w-100" {{ $disabled ? 'disabled' : '' }}>
                                        {{ $button_text }}
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection