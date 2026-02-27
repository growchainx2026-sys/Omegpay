@props(['produto'])
<div class="offcanvas offcanvas-end" tabindex="-1" id="addCheckout" aria-labelledby="addCheckoutLabel">
    <div class="offcanvas-header">
        <h5 id="addCheckoutLabel" class="texto-branco">Criar novo checkout</h5>
        <button type="button" class="btn-close texto-branco text-reset" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row" id="form-novo-checkout">
            <div class="col-12 mb-3">
                <label for="checkout_name" class="texto-branco">Nome</label>
                <input autofocus="true" type="text" class="form-control texto-branco" name="checkout_name"
                    id="checkout_name">
            </div>
            <div class="col-12 mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="checkout_padrao">
                    <label class="form-check-label texto-branco" for="checkout_padrao">Definir como checkout
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
                                        value="{{ $produto->id }}" id="checkout_produto_id">
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
                <button type="button" class="btn btn-primary" id="btn-add-checkout">Salvar alterações</button>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('btn-add-checkout').addEventListener('click', async function () {
    try {
        const form = document.getElementById("form-novo-checkout");

        const name = form.querySelector('#checkout_name').value.trim();
        const padrao = form.querySelector('#checkout_padrao').checked;

        const produto_id = [];
        form.querySelectorAll('input[name="checkout_produto_id"]:checked').forEach((checkbox) => {
            produto_id.push(checkbox.value);
        });

        const data = {
            name,
            default: padrao,
            produto_id: produto_id[0] // ou envie o array inteiro
        };

        const response = await fetch('/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (!response.ok) throw new Error(result.message || 'Erro ao enviar');

        const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('addCheckout'));
        offcanvas.hide();
        showToast('success', 'Checkout criado com sucesso!');
        window.location.reload();

    } catch (error) {
        console.error("Erro capturado:", error);
        showToast("error", "Erro ao criar o checkout.");
    }
});

</script>