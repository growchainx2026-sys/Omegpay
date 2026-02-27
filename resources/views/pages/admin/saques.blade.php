@extends('layouts.app')

@section('title', 'Saques')

@section('content')
<style>
.dataTables_wrapper .dataTables_filter input {
    position: absolute;
    top: -50px !important;
    right: -10px !important;
}
</style>
    <div class="header mb-3 d-flex align-items-center justify-content-between">
        <h1 class="header-title mb-0">
            Saques
        </h1>
        @php
            $periodo = request()->input('periodo', 'dia');
            $start = request()->input('start', now()->startOfDay()->format('Y-m-d'));
            $end = request()->input('end', now()->endOfDay()->format('Y-m-d'));

            $cancelado = 0;
        @endphp

        <form id="form-filter" method="GET">
            <input type="hidden" name="start" id="start" value="{{ request('start') }}">
            <input type="hidden" name="end" id="end" value="{{ request('end') }}">
            <input type="hidden" name="periodo" id="periodo" value="{{ $periodo }}">

            <input type="text" id="daterange" class="form-select"
                style="border-color:transparent;color:white;border-radius:10px;background:var(--gateway-sidebar-color)!important"
                readonly value="{{ $periodo === 'custom' ? 'Personalizado' : ucfirst($periodo) }}">
        </form>
    </div>

     <div class="row">
        {{-- Aprovadas (hoje) --}}
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card card-dash p-4" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Aprovadas (hoje)</span>
                    <i data-lucide="trending-up" class="text-muted" style="width:16px;height:16px;"></i>
                </div>

                <h4 class="fw-bold mb-0 t900">
                    R$ {{ number_format((clone $saques)->where('status', 'pago')->sum('amount'), 2, ',', '.') }}
                    <span class="{{ $crescimento ?? 0 >= 0 ? 'text-success' : 'text-danger' }} fs-6">
                        <i data-lucide="{{ $crescimento ?? 0 > 0 ? 'arrow-up' : ($crescimento ?? 0 < 0 ? 'arrow-down' : '') }}"
                            style="stroke: {{ $crescimento ?? 0 > 0 ? 'green' : 'red' }} !important;width: 14px;"></i>
                        {{ number_format($crescimento ?? 0, 2) }}%
                    </span>
                </h4>

                <hr class="my-3">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="link" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>API</span>
                    </div>
                    <span class="fw-semibold">R$ {{ number_format((clone $saques)->where('status', 'pago')->where('plataforma','api')->sum('amount') ?? 0, 2, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="app-window" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Gateway</span>
                    </div>
                    <span class="fw-semibold">R$ {{ number_format((clone $saques)->where('status', 'pago')->where('plataforma','web')->sum('amount') ?? 0 , 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Abandonadas --}}
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card card-dash p-4" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Pendentes</span>
                    <i data-lucide="trending-down" class="text-muted" style="width:16px;height:16px;"></i>
                </div>

                <h4 class="fw-bold mb-0 t900">
                    R$ {{ number_format((clone $saques)->where('status', 'pendente')->sum('amount') ?? 0, 2, ',', '.') }}
                    <span class="{{ $abandono ?? 0 >= 0 ? 'text-success' : 'text-danger' }} fs-6">
                        <i data-lucide="{{ $abandono ?? 0 > 0 ? 'arrow-up' : ($abandono ?? 0 < 0 ? 'arrow-down' : '') }}"
                            style="stroke: {{ $abandono ?? 0 > 0 ? 'green' : 'red' }} !important;width: 14px;"></i>
                        {{ number_format($abandono ?? 0, 2) }}%
                    </span>
                </h4>

                <hr class="my-3">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="link" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>API</span>
                    </div>
                    <span class="fw-semibold">R$ {{ number_format((clone $saques)->where('status', 'pendente')->where('plataforma', 'api')->sum('amount') ?? 0, 2, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="app-window" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Gateway</span>
                    </div>
                    <span class="fw-semibold">R$ {{ number_format((clone $saques)->where('status', 'pendente')->where('plataforma', 'web')->sum('amount') ?? 0 , 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Lucros --}}
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card card-dash p-4" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Cancelados</span>
                    <i data-lucide="dollar-sign" class="text-muted" style="width:16px;height:16px;"></i>
                </div>

                <h4 class="fw-bold mb-0 t900">
                    R$ {{ number_format((clone $saques)->where('status', 'cancelado')->sum('amount') ?? 0, 2, ',', '.') }}
                    <span class="{{ $cancelado ?? 0 >= 0 ? 'text-success' : 'text-danger' }} fs-6">
                        <i data-lucide="{{ $cancelado ?? 0 > 0 ? 'arrow-up' : ($cancelado < 0 ? 'arrow-down' : '') }}"
                            style="stroke: {{ $cancelado ?? 0 > 0 ? 'green' : 'red' }} !important;width: 14px;"></i>
                        {{ number_format($cancelado ?? 0, 2) }}%
                    </span>
                </h4>

                <hr class="my-3">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="link" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>API</span>
                    </div>
                    <span class="fw-semibold">R$ {{ number_format((clone $saques)->where('status', 'cancelado')->where('plataforma', 'api')->sum('amount') ?? 0, 2, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="app-window" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Gateway</span>
                    </div>
                    <span class="fw-semibold">R$ {{ number_format((clone $saques)->where('status', 'cancelado')->where('plataforma', 'web')->sum('amount') ?? 0 , 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Estornos (ainda fixo) --}}
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card card-dash p-4" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Estornos</span>
                    <i data-lucide="refresh-cw" class="text-muted" style="width:16px;height:16px;"></i>
                </div>

                <h4 class="fw-bold mb-0 t900">R$ 0,00 <span class="text-success fs-6">0.00%</span></h4>
                <hr class="my-3">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="link" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>API</span>
                    </div>
                    <span class="fw-semibold">R$ {{ number_format((clone $saques)->where('status', 'revisao')->where('plataforma', 'api')->sum('amount') ?? 0, 2, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="app-window" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Gateway</span>
                    </div>
                    <span class="fw-semibold">R$ {{ number_format((clone $saques)->where('status', 'revisao')->where('plataforma', 'web')->sum('amount') ?? 0 , 2, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-header">
            <h5 class="card-title pt-1">Transações</h5>
        </div>
        <div class="card-body">
            <table class="table pt-0 " id="table-admin-saques">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Cliente</th>
                        <th>Transação ID</th>
                        <th>End2End</th>
                        <th>Status</th>
                        <th>Valor Bruto</th>
                        <th>Valor Liquido</th>
                        <th>Taxas</th>
                        <th>Adquirente</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($saques->sortByDesc('created_at') as $saque)
                        <tr class="row-descricao-saque" data-descricao="{{ e($saque->descricao_transacao ?? '—') }}" data-valor="R$ {{ number_format($saque->amount, 2, ',', '.') }}" data-cliente="{{ e($saque->user->name ?? 'Conta excluída') }}" data-data="{{ $saque->created_at->format('d/m/Y H:i') }}" style="cursor: pointer;">
                            <td>{{ $saque->created_at->format('d/m/Y \à\s H:i:s') }}</td>
                            <td>{{ $saque->user->name ?? 'Conta excluída' }}</td>
                            <td>{{ $saque->idTransaction }}</td>
                            <td>{{ $saque->end2end }}</td>
                            <td>
                                @if ($saque->status === 'pendente')
                                    <button class="btn btn-sm btn-outline-warning pendente" disabled="">Pendente</button>
                                @elseif ($saque->status === 'pago')
                                    <button class="btn btn-sm btn-outline-success pago" disabled="">Pago</button>
                                @elseif ($saque->status === 'cancelado')
                                    <button class="btn btn-sm btn-outline-danger cancelado"
                                        disabled="">Cancelado</button>
                                @elseif ($saque->status === 'revisao')
                                    <button class="btn btn-sm btn-outline-secondary padrao" disabled="">Em
                                        revisão</button>
                                @endif
                            </td>
                            <td>R$ {{ number_format($saque->amount, '2', ',', '.') }}</td>
                            <td>R$ {{ number_format($saque->cash_out_liquido, '2', ',', '.') }}</td>
                            <td>R$ {{ number_format($saque->taxa_cash, '2', ',', '.') }}</td>
                            <td>{{ $saque->adquirente_ref }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal descrição da transação --}}
    <div class="modal fade" id="modalDescricaoSaque" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Descrição do saque</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-1 small text-muted"><span id="md-saq-data"></span> · <span id="md-saq-valor"></span> · <span id="md-saq-cliente"></span></p>
                    <p class="mb-0"><strong>Descrição:</strong></p>
                    <div class="p-2 mt-1 rounded bg-light" id="md-saq-descricao">—</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var table = $("#table-admin-saques").DataTable({
                responsive: true,
                ordering: false,
                lengthChange: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                    search: ''
                }
            });

            table.on('draw', function() {
                $('#table-admin-saques tbody tr').each(function() {
                    $(this).find('td').css('border-bottom', 'none');
                });
            });

            $('#table-admin-saques').on('click', 'tbody tr.row-descricao-saque', function(e) {
                if ($(e.target).closest('button, a, [onclick]').length) return;
                var tr = $(this);
                $('#md-saq-data').text(tr.data('data') || '—');
                $('#md-saq-valor').text(tr.data('valor') || '—');
                $('#md-saq-cliente').text(tr.data('cliente') || '—');
                $('#md-saq-descricao').text(tr.data('descricao') || '—');
                new bootstrap.Modal(document.getElementById('modalDescricaoSaque')).show();
            });

            table.draw();
        });
    </script>

    <script>
        $(function() {
            let start = "{{ request('start') ?? '' }}";
            let end = "{{ request('end') ?? '' }}";
            let periodo = "{{ $periodo }}";

            // Função para atualizar o texto no input
            function setLabel(label) {
                $('#daterange').val(label);
            }

            function cb(start, end, label) {
                $('#start').val(start.format('YYYY-MM-DD'));
                $('#end').val(end.format('YYYY-MM-DD'));

                if (label) {
                    switch (label) {
                        case 'Hoje':
                            $('#periodo').val('dia');
                            break;
                        case 'Semana':
                            $('#periodo').val('semana');
                            break;
                        case 'Mês':
                            $('#periodo').val('mes'); // 👈 converte para "mes" sem acento
                            break;
                        case 'Tudo':
                            $('#periodo').val('tudo');
                            break;
                        default:
                            $('#periodo').val('custom');
                    }
                    $('#daterange').val(label);
                } else {
                    $('#periodo').val('custom');
                    $('#daterange').val('Personalizado');
                }

                $('#form-filter').submit();
            }

            $('#daterange').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'DD/MM/YYYY',
                    applyLabel: 'Aplicar',
                    cancelLabel: 'Cancelar',
                    customRangeLabel: 'Personalizado', // 👈 Aqui muda o texto
                    daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                    monthNames: [
                        'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                    ],
                    firstDay: 1
                },
                ranges: {
                    'Hoje': [moment(), moment()],
                    'Semana': [moment().startOf('week'), moment()],
                    'Mês': [moment().startOf('month'), moment()],
                    'Tudo': [moment('2020-01-01'), moment()]
                }
            }, cb);

            // Ao carregar a página, define o label atual
            switch (periodo) {
                case 'dia':
                    setLabel('Hoje');
                    break;
                case 'semana':
                    setLabel('Semana');
                    break;
                case 'mes':
                    setLabel('Mês');
                    break;
                case 'tudo':
                    setLabel('Tudo');
                    break;
                case 'custom':
                    setLabel('Personalizado');
                    break;
            }
        });

        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
            // Atualiza os campos hidden
            $('#start').val(picker.startDate.format('YYYY-MM-DD'));
            $('#end').val(picker.endDate.format('YYYY-MM-DD'));

            // Atualiza label
            let label = picker.chosenLabel;
            switch (label) {
                case 'Hoje':
                    $('#periodo').val('dia');
                    break;
                case 'Semana':
                    $('#periodo').val('semana');
                    break;
                case 'Mês':
                    $('#periodo').val('mes');
                    break;
                case 'Tudo':
                    $('#periodo').val('tudo');
                    break;
                default:
                    $('#periodo').val('custom');
                    label = 'Personalizado';
            }
            $('#daterange').val(label);

            // Envia formulário
            $('#form-filter').submit();
        });
    </script>
@endsection
