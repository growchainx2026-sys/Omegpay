@extends('layouts.app')

@section('title', 'Pagamento Confirmado')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                    </div>
                    
                    <h2 class="card-title text-success mb-3">Pagamento Confirmado!</h2>
                    
                    <p class="card-text mb-4">
                        Seu pagamento foi processado com sucesso.
                    </p>
                    
                    <div class="alert alert-info">
                        <strong>Código do Voucher:</strong><br>
                        <code style="font-size: 1.2em;">{{ $link->idTransaction ?? $link->codigo }}</code>
                    </div>
                    
                    <div class="mt-4">
                        <p class="text-muted">
                            <strong>Descrição:</strong> {{ $link->descricao }}<br>
                            <strong>Valor:</strong> R$ {{ number_format($link->valor, 2, ',', '.') }}
                        </p>
                    </div>
                    
                    <hr class="my-4">
                    
                    <p class="text-muted small">
                        Guarde este código do voucher para futuras referências.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection