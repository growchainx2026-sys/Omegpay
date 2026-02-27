@extends('layouts.app')

@section('title', 'Depósitos')

@section('content')
<style>
    .extrato-page { --extrato-card-bg: var(--gateway-background-color); --extrato-card-border: rgba(0,0,0,.06); --extrato-text: var(--gateway-text-color); --extrato-muted: #6c757d; --extrato-table-border: rgba(0,0,0,.08); }
    body.dark-mode .extrato-page { --extrato-card-bg: #0f172a; --extrato-card-border: rgba(255,255,255,.1); --extrato-text: #e2e8f0; --extrato-muted: #94a3b8; --extrato-table-border: rgba(255,255,255,.08); }
    body.dark-mode .extrato-page .extrato-title,
    body.dark-mode .extrato-page .extrato-card-value,
    body.dark-mode .extrato-page .extrato-card-methods .method-row,
    body.dark-mode .extrato-page .extrato-card-methods .method-row span,
    body.dark-mode .extrato-page .extrato-table-wrap .table tbody td,
    body.dark-mode .extrato-page .extrato-table-wrap .table tbody td .cell-method,
    body.dark-mode .extrato-page .extrato-dt-footer .dataTables_paginate .paginate_button { color: #e2e8f0 !important; }
    body.dark-mode .extrato-page .extrato-card-label,
    body.dark-mode .extrato-page .extrato-table-wrap .table thead th,
    body.dark-mode .extrato-page .extrato-table-wrap .table .cell-date,
    body.dark-mode .extrato-page .extrato-dt-header .dataTables_filter label,
    body.dark-mode .extrato-page .extrato-dt-footer .dataTables_info { color: #94a3b8 !important; }
    body.dark-mode .extrato-page .extrato-dt-header input { background: #1e293b !important; border-color: rgba(255,255,255,.1) !important; color: #e2e8f0 !important; }
    .extrato-page .extrato-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; }
    .extrato-page .extrato-title { font-size: 1.35rem; font-weight: 600; color: var(--extrato-text); margin: 0; }
    .extrato-page .extrato-filters { display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; }
    .extrato-page .extrato-filters .form-select,
    .extrato-page .extrato-filters .form-control { border: 1px solid var(--extrato-card-border); background: var(--extrato-card-bg); color: var(--extrato-text); border-radius: 10px; padding: .5rem 2rem .5rem .85rem; font-size: .875rem; min-height: 40px; transition: border-color .2s ease, box-shadow .2s ease, transform .15s ease; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%236c757d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right .65rem center; }
    .extrato-page .extrato-filters .form-control { width: 240px; background-image: none; padding-right: .85rem; }
    .extrato-page .extrato-filters .form-select:hover,
    .extrato-page .extrato-filters .form-control:hover { border-color: rgba(0,0,0,.12); }
    body.dark-mode .extrato-page .extrato-filters .form-select:hover,
    body.dark-mode .extrato-page .extrato-filters .form-control:hover { border-color: rgba(255,255,255,.15); }
    .extrato-page .extrato-filters .form-select:focus,
    .extrato-page .extrato-filters .form-control:focus { border-color: var(--gateway-primary-color); outline: none; box-shadow: 0 0 0 3px rgba(var(--gateway-primary-color-rgb, 0, 0), .12); }
    .extrato-page .extrato-filters .form-select:active { transform: scale(0.99); }
    .extrato-page .extrato-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: .75rem; margin-bottom: 1.5rem; }
    @media (max-width: 992px) { .extrato-page .extrato-cards { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .extrato-page .extrato-cards { grid-template-columns: 1fr; } }
    .extrato-page .extrato-card { background: var(--extrato-card-bg); border: 1px solid var(--extrato-card-border); border-radius: 10px; padding: .9rem 1rem; border-left: 3px solid var(--gateway-primary-color); min-width: 0; transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease; }
    .extrato-page .extrato-card:hover { transform: translateY(-1px); border-color: rgba(0,0,0,.1); }
    body.dark-mode .extrato-page .extrato-card:hover { border-color: rgba(255,255,255,.12); }
    .extrato-page .extrato-card:active { transform: translateY(0); transition-duration: .1s; }
    .extrato-page .extrato-card-label { font-size: .7rem; color: var(--extrato-muted); text-transform: uppercase; letter-spacing: .04em; margin-bottom: .2rem; }
    .extrato-page .extrato-card-value { font-size: 1.1rem; font-weight: 600; color: var(--extrato-text); line-height: 1.3; }
    .extrato-page .extrato-card-methods { margin-top: .6rem; padding-top: .6rem; border-top: 1px solid var(--extrato-card-border); }
    .extrato-page .extrato-card-methods .method-row { display: flex; justify-content: space-between; align-items: center; font-size: .75rem; color: var(--extrato-text); padding: .15rem 0; }
    .extrato-page .extrato-card-methods .method-row span:last-child { font-weight: 500; }
    .extrato-page .extrato-dt-header { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: .75rem 1rem; margin-bottom: 0; background: var(--extrato-card-bg); border: 1px solid var(--extrato-card-border); border-bottom: none; border-radius: 12px 12px 0 0; }
    .extrato-page .extrato-dt-header .dataTables_filter { margin: 0; }
    .extrato-page .extrato-dt-header .dataTables_filter label { display: flex; align-items: center; gap: .5rem; margin: 0; font-size: .875rem; color: var(--extrato-muted); }
    .extrato-page .extrato-dt-header .dataTables_filter input { border: 1px solid var(--extrato-card-border); background: var(--extrato-card-bg); color: var(--extrato-text); border-radius: 8px; padding: .45rem .75rem; font-size: .875rem; margin-left: 0; width: 200px; transition: border-color .2s ease, box-shadow .2s ease; }
    .extrato-page .extrato-dt-header .dataTables_filter input:focus { border-color: var(--gateway-primary-color); outline: none; box-shadow: 0 0 0 2px rgba(var(--gateway-primary-color-rgb, 0, 0), .12); }
    .extrato-page .extrato-dt-header .dataTables_filter input::placeholder { color: var(--extrato-muted); }
    .extrato-page .extrato-table-wrap { background: var(--extrato-card-bg); border: 1px solid var(--extrato-card-border); border-top: none; border-radius: 0 0 12px 12px; overflow: hidden; }
    .extrato-page .extrato-dt-container .dataTables_wrapper { padding: 0; border: none; }
.extrato-page .extrato-dt-container .dataTables_wrapper > .extrato-dt-header { border-radius: 12px 12px 0 0; }
.extrato-page .extrato-dt-container .dataTables_wrapper > .extrato-table-wrap { border-radius: 0; }
.extrato-page .extrato-dt-container .dataTables_wrapper > .extrato-dt-footer { border-radius: 0 0 12px 12px; }
    .extrato-page .extrato-table-wrap .table { margin: 0; border-collapse: collapse; }
    .extrato-page .extrato-table-wrap .table thead th { font-size: .7rem; font-weight: 600; color: var(--extrato-muted); text-transform: uppercase; letter-spacing: .06em; padding: .85rem 1rem; border-bottom: 1px solid var(--extrato-table-border); background: var(--extrato-card-bg); white-space: nowrap; }
    .extrato-page .extrato-table-wrap .table tbody td { padding: .85rem 1rem; border-bottom: 1px solid var(--extrato-table-border); color: var(--extrato-text); font-size: .875rem; vertical-align: middle; }
    .extrato-page .extrato-table-wrap .table tbody tr { transition: background .2s ease; }
    .extrato-page .extrato-table-wrap .table tbody tr:hover { background: rgba(0,0,0,.04); }
    body.dark-mode .extrato-page .extrato-table-wrap .table tbody tr:hover { background: rgba(255,255,255,.06); }
    .extrato-page .extrato-table-wrap .table tbody tr:last-child td { border-bottom: none; }
    .extrato-page .extrato-table-wrap .table th.sortable-date { cursor: pointer; user-select: none; white-space: nowrap; transition: color .2s ease, background .2s ease; }
    .extrato-page .extrato-table-wrap .table th.sortable-date:hover { color: var(--gateway-primary-color); }
    .extrato-page .extrato-table-wrap .table th.sortable-date .sort-indicator { display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; margin-left: .4rem; border-radius: 6px; background: rgba(0,0,0,.07); color: #64748b; vertical-align: middle; transition: background .2s ease, color .2s ease, transform .2s ease; }
    .extrato-page .extrato-table-wrap .table th.sortable-date .sort-indicator svg,
    .extrato-page .extrato-table-wrap .table th.sortable-date .sort-indicator i { width: 14px; height: 14px; stroke: currentColor !important; color: inherit !important; }
    body.dark-mode .extrato-page .extrato-table-wrap .table th.sortable-date .sort-indicator { background: rgba(255,255,255,.1); color: #94a3b8; }
    body.dark-mode .extrato-page .extrato-table-wrap .table th.sortable-date .sort-indicator svg,
    body.dark-mode .extrato-page .extrato-table-wrap .table th.sortable-date .sort-indicator i { stroke: currentColor !important; }
    .extrato-page .extrato-table-wrap .table th.sortable-date:hover .sort-indicator { background: rgba(var(--gateway-primary-color-rgb, 0, 0), .15); color: var(--gateway-primary-color); }
    .extrato-page .extrato-table-wrap .table th.sortable-date:active .sort-indicator { transform: scale(0.95); }
    .extrato-page .extrato-table-wrap .table .cell-date { font-variant-numeric: tabular-nums; color: var(--extrato-muted); font-size: .8rem; }
    .extrato-page .extrato-table-wrap .table .cell-method { display: inline-flex; align-items: center; gap: .4rem; font-variant-numeric: tabular-nums; }
    .extrato-page .extrato-table-wrap .table .cell-method .method-icon { flex-shrink: 0; opacity: .9; transition: opacity .2s ease; }
    .extrato-page .extrato-table-wrap .table tbody tr:hover .cell-method .method-icon { opacity: 1; }
    .extrato-page .extrato-table-wrap .table .cell-status { display: inline-flex; align-items: center; gap: .35rem; }
    .extrato-page .extrato-table-wrap .table .badge-status { display: inline-flex; align-items: center; padding: .25rem .55rem; border-radius: 6px; font-size: .7rem; font-weight: 600; text-transform: uppercase; letter-spacing: .03em; transition: transform .15s ease, opacity .15s ease; }
    .extrato-page .extrato-table-wrap .table .badge-status:hover { transform: scale(1.02); }
    .extrato-page .extrato-table-wrap .table .badge-status.pago { background: rgba(34,197,94,.12); color: #16a34a; }
    body.dark-mode .extrato-page .extrato-table-wrap .table .badge-status.pago { background: rgba(34,197,94,.2); color: #4ade80; }
    .extrato-page .extrato-table-wrap .table .badge-status.pendente { background: rgba(234,179,8,.12); color: #a16207; }
    body.dark-mode .extrato-page .extrato-table-wrap .table .badge-status.pendente { background: rgba(234,179,8,.2); color: #facc15; }
    .extrato-page .extrato-table-wrap .table .badge-status.revisao { background: rgba(59,130,246,.12); color: #2563eb; }
    body.dark-mode .extrato-page .extrato-table-wrap .table .badge-status.revisao { background: rgba(59,130,246,.2); color: #60a5fa; }
    .extrato-page .extrato-table-wrap .table .badge-status.cancelado { background: rgba(239,68,68,.12); color: #b91c1c; }
    body.dark-mode .extrato-page .extrato-table-wrap .table .badge-status.cancelado { background: rgba(239,68,68,.2); color: #f87171; }
    .extrato-page .extrato-table-wrap .table .btn-info-revisao { display: inline-flex; align-items: center; justify-content: center; width: 20px; height: 20px; border-radius: 50%; color: var(--gateway-primary-color); background: transparent; border: none; cursor: pointer; transition: transform .2s ease, opacity .2s ease; padding: 0; }
    .extrato-page .extrato-table-wrap .table .btn-info-revisao:hover { opacity: .85; transform: scale(1.08); }
    .extrato-page .extrato-table-wrap .table .btn-info-revisao:active { transform: scale(0.96); }
    .extrato-page .extrato-table-wrap .table .cell-money { font-variant-numeric: tabular-nums; text-align: right; font-weight: 500; }
    .extrato-page .extrato-dt-footer { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .75rem; padding: .65rem 1rem; background: var(--extrato-card-bg); border: 1px solid var(--extrato-card-border); border-top: none; border-radius: 0 0 12px 12px; font-size: .8rem; color: var(--extrato-muted); }
    .extrato-page .extrato-dt-footer .dataTables_info { padding: 0; margin: 0; }
    .extrato-page .extrato-dt-footer .dataTables_paginate { margin: 0; padding: 0; }
    .extrato-page .extrato-dt-footer .dataTables_paginate .paginate_button { margin: 0 .15rem; padding: .4rem .65rem; min-width: 32px; border-radius: 8px; border: none !important; background: transparent !important; color: var(--extrato-text) !important; font-weight: 500 !important; transition: background .2s ease, color .2s ease, transform .15s ease !important; }
    .extrato-page .extrato-dt-footer .dataTables_paginate .paginate_button:hover { background: rgba(0,0,0,.06) !important; color: var(--gateway-primary-color) !important; }
    body.dark-mode .extrato-page .extrato-dt-footer .dataTables_paginate .paginate_button:hover { background: rgba(255,255,255,.08) !important; }
    .extrato-page .extrato-dt-footer .dataTables_paginate .paginate_button.current { background: var(--gateway-primary-color) !important; color: #fff !important; }
    .extrato-page .extrato-dt-footer .dataTables_paginate .paginate_button.current:hover { background: var(--gateway-primary-color) !important; color: #fff !important; opacity: .95; }
    .extrato-page .extrato-dt-footer .dataTables_paginate .paginate_button.disabled { opacity: .4; cursor: default !important; }
    .extrato-page .extrato-dt-footer .dataTables_paginate .paginate_button:active:not(.disabled) { transform: scale(0.97); }
    .extrato-page .extrato-dt-footer .dataTables_paginate .paginate_button.previous,
    .extrato-page .extrato-dt-footer .dataTables_paginate .paginate_button.next { font-size: 0; }
    .extrato-page .extrato-dt-footer .dataTables_paginate .paginate_button.previous:after { content: '‹'; font-size: 1.1rem; font-weight: 600; line-height: 1; }
    .extrato-page .extrato-dt-footer .dataTables_paginate .paginate_button.next:after { content: '›'; font-size: 1.1rem; font-weight: 600; line-height: 1; }
    .extrato-page .extrato-dt-footer .dataTables_paginate .paginate_button.previous.disabled:after,
    .extrato-page .extrato-dt-footer .dataTables_paginate .paginate_button.next.disabled:after { opacity: .5; }
</style>

<div class="extrato-page">
    <div class="extrato-header">
        <h1 class="extrato-title">Entradas</h1>
        <form id="form-filter" action="{{ route('extrato.depositos') }}" method="GET" class="extrato-filters">
            <input type="text" name="periodo_personalizado" id="custom-period-input" value="{{ request('periodo_personalizado') }}"
                class="form-control" placeholder="dd/mm/aaaa – dd/mm/aaaa" style="display: none;">
            <select class="form-select" name="periodo" id="periodo-select" onchange="handlePeriodChangeDepositos(this.value)">
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
                    $abandono = 0;
                    $lucro = 100;
                    break;

                case 'mes':
                    $mesAtualInicio = Carbon::now()->startOfMonth();
                    $mesAtualFim = Carbon::now()->endOfMonth();
                    $mesPassadoInicio = Carbon::now()->subMonth()->startOfMonth();
                    $mesPassadoFim = Carbon::now()->subMonth()->endOfMonth();

                    $vendasAnterior =
                        $depositos
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$mesPassadoInicio, $mesPassadoFim])
                            ->sum('amount') ?? 0;

                    $vendasAtual =
                        $depositos
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$mesAtualInicio, $mesAtualFim])
                            ->sum('amount') ?? 0;

                    if ($vendasAnterior > 0) {
                        $crescimento = (($vendasAtual - $vendasAnterior) / $vendasAnterior) * 100;
                    } elseif ($vendasAtual > 0 && $vendasAnterior == 0) {
                        $crescimento = 100; // ou null / '—' se quiser indicar indefinido
                    } else {
                        $crescimento = 0;
                    }

                    $abandonoAnterior =
                        $depositos
                            ->where('status', 'pendente')
                            ->whereBetween('created_at', [$mesPassadoInicio, $mesPassadoFim])
                            ->sum('amount') ?? 0;

                    $abandonoAtual =
                        $depositos
                            ->where('status', 'pendente')
                            ->whereBetween('created_at', [$mesAtualInicio, $mesAtualFim])
                            ->sum('amount') ?? 0;

                    if ($abandonoAnterior > 0) {
                        $abandono = (($abandonoAtual - $abandonoAnterior) / $abandonoAnterior) * 100;
                    } elseif ($abandonoAtual > 0 && $abandonoAnterior == 0) {
                        $abandono = 100; // ou null / '—' se quiser indicar indefinido
                    } else {
                        $abandono = 0;
                    }

                    $lucroAnterior =
                        $depositos
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$mesPassadoInicio, $mesPassadoFim])
                            ->sum('cash_in_liquido') ?? 0;

                    $lucroAtual =
                        $depositos
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$mesAtualInicio, $mesAtualFim])
                            ->sum('cash_in_liquido') ?? 0;

                    if ($lucroAnterior > 0) {
                        $lucro = (($lucroAtual - $lucroAnterior) / $lucroAnterior) * 100;
                    } elseif ($lucroAtual > 0 && $lucroAnterior == 0) {
                        $lucro = 100; // ou null / '—' se quiser indicar indefinido
                    } else {
                        $lucro = 0;
                    }
                    break;

                case 'semana':
                    $semanaAtualInicio = Carbon::now()->startOfWeek();
                    $semanaAtualFim = Carbon::now()->endOfDay(); // até hoje
                    $semanaPassadaInicio = Carbon::now()->subWeek()->startOfWeek();
                    $semanaPassadaFim = Carbon::now()->subWeek()->endOfWeek();

                    $vendasAnterior =
                        $depositos
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$semanaPassadaInicio, $semanaPassadaFim])
                            ->sum('amount') ?? 0;

                    $vendasAtual =
                        $depositos
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$semanaAtualInicio, $semanaAtualFim])
                            ->sum('amount') ?? 0;

                    if ($vendasAnterior > 0) {
                        $crescimento = (($vendasAtual - $vendasAnterior) / $vendasAnterior) * 100;
                    } elseif ($vendasAtual > 0 && $vendasAnterior == 0) {
                        $crescimento = 100;
                    } else {
                        $crescimento = 0;
                    }

                    $abandonoAnterior =
                        $depositos
                            ->where('status', 'pendente')
                            ->whereBetween('created_at', [$semanaPassadaInicio, $semanaPassadaFim])
                            ->sum('amount') ?? 0;

                    $abandonoAtual =
                        $depositos
                            ->where('status', 'pendente')
                            ->whereBetween('created_at', [$semanaAtualInicio, $semanaAtualFim])
                            ->sum('amount') ?? 0;

                    if ($abandonoAnterior > 0) {
                        $abandono = (($abandonoAtual - $abandonoAnterior) / $abandonoAnterior) * 100;
                    } elseif ($abandonoAtual > 0 && $abandonoAnterior == 0) {
                        $abandono = 100;
                    } else {
                        $abandono = 0;
                    }

                    $lucroAnterior =
                        $depositos
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$semanaPassadaInicio, $semanaPassadaFim])
                            ->sum('cash_in_liquido') ?? 0;

                    $lucroAtual =
                        $depositos
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$semanaAtualInicio, $semanaAtualFim])
                            ->sum('cash_in_liquido') ?? 0;

                    if ($lucroAnterior > 0) {
                        $lucro = (($lucroAtual - $lucroAnterior) / $lucroAnterior) * 100;
                    } elseif ($lucroAtual > 0 && $lucroAnterior == 0) {
                        $lucro = 100; // ou null / '—' se quiser indicar indefinido
                    } else {
                        $lucro = 0;
                    }
                    break;

                case 'dia':
                    $diaAtualInicio = Carbon::now()->startOfDay();
                    $diaAtualFim = Carbon::now()->endOfDay();
                    $diaPassadoInicio = Carbon::now()->subDay()->startOfDay();
                    $diaPassadoFim = Carbon::now()->subDay()->endOfDay();

                    $vendasAnterior =
                        $depositos
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$diaPassadoInicio, $diaPassadoFim])
                            ->sum('amount') ?? 0;

                    $vendasAtual =
                        $depositos
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$diaAtualInicio, $diaAtualFim])
                            ->sum('amount') ?? 0;

                    if ($vendasAnterior > 0) {
                        $crescimento = (($vendasAtual - $vendasAnterior) / $vendasAnterior) * 100;
                    } elseif ($vendasAtual > 0 && $vendasAnterior == 0) {
                        $crescimento = 100;
                    } else {
                        $crescimento = 0;
                    }

                    $abandonoAnterior =
                        $depositos
                            ->where('status', 'pendente')
                            ->whereBetween('created_at', [$diaPassadoInicio, $diaPassadoFim])
                            ->sum('amount') ?? 0;

                    $abandonoAtual =
                        $depositos
                            ->where('status', 'pendente')
                            ->whereBetween('created_at', [$diaAtualInicio, $diaAtualFim])
                            ->sum('amount') ?? 0;

                    if ($abandonoAnterior > 0) {
                        $abandono = (($abandonoAtual - $abandonoAnterior) / $abandonoAnterior) * 100;
                    } elseif ($abandonoAtual > 0 && $abandonoAnterior == 0) {
                        $abandono = 100;
                    } else {
                        $abandono = 0;
                    }

                    $lucroAnterior =
                        $depositos
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$diaPassadoInicio, $diaPassadoFim])
                            ->sum('cash_in_liquido') ?? 0;

                    $lucroAtual =
                        $depositos
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$diaAtualInicio, $diaAtualFim])
                            ->sum('cash_in_liquido') ?? 0;

                    if ($lucroAnterior > 0) {
                        $lucro = (($lucroAtual - $lucroAnterior) / $lucroAnterior) * 100;
                    } elseif ($lucroAtual > 0 && $lucroAnterior == 0) {
                        $lucro = 100; // ou null / '—' se quiser indicar indefinido
                    } else {
                        $lucro = 0;
                    }
                    break;

                case 'personalizado':
                    $crescimento = 0;
                    $abandono = 0;
                    $lucro = 0;
                    break;

                default:
                    $crescimento = 0;
                    $abandono = 0;
                    $lucro = 0;
                    break;
            }
        @endphp
        <div class="col-12">
            <div class="extrato-cards">
                <div class="extrato-card">
                    <div class="extrato-card-label">Aprovadas ({{ $periodo }})</div>
                    <div class="extrato-card-value">R$ {{ number_format((clone $depositos)->whereIn('status', ['pago', 'revisao'])->sum('amount'), 2, ',', '.') }} <span class="{{ $crescimento >= 0 ? 'text-success' : 'text-danger' }}" style="font-size:.75rem;">{{ number_format($crescimento, 1) }}%</span></div>
                    <div class="extrato-card-methods">
                        <div class="method-row"><span>Cartão</span><span>R$ {{ number_format((clone $depositos)->whereIn('status', ['pago', 'revisao'])->where('method', 'card')->sum('amount'), 2, ',', '.') }}</span></div>
                        <div class="method-row"><span>Pix</span><span>R$ {{ number_format((clone $depositos)->where('status', 'pago')->where('method', 'pix')->sum('amount'), 2, ',', '.') }}</span></div>
                        <div class="method-row"><span>Boleto</span><span>R$ {{ number_format((clone $depositos)->whereIn('status', ['pago', 'revisao'])->where('method', 'billet')->sum('amount'), 2, ',', '.') }}</span></div>
                    </div>
                </div>
                <div class="extrato-card">
                    <div class="extrato-card-label">Abandonadas</div>
                    <div class="extrato-card-value">R$ {{ number_format((clone $depositos)->where('status', 'pendente')->sum('amount'), 2, ',', '.') }} <span class="{{ $abandono >= 0 ? 'text-success' : 'text-danger' }}" style="font-size:.75rem;">{{ number_format($abandono, 1) }}%</span></div>
                    <div class="extrato-card-methods">
                        <div class="method-row"><span>Cartão</span><span>R$ {{ number_format((clone $depositos)->where('status', 'pendente')->where('method', 'card')->sum('amount'), 2, ',', '.') }}</span></div>
                        <div class="method-row"><span>Pix</span><span>R$ {{ number_format((clone $depositos)->where('status', 'pendente')->where('method', 'pix')->sum('amount'), 2, ',', '.') }}</span></div>
                        <div class="method-row"><span>Boleto</span><span>R$ {{ number_format((clone $depositos)->where('status', 'pendente')->where('method', 'billet')->sum('amount'), 2, ',', '.') }}</span></div>
                    </div>
                </div>
                <div class="extrato-card">
                    <div class="extrato-card-label">Lucros</div>
                    <div class="extrato-card-value">R$ {{ number_format((clone $depositos)->whereIn('status', ['pago', 'revisao'])->sum('cash_in_liquido'), 2, ',', '.') }} <span class="{{ $lucro >= 0 ? 'text-success' : 'text-danger' }}" style="font-size:.75rem;">{{ number_format($lucro, 1) }}%</span></div>
                    <div class="extrato-card-methods">
                        <div class="method-row"><span>Cartão</span><span>R$ {{ number_format((clone $depositos)->whereIn('status', ['pago', 'revisao'])->where('method', 'card')->sum('cash_in_liquido'), 2, ',', '.') }}</span></div>
                        <div class="method-row"><span>Pix</span><span>R$ {{ number_format((clone $depositos)->where('status', 'pago')->where('method', 'pix')->sum('cash_in_liquido'), 2, ',', '.') }}</span></div>
                        <div class="method-row"><span>Boleto</span><span>R$ {{ number_format((clone $depositos)->whereIn('status', ['pago', 'revisao'])->where('method', 'billet')->sum('cash_in_liquido'), 2, ',', '.') }}</span></div>
                    </div>
                </div>
                <div class="extrato-card">
                    <div class="extrato-card-label">Estornos</div>
                    <div class="extrato-card-value">R$ 0,00</div>
                    <div class="extrato-card-methods">
                        <div class="method-row"><span>Cartão</span><span>R$ 0,00</span></div>
                        <div class="method-row"><span>Pix</span><span>R$ 0,00</span></div>
                        <div class="method-row"><span>Boleto</span><span>R$ 0,00</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="extrato-dt-container">
            <table class="table" id="table-admin-depositos">
                <thead>
                    <tr>
                        <th class="sortable-date" data-sort-dir="desc" title="Clique para ordenar: mais recentes / mais antigos">Data <span class="sort-indicator"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></span></th>
                        <th>Cliente</th>
                        <th>CPF</th>
                        <th>ID / Método</th>
                        <th>Status</th>
                        <th class="text-end">Bruto</th>
                        <th class="text-end">Taxas</th>
                        <th class="text-end">Reserva</th>
                        <th class="text-end">Líquido</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($depositos as $deposito)
                        <tr>
                            <td data-order="{{ $deposito->created_at->format('Y-m-d H:i:s') }}" class="cell-date">{{ $deposito->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $deposito->client_name }}</td>
                            <td style="font-variant-numeric: tabular-nums;">{{ $deposito->client_cpf }}</td>
                            <td>
                                <span class="cell-method">
                                    @if ($deposito->method == 'pix')
                                        <i data-lucide="qr-code" class="method-icon" style="width:14px;height:14px;stroke: var(--gateway-primary-color);"></i>
                                    @elseif($deposito->method == 'billet')
                                        <i data-lucide="barcode" class="method-icon" style="width:14px;height:14px;stroke: var(--gateway-primary-color);"></i>
                                    @else
                                        <i data-lucide="credit-card" class="method-icon" style="width:14px;height:14px;stroke: var(--gateway-primary-color);"></i>
                                    @endif
                                    <span>{{ $deposito->idTransaction }}</span>
                                </span>
                            </td>
                            <td>
                                <span class="cell-status">
                                    @if ($deposito->status === 'pendente')
                                        <span class="badge-status pendente">Pendente</span>
                                    @elseif ($deposito->status === 'pago')
                                        <span class="badge-status pago">Pago</span>
                                    @elseif ($deposito->status === 'cancelado')
                                        <span class="badge-status cancelado">Cancelado</span>
                                    @elseif ($deposito->status === 'revisao')
                                        <span class="badge-status revisao">A liberar</span>
                                        <button type="button" class="btn-info-revisao" data-bs-toggle="modal" data-bs-target="#info-revisao-deposito-{{ $deposito->id }}" title="Ver detalhes" aria-label="Ver detalhes"><i data-lucide="info" style="width:12px;height:12px;"></i></button>
                                    @endif
                                </span>
                            </td>
                            <td class="cell-money">R$ {{ number_format($deposito->amount, 2, ',', '.') }}</td>
                            <td class="cell-money">R$ {{ number_format($deposito->taxa_cash_in, 2, ',', '.') }}</td>
                            <td class="cell-money">R$ {{ number_format($deposito->taxa_reserva, 2, ',', '.') }}</td>
                            <td class="cell-money fw-semibold">R$ {{ number_format($deposito->cash_in_liquido, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </div>

    @foreach ($depositos->sortByDesc('created_at') as $deposito)
        <!-- Modal -->
        <div class="modal fade" id="info-revisao-deposito-{{ $deposito->id }}" tabindex="-1"
            aria-labelledby="info-revisao-deposito-{{ $deposito->id }}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="info-revisao-deposito-{{ $deposito->id }}Label">Dados do Depósito</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @php
                            $setting = \App\Models\Setting::first();

                            $tempo = $setting->card_days_to_release;
                            if ($deposito->method == 'billet') {
                                $tempo = $setting->billet_days_to_release;
                            }

                            if($deposito->method == 'card' && $deposito->dias_recebimento > 0){
                                $tempo = $deposito->dias_recebimento;
                            }

                            $pagoem = $deposito->created_at;
                            $dataliberacao = \Carbon\Carbon::parse($pagoem)->addDays($tempo);
                            $diasrestantes = (int) \Carbon\Carbon::now()->diffInDays($dataliberacao, false);
                            $dataliberacaoFormatada = $dataliberacao->locale('pt_BR')->translatedFormat('d/m/Y');
                        @endphp

                        <table class="table table-borderless table-sm mb-0">
                            <tbody>
                                <tr>
                                    <td>Valor bruto da venda:</td>
                                    <td class="text-end">R$ {{ number_format($deposito->amount, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Valor líquido a receber:</td>
                                    <td class="text-end">R$ {{ number_format($deposito->cash_in_liquido, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Taxas aplicadas:</td>
                                    <td class="text-end">R$ {{ number_format($deposito->taxa_cash_in, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Prazo de recebimento:</td>
                                    <td class="text-end">{{ $tempo }} dias</td>
                                </tr>
                                <tr>
                                    <td>Dias restantes:</td>
                                    <td class="text-end">{{ (int) $diasrestantes }} dias</td>
                                </tr>
                                <tr>
                                    <td>Data de liberação automática:</td>
                                    <td class="text-end">{{ $dataliberacaoFormatada }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    @endforeach

    <script>
        function handlePeriodChangeDepositos(selectedValue) {
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
            document.querySelectorAll('.extrato-page .modal').forEach(function(modal) {
                document.body.appendChild(modal);
            });
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

            var table = $("#table-admin-depositos").DataTable({
                responsive: true,
                ordering: true,
                order: [[0, 'desc']],
                lengthChange: false,
                dom: '<"extrato-dt-header"f>t<"extrato-dt-footer"ip>',
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                    search: '',
                    searchPlaceholder: 'Buscar registros...',
                    info: 'Exibindo _START_ a _END_ de _TOTAL_',
                    infoEmpty: 'Nenhum registro',
                    infoFiltered: '(filtrado de _MAX_)',
                    paginate: { first: '«', last: '»', next: '›', previous: '‹' }
                },
                columnDefs: [
                    { orderable: false, targets: 0 },
                    { orderable: false, targets: '_all' }
                ],
                initComplete: function() {
                    var api = this.api();
                    $(api.table().container()).wrap('<div class="extrato-table-wrap"/>');
                    var $filter = $('.extrato-dt-header input');
                    if ($filter.length) $filter.attr('placeholder', 'Buscar registros...');
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });

            $(document).on('click', '#table-admin-depositos thead th.sortable-date', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var $tbl = $('#table-admin-depositos');
                if (!$.fn.DataTable.isDataTable($tbl)) return;
                var api = $tbl.DataTable();
                var $th = $tbl.closest('.extrato-page').find('thead th.sortable-date');
                var dir = $th.attr('data-sort-dir') === 'desc' ? 'asc' : 'desc';
                api.order([[0, dir]]).draw();
                $th.attr('data-sort-dir', dir);
                var path = dir === 'desc' ? 'm6 9 6 6 6-6' : 'm18 15-6-6-6 6';
                $th.find('.sort-indicator svg path').attr('d', path);
            });

            table.on('draw', function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    </script>
</div>{{-- .extrato-page --}}
@endsection
