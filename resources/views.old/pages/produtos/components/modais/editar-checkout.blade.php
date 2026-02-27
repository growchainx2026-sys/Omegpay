@props([
    'checkout'
    ])

    @php
        $produto = $checkout->produto;
        $formId = "form-edit-checkout-{$checkout->id}";
        $checkboxId = "checkout_padrao_{$checkout->id}";
    @endphp
<div class="offcanvas offcanvas-end" tabindex="-1" id="editCheckout{{ $checkout->id }}"
    aria-labelledby="editCheckout{{ $checkout->id }}Label">
    <div class="offcanvas-header">
        <h5 id="editCheckout{{ $checkout->id }}Label" class="texto-branco">Editar checkout</h5>
        <button type="button" class="btn-close texto-branco text-reset" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row" id="{{ $formId }}">
            <div class="col-12 mb-3">
                <label for="checkout_name" class="texto-branco">Nome</label>
                <input autofocus="true" type="text" class="form-control texto-branco" name="checkout_name"
                    value="{{ $checkout->name }}" id="{{ uniqid() }}">
            </div>
            <div class="col-12 mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="{{ $checkboxId }}"
                        value="{{ $checkout->default }}" {{ $checkout->default ? 'checked' : '' }}>
                    <label class="form-check-label texto-branco" for="{{ $checkboxId }}">Definir como checkout
                        padrão</label>
                </div>
            </div>
            <div class="col-12 mb-3 table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="texto-branco">Link</th>
                            <th class="texto-branco">Preço</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="checkout_produto_id"
                                        value="{{ $produto->id }}" id="checkout_produto_id" checked>
                                </div>
                            </td>
                            <td class="texto-branco">{{ $produto->name }}</td>
                            <td class="texto-branco">R$ {{ number_format($produto->price, 2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-12 mb-3 text-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="editarCheckout({{ $checkout->id }})">Salvar alterações</button>
            </div>
        </div>
    </div>
</div>

<script>
    function editarCheckout(id) {
        const form = document.getElementById(`form-edit-checkout-${id}`);
        const name = form.querySelector('input[name="checkout_name"]').value.trim();
        const padrao = form.querySelector(`#checkout_padrao_${id}`).checked;
        const produto_id = [];

        form.querySelectorAll('input[name="checkout_produto_id"]:checked').forEach((checkbox) => {
            produto_id.push(checkbox.value);
        });

        const data = {
            name,
            default: padrao,
            produto_id: produto_id[0] || null,
        };

        fetch(`/checkout/editar/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) throw new Error('Erro ao atualizar');
            return response.json();
        })
        .then(result => {
            showToast('success', 'Checkout atualizado com sucesso!');
            const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById(`editCheckout${id}`));
            offcanvas.hide();
            setTimeout(() => window.location.reload(), 3000);
        })
        .catch(error => {
            console.error(error);
            showToast("error", "Erro ao atualizar o checkout.");
        });
    }
</script>
