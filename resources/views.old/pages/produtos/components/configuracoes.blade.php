@props([
'produto'
])
@php
$checks = $produto->methods ?? [];
@endphp
<div class="row">
    <div class="col-lg-4">
        <h4 class="texto-branco">Pagamento</h4>
        <p class="texto-branco">Configure as opções de pagamentos aceitos</p>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="text-header">Métodos de pagamento</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="row">
                            <div class="col-12 col-lg-4 col-xl-3 mb-3">
                                <x-card-forma-pagamento-produto
                                    :id="'pix'"
                                    :method="'PIX'"
                                    :icon="'fa-brands fa-pix'"
                                    :default="true"
                                    :checked="in_array('pix', $checks, true)"></x-card-forma-pagamento-produto>
                            </div>
                            <div class="col-12 col-lg-4 col-xl-3 mb-3">
                                <x-card-forma-pagamento-produto
                                    :id="'cartao'"
                                    :method="'Cartão'"
                                    :icon="'fa-solid fa-credit-card'"
                                    :checked="in_array('cartao', $checks, true)"></x-card-forma-pagamento-produto>
                            </div>
                            <div class="col-12 col-lg-4 col-xl-3 mb-3">
                                <x-card-forma-pagamento-produto
                                    :id="'boleto'"
                                    :method="'Boleto'"
                                    :icon="'fa-solid fa-barcode'"
                                    :checked="in_array('boleto', $checks, true)"></x-card-forma-pagamento-produto>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>