@extends('layouts.app')

@section('title', 'Adquirentes')

@section('content')
    <div class="header mb-3">
        <h3 class="header-title">
            Adquirentes
        </h3>
    </div>

    <form class="row" method="POST" action="{{ route('admin.adquirentes.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Adquirência PIX</h5>
                    <small>Define a adquirente padrão para transações PIX</small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <select class="form-control" id="adquirencia_pix" name="adquirencia_pix">
                                    <option value="cashtime" {{ $adquirenciaPix == 'cashtime' ? 'selected' : '' }}>Cashtime
                                    </option>
                                    <option value="efi" {{ $adquirenciaPix == 'efi' ? 'selected' : '' }}>Efí</option>
                                    <option value="transfeera" {{ $adquirenciaPix == 'transfeera' ? 'selected' : '' }}>
                                        Transfeera</option>
                                        <option value="witetec" {{ $adquirenciaPix == 'witetec' ? 'selected' : '' }}>
                                        Witetec</option>
                                         <option value="pagarme" {{ $adquirenciaPix == 'pagarme' ? 'selected' : '' }}>
                                        Pagar.me</option>
                                </select>
                                <label for="adquirencia_pix">Adquirente padrão (pix)</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">Atualizar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Adquirência Boleto</h5>
                    <small>Define a adquirente padrão para transações via boleto</small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <select class="form-control" id="adquirencia_billet" name="adquirencia_billet">
                                    <option value="efi" {{ $adquirenciaBillet == 'efi' ? 'selected' : '' }}>Efí</option>
                                         <option value="pagarme" {{ $adquirenciaBillet == 'pagarme' ? 'selected' : '' }}>
                                        Pagar.me</option>
                                </select>
                                <label for="adquirencia_billet">Adquirente padrão (boleto)</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">Atualizar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Adquirência Cartão</h5>
                    <small>Define a adquirente padrão para transações via cartões</small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-floating mb-3">
                                <select class="form-control" id="adquirencia_card" name="adquirencia_card">
                                    <option value="efi" {{ $adquirenciaCard == 'efi' ? 'selected' : '' }}>Efí</option>
                                         <option value="pagarme" {{ $adquirenciaCard == 'pagarme' ? 'selected' : '' }}>
                                        Pagar.me</option>
                                        <option value="stripe" {{ $adquirenciaCard == 'stripe' ? 'selected' : '' }}>
                                        Stripe</option>
                                </select>
                                <label for="adquirencia_card">Adquirente padrão (cartão)</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">Atualizar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="col-12 col-md-3">
            <x-card-adquirente :title="'SIXXPAYMENTS'" :name="'sixxpayments'" :id="'sixxpayments'" :image="'https://app.sixxpayments.com/storage/images/favicon_light.png'" :data="$sixxpayments">
            </x-card-adquirente>
        </div> --}}
        <div class="col-12 col-md-3">
            <x-card-adquirente :title="'TRANSFEERA'" :name="'transfeera'" :id="'transfeera'" :image="asset('assets/images/transfeera.svg')" :data="$transfeera">
            </x-card-adquirente>
        </div>
        <div class="col-12 col-md-3">
            <x-card-adquirente :title="'CASHTIME'" :name="'cashtime'" :id="'cashtime'" :image="asset('assets/images/cashtime.png')"
                :background="'black'" :data="$cashtime">
            </x-card-adquirente>
        </div>
        <div class="col-12 col-md-3">
            <x-card-adquirente :title="'EFÍ BANK'" :name="'efi'" :id="'efi'" :image="asset('assets/images/efi.svg')"
                :background="'black'" :data="$efi">
            </x-card-adquirente>
        </div>
        <div class="col-12 col-md-3">
            <x-card-adquirente :title="'WITETEC'" :name="'witetec'" :id="'witetec'" :image="asset('assets/images/witetec.png')"
                :background="'black'" :data="$witetec">
            </x-card-adquirente>
        </div>
        <div class="col-12 col-md-3">
            <x-card-adquirente :title="'PAGARME'" :name="'pagarme'" :id="'pagarme'" :image="asset('assets/images/pagarme.png')"
                :background="'black'" :data="$pagarme">
            </x-card-adquirente>
        </div>
         <div class="col-12 col-md-3">
            <x-card-adquirente :title="'STRIPE'" :name="'stripe'" :id="'stripe'" :image="asset('assets/images/stripe.png')"
                :background="'black'" :data="$stripe">
            </x-card-adquirente>
        </div>
    </form>

@endsection
