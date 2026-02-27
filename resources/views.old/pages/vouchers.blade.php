@extends('layouts.app')

@section('title', 'Vouchers Gerados')

@section('content')
    <div class="header mb-3 d-flex align-items-center justify-content-between">
        <h1 class="header-title">
            Vouchers Gerados
        </h1>
        <form id="form-filter" action="{{ route('vouchers.index') }}" method="GET">
            <select class="form-select" name="periodo" onchange="document.getElementById('form-filter').submit()"
                style="border-color:transparent;color:white;border-radius:10px;background:var(--gateway-sidebar-color)!important">
                @php $periodo = request()->input('periodo', 'todos'); @endphp
                <option value="hoje" {{ $periodo == 'hoje' ? 'selected' : '' }}>Hoje</option>
                <option value="semana" {{ $periodo == 'semana' ? 'selected' : '' }}>Semana</option>
                <option value="mes" {{ $periodo == 'mes' ? 'selected' : '' }}>Mês</option>
                <option value="todos" {{ $periodo == 'todos' ? 'selected' : '' }}>Todos</option>
            </select>
        </form>
    </div>

    <div class="row">
        <!-- Card Total de Vouchers -->
        <div class="col-md-6 col-xl-6">
            <div class="card card-dash">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Total de Vouchers</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-primary">
                                <i class="fa-solid fa-ticket"></i>
                            </div>
                        </div>
                    </div>
                    <h6 class="text-start display-5">{{ $totalVouchers }}</h6>
                    <div class="mb-0">
                        <span class="text-success">
                            <i class="mdi mdi-arrow-bottom-right"></i>
                        </span>
                        Vouchers pagos
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Valor Total -->
        <div class="col-md-6 col-xl-6">
            <div class="card card-dash">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Valor Total</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-primary">
                                <i class="fa-solid fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                    <h6 class="text-start display-5">R$ {{ number_format($valorTotal, 2, ',', '.') }}</h6>
                    <div class="mb-0">
                        <span class="text-success">
                            <i class="mdi mdi-arrow-bottom-right"></i>
                        </span>
                        Em vouchers pagos
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-dash">
        <div class="card-body">
            <table class="table" id="table-vouchers-gerados">
                <thead>
                    <tr>
                        <th>Data Pagamento</th>
                        <th>Código Voucher</th>
                        <th>Cliente</th>
                        <th>CPF</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Ativação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vouchers as $voucher)
                        <tr>
                            <td>{{ $voucher->created_at ? $voucher->created_at->format('d/m/Y \à\s H:i:s') : '-' }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $voucher->codigo_voucher }}</span>
                            </td>
                            <td>{{ $voucher->client_name ?? '-' }}</td>
                            <td>{{ $voucher->client_cpf ?? '-' }}</td>
                            <td>R$ {{ number_format($voucher->valor, 2, ',', '.') }}</td>
                            <td>
                                @if ($voucher->status === 'pendente')
                                    <span class="pendente" disabled="">Pendente</span>
                                @elseif ($voucher->status === 'pago')
                                    <span class="pago" disabled="">Pago</span>
                                @elseif ($voucher->status === 'cancelado')
                                    <span class="cancelado" disabled="">Cancelado</span>
                                @endif
                            </td>
                            <td>
    @if ($voucher->ativacao === 'validar?')
        <form action="{{ route('vouchers.validar', $voucher->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm">
                Validar?
            </button>
        </form>
    @elseif ($voucher->ativacao === 'Validado')
        <span class="badge bg-success">Validado</span>
    @elseif ($voucher->ativacao === 'Cancelado')
        <span class="badge bg-danger">Cancelado</span>
    @else
        <span>-</span>
    @endif
</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var table = $("#table-vouchers-gerados").DataTable({
                responsive: true,
                ordering: false,
                lengthChange: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                    search: ''
                }
            });

            table.on('draw', function() {
                $('#table-vouchers-gerados tbody tr').each(function() {
                    $(this).find('td').css('border-bottom', 'none');
                });
            });

            // Garante que o evento draw também seja executado na primeira renderização
            table.draw();
        });
    </script>
@endsection
