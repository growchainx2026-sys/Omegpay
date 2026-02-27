@props(['produto'])

@php
    foreach (auth()->user()->unreadNotifications as $notification) {
        $notification->markAsRead();
    }
@endphp

<style>
.pe-section-body { border: 1px solid rgba(165,170,177,0.2); border-radius: 10px; overflow: hidden; }
body.dark-mode .pe-section-body { border-color: rgba(30,41,59,0.8); }
#table-coprodutor { margin: 0; }
#table-coprodutor thead th { font-size: 0.8125rem; font-weight: 600; padding: 0.75rem 1rem; border-bottom: 1px solid rgba(165,170,177,0.2); background: transparent; }
#table-coprodutor tbody td { padding: 0.75rem 1rem; font-size: 0.875rem; border-bottom: none; }
#table-coprodutor tbody tr { border-bottom: 1px solid rgba(165,170,177,0.12); }
.pe-co-empty { text-align: center; padding: 1.5rem; color: #64748b; }
</style>

@if($produto?->coproducao)
    <div class="pe-section-body">
        <table class="table w-100" id="table-coprodutor">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Coprodutor</th>
                                <th>Valor do produto</th>
                                <th>Comissão (%)</th>
                                <th>Período</th>
                                <th>Status</th>
                                <th></th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                style="background-color: {{ isset($id) && $id == $produto->coproducao->id ? '#f563633d' : 'transparent' }};">
                                <td>{{ $produto->coproducao->created_at->format('d/m/Y \à\s H:i:s') }}</td>
                                <td>{{ $produto->coproducao->user->name }}
                                </td>
                                <td>R$ {{ number_format($produto->price, 2, ',', '.') }}</td>
                                <td>{{ number_format($produto->coproducao->percentage, '2') }}%</td>
                                <td>
                                    @php
                                        $periodos = [
                                            '30' => '1 mês',
                                            '60' => '2 meses',
                                            '90' => '3 meses',
                                            '120' => '4 meses',
                                            '150' => '5 meses',
                                            '180' => '6 meses',
                                            '210' => '7 meses',
                                            '240' => '8 meses',
                                            '270' => '9 meses',
                                            '300' => '10 meses',
                                            '330' => '11 meses',
                                            '365' => '1 ano',
                                            'sempre' => 'Sempre'
                                        ];
                                        $periodo = $periodos[$produto->coproducao->periodo];
                                    @endphp
                                    <span class="pago">{{ $periodo }}</span>
                                </td>
                                <td>
                                    @if($produto->coproducao->accept == 'accept')
                                        <span class="badge text-bg-success text-white">Aceito</span>
                                    @else
                                        <span class="badge text-bg-warning text-white">Pendente</span>
                                    @endif
                                </td>
                                <td>

                                    <div class="dropdown">
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a data-bs-toggle="modal"
                                                data-bs-target="#revogueCoproducao{{ $produto->coproducao->id }}">
                                                <button class="dropdown-item btn-visualizar">
                                                    <i data-lucide="square-x" class="me-2"></i>Revogar
                                                </button>
                                            </a>
                                        </li>
                                    </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
        </table>
    </div>
    <div class="modal fade" id="revogueCoproducao{{ $produto->coproducao->id }}" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1"
                        aria-labelledby="revogueCoproducao{{ $produto->coproducao->id }}Label" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="revogueCoproducao{{ $produto->coproducao->id }}Label">
                                        Recusar
                                        coprodução
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h6 class="text-danger">Você tem certeza que deseja revogar a coprodução?</h6>
                                    <h6 class="text-danger">Produto: {{ $produto->name }}</h6>
                                    <h6 class="text-danger">Valor do produto: R$
                                        {{ number_format($produto->price, 2, ',', '.') }}
                                    </h6>
                                    <h6 class="text-danger">Comissão:
                                        {{ number_format($produto->coproducao->percentage, '2') }}%
                                    </h6>
                                    <h6 class="text-danger">Produtor:
                                        {{ $produto->name_exibition ?? explode(' ', $produto->user->name)[0] }}
                                    </h6>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-danger text-white btn-revogar"
                                        data-url="{{ route('coproducao.revogue', ['id' => $produto->coproducao->id]) }}">
                                        Revogar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
@else
    <div class="pe-section-body p-4">
        <p class="pe-co-empty mb-0">Este produto não possui coprodutor.</p>
        <div class="text-center mt-2">
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCoprodutorModal"><i class="fa-solid fa-plus me-1"></i> Adicionar</button>
        </div>
    </div>
@endif


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
        var table = $("#table-coprodutor").DataTable({
            responsive: true,
            ordering: false,
            lengthChange: false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                search: ''
            }
        });

        table.on('draw', function () {
            $('#table-coprodutor tbody tr').each(function () {
                $(this).find('td').css('border-bottom', 'none');
            });
        });

        // Garante que o evento draw também seja executado na primeira renderização
        table.draw();
    });
</script>