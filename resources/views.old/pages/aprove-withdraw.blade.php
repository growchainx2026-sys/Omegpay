@extends('layouts.app')

@section('title', 'Aprovar Saques')

@section('content')
<div class="header mb-3">
    <h1 class="header-title">
        Aprovar Saques
    </h1>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title">Saques pendentes</h5>
    </div>
    <div class="card-body">
            <table class="table" id="table-admin-saques">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Nome Recebedor</th>
                    <th>CPF Recebedor</th>
                    <th>Chave PIX</th>
                    <th>Transação ID</th>
                    <th>Status</th>
                    <th>Valor Liquido</th>
                    <th>Valor Bruto</th>
                    <th>Taxas</th>
                    <th>Ações</th>

                </tr>
            </thead>
            <tbody>
                @foreach (auth()->user()->transactions_out->where('status', 'pendente') as $saque)
                <tr>
                    <td>{{ $saque->created_at->format('d/m/Y \à\s H:i:s') }}</td>
                    <td>{{ $saque->recebedor_name }}</td>
                    <td>{{ $saque->recebedor_cpf }}</td>
                    <td>{{ $saque->pixKey }}</td>
                    <td>{{ $saque->idTransaction }}</td>
                    <td>
                        @if ($saque->status === 'pendente')
                        <button class="btn btn-sm btn-outline-warning pendente" disabled="">Pendente</button>
                        @elseif ($saque->status === 'pago')
                        <button class="btn btn-sm btn-outline-success pago" disabled="">Pago</button>
                        @elseif ($saque->status === 'cancelado')
                        <button class="btn btn-sm btn-outline-danger cancelado" disabled="">Cancelado</button>
                        @elseif ($saque->status === 'revisao')
                        <button class="btn btn-sm btn-outline-secondary padrao" disabled="">Em revisão</button>
                        @endif
                    </td>
                    <td>R$ {{ number_format($saque->amount, '2',',','.') }}</td>
                    <td>R$ {{ number_format($saque->cash_out_liquido, '2',',','.') }}</td>
                    <td>{{ $saque->taxa_cash_out }}%</td>
                    <td>
                        <button type="button" class="btn bnt-sm btn-success" data-bs-toggle="modal" data-bs-target="#aproveSaque">
                            Aprovar
                        </button>
                        <button type="button" class="btn bnt-sm btn-danger" data-bs-toggle="modal" data-bs-target="#recuseSaque">
                            Recusar
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
 document.addEventListener("DOMContentLoaded", function() {
    $("#table-admin-saques").DataTable({
		responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
        }
	});
 });

</script>
@endsection
