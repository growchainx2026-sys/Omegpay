@props(['produto'])

<style>
.pe-section-body { border: 1px solid rgba(165,170,177,0.2); border-radius: 10px; overflow: hidden; }
body.dark-mode .pe-section-body { border-color: rgba(30,41,59,0.8); }
#table-affiliates { margin: 0; }
#table-affiliates thead th { font-size: 0.8125rem; font-weight: 600; padding: 0.75rem 1rem; border-bottom: 1px solid rgba(165,170,177,0.2); background: transparent; }
#table-affiliates tbody td { padding: 0.75rem 1rem; font-size: 0.875rem; border-bottom: none; }
#table-affiliates tbody tr { border-bottom: 1px solid rgba(165,170,177,0.12); }
</style>

<div class="pe-section-body">
    <table class="table w-100" id="table-affiliates">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Afiliado</th>
                            <th>Comissão (%)</th>
                            <th>Vendas</th>
                            <th>Ganhos</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produto->afiliados as $afiliado)
                            <tr
                                style="background-color: {{ isset($id) && $id == $afiliado->id ? '#f563633d' : 'transparent' }};">
                                <td>{{ $afiliado->created_at->format('d/m/Y \à\s H:i:s') }}</td>
                                <td>{{ $afiliado->user->name }}
                                </td>
                                <td>{{ number_format($afiliado->percentage, '2') }}%</td>
                                <td>{{ $afiliado->user->historicoAfiliado()
                                    ->whereHas('pedido', function ($q) use ($produto) {
                                        $q->where('produto_id', $produto->id);
                                    })
                                    ->where('status', 'pago')
                                    ->count()}}
                                </td>
                                <td>{{ "R$ ".number_format($afiliado->user->historicoAfiliado()
                                    ->whereHas('pedido', function ($q) use ($produto) {
                                        $q->where('produto_id', $produto->id);
                                    })
                                    ->where('status', 'pago')
                                    ->sum('amount'), 2, ',', '.')}}
                                </td>

                            </tr>

                            <div class="modal fade" id="revogueCoproducao{{ $afiliado->id }}" data-bs-backdrop="static"
                                data-bs-keyboard="false" tabindex="-1"
                                aria-labelledby="revogueCoproducao{{ $afiliado->id }}Label" aria-hidden="true">
                                <div class="modal-dialog modal-md modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="revogueCoproducao{{ $afiliado->id }}Label">
                                                Recusar
                                                Afiliação
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h6 class="text-danger">Você tem certeza que deseja revogar a afiliação?</h6>
                                            <h6 class="text-danger">Produto: {{ $produto->name }}</h6>
                                            <h6 class="text-danger">Comissão:
                                                {{ number_format($produto?->coproducao?->percentage, '2') }}%
                                            </h6>
                                            <h6 class="text-danger">Afiliado:
                                                {{ $produto?->affiliate?->user?->name  }}
                                            </h6>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancelar</button>
                                            <button type="button" class="btn btn-danger text-white btn-revogar"
                                                data-url="{{ route('coproducao.revogue', ['id' => $produto?->affiliate?->id ?? '0']) }}">
                                                Revogar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
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
        var table = $("#table-affiliates").DataTable({
            responsive: true,
            ordering: false,
            lengthChange: false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                search: ''
            }
        });

        table.on('draw', function () {
            $('#table-affiliates tbody tr').each(function () {
                $(this).find('td').css('border-bottom', 'none');
            });
        });

        // Garante que o evento draw também seja executado na primeira renderização
        table.draw();
    });
</script>