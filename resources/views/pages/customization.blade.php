@extends('layouts.app')

@section('title', 'Customização')

@section('content')
<div class="header mb-3">
    <h1 class="header-title">
        Customizações
    </h1>
</div>
<form class="row" method="POST" action="{{ route('user.customization.update') }}" enctype="multipart/form-data">
    @csrf

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @php
                        $isAdmin = in_array(auth()->user()->permission, ['admin', 'dev']);
                    @endphp
                    
                    <div class="{{ $isAdmin ? 'col-md-3' : 'col-md-12' }}">
                        <div class="form-floating mb-3">
                            <input type="color" class="form-control" id="software_color" name="software_color" value="{{ auth()->user()->software_color ?? null }}" placeholder="Digite uma descrição para a plataforma">
                            <label for="software_color">Cor principal (ícones e botões)</label>
                        </div>
                    </div>
                    
                    @if($isAdmin)
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input type="color" class="form-control" id="software_color_background" name="software_color_background" value="{{ auth()->user()->software_color_background ?? null }}" placeholder="Digite uma descrição para a plataforma">
                                <label for="software_color_background">Cor do fundo</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input type="color" class="form-control" id="software_color_sidebar" name="software_color_sidebar" value="{{ auth()->user()->software_color_sidebar ?? null }}" placeholder="Digite uma descrição para a plataforma">
                                <label for="software_color_sidebar">Cor do menu lateral</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input type="color" class="form-control" id="software_color_text" name="software_color_text" value="{{ auth()->user()->software_color_text ?? null }}" placeholder="Digite uma descrição para a plataforma">
                                <label for="software_color_text">Cor dos textos</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-image-upload id="logo_light" name="logo_light" label="Logo (1080x174)" :value="auth()->user()->logo_light ?? null" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-image-upload id="favicon_light" name="favicon_light" label="Icone (512x512)" :value="auth()->user()->favicon_light ?? null" />
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary" ><i class="fa-solid fa-save"></i>&nbsp;Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>
@endsection
