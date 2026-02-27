@extends('layouts.app')

@section('title', 'Página não encontrada')

@section('content')
    <div class="w-100 d-flex align-items-center justify-content-center" style="height: 85vh;">
        <div class="row gap-3">
            <div class="col-12 text-center fs-3">Pagina não encontrada</div>
            <div class="col-12 text-center">
                <button class="btn btn-primary" onclick="window.history.back()">
                    Voltar
                </button>
            </div>
        </div>
    </div>

@endsection