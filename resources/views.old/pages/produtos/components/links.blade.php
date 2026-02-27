@props([
'produto',
'checkouts' => []
])
<div class="row">
    <div class="col-12">
        <div class="card w-100">
            <div class="card-body w-100">
                <table class="table w-100" id="table-links-checkout">
                    <thead>
                        <tr>
                            <th>Checkout</th>
                            <th>Link</th>
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
                                <div class="d-grid gap-2 d-md-block">
                                    <a href="/produto/{{$checkout->uuid}}" target="_blank">
                                        <button class="btn btn-primary text-white btn-sm" type="button"><x-lucide-icon :icon="'globe'" :color="'white'" />{{' '}}Visualizar</button>
                                    </a>
                                    <button class="btn btn-primary text-white btn-sm" type="button"><i class="fab fa-whatsapp" style="font-size:16px"></i>{{' '}}Whatsapp</button>
                                    <button
                                        class="btn btn-primary text-white btn-sm btn-share"
                                        type="button"
                                        data-url="{{ '/produto/' . $checkout->uuid }}"><x-lucide-icon :icon="'share-2'" :color="'white'"/>{{' '}}Compartilhar</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // ... seu código DataTables ...

        // Compartilhamento
        const shareButtons = document.querySelectorAll('.btn-share');

        shareButtons.forEach(button => {
            button.addEventListener('click', async () => {
                const url = button.getAttribute('data-url');
                const fullUrl = window.location.origin + url;

                if (navigator.share) {
                    try {
                        await navigator.share({
                            title: '{{ $produto->name }}',
                            text: 'Aproveite esta oferta incrível: {{ $produto->name }} por apenas {{ "R$ ".number_format($produto->price, 2, ",",".") }}',
                            url: fullUrl,
                        });
                    } catch (err) {
                        console.error('Erro ao compartilhar:', err);
                    }
                } else {
                    // Fallback para copiar o link
                    navigator.clipboard.writeText(fullUrl).then(() => {
                        alert("Link copiado para a área de transferência!");
                    });
                }
            });
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var table = $("#table-links-checkout").DataTable({
            responsive: true,
            ordering: false,
            lengthChange: false,
            dom: 't', // Remove o search padrão do DataTables
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
            }
        });

        // Aplicar busca personalizada
        $('#custom-search').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Ajuste visual após renderização
        table.on('draw', function() {
            $('#table-links-checkout tbody tr').each(function() {
                $(this).find('td').css('border-bottom', 'none');
            });
        });

        table.draw(); // Inicializa com ajustes
    });
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
    }

    /* <-- esta chave final estava faltando */
</style>