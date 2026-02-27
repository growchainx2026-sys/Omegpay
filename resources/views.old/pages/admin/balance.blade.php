@extends('layouts.app')

@section('title', 'Criar transações de balanceamento')

@section('content')
<div class="header mb-3">
    <h3 class="header-title">
        Balanceamento de saldo
    </h3>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">
          Criar transação de entrada
        </h5>
        <p class="card-subtitle">
          Adicionar saldo a carteira do cliente
        </p>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.balance.addentrada') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="client_id" class="form-label">Cliente</label>
            <select name="deposito_id" id="client_id" class="form-select" required>
              <option value="">Selecione um cliente</option>
              @foreach($users as $client)
                <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->email }})</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="amount" class="form-label">Valor a adicionar</label>
            <input type="number" name="deposito_amount" id="amount" class="form-control" step="0.01" required>
          </div>
          <button type="submit" class="btn btn-primary">Adicionar saldo</button>
        </form>
      </div>
    </div>
  </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">
            Criar transação de saída
          </h5>
          <p class="card-subtitle">
            Remover saldo da carteira do cliente
          </p>
          </div>
        <div class="card-body">
          <form action="{{ route('admin.balance.addsaida') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="client_id_withdraw" class="form-label">Cliente</label>
              <select name="saque_id" id="client_id_withdraw" class="form-select" required>
                <option value="">Selecione um cliente</option>
                @foreach($users as $client)
                  <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->email }})</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label for="amount_withdraw" class="form-label">Valor a remover</label>
              <input type="number" name="saque_amount" id="amount_withdraw" class="form-control" step="0.01" required> 
            </div>
            <button type="submit" class="btn btn-danger">Remover saldo</button>
          </form>
        </div>
      </div>
    </div>
</div>
@endsection
