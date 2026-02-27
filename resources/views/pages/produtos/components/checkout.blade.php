@props(['produto', 'checkouts' => []])

<style>
.pe-ck-bar { display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center; margin-bottom: 0.75rem; }
.pe-ck-bar .form-control { max-width: 220px; font-size: 0.875rem; border-radius: 8px; }
.pe-section-body { border: 1px solid rgba(165,170,177,0.2); border-radius: 10px; overflow: hidden; }
body.dark-mode .pe-section-body { border-color: rgba(30,41,59,0.8); }
#table-produto-checkout { margin: 0; }
#table-produto-checkout thead th { font-size: 0.8125rem; font-weight: 600; padding: 0.75rem 1rem; border-bottom: 1px solid rgba(165,170,177,0.2); background: transparent; }
#table-produto-checkout tbody td { padding: 0.75rem 1rem; font-size: 0.875rem; vertical-align: middle; border-bottom: none; }
#table-produto-checkout tbody tr { border-bottom: 1px solid rgba(165,170,177,0.12); }
body.dark-mode #table-produto-checkout tbody tr { border-bottom-color: rgba(30,41,59,0.5); }
</style>

<div class="pe-ck-bar" id="custom-toolbar">
    <input type="text" id="custom-search" placeholder="Pesquisar..." class="form-control" />
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="offcanvas" data-bs-target="#addCheckout" aria-controls="addCheckout"><i class="fa-solid fa-plus me-1"></i> Adicionar Checkout</button>
</div>
<div class="pe-section-body">
    <table class="table w-100" id="table-produto-checkout">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th>Visitas</th>
                            <th>Oferta</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produto->checkouts as $checkout)
                        <tr>
                            <td>
                            {{ $checkout->name }}
                            @if ($checkout->default)
                                <span class="badge bg-primary">Padrão</span>
                            @endif
                            </td>
                            <td>
                                <span class="badge {{ $checkout->price ? 'bg-primary' : 'bg-dark' }}">
                                    {{ $checkout->price ? "R$ ".number_format($checkout->price,2,',','.') : 'N/A' }}
                                </span>
                            </td>
                                <td>{{ $checkout->visits ?? 0 }}</td>
                            <td>
                                <span class="badge {{ $checkout->oferta ? 'bg-primary' : 'bg-dark' }}">
                                    {{ $checkout->oferta ? $checkout->oferta : 'N/A' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-outline-primary btn-sm button-more" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical text info"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="/checkout-builder/{{ $checkout->uuid }}" target="_blank">
                                        <button type="button" class="dropdown-item btn-visualizar" >
                                            <i class="fa-solid fa-image pr-2"></i>&nbsp;Personalizar
                                        </button>
                                    </a>
                                </li>
                                <li>
                                    <button 
                                    type="button" 
                                    class="dropdown-item btn-visualizar"
                                    data-bs-toggle="offcanvas"
                                    data-bs-target="#editCheckout{{ $checkout->id }}">
                                        <i class="fa-solid fa-gear pr-2"></i>&nbsp;Configurações
                                    </button>
                                </li>
                                <li>
                                    <button 
                                    type="button" 
                                    class="dropdown-item btn-visualizar"
                                    onclick="duplicateCheckout({{ $checkout->id }})">
                                        <i class="fa-solid fa-copy pr-2"></i>&nbsp;Duplicar
                                    </button>
                                </li>
                                <li>
                                    <button 
                                    id="btn-excluir-checkout" 
                                    type="button" 
                                    class="dropdown-item btn-visualizar"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#excluir{{ $checkout->id }}">
                                        <i class="fa-solid fa-trash pr-2"></i>&nbsp;Deletar
                                    </button>
                                </li>
                            </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
@foreach ($produto->checkouts as $checkout)
    @include('pages.produtos.components.modais.editar-checkout', ['checkout' => $checkout])
    @include('pages.produtos.components.modais.excluir-checkout', ['checkout' => $checkout])
@endforeach
@include('pages.produtos.components.modais.adicionar-checkout', compact('produto'))





<script>
document.addEventListener("DOMContentLoaded", function () {
    var table = $("#table-produto-checkout").DataTable({
        responsive: true,
        ordering: false,
        lengthChange: false,
        dom: 't', // Remove o search padrão do DataTables
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
        }
    });

    // Aplicar busca personalizada
    $('#custom-search').on('keyup', function () {
        table.search(this.value).draw();
    });

    // Ajuste visual após renderização
    table.on('draw', function () {
        $('#table-produto-checkout tbody tr').each(function () {
            $(this).find('td').css('border-bottom', 'none');
        });
    });

    table.draw(); // Inicializa com ajustes
});

</script>

<script>
    function duplicateCheckout(id) {
        const data = {};

        fetch(`/checkout/duplicate/${id}`, {
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
            showToast('success', 'Checkout duplicado com sucesso!');
            setTimeout(() => window.location.reload(), 3000);
        })
        .catch(error => {
            console.error(error);
            showToast("error", "Erro ao duplicar o checkout.");
        });
    }
</script>


<!-- CSS opcional para alinhamento -->
<style>
#custom-search {
    max-width: 180px;
}

@media screen and (max-width: 540px) {
    #custom-toolbar {
        display: inline;
    }

    #custom-search {
        width: 100%;
    }
} /* <-- esta chave final estava faltando */
</style>
