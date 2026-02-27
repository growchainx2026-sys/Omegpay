@extends('layouts.app')

@section('title', 'Depósitos')

@section('content')
    <style>
        .dataTables_wrapper .dataTables_filter input {
            position: absolute;
            top: -50px !important;
            right: -10px !important;
        }
    </style>
    <div class="header mb-3 d-flex align-items-center justify-content-between">
        <h1 class="header-title">
            Depósitos
        </h1>
        @php
            $periodo = request()->input('periodo', 'dia');
            $start = request()->input('start', now()->startOfDay()->format('Y-m-d'));
            $end = request()->input('end', now()->endOfDay()->format('Y-m-d'));
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
                    R$ {{ number_format($vendasAtual, 2, ',', '.') }}
                    <span class="{{ $crescimento >= 0 ? 'text-success' : 'text-danger' }} fs-6">
                        <i data-lucide="{{ $crescimento > 0 ? 'arrow-up' : ($crescimento < 0 ? 'arrow-down' : '') }}"
                            style="stroke: {{ $crescimento > 0 ? 'green' : 'red' }} !important;width: 14px;"></i>
                        {{ number_format($crescimento, 2) }}%
                    </span>
                </h4>

                <hr class="my-3">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="credit-card" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Cartão</span>
                    </div>
                    <span class="fw-semibold">R$ {{ number_format($vendasCard, 2, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="qr-code" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Pix</span>
                    </div>
                    <span class="fw-semibold">R$ {{ number_format($vendasPix, 2, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i data-lucide="banknote" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Boleto</span>
                    </div>
                    <span class="fw-semibold">R$ {{ number_format($vendasBoleto, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Abandonadas --}}
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card card-dash p-4" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Abandonadas</span>
                    <i data-lucide="trending-down" class="text-muted" style="width:16px;height:16px;"></i>
                </div>

                <h4 class="fw-bold mb-0 t900">
                    R$ {{ number_format($abandonoAtual, 2, ',', '.') }}
                    <span class="{{ $abandono >= 0 ? 'text-success' : 'text-danger' }} fs-6">
                        <i data-lucide="{{ $abandono > 0 ? 'arrow-up' : ($abandono < 0 ? 'arrow-down' : '') }}"
                            style="stroke: {{ $abandono > 0 ? 'green' : 'red' }} !important;width: 14px;"></i>
                        {{ number_format($abandono, 2) }}%
                    </span>
                </h4>

                <hr class="my-3">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="credit-card" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Cartão</span>
                    </div>
                    <span class="fw-semibold">R$ {{ number_format($abandonoCard, 2, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="qr-code" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Pix</span>
                    </div>
                    <span class="fw-semibold">R$ {{ number_format($abandonoPix, 2, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i data-lucide="banknote" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Boleto</span>
                    </div>
                    <span class="fw-semibold">R$ {{ number_format($abandonoBoleto, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Lucros --}}
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card card-dash p-4" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Lucros</span>
                    <i data-lucide="dollar-sign" class="text-muted" style="width:16px;height:16px;"></i>
                </div>

                <h4 class="fw-bold mb-0 t900">
                    R$ {{ number_format($lucroAtual, 2, ',', '.') }}
                    <span class="{{ $lucro >= 0 ? 'text-success' : 'text-danger' }} fs-6">
                        <i data-lucide="{{ $lucro > 0 ? 'arrow-up' : ($lucro < 0 ? 'arrow-down' : '') }}"
                            style="stroke: {{ $lucro > 0 ? 'green' : 'red' }} !important;width: 14px;"></i>
                        {{ number_format($lucro, 2) }}%
                    </span>
                </h4>

                <hr class="my-3">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="credit-card" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Cartão</span>
                    </div>
                    <span class="fw-semibold">R$ {{ number_format($lucroCard, 2, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="qr-code" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Pix</span>
                    </div>
                    <span class="fw-semibold">R$ {{ number_format($lucroPix, 2, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i data-lucide="banknote" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Boleto</span>
                    </div>
                    <span class="fw-semibold">R$ {{ number_format($lucroBoleto, 2, ',', '.') }}</span>
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
                        <i data-lucide="credit-card" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Cartão</span>
                    </div>
                    <span class="fw-semibold">R$ 0,00</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="qr-code" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Pix</span>
                    </div>
                    <span class="fw-semibold">R$ 0,00</span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i data-lucide="banknote" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Boleto</span>
                    </div>
                    <span class="fw-semibold">R$ 0,00</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabela --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title pt-1">Transações</h5>
        </div>
        <div class="card-body">
            <table class="table" id="table-admin-depositos">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Cliente</th>
                        <th>Transação ID</th>
                        <th>Status</th>
                        <th>Valor Bruto</th>
                        <th>Taxas</th>
                        <th>Reserva</th>
                        <th>Valor Liquido</th>
                        <th>Adquirente</th>
                        <th>Webhook</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($depositos->sortByDesc('created_at') as $deposito)
                        <tr>
                            <td>{{ $deposito->created_at->format('d/m/Y \à\s H:i:s') }}</td>
                            <td>{{ $deposito->user->name }}</td>
                            <td>
                                @if ($deposito->method == 'pix')
                                    <i data-lucide="qr-code" class="me-2"
                                        style="stroke: var(--gateway-primary-color) !important;"></i>
                                @elseif($deposito->method == 'billet')
                                    <i data-lucide="barcode" class="me-2"
                                        style="stroke: var(--gateway-primary-color) !important;"></i>
                                @elseif($deposito->method == 'card')
                                    <i data-lucide="credit-card" class="me-2"
                                        style="stroke: var(--gateway-primary-color) !important;"></i>
                                @endif
                                &nbsp;{{ $deposito->idTransaction }}
                            </td>
                            <td>
                                @if ($deposito->status === 'pendente')
                                    <button class="btn btn-sm btn-outline-warning pendente" disabled>Pendente</button>
                                @elseif ($deposito->status === 'pago')
                                    <button class="btn btn-sm btn-outline-success pago" disabled>Pago</button>
                                @elseif ($deposito->status === 'cancelado')
                                    <button class="btn btn-sm btn-outline-danger cancelado" disabled>Cancelado</button>
                                @elseif ($deposito->status === 'revisao')
                                    <button class="btn btn-sm btn-secondary" disabled>Em revisão</button>
                                    <i data-bs-toggle="modal" data-bs-target="#info-revisao-deposito-{{ $deposito->id }}"
                                        data-lucide="info" class="me-2"
                                        style="cursor: pointer;stroke: var(--gateway-primary-color) !important;"></i>
                                @endif
                            </td>
                            <td>R$ {{ number_format($deposito->amount, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($deposito->taxa_cash_in, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($deposito->taxa_reserva, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($deposito->cash_in_liquido, 2, ',', '.') }}</td>
                            <td>{{ $deposito->adquirente_ref }}</td>
                            <td>
                                @if ($deposito->status === 'pago' && $deposito?->callback?->status == 'falhou')
                                    <span class="badge bg-danger text-white">Falha</span>&nbsp;
                                    <i id="resend-{{ $deposito->callback->id }}" class="fas fa-sync text-info"
                                        style="cursor:pointer;" onclick="resendWebhook('{{ $deposito->callback->id }}')"></i>
                                @elseif($deposito->status === 'pago' && $deposito?->callback?->status == 'enviado')
                                    <span class="badge bg-success text-white">Enviado</span>
                                @else
                                    {{ '---' }}
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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

                        <hr>
                        <p class="text-center">Caso queira antecipar a liberação do saldo para o cliente
                            {{ $deposito->user->name }}, clique em
                            Antecipar!
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <form method="POST" action="{{ route('admin.depositos.antecipar', $deposito->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">Antecipar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var table = $("#table-admin-depositos").DataTable({
                responsive: true,
                ordering: false,
                lengthChange: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                    search: ''
                }
            });

            table.on('draw', function () {
                $('#table-admin-depositos tbody tr').each(function () {
                    $(this).find('td').css('border-bottom', 'none');
                });
            });

            table.draw();
        });
    </script>

    <script>
        $(function () {
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

        $('#daterange').on('apply.daterangepicker', function (ev, picker) {
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

        function resendWebhook(id) {
            const el = document.getElementById(`resend-${id}`);

            // adiciona classe de rotação
            el.classList.add('rotate-icon');

            fetch('/api/resend-callback', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content // se for Laravel
                },
                body: JSON.stringify({
                    id: id
                })
            })
                .then((res) => res.json())
                .then((res) => {
                    if (res.status) {
                        showToast('success', 'Webhook enviado com sucesso');
                    } else {
                        showToast('error', 'Falha ao reenviar webhook');
                    }
                })
                .catch(() => {
                    showToast('error', 'Erro na comunicação com o servidor');
                })
                .finally(() => {
                    // remove a rotação após terminar
                    el.classList.remove('rotate-icon');
                    el.style.transform = '';
                });
        }
    </script>
@endsection