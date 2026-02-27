@props(['produto'])

<style>
.pe-cupom-bar { display: flex; justify-content: flex-end; margin-bottom: 0.75rem; }
.pe-section-body { border: 1px solid rgba(165,170,177,0.2); border-radius: 10px; overflow: hidden; }
body.dark-mode .pe-section-body { border-color: rgba(30,41,59,0.8); }
#table-cupons { margin: 0; }
#table-cupons thead th { font-size: 0.8125rem; font-weight: 600; padding: 0.75rem 1rem; border-bottom: 1px solid rgba(165,170,177,0.2); background: transparent; }
#table-cupons tbody td { padding: 0.75rem 1rem; font-size: 0.875rem; vertical-align: middle; border-bottom: none; }
#table-cupons tbody tr { border-bottom: 1px solid rgba(165,170,177,0.12); }
body.dark-mode #table-cupons tbody tr { border-bottom-color: rgba(30,41,59,0.5); }
.pe-cupom-actions { display: flex; gap: 0.5rem; }
</style>

<div class="pe-cupom-bar">
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="offcanvas" data-bs-target="#addCupon"
        onclick="document.getElementById('input-produto-id-form-cadastrar-cupom').value = '{{ $produto->id }}'"
        aria-controls="offcanvasRight"><i class="fa-solid fa-plus me-1"></i> Cadastrar cupom</button>
</div>
<div class="pe-section-body">
    <table class="table table-responsive w-100" id="table-cupons">
        <thead>
            <tr>
                <th>Código</th>
                <th>Produto</th>
                <th>Tipo</th>
                <th>Desconto</th>
                <th>Início</th>
                <th>Fim</th>
                <th>Usos</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produto->cupons as $cupom)
            <tr>
                <td><strong>{{ $cupom->codigo }}</strong></td>
                <td>{{ $cupom->produto->name }}</td>
                <td>{{ $cupom->type == 'percent' ? 'Porcentagem' : 'Fixo' }}</td>
                <td>
                    @if ($cupom->type == 'percent')
                        {{ number_format($cupom->desconto, 2) }}%
                    @else
                        R$ {{ number_format($cupom->desconto, 2, ',', '.') }}
                    @endif
                </td>
                <td>{{ $cupom->data_inicio->format('d/m/Y H:i') }}</td>
                <td>{{ $cupom->data_termino ? $cupom->data_termino->format('d/m/Y H:i') : '—' }}</td>
                <td>{{ $cupom->usage }}</td>
                <td>
                    <div class="pe-cupom-actions">
                        <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#editCupom{{ $cupom->id }}" title="Editar"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-outline-danger btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#delCupomModal{{ $cupom->id }}" title="Excluir"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if(session('modal'))
<script>document.addEventListener('DOMContentLoaded', function () { var m = document.getElementById('addCoprodutorModal'); if (m) new bootstrap.Modal(m).show(); });</script>
@endif

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.btn-revogar').forEach(function(button) {
        button.addEventListener('click', function () {
            var url = this.getAttribute('data-url');
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.enctype = 'multipart/form-data';
            var csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            var method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        });
    });
    var table = $("#table-cupons").DataTable({
        responsive: true,
        ordering: false,
        lengthChange: false,
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json', search: '' }
    });
    table.on('draw', function () {
        $('#table-cupons tbody tr').each(function () { $(this).find('td').css('border-bottom', 'none'); });
    });
    table.draw();
});
</script>
