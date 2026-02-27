@props(['produto', 'checkouts' => []])

<style>
.pe-section-body { border: 1px solid rgba(165,170,177,0.2); border-radius: 10px; overflow: hidden; }
body.dark-mode .pe-section-body { border-color: rgba(30,41,59,0.8); }
#table-links-checkout { margin: 0; }
#table-links-checkout thead th { font-size: 0.8125rem; font-weight: 600; padding: 0.75rem 1rem; border-bottom: 1px solid rgba(165,170,177,0.2); background: transparent; }
#table-links-checkout tbody td { padding: 0.75rem 1rem; font-size: 0.875rem; vertical-align: middle; border-bottom: none; }
#table-links-checkout tbody tr { border-bottom: 1px solid rgba(165,170,177,0.12); }
body.dark-mode #table-links-checkout tbody tr { border-bottom-color: rgba(30,41,59,0.5); }
.pe-link-actions { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.pe-link-actions .btn { font-size: 0.8125rem; }
</style>

<div class="pe-section-body">
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
                    @if ($checkout->default)<span class="badge bg-primary ms-1">Padrão</span>@endif
                </td>
                <td>
                    <div class="pe-link-actions">
                        <a href="/produto/{{ $checkout->uuid }}" target="_blank" class="btn btn-primary btn-sm text-white">
                            <i class="fa-solid fa-external-link-alt me-1"></i> Visualizar
                        </a>
                        <a href="https://wa.me/?text={{ urlencode('/produto/'.$checkout->uuid) }}" target="_blank" class="btn btn-success btn-sm text-white">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                        <button type="button" class="btn btn-outline-primary btn-sm btn-share" data-url="{{ '/produto/' . $checkout->uuid }}">
                            <i class="fa-solid fa-share-alt me-1"></i> Compartilhar
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var table = $("#table-links-checkout").DataTable({
        responsive: true,
        ordering: false,
        lengthChange: false,
        dom: 't',
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json' }
    });
    table.on('draw', function () {
        $('#table-links-checkout tbody tr').each(function () { $(this).find('td').css('border-bottom', 'none'); });
    });
    table.draw();

    document.querySelectorAll('.btn-share').forEach(function(button) {
        button.addEventListener('click', async function() {
            var url = button.getAttribute('data-url');
            var fullUrl = window.location.origin + url;
            if (navigator.share) {
                try {
                    await navigator.share({
                        title: '{{ addslashes($produto->name) }}',
                        text: '{{ addslashes("R$ ".number_format($produto->price, 2, ",",".")) }}',
                        url: fullUrl
                    });
                } catch (err) { console.error(err); }
            } else {
                navigator.clipboard.writeText(fullUrl).then(function() { typeof showToast === 'function' && showToast('success', 'Link copiado!'); });
            }
        });
    });
});
</script>
