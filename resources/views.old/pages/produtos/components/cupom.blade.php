@props(['produto'])
<div class="row">

    <div class="col-12 mb-3 d-flex align-items-center justify-content-end">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="offcanvas" data-bs-target="#addCupon"
            onclick="document.getElementById('input-produto-id-form-cadastrar-cupom').value = '{{ $produto->id }}'"
            aria-controls="offcanvasRight">+ Cadastrar</button>
    </div>
    <div class="col-12">
        <div class="card card-dash">
            <div class="card-body">
                <table class="table table-responsive" id="table-cupons">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Produto</th>
                            <th>Tipo de desconto</th>
                            <th>Desconto</th>
                            <th>Início</th>
                            <th>Fim</th>
                            <th>#Usos</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produto->cupons as $cupom)
                            <tr>
                                <td>{{ $cupom->codigo }}</td>
                                <td>{{ $cupom->produto->name }}</td>
                                <td>
                                    @if ($cupom->type == 'percent')
                                        {{ 'Porcentagem' }}
                                    @else
                                        {{ 'Fixo' }}
                                    @endif
                                </td>
                                <td>

                                    @if ($cupom->type == 'percent')
                                        {{ number_format($cupom->desconto, '2') }}%
                                    @else
                                        R$ {{ number_format($cupom->desconto, '2', ',', '.') }}
                                    @endif
                                </td>
                                <td>{{ $cupom->data_inicio->format('d/m/Y \à\s H:i:s')}}</td>
                                <td>{{ $cupom->data_termino->format('d/m/Y \à\s H:i:s')}}</td>
                                <td>{{ $cupom->usage }}</td>
                                <td class="gap-2">
                                    <button class="btn btn-info text-white btn-sm" type="button" data-bs-toggle="offcanvas"
                                        data-bs-target="#editCupom{{ $cupom->id }}">
                                        <x-lucide-icon :icon="'square-pen'" :color="'white'" />
                                    </button>
                                    <button class="btn btn-danger text-white btn-sm" type="button" data-bs-toggle="modal"
                                        data-bs-target="#delCupomModal{{ $cupom->id }}">
                                        <x-lucide-icon :icon="'trash'" :color="'white'" />
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>




@if(session('modal'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = new bootstrap.Modal(document.getElementById('addCoprodutorModal'));
            modal.show();
        });
    </script>
@endif

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.btn-revogar').forEach(button => {
            button.addEventListener('click', function () {
                let url = this.getAttribute('data-url');

                const form = document.createElement('form');
                form.method = 'POST'; // ou 'GET'
                form.action = url; // coloque a rota de envio
                form.enctype = 'multipart/form-data'; // importante para uploads

                // Adicionar token CSRF
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';
                form.appendChild(method);

                document.body.appendChild(form);

                form.submit();
            });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var table = $("#table-cupons").DataTable({
            responsive: true,
            ordering: false,
            lengthChange: false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                search: ''
            }
        });

        table.on('draw', function () {
            $('#table-cupons tbody tr').each(function () {
                $(this).find('td').css('border-bottom', 'none');
            });
        });

        // Garante que o evento draw também seja executado na primeira renderização
        table.draw();

    });
</script>