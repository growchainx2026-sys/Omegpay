@extends('layouts.app')

@section('title', 'Configurações')

@section('content')
<div class="header mb-3">
    <h1 class="header-title">
        Customizações
    </h1>
</div>
<form class="row" method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Identidade visual</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <input type="color" class="form-control" id="software_color" name="software_color" value="{{ $settings->software_color }}" placeholder="Digite uma descrição para a plataforma">
                            <label for="software_color">Cor dos icones e botões</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <input type="color" class="form-control" id="software_color_background" name="software_color_background" value="{{ $settings->software_color_background }}" placeholder="Digite uma descrição para a plataforma">
                            <label for="software_color_background">Cor do fundo</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <input type="color" class="form-control" id="software_color_sidebar" name="software_color_sidebar" value="{{ $settings->software_color_sidebar }}" placeholder="Digite uma descrição para a plataforma">
                            <label for="software_color_sidebar">Cor do menu lateral</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating mb-3">
                            <input type="color" class="form-control" id="software_color_text" name="software_color_text" value="{{ $settings->software_color_text }}" placeholder="Digite uma descrição para a plataforma">
                            <label for="software_color_text">Cor dos textos</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <x-image-upload id="logo_light" name="logo_light" label="Logo (1080x174)" :value="$settings->logo_light" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <x-image-upload id="favicon_light" name="favicon_light" label="Icone (512x512)" :value="$settings->favicon_light" />
                    </div>
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
<script>
 $("input[id*='taxa_cash_in'], input[id*='taxa_cash_out']").inputmask({
    alias: "decimal",
    integerDigits: 3,
    digits: 2,
    max: 100,
    allowMinus: false,
    digitsOptional: true,           // <- Torna os decimais opcionais na digitação
    placeholder: "",                // <- Campo visualmente limpo ao carregar
    radixPoint: ".",
    removeMaskOnSubmit: true,
    autoGroup: false
});
$("input[id*='taxa_fixa'], input[id*='baseline']").inputmask('decimal', {
    alias: 'numeric',
    groupSeparator: '',
    autoGroup: false,
    digits: 2,
    radixPoint: ".",
    digitsOptional: true,
    allowMinus: false,
    prefix: 'R$ ',
    placeholder: '',                 // <- Nada visível no campo por padrão
    removeMaskOnSubmit: true,
    unmaskAsNumber: true
});

</script>
@endsection
