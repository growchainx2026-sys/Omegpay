@extends('layouts.app')

@section('title', 'Saque Pix Copia e Cola')

@section('content')
<div class="header">
    <h1 class="header-title">
        Saque Pix Copia e Cola
    </h1>
    <p class="header-subtitle">Preencha os dados abaixo para fazer o pagamento</p>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Dados do Pagamento</h5>
            </div>
            <div class="card-body">
                <span class="badge text-bg-dark mb-4 py-2" style="width: 100%;height:40px;font-size:32px;display:flex;align-items:center;justify-content:center;">
                    R$ 25.000,00
                </span>
                <form class="row row-cols-md-auto align-items-center">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label>Copia e Cola</label>
                            <input type="text" class="form-control" >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label>Valor</label>
                            <input type="text" class="form-control" >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label>Taxa</label>
                            <input type="text" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label>Valor Total</label>
                            <input type="text" class="form-control" >
                        </div>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 mb-0"><i class="fa-solid fa-upload"></i>&nbsp;Sacar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
