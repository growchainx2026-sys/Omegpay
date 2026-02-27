@if (Cookie::has('form_errors'))
    @php
        $formErrors  = json_decode(Cookie::get('form_errors'), true);
        // Limpa o cookie após carregar a página
        \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forget('form_errors'));
    @endphp
@endif


@extends('layouts.app')

@section('title', ' Transferir Saldo')

@section('content')

<div class="header">
    <h1 class="header-title">
        Transferir Saldo
    </h1>
    <p class="header-subtitle">Preencha os dados abaixo para fazer transferir o saldo para outra carteira</p>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Dados do Pagamento</h5>
            </div>
            <div class="card-body">
                <span class="badge text-bg-dark mb-4 py-2" style="width: 100%;height:40px;font-size:32px;display:flex;align-items:center;justify-content:center;">
                   DISPONÍVEL: R$ {{ number_format(auth()->user()->saldo, '2',',','.') }}
                </span>
                <form class="row row-cols-md-auto align-items-center" method="POST" action="{{ route('transferencia.saldo') }}">
                    @csrf

                    <div class="col-md-7">
                        <div>
                            <label for="codigo_referencia">Conta</label>
                            <input type="text" class="form-control" id="codigo_referencia" name="codigo_referencia" value="{{ old('codigo_referencia') }}" {{ auth()->user()->saldo <= 0 ? 'readonly' : ''  }} required>
                        </div>

                        @if (!empty($formErrors['codigo_referencia']))
                            <div class="text-danger">{{ $formErrors['codigo_referencia'][0] }}</div>
                        @endif
                    </div>
                    <div class="col-md-3">
                        <div>
                            <label for="amount">Valor</label>
                            <input type="number"
                            class="form-control"
                            min="0"
                            max="{{ auth()->user()->saldo }}"
                            value="{{ old('amount') }}"
                            name="amount"
                            id="amount"
                            required
                            {{ auth()->user()->saldo <= 0 ? 'readonly' : ''  }}>
                        </div>
                        @if (!empty($formErrors['amount']))
                            <div class="text-danger">{{ $formErrors['amount'][0] }}</div>
                        @endif
                    </div>


                    <div class="col-md-2 mt-3">
                        <button type="submit" class="btn btn-primary w-100 mb-0" {{ auth()->user()->saldo <= 0 ? 'readonly' : ''  }}><i class="fa-solid fa-share"></i>&nbsp;Transferir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        const maxSaldo = parseFloat("{{ number_format(auth()->user()->saldo, 2, '.', '') }}");

        $('#amount').on('input', function () {
            let valor = parseFloat($(this).val().replace(',', '.'));

            if (valor > maxSaldo) {
                $(this).val(maxSaldo.toFixed(2));
            }
        });
    });
    </script>
@endsection
