@extends('layouts.app')

@section('title', 'Minhas carteira')

@section('content')
<div class="header mb-3">
    <h1 class="header-title">
        Minhas Carteira
    </h1>
</div>

    <div class="row">
      <div class="col-sm-6 col-md-3">
        <div class="card" style="min-height: 155px">
            <div class="card-body">
                <div class="row">
                    <div class="col mt-0">
                        <h5 class="card-title text-start">Saldo disponível</h5>
                    </div>
                    <div class="col-auto">
                        <span class="text-success" style="font-size:36px"><i class="fa-solid fa-wallet"></i></span>
                    </div>
                </div>
                <h1 class=" display-5 mt-1 mb-3 text-start text-success">R$ {{ number_format(auth()->user()->saldo, '2',',','.') }}</h1>
                <div class="mb-0 text-start">
                    <span class="text-success text-start"> <i class="mdi mdi-arrow-bottom-right"></i>  </span>

                </div>
            </div>
        </div>   
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card" style="min-height: 155px">
            <div class="card-body">
                <div class="row">
                    <div class="col mt-0">
                        <h5 class="card-title text-start">Saldo bloqueado</h5>
                    </div>
                    <div class="col-auto">
                        <span class="text-warning" style="font-size:36px"><i class="fa-solid fa-wallet"></i></span>
                    </div>
                </div>
                <h1 class=" display-5 mt-1 mb-3 text-start text-warning">R$ {{ number_format(auth()->user()->saldo, '2',',','.') }}</h1>
                <div class="mb-0 text-start">
                    <span class="text-success text-start"> <i class="mdi mdi-arrow-bottom-right"></i>  </span>

                </div>
            </div>
        </div>   
      </div>
    </div>
@endsection