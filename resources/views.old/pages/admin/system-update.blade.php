@extends('layouts.app')

@section('title', 'System Update')

@section('content')
    <div class="header mb-3">
        <h3 class="header-title">
            Atualizações do sistema
        </h3>
    </div>

    <div class="d-flex align-items-center justify-content-center" style="height: 80vh">
        <div class="row">
            <div class="col-12 text-center">
                <h6>Versão atual: {{ config('app.version') }}</h6>
                @if ($new['version'] == config('app.version'))
                    <div class="row">
                        <small class="text-warning fs-6">Nova versão disponínvel</small>
                        <button class="btn btn-sm btn-warning">Atualizar para {{ $new['version'] }}</button>
                    </div>
                @else
                    <small class="text-success fs-6">Seu sistema já encontra-se na versão mais recente.</small>
                @endif
            </div>
        </div>
    </div>
@endsection
