@extends('layouts.app')

@section('title', 'Customizações')

@section('content')
<div class="customization-page">
    <div class="customization-header">
        <h1 class="customization-title">Customizações</h1>
        <p class="customization-subtitle">Identidade visual e cores da plataforma</p>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="customization-form">
        @csrf

        <section class="customization-section">
            <h2 class="customization-section-title">Cores</h2>
            <div class="customization-colors">
                <div class="customization-color-field">
                    <label for="software_color">Ícones e botões</label>
                    <input type="color" id="software_color" name="software_color" value="{{ $settings->software_color }}" class="customization-color-input">
                </div>
                <div class="customization-color-field">
                    <label for="software_color_background">Fundo</label>
                    <input type="color" id="software_color_background" name="software_color_background" value="{{ $settings->software_color_background }}" class="customization-color-input">
                </div>
                <div class="customization-color-field">
                    <label for="software_color_sidebar">Menu lateral</label>
                    <input type="color" id="software_color_sidebar" name="software_color_sidebar" value="{{ $settings->software_color_sidebar }}" class="customization-color-input">
                </div>
                <div class="customization-color-field">
                    <label for="software_color_text">Textos</label>
                    <input type="color" id="software_color_text" name="software_color_text" value="{{ $settings->software_color_text }}" class="customization-color-input">
                </div>
            </div>
        </section>

        <section class="customization-section">
            <h2 class="customization-section-title">Imagens</h2>

            <div class="customization-image-block">
                <div class="customization-image-main">
                    <x-image-upload id="logo_light" name="logo_light" label="Logo (1080×174)" :value="$settings->logo_light" />
                </div>
                <form method="POST" action="{{ route('admin.settings.update') }}" class="customization-restore-form" onsubmit="return confirm('Usar logo padrão do sistema?');">
                    @csrf
                    <input type="hidden" name="restore_default" value="logo_light">
                    <button type="submit" class="btn-restore"><i class="fa-solid fa-rotate-left"></i> Restaurar padrão</button>
                </form>
            </div>

            <div class="customization-image-block">
                <div class="customization-image-main">
                    <x-image-upload id="favicon_light" name="favicon_light" label="Ícone (512×512)" :value="$settings->favicon_light" />
                </div>
                <form method="POST" action="{{ route('admin.settings.update') }}" class="customization-restore-form" onsubmit="return confirm('Usar ícone padrão do sistema?');">
                    @csrf
                    <input type="hidden" name="restore_default" value="favicon_light">
                    <button type="submit" class="btn-restore"><i class="fa-solid fa-rotate-left"></i> Restaurar padrão</button>
                </form>
            </div>

            <div class="customization-image-block customization-image-block--full">
                <div class="customization-image-main">
                    <x-image-upload id="login_background" name="login_background" label="Background da tela de login (recomendado: 980×1200)" :value="$settings->login_background ?? null" height="200px" />
                </div>
                @if(\Illuminate\Support\Facades\Schema::hasColumn('settings', 'login_background'))
                <form method="POST" action="{{ route('admin.settings.update') }}" class="customization-restore-form" onsubmit="return confirm('Usar background padrão do sistema?');">
                    @csrf
                    <input type="hidden" name="restore_default" value="login_background">
                    <button type="submit" class="btn-restore"><i class="fa-solid fa-rotate-left"></i> Restaurar padrão</button>
                </form>
                @endif
            </div>
        </section>

        <section class="customization-actions">
            <button type="submit" class="btn-save"><i class="fa-solid fa-save"></i> Salvar alterações</button>
        </section>
    </form>
</div>

<style>
.customization-page {
    max-width: 720px;
    margin: 0 auto;
    padding: 0 1rem 2rem;
}

.customization-header {
    margin-bottom: 2rem;
}

.customization-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 0 0.25rem 0;
    letter-spacing: -0.02em;
}

.customization-subtitle {
    font-size: 0.9375rem;
    margin: 0;
    opacity: 0.8;
}

.customization-section {
    margin-bottom: 2rem;
}

.customization-section-title {
    font-size: 0.8125rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin: 0 0 1rem 0;
    opacity: 0.85;
}

.customization-colors {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 1rem;
}

.customization-color-field {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.customization-color-field label {
    font-size: 0.8125rem;
    font-weight: 500;
}

.customization-color-input {
    width: 100%;
    height: 44px;
    padding: 4px;
    border-radius: 10px;
    border: 1px solid rgba(0,0,0,0.1);
    cursor: pointer;
    background: #fff;
}

.customization-image-block {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.customization-image-block--full {
    margin-bottom: 0;
}

.customization-image-main {
    flex: 1;
    min-width: 200px;
}

.customization-restore-form {
    flex-shrink: 0;
}

.btn-restore {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.875rem;
    font-size: 0.8125rem;
    font-weight: 500;
    border-radius: 8px;
    border: 1px solid #64748b;
    background: transparent;
    color: #475569;
    cursor: pointer;
    transition: border-color 0.2s, color 0.2s, background 0.2s;
}

.btn-restore:hover {
    border-color: #475569;
    color: #334155;
    background: rgba(0,0,0,0.03);
}

.btn-restore i {
    font-size: 0.75rem;
    opacity: 0.9;
}

.customization-actions {
    padding-top: 1.5rem;
    border-top: 1px solid rgba(0,0,0,0.08);
}

.btn-save {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    font-size: 0.9375rem;
    font-weight: 600;
    border-radius: 10px;
    border: none;
    background: var(--gateway-primary-color);
    color: #fff;
    cursor: pointer;
    transition: opacity 0.2s, transform 0.05s;
}

.btn-save:hover {
    opacity: 0.95;
}

.btn-save:active {
    transform: scale(0.98);
}

/* Dark mode */
body.dark-mode .customization-title,
body.dark-mode .customization-subtitle {
    color: #e2e8f0 !important;
}

body.dark-mode .customization-section-title {
    color: #94a3b8 !important;
}

body.dark-mode .customization-color-field label {
    color: #cbd5e1 !important;
}

body.dark-mode .customization-color-input {
    background: #1e293b;
    border-color: #334155;
}

body.dark-mode .btn-restore {
    border-color: #475569;
    color: #94a3b8;
}

body.dark-mode .btn-restore:hover {
    border-color: #64748b;
    color: #e2e8f0;
    background: rgba(255,255,255,0.06);
}

body.dark-mode .customization-actions {
    border-top-color: #1e293b;
}

body.dark-mode .customization-page .form-group label {
    color: #cbd5e1 !important;
}

body.dark-mode .customization-page .dropzone-area {
    border-color: #334155;
    background: rgba(30, 41, 59, 0.5);
}

body.dark-mode .customization-page .dropzone-area:hover {
    background: rgba(30, 41, 59, 0.7);
}

body.dark-mode .customization-page .dropzone-text {
    color: #64748b !important;
}
</style>

<script>
$("input[id*='taxa_cash_in'], input[id*='taxa_cash_out']").inputmask({
    alias: "decimal",
    integerDigits: 3,
    digits: 2,
    max: 100,
    allowMinus: false,
    digitsOptional: true,
    placeholder: "",
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
    placeholder: '',
    removeMaskOnSubmit: true,
    unmaskAsNumber: true
});
</script>
@endsection
