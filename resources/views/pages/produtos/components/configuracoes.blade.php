@props(['produto'])
@php $checks = $produto->methods ?? []; @endphp

<style>
.pe-section { margin-bottom: 1.75rem; }
.pe-section-title { font-size: 0.9375rem; font-weight: 600; color: var(--gateway-text-color); margin: 0 0 0.5rem 0; }
.pe-section-desc { font-size: 0.8125rem; color: #64748b; margin: 0 0 0.75rem 0; }
body.dark-mode .pe-section-desc { color: #94a3b8; }
.pe-section-body { border: 1px solid rgba(165,170,177,0.2); border-radius: 10px; padding: 1.25rem; }
body.dark-mode .pe-section-body { border-color: rgba(30,41,59,0.8); }
.pe-methods { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 0.75rem; }
</style>

<div class="pe-section">
    <h3 class="pe-section-title">Pagamento</h3>
    <p class="pe-section-desc">Métodos de pagamento aceitos para este produto.</p>
    <div class="pe-section-body">
        <div class="pe-methods">
            <x-card-forma-pagamento-produto :id="'pix'" :method="'PIX'" :icon="'fa-brands fa-pix'" :default="true" :checked="in_array('pix', $checks, true)" />
            <x-card-forma-pagamento-produto :id="'cartao'" :method="'Cartão'" :icon="'fa-solid fa-credit-card'" :checked="in_array('cartao', $checks, true)" />
            <x-card-forma-pagamento-produto :id="'boleto'" :method="'Boleto'" :icon="'fa-solid fa-barcode'" :checked="in_array('boleto', $checks, true)" />
        </div>
    </div>
</div>
