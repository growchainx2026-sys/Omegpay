@extends('layouts.app')

@section('title', 'Clientes cadastrados')

@section('content')
    <div class="header mb-3">
        <h3 class="header-title">
            Clientes cadastrados
        </h3>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="card" style="min-height: 120px; max-height: 120px; border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h6 class="card-title text-start">Cadastros (Hoje)</h6>
                        </div>

                        <div class="col-auto">
                            <span class="text-primary" style="font-size:28px"><i class="fa-solid fa-users"></i></span>
                        </div>
                    </div>
                    <h3 class=" display-5 mt-1 mb-3 text-start">
                        {{ $clientes->where('created_at', '>=', \Carbon\Carbon::today())->where('created_at', '<=', \Carbon\Carbon::now())->count() }}
                    </h3>
                    <div class="mb-0 text-start">
                        <span class="text-success text-start"> <i class="mdi mdi-arrow-bottom-right"></i> </span>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card" style="min-height: 120px; max-height: 120px; border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h6 class="card-title text-start">Cadastros (Semana)</h6>
                        </div>

                        <div class="col-auto">
                            <span class="text-primary" style="font-size:28px"><i class="fa-solid fa-users"></i></span>
                        </div>
                    </div>
                    <h3 class=" display-5 mt-1 mb-3 text-start">
                        {{ $clientes->filter(function ($cliente) {
        return $cliente->created_at >= \Carbon\Carbon::now()->startOfWeek() &&
            $cliente->created_at <= \Carbon\Carbon::now()->endOfWeek();
    })->count() }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card" style="min-height: 120px; max-height: 120px; border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h6 class="card-title text-start">Cadastros (Mês)</h6>
                        </div>

                        <div class="col-auto">
                            <span class="text-primary" style="font-size:28px"><i class="fa-solid fa-users"></i></span>
                        </div>
                    </div>
                    <h3 class=" display-5 mt-1 mb-3 text-start">
                        {{ $clientes->filter(function ($cliente) {
        return $cliente->created_at >= \Carbon\Carbon::now()->startOfMonth() &&
            $cliente->created_at <= \Carbon\Carbon::now()->endOfMonth();
    })->count() }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card" style="min-height: 120px; max-height: 120px; border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h6 class="card-title text-start">Cadastros (Total)</h6>
                        </div>

                        <div class="col-auto">
                            <span class="text-primary" style="font-size:28px"><i class="fa-solid fa-users"></i></span>
                        </div>
                    </div>
                    <h3 class=" display-5 mt-1 mb-3 text-start">{{ $clientes->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="card" style="min-height: 80px; max-height: 80px; border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h6 class="card-title text-primary text-start"><strong
                                    style="font-size:32px;">{{ $clientes->where('status', '==', 'aprovado')->where('banido', '==', 0)->count() }}</strong>&nbsp;clientes
                                Ativos</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card" style="min-height: 80px; max-height: 80px; border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h6 class="card-title text-secondary text-start"><strong
                                    style="font-size:32px;">{{ $clientes->where('status', '==', 'analise')->count() }}</strong>&nbsp;clientes
                                em análise</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card" style="min-height: 80px; max-height: 80px; border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h6 class="card-title text-danger text-start"><strong
                                    style="font-size:32px;">{{ $clientes->where('status', '==', 'reprovado')->count() }}</strong>&nbsp;clientes
                                reprovados</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card" style="min-height: 80px; max-height: 80px; border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h6 class="card-title text-warning text-start"><strong
                                    style="font-size:32px;">{{ $clientes->where('banido', '==', 1)->count() }}</strong>&nbsp;clientes
                                banidos</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach ($clientes as $client)
            <div class="col-12 col-md-6 col-lg-4 mb-3">
                <x-card-client :client="$client"></x-card-client>
            </div>


            <div class="modal fade" id="delCliente{{ $client->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="delCliente{{ $client->id }}Label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="delCliente{{ $client->id }}Label">Excluir Cliente
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h6>Você tem certeza que deseja excluir o cliente <span
                                    class="text-danger">{{ $client->name }}</span>?</h6>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <form method="POST" action="{{ route('admin.clientes.excluir') }}">
                                @csrf
                                <input hidden name="id" value="{{ $client->id }}">
                                <button type="submit" class="btn btn-danger text-white">Excluir</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

