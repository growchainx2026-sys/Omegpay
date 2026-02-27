@props([
    'id'      => Str::uuid(),          // garante id único se não for passado
    'name'    => 'methods[]',          // → array no request
    'method'  => 'PIX',
    'valor'   => 0,
    'icon'    => 'fa-solid fa-gem',
    'default' => false,
    'checked' => false
])

{{-- checkbox oculto --}}
<input
    type="checkbox"
    class="btn-check"                   {{-- Bootstrap esconde o input --}}
    id="{{ $id }}"
    name="{{ $name }}"
    value="{{ $id }}"
    autocomplete="off"
    {{ $checked ? 'checked' : '' }}    {{-- pré‑seleciona se $default --}}
/>

{{-- card vira o <label> --}}
<label class="pix-card position-relative p-3 text-center" for="{{ $id }}">
    <i class="{{ $icon }} fa-2x mb-3"></i>

    <h6 class="fw-bold mb-1">{{ $method }}</h6>

    <small class="d-block mb-3">
        Valor líquido:
        <strong>R$ {{ number_format($valor, 2, ',', '.') }}</strong>
    </small>

    @if ($default)
        <span class="pix-default">Método padrão</span>
    @endif
</label>

@once      {{-- evita duplicar estilo em loops --}}
    <style>
        /* --------- Card base --------- */
        .pix-card {
            background: var(--gateway-opacity-2);
            border: 1px solid var(--gateway-primary-color);
            border-radius: .75rem;
            color: var(--gateway-text-color);
            width: 100%;
            cursor: pointer;          /* deixa claro que é clicável */
            transition: .2s;
        }

        /* efeito quando selecionado */
        .btn-check:checked + .pix-card,
        .pix-card:hover {
            box-shadow: 0 0 0 3px var(--gateway-primary-color);
        }

        /* etiqueta “Método padrão” */
        .pix-default {
            background: var(--gateway-primary-color);
            color: #d1d1d1;
            font-weight: 500;
            font-size: .65rem;
            padding: .25rem 1rem;
            border-radius: 0.5rem;
            position: absolute;
            bottom: 3px;
            left:3px;
            right:3px;
        }
    </style>
@endonce
