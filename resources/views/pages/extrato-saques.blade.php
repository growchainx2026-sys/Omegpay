@extends('layouts.app')

@section('title', 'Saques')

@section('content')
<style>
    .extrato-page { --extrato-card-bg: var(--gateway-background-color); --extrato-card-border: rgba(0,0,0,.06); --extrato-text: var(--gateway-text-color); --extrato-muted: #6c757d; --extrato-table-border: rgba(0,0,0,.08); }
    body.dark-mode .extrato-page { --extrato-card-bg: #0f172a; --extrato-card-border: rgba(255,255,255,.1); --extrato-text: #e2e8f0; --extrato-muted: #94a3b8; --extrato-table-border: rgba(255,255,255,.08); }
    body.dark-mode .extrato-page .extrato-title,
    body.dark-mode .extrato-page .extrato-card-value,
    body.dark-mode .extrato-page .extrato-card-methods .method-row,
    body.dark-mode .extrato-page .extrato-card-methods .method-row span,
    body.dark-mode .extrato-page .extrato-table-wrap .table tbody td { color: #e2e8f0 !important; }
    body.dark-mode .extrato-page .extrato-card-label,
    body.dark-mode .extrato-page .extrato-table-wrap .table thead th,
    body.dark-mode .extrato-page .extrato-table-wrap .table .cell-date { color: #94a3b8 !important; }
    body.dark-mode .extrato-page .dataTables_wrapper .dataTables_filter input { background: #1e293b !important; border-color: rgba(255,255,255,.1) !important; color: #e2e8f0 !important; }
    body.dark-mode .extrato-page .dataTables_wrapper .dataTables_info { color: #94a3b8 !important; }
    body.dark-mode .extrato-page .dataTables_wrapper .dataTables_paginate .paginate_button { color: #e2e8f0 !important; }
    .extrato-page .extrato-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; }
    .extrato-page .extrato-title { font-size: 1.35rem; font-weight: 600; color: var(--extrato-text); margin: 0; }
    .extrato-page .extrato-filters { display: flex; align-items: center; gap: .5rem; }
    .extrato-page .extrato-filters .form-select,
    .extrato-page .extrato-filters .form-control { border: 1px solid var(--extrato-card-border); background: var(--extrato-card-bg); color: var(--extrato-text); border-radius: 8px; padding: .4rem .75rem; font-size: .9rem; min-height: 38px; }
    .extrato-page .extrato-filters .form-control { width: 240px; }
    .extrato-page .extrato-filters .form-select:focus,
    .extrato-page .extrato-filters .form-control:focus { border-color: var(--gateway-primary-color); outline: none; box-shadow: 0 0 0 2px rgba(var(--gateway-primary-color-rgb, 0, 0), .15); }
    .extrato-page .extrato-cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .extrato-page .extrato-card { background: var(--extrato-card-bg); border: 1px solid var(--extrato-card-border); border-radius: 10px; padding: 1.25rem; border-left: 3px solid var(--gateway-primary-color); }
    .extrato-page .extrato-card-label { font-size: .8rem; color: var(--extrato-muted); margin-bottom: .25rem; }
    .extrato-page .extrato-card-value { font-size: 1.25rem; font-weight: 600; color: var(--extrato-text); }
    .extrato-page .extrato-card-methods { margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--extrato-card-border); }
    .extrato-page .extrato-card-methods .method-row { display: flex; justify-content: space-between; align-items: center; font-size: .85rem; color: var(--extrato-text); padding: .25rem 0; }
    .extrato-page .extrato-card-methods .method-row span:last-child { font-weight: 500; }
    .extrato-page .extrato-table-wrap { background: var(--extrato-card-bg); border: 1px solid var(--extrato-card-border); border-radius: 10px; overflow: hidden; }
    .extrato-page .extrato-table-wrap .table { margin: 0; }
    .extrato-page .extrato-table-wrap .table thead th { font-size: .8rem; font-weight: 600; color: var(--extrato-muted); text-transform: uppercase; letter-spacing: .02em; padding: .75rem 1rem; border-bottom: 1px solid var(--extrato-table-border); background: var(--extrato-card-bg); }
    .extrato-page .extrato-table-wrap .table tbody td { padding: .75rem 1rem; border-bottom: 1px solid var(--extrato-table-border); color: var(--extrato-text); font-size: .9rem; }
    .extrato-page .extrato-table-wrap .table tbody tr:last-child td { border-bottom: none; }
    .extrato-page .extrato-table-wrap .table th.sortable-date { cursor: pointer; user-select: none; white-space: nowrap; transition: color .2s ease; }
    .extrato-page .extrato-table-wrap .table th.sortable-date:hover { color: var(--gateway-primary-color); }
    .extrato-page .extrato-table-wrap .table th.sortable-date .sort-indicator { display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; margin-left: .4rem; border-radius: 6px; background: rgba(0,0,0,.07); color: #64748b; vertical-align: middle; transition: background .2s ease, color .2s ease, transform .2s ease; }
    .extrato-page .extrato-table-wrap .table th.sortable-date .sort-indicator svg { width: 14px; height: 14px; stroke: currentColor !important; color: inherit !important; }
    body.dark-mode .extrato-page .extrato-table-wrap .table th.sortable-date .sort-indicator { background: rgba(255,255,255,.1); color: #94a3b8; }
    body.dark-mode .extrato-page .extrato-table-wrap .table th.sortable-date .sort-indicator svg { stroke: currentColor !important; }
    .extrato-page .extrato-table-wrap .table th.sortable-date:hover .sort-indicator { background: rgba(var(--gateway-primary-color-rgb, 0, 0), .15); color: var(--gateway-primary-color); }
    .extrato-page .extrato-table-wrap .table th.sortable-date:active .sort-indicator { transform: scale(0.95); }
</style>

<div class="extrato-page">
    <div class="extrato-header">
        <h1 class="extrato-title">Saídas</h1>
        <form id="form-filter" action="{{ route('extrato.saques') }}" method="GET" class="extrato-filters">
            <input type="text" name="periodo_personalizado" id="custom-period-input" value="{{ request('periodo_personalizado') }}"
                class="form-control" placeholder="dd/mm/aaaa – dd/mm/aaaa" style="display: none;">
            <select class="form-select" name="periodo" id="periodo-select" onchange="handlePeriodChangeSaques(this.value)">
                @php $periodo = request()->input('periodo', 'hoje'); @endphp
                <option value="hoje" {{ $periodo == 'hoje' ? 'selected' : '' }}>Hoje</option>
                <option value="semana" {{ $periodo == 'semana' ? 'selected' : '' }}>Semana</option>
                <option value="mes" {{ $periodo == 'mes' ? 'selected' : '' }}>Mês</option>
                <option value="personalizado" {{ $periodo == 'personalizado' ? 'selected' : '' }}>Personalizado</option>
                <option value="todos" {{ $periodo == 'todos' ? 'selected' : '' }}>Todos</option>
            </select>
        </form>
    </div>
    <div class="row">
@php
    use Carbon\Carbon;

    switch ($periodo) {
        case 'tudo':
            $crescimento = 100;
            $perpendentes = 0;
            $percancelados = 0;
            break;

        case 'mes':
            $mesAtualInicio = Carbon::now()->startOfMonth();
            $mesAtualFim = Carbon::now()->endOfMonth();
            $mesPassadoInicio = Carbon::now()->subMonth()->startOfMonth();
            $mesPassadoFim = Carbon::now()->subMonth()->endOfMonth();

            $vendasAnterior =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'pago')
                    ->whereBetween('created_at', [$mesPassadoInicio, $mesPassadoFim])
                    ->sum('amount') ?? 0;

            $vendasAtual =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'pago')
                    ->whereBetween('created_at', [$mesAtualInicio, $mesAtualFim])
                    ->sum('amount') ?? 0;

            if ($vendasAnterior > 0) {
                $crescimento = (($vendasAtual - $vendasAnterior) / $vendasAnterior) * 100;
            } elseif ($vendasAtual > 0 && $vendasAnterior == 0) {
                $crescimento = 100; // ou null / '—' se quiser indicar indefinido
            } else {
                $crescimento = 0;
            }

            $pendentesAnterior =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'pendente')
                    ->whereBetween('created_at', [$mesPassadoInicio, $mesPassadoFim])
                    ->sum('amount') ?? 0;

            $pendentesAtual =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'pendente')
                    ->whereBetween('created_at', [$mesAtualInicio, $mesAtualFim])
                    ->sum('amount') ?? 0;

            if ($pendentesAnterior > 0) {
                $perpendentes = (($pendentesAtual - $pendentesAnterior) / $pendentesAnterior) * 100;
            } elseif ($pendentesAtual > 0 && $pendentesAnterior == 0) {
                $perpendentes = 100; // ou null / '—' se quiser indicar indefinido
            } else {
                $perpendentes = 0;
            }

            $canceladosAnterior =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'cancelado')
                    ->whereBetween('created_at', [$mesPassadoInicio, $mesPassadoFim])
                    ->sum('amount') ?? 0;

            $canceladosAtual =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'cancelado')
                    ->whereBetween('created_at', [$mesAtualInicio, $mesAtualFim])
                    ->sum('amount') ?? 0;

            if ($canceladosAnterior > 0) {
                $percancelados = (($canceladosAtual - $canceladosAnterior) / $canceladosAnterior) * 100;
            } elseif ($canceladosAtual > 0 && $canceladosAnterior == 0) {
                $percancelados = 100; // ou null / '—' se quiser indicar indefinido
            } else {
                $percancelados = 0;
            }
            break;

        case 'semana':
            $semanaAtualInicio = Carbon::now()->startOfWeek();
            $semanaAtualFim = Carbon::now()->endOfDay(); // até hoje
            $semanaPassadaInicio = Carbon::now()->subWeek()->startOfWeek();
            $semanaPassadaFim = Carbon::now()->subWeek()->endOfWeek();

            $vendasAnterior =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'pago')
                    ->whereBetween('created_at', [$semanaPassadaInicio, $semanaPassadaFim])
                    ->sum('amount') ?? 0;

            $vendasAtual =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'pago')
                    ->whereBetween('created_at', [$semanaAtualInicio, $semanaAtualFim])
                    ->sum('amount') ?? 0;

            if ($vendasAnterior > 0) {
                $crescimento = (($vendasAtual - $vendasAnterior) / $vendasAnterior) * 100;
            } elseif ($vendasAtual > 0 && $vendasAnterior == 0) {
                $crescimento = 100;
            } else {
                $crescimento = 0;
            }

            $pendentesAnterior =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'pendente')
                    ->whereBetween('created_at', [$semanaPassadaInicio, $semanaPassadaFim])
                    ->sum('amount') ?? 0;

            $pendentesAtual =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'pendente')
                    ->whereBetween('created_at', [$semanaAtualInicio, $semanaAtualFim])
                    ->sum('amount') ?? 0;

            if ($pendentesAnterior > 0) {
                $perpendentes = (($pendentesAtual - $pendentesAnterior) / $pendentesAnterior) * 100;
            } elseif ($pendentesAtual > 0 && $pendentesAnterior == 0) {
                $perpendentes = 100; // ou null / '—' se quiser indicar indefinido
            } else {
                $perpendentes = 0;
            }

            $canceladosAnterior =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'cancelado')
                    ->whereBetween('created_at', [$semanaPassadaInicio, $semanaPassadaFim])
                    ->sum('amount') ?? 0;

            $canceladosAtual =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'cancelado')
                    ->whereBetween('created_at', [$semanaAtualInicio, $semanaAtualFim])
                    ->sum('amount') ?? 0;

            if ($canceladosAnterior > 0) {
                $percancelados = (($canceladosAtual - $canceladosAnterior) / $canceladosAnterior) * 100;
            } elseif ($canceladosAtual > 0 && $canceladosAnterior == 0) {
                $percancelados = 100; // ou null / '—' se quiser indicar indefinido
            } else {
                $percancelados = 0;
            }
            break;

        case 'dia':
            $diaAtualInicio = Carbon::now()->startOfDay();
            $diaAtualFim = Carbon::now()->endOfDay();
            $diaPassadoInicio = Carbon::now()->subDay()->startOfDay();
            $diaPassadoFim = Carbon::now()->subDay()->endOfDay();

            $vendasAnterior =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'pago')
                    ->whereBetween('created_at', [$diaPassadoInicio, $diaPassadoFim])
                    ->sum('amount') ?? 0;

            $vendasAtual =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'pago')
                    ->whereBetween('created_at', [$diaAtualInicio, $diaAtualFim])
                    ->sum('amount') ?? 0;

            if ($vendasAnterior > 0) {
                $crescimento = (($vendasAtual - $vendasAnterior) / $vendasAnterior) * 100;
            } elseif ($vendasAtual > 0 && $vendasAnterior == 0) {
                $crescimento = 100;
            } else {
                $crescimento = 0;
            }

            $pendentesAnterior =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'pendente')
                    ->whereBetween('created_at', [$diaPassadoInicio, $diaPassadoFim])
                    ->sum('amount') ?? 0;

            $pendentesAtual =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'pendente')
                    ->whereBetween('created_at', [$diaAtualInicio, $diaAtualFim])
                    ->sum('amount') ?? 0;

            if ($pendentesAnterior > 0) {
                $perpendentes = (($pendentesAtual - $pendentesAnterior) / $pendentesAnterior) * 100;
            } elseif ($pendentesAtual > 0 && $pendentesAnterior == 0) {
                $perpendentes = 100; // ou null / '—' se quiser indicar indefinido
            } else {
                $perpendentes = 0;
            }

            $canceladosAnterior =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'cancelado')
                    ->whereBetween('created_at', [$diaPassadoInicio, $diaPassadoFim])
                    ->sum('amount') ?? 0;

            $canceladosAtual =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'cancelado')
                    ->whereBetween('created_at', [$diaAtualInicio, $diaAtualFim])
                    ->sum('amount') ?? 0;

            if ($canceladosAnterior > 0) {
                $percancelados = (($canceladosAtual - $canceladosAnterior) / $canceladosAnterior) * 100;
            } elseif ($canceladosAtual > 0 && $canceladosAnterior == 0) {
                $percancelados = 100; // ou null / '—' se quiser indicar indefinido
            } else {
                $percancelados = 0;
            }
            break;

        case 'personalizado':
            $crescimento = 0;
            $perpendentes = 0;
            $percancelados = 0;
            break;

        default:
            $crescimento = 0;
            $perpendentes = 0;
            $percancelados = 0;
            break;
    }

   
@endphp
        <div class="col-12">
            <div class="extrato-cards">
                <div class="extrato-card">
                    <div class="extrato-card-label">Pagos ({{ $periodo }})</div>
                    <div class="extrato-card-value">R$ {{ number_format((clone $saques)->where('status', 'pago')->sum('amount'), 2, ',', '.') }} <span class="{{ ($crescimento ?? 0) >= 0 ? 'text-success' : 'text-danger' }}" style="font-size:.75rem;">{{ number_format($crescimento ?? 0, 1) }}%</span></div>
                    <div class="extrato-card-methods">
                        <div class="method-row"><span>API</span><span>R$ {{ number_format((clone $saques)->where('status', 'pago')->where('plataforma', 'api')->sum('amount'), 2, ',', '.') }}</span></div>
                        <div class="method-row"><span>Gateway</span><span>R$ {{ number_format((clone $saques)->where('status', 'pago')->where('plataforma', 'web')->sum('amount'), 2, ',', '.') }}</span></div>
                    </div>
                </div>
                <div class="extrato-card">
                    <div class="extrato-card-label">Pendentes ({{ $periodo }})</div>
                    <div class="extrato-card-value">R$ {{ number_format((clone $saques)->where('status', 'pendente')->sum('amount'), 2, ',', '.') }} <span class="{{ $perpendentes >= 0 ? 'text-success' : 'text-danger' }}" style="font-size:.75rem;">{{ number_format($perpendentes, 1) }}%</span></div>
                    <div class="extrato-card-methods">
                        <div class="method-row"><span>API</span><span>R$ {{ number_format((clone $saques)->where('status', 'pendente')->where('plataforma', 'api')->sum('amount'), 2, ',', '.') }}</span></div>
                        <div class="method-row"><span>Gateway</span><span>R$ {{ number_format((clone $saques)->where('status', 'pendente')->where('plataforma', 'web')->sum('amount'), 2, ',', '.') }}</span></div>
                    </div>
                </div>
                <div class="extrato-card">
                    <div class="extrato-card-label">Recusados ({{ $periodo }})</div>
                    <div class="extrato-card-value">R$ {{ number_format((clone $saques)->where('status', 'cancelado')->sum('amount'), 2, ',', '.') }} <span class="{{ ($percancelados ?? 0) >= 0 ? 'text-success' : 'text-danger' }}" style="font-size:.75rem;">{{ number_format($percancelados ?? 0, 1) }}%</span></div>
                    <div class="extrato-card-methods">
                        <div class="method-row"><span>API</span><span>R$ {{ number_format((clone $saques)->where('status', 'cancelado')->where('plataforma', 'api')->sum('amount'), 2, ',', '.') }}</span></div>
                        <div class="method-row"><span>Gateway</span><span>R$ {{ number_format((clone $saques)->where('status', 'cancelado')->where('plataforma', 'web')->sum('amount'), 2, ',', '.') }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="extrato-table-wrap">
            <table class="table" id="table-admin-saques">
                <thead>
                    <tr>
                        <th class="sortable-date" data-sort-dir="desc" title="Clique para ordenar: mais recentes / mais antigos">Data <span class="sort-indicator"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></span></th>
                        <th>Nome Recebedor</th>
                        <th>CPF Recebedor</th>
                        <th>Chave PIX</th>
                        <th>Transação ID</th>
                        <th>Status</th>
                        <th>Valor Liquido</th>
                        <th>Valor Bruto</th>
                        <th>Taxas</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($saques as $saque)
                        <tr>
                            <td data-order="{{ $saque->created_at->format('Y-m-d H:i:s') }}">{{ $saque->created_at->format('d/m/Y \à\s H:i:s') }}</td>
                            <td>{{ $saque->recebedor_name }}</td>
                            <td>{{ $saque->recebedor_cpf }}</td>
                            <td>{{ $saque->pixKey }}</td>
                            <td>{{ $saque->idTransaction }}</td>
                            <td>
                                @if ($saque->status === 'pendente')
                                    <span class="pendente" disabled="">Pendente</span>
                                @elseif ($saque->status === 'pago')
                                    <span class=" pago" disabled="">Pago</span>
                                @elseif ($saque->status === 'cancelado')
                                    <span class=" cancelado" disabled="">Cancelado</span>
                                @elseif ($saque->status === 'revisao')
                                    <span class="padrao" disabled="">Em
                                        revisão</span>
                                @endif
                            </td>
                            <td>R$ {{ number_format($saque->amount, '2', ',', '.') }}</td>
                            <td>R$ {{ number_format($saque->cash_out_liquido, '2', ',', '.') }}</td>
                            <td>R$ {{ number_format($saque->taxa_cash_out, '2', ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </div>

    <script>
        function handlePeriodChangeSaques(selectedValue) {
            var form = document.getElementById('form-filter');
            var customInput = document.getElementById('custom-period-input');
            if (selectedValue === 'personalizado') {
                customInput.style.display = 'block';
                if ($(customInput).data('daterangepicker') && !customInput.value) {
                    $(customInput).data('daterangepicker').show();
                }
            } else {
                customInput.style.display = 'none';
                form.submit();
            }
        }
        document.addEventListener("DOMContentLoaded", function() {
            var formFilter = document.getElementById('form-filter');
            var customInput = document.getElementById('custom-period-input');
            var selectPeriodo = document.getElementById('periodo-select');
            if (selectPeriodo.value === 'personalizado') {
                customInput.style.display = 'block';
            }
            @php
                $daterange_start = '';
                $daterange_end = '';
                if (request('periodo_personalizado') && request('periodo') === 'personalizado') {
                    $parts = explode(' - ', request('periodo_personalizado'), 2);
                    if (count($parts) === 2) {
                        $daterange_start = trim($parts[0]);
                        $daterange_end = trim($parts[1]);
                    }
                }
            @endphp
            $(customInput).daterangepicker({
                autoUpdateInput: false,
                @if($daterange_start !== '' && $daterange_end !== '')
                    startDate: moment("{{ $daterange_start }}", "DD/MM/YYYY"),
                    endDate: moment("{{ $daterange_end }}", "DD/MM/YYYY"),
                @endif
                locale: {
                    format: 'DD/MM/YYYY',
                    cancelLabel: 'Limpar',
                    applyLabel: 'Aplicar',
                    separator: ' - ',
                    daysOfWeek: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
                    monthNames: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
                }
            });
            $(customInput).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                formFilter.submit();
            });
            $(customInput).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                selectPeriodo.value = 'todos';
                formFilter.submit();
            });

            var table = $("#table-admin-saques").DataTable({
                responsive: true,
                ordering: true,
                order: [[0, 'desc']],
                lengthChange: false,
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json', search: '' },
                columnDefs: [
                    { orderable: false, targets: 0 },
                    { orderable: false, targets: '_all' }
                ]
            });

            $(document).on('click', '#table-admin-saques thead th.sortable-date', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var $tbl = $('#table-admin-saques');
                if (!$.fn.DataTable.isDataTable($tbl)) return;
                var api = $tbl.DataTable();
                var $th = $tbl.closest('.extrato-page').find('thead th.sortable-date');
                var dir = $th.attr('data-sort-dir') === 'desc' ? 'asc' : 'desc';
                api.order([[0, dir]]).draw();
                $th.attr('data-sort-dir', dir);
                var path = dir === 'desc' ? 'm6 9 6 6 6-6' : 'm18 15-6-6-6 6';
                $th.find('.sort-indicator svg path').attr('d', path);
            });
        });
    </script>
</div>{{-- .extrato-page --}}
@endsection
