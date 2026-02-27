@extends('layouts.app')
@section('title', 'Vendas')
@section('content')
    <div class="mt-3" style="min-height: 100vh;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">Vendas</h3>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Exportar
                </button>
                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="exportDropdown">
                    <li><a class="dropdown-item" href="#" onclick="exportarXLS()">XLS</a></li>
                    <li><a class="dropdown-item" href="#" onclick="exportarCSV()">CSV</a></li>
                </ul>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6" style="min-height: 150px;">
                <div class="card h-100" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                    <div class="card-body d-flex justify-content-between align-items-center  h-100">
                        <div>
                            <small>Vendas encontradas</small>
                            <h4 class="mt-3 mb-0 value-visible fs-1">
                                {{ (clone $vendas)->whereIn('status', ['pago', 'revisao'])->count() }}</h4>
                            <h4 class="mt-3 mb-0 value-visible fs-1 d-none">---</h4>
                        </div>
                        <i class="bi bi-eye cursor-pointer toggle-visibility "></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6" style="min-height: 150px;">
                <div class="card h-100" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small>Valor líquido</small>
                            <h4 class="mt-3 fs-1 mb-0 value-visible">R$
                                {{ number_format((clone $vendas)->whereIn('status', ['pago', 'revisao'])->sum('valor_liquido') ?? 0, 2, ',', '.') }}
                            </h4>
                            <h4 class="mt-3 mb-0 value-visible fs-1 d-none">R$ ---</h4>
                        </div>
                        <i class="bi bi-eye cursor-pointer toggle-visibility "></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small>Total reembolsado</small>
                            <h4 class="mb-0 value-visible">R$ 0,00</h4>
                            <h4 class="mb-0 value-visible d-none">R$ ---</h4>
                        </div>
                        <i class="bi bi-eye cursor-pointer toggle-visibility "></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small>Vendas no pix</small>
                            <h4 class="mb-0 value-visible">R$
                                {{ number_format((clone $vendas)->where('status', 'pago')->where('metodo', 'pix')->sum('valor') ?? 0, 2, ',', '.') }}
                            </h4>
                            <h4 class="mb-0 value-visible d-none">R$ ---</h4>
                        </div>
                        <i class="bi bi-eye cursor-pointer toggle-visibility "></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small>Porcentagem de reembolso</small>
                            <h4 class="mb-0 value-visible">0,0%</h4>
                            <h4 class="mb-0 value-visible d-none">---%</h4>
                        </div>
                        <i class="bi bi-eye cursor-pointer toggle-visibility "></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small>Chargeback</small>
                            <h4 class="mb-0 value-visible">R$ 0,00</h4>
                            <h4 class="mb-0 value-visible d-none">R$ ---</h4>
                        </div>
                        <i class="bi bi-eye cursor-pointer toggle-visibility "></i>
                    </div>
                </div>
            </div>
        </div>

        <form id="filterForm" method="GET" action="{{ route('pedidos.index') }}">
            <input hidden name="status" value="" id="status">
            <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link {{ request()->status == 'aprovados' ? 'active' : '' }}"
                        id="aprovados-tab" data-bs-toggle="tab" href="#aprovados" role="tab"
                        onclick="document.getElementById('status').setAttribute('value', 'aprovados');this.form.submit();">Aprovados</button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link {{ request()->status == 'reembolsados' ? 'active' : '' }}"
                        id="reembolsados-tab" data-bs-toggle="tab" href="#reembolsados" role="tab"
                        onclick="document.getElementById('status').setAttribute('value', 'reembolsados');this.form.submit();">Reembolsados</button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link {{ request()->status == 'chargeback' ? 'active' : '' }}"
                        id="chargeback-tab" data-bs-toggle="tab" href="#chargeback" role="tab"
                        onclick="document.getElementById('status').setAttribute('value', 'chargeback');this.form.submit();">Chargeback</button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link {{ request()->status == 'med' ? 'active' : '' }}" id="med-tab"
                        data-bs-toggle="tab" href="#med" role="tab"
                        onclick="document.getElementById('status').setAttribute('value', 'med');this.form.submit();">MED</button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link {{ request()->status == 'abandonados' ? 'active' : '' }}"
                        id="abandonados-tab" data-bs-toggle="tab" href="#abandonados" role="tab"
                        onclick="document.getElementById('status').setAttribute('value', 'abandonados');this.form.submit();">Abandonados</button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link {{ request()->status == 'todos' ? 'active' : '' }}" id="todos-tab"
                        data-bs-toggle="tab" href="#todos" role="tab"
                        onclick="document.getElementById('status').setAttribute('value', 'todos');this.form.submit();">Todos</button>
                </li>
            </ul>
        </form>

        <div class="table-responsive">
            <table class="table  text-white hovered" id="table-pedidos">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Produto</th>
                        <th>Cliente</th>
                        <th>Status</th>
                        <th>Valor Bruto</th>
                        <th>Taxas</th>
                        <th>Comissões</th>
                        <th>Valor Líquido</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                        <tr style="cursor:pointer;" data-bs-toggle="offcanvas"
                            data-bs-target="#details-pedido-{{ $pedido->id }}" aria-controls="details-pedido-{{ $pedido->id }}">
                            <td>{{ $pedido->created_at->format('d/m/Y H:i:s') }}</td>
                            <td>{{ $pedido->produto->name }}</td>
                            <td>{{ $pedido->comprador['name'] }}</td>
                            <td>
                                @if ($pedido->status === 'pendente')
                                    <button class="btn btn-sm btn-outline-warning pendente" disabled=""
                                        style="width: 80px !important;">Pendente</button>
                                @elseif ($pedido->status === 'pago')
                                    <button class="btn btn-sm btn-outline-success pago" disabled=""
                                        style="width: 80px !important;">Pago</button>
                                @elseif ($pedido->status === 'cancelado')
                                    <button class="btn btn-sm btn-outline-danger cancelado" disabled=""
                                        style="width: 80px !important;">Cancelado</button>
                                @elseif ($pedido->status === 'revisao')
                                    <button class="btn btn-sm btn-outline-dark liberar" disabled=""
                                        style="width: 80px !important;">A Liberar</button>
                                    <i data-bs-toggle="modal" data-bs-target="#info-revisao-pedido-{{ $pedido->id }}"
                                        data-lucide="info" class="me-2"
                                        style="cursor: pointer;stroke: var(--gateway-primary-color) !important;"></i>
                                @endif
                            </td>
                            <td>{{ "R$ " . number_format($pedido->valor, 2, ',', '.') }}</td>
                            <td>{{ "R$ " . number_format($pedido->taxa, 2, ',', '.') }}</td>
                            <td>{{ "R$ " . number_format(((float) ($pedido->afiliado['comission'] ?? 0) + (float) ($pedido->coprodutor['comission'] ?? 0)), 2, ',', '.') }}</td>
                            <td>{{ "R$ " . number_format($pedido->valor_liquido, 2, ',', '.') }}</td>
                            <td>
                                @if($pedido->status === 'pendente')
                                    <button class="btn btn-primary text-white btn-sm" onclick="recuperarVenda('{{ $pedido }}')"
                                        type="button"><i class="fab fa-whatsapp"></i>{{ ' ' }}Recuperar venda</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @foreach ($pedidos as $pedido)
        <!-- Modal -->
        <div class="modal fade" id="info-revisao-pedido-{{ $pedido->id }}" tabindex="-1"
            aria-labelledby="info-revisao-pedido-{{ $pedido->id }}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="info-revisao-pedido-{{ $pedido->id }}Label">Dados do Pedido</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @php
                            $setting = \App\Models\Setting::first();

                            $transaction = auth()->user()->transactions_in()->where('idTransaction', $pedido->idTransaction)->first();
                            $tempo = $setting->card_days_to_release;
                            if ($pedido->metodo == 'billet') {
                                $tempo = $setting->billet_days_to_release;
                            }

                            $pagoem = $pedido->created_at;
                            $dataliberacao = \Carbon\Carbon::parse($pagoem)->addDays($tempo);
                            $diasrestantes = (int) \Carbon\Carbon::now()->diffInDays($dataliberacao, false);
                            $dataliberacaoFormatada = $dataliberacao->locale('pt_BR')->translatedFormat('d/m/Y');
                        @endphp

                        <table class="table table-borderless table-sm mb-0">
                            <tbody>
                                <tr>
                                    <td>Valor bruto da venda:</td>
                                    <td class="text-end">R$ {{ number_format($pedido->valor, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Valor líquido a receber:</td>
                                    <td class="text-end">R$ {{ number_format($transaction->cash_in_liquido ?? 0, 2, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Taxas aplicadas:</td>
                                    <td class="text-end">R$ {{ number_format($transaction->taxa_cash_in ?? 0, 2, ',', '.') }}
                                    </td>
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


        <div class="offcanvas offcanvas-end" tabindex="-1" id="details-pedido-{{ $pedido->id }}"
            aria-labelledby="details-pedido-{{ $pedido->id }}Label">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="details-pedido-{{ $pedido->id }}Label">Dados do pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="comprador-tab-{{ $pedido->id }}" data-bs-toggle="tab"
                            data-bs-target="#comprador-{{ $pedido->id }}" type="button" role="tab"
                            aria-controls="comprador-{{ $pedido->id }}" aria-selected="true">
                            Comprador
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pagamento-tab-{{ $pedido->id }}" data-bs-toggle="tab"
                            data-bs-target="#pagamento-{{ $pedido->id }}" type="button" role="tab"
                            aria-controls="pagamento-{{ $pedido->id }}" aria-selected="false">
                            Pagamento
                        </button>
                    </li>
                </ul>

                <!-- Conteúdo das Tabs -->
                <div class="tab-content" id="myTabContent-{{ $pedido->id }}">
                    <div class="tab-pane fade show active p-3" id="comprador-{{ $pedido->id }}" role="tabpanel"
                        aria-labelledby="comprador-tab-{{ $pedido->id }}">
                        <h5 class="my-3">Informações do comprador</h5>
                        <table class="table table-borderless table-sm mb-0">
                            <tbody>
                                <tr>
                                    <td>Nome: </td>
                                    <td class="text-end">{{ $pedido->comprador['name'] }}</td>
                                </tr>
                                <tr>
                                    <td>CPF: </td>
                                    <td class="text-end">{{ $pedido->comprador['cpf'] }}</td>
                                </tr>
                                <tr>
                                    <td>Celular: </td>
                                    <td class="text-end">{{ $pedido->comprador['phone'] }}</td>
                                </tr>
                                <tr>
                                    <td>Email: </td>
                                    <td class="text-end">{{ $pedido->comprador['email'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade p-3" id="pagamento-{{ $pedido->id }}" role="tabpanel"
                        aria-labelledby="pagamento-tab-{{ $pedido->id }}">
                        <h5 class="my-3">Informações de Pagamento</h5>
                        <table class="table table-borderless table-sm mb-0">
                            <tbody>
                                <tr>
                                    <td>Valor Bruto: </td>
                                    <td class="text-end">R$ {{ number_format($pedido->valor, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Valor Liquido: </td>
                                    <td class="text-end">R$ {{ number_format($pedido->valor_liquido, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Taxas: </td>
                                    <td class="text-end">R$ {{ number_format($pedido->taxa, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Afiliado: </td>
                                    <td class="text-end">R$ {{ number_format($pedido->afiliado['comission'] ?? 0, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Coprodutor: </td>
                                    <td class="text-end">R$
                                        {{ number_format($pedido->coprodutor['comission'] ?? 0, 2, ',', '.') }}</td>
                                </tr>
                                    <tr>
                                        <td>Código do cupom: </td>
                                        <td class="text-end">{{ $pedido->cupom_code ?? '---' }}</td>
                                    </tr>
                                     <tr>
                                        <td>Desconto do cupom: </td>
                                        <td class="text-end">{{ $pedido->cupom_desconto ? 'R$ '.number_format($pedido->cupom_desconto, 2, ',', '.') : '---' }}</td>
                                    </tr>
                                    <tr>
                                    <td>Meio de pagamento: </td>
                                    <td class="text-end">
                                        @if ($pedido->pagamento['metodo'] == 'pix')
                                            <h6 class="">PIX</h6>
                                        @elseif ($pedido->pagamento['metodo'] == 'boleto')
                                            <h6 class="">Boleto</h6>
                                        @elseif ($pedido->pagamento['metodo'] == 'cartao')
                                            <h6 class="">Cartão</h6>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status: </td>
                                    <td class="text-end">
                                        @if ($pedido->status === 'pendente')
                                            <span class="pendente">Pendente</span>
                                        @elseif ($pedido->status === 'pago')
                                            <span class="pago">Pago</span>
                                        @elseif ($pedido->status === 'cancelado')
                                            <span class="cancelado">Cancelado</span>
                                        @elseif ($pedido->status === 'revisao')
                                            <span class="liberar">A Liberar</span>
                                            <i data-bs-toggle="modal" data-bs-target="#info-revisao-pedido-{{ $pedido->id }}"
                                                data-lucide="info" class="me-2"
                                                style="cursor: pointer;stroke: var(--gateway-primary-color) !important;"></i>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <h6 class="my-2 mt-3">Afiliado</h6>
                        <table class="table table-borderless table-sm mb-0">

                            <tbody>
                                <tr>
                                    <td>Nome: </td>
                                    <td class="text-end">{{ $pedido->afiliado['name'] ?? '---' }}</td>
                                </tr>
                                <tr>
                                    <td>Comissão: </td>
                                    <td class="text-end">R$
                                        {{ number_format($pedido->afiliado['comission'] ?? 0, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Porcentagem: </td>
                                    <td class="text-end">{{ number_format($pedido->afiliado['porcentage'] ?? 0, 2) }}%</td>
                                </tr>
                            </tbody>
                        </table>
                        <h6 class="my-2 mt-3">Coprodutor</h6>
                        <table class="table table-borderless table-sm mb-0">

                            <tbody>
                                <tr>
                                    <td>Nome: </td>
                                    <td class="text-end">{{ $pedido->coprodutor['name'] ?? '---' }}</td>
                                </tr>
                                <tr>
                                    <td>Comissão: </td>
                                    <td class="text-end">R$
                                        {{ number_format($pedido->coprodutor['comission'] ?? 0, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Porcentagem: </td>
                                    <td class="text-end">{{ number_format($pedido->coprodutor['porcentage'] ?? 0, 2) }}%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        </div>
    @endforeach


    <script>
        document.querySelectorAll('.toggle-visibility').forEach(button => {
            button.addEventListener('click', () => {
                const parent = button.closest('.card-body');
                const values = parent.querySelectorAll('.value-visible');

                values.forEach(value => {
                    value.classList.toggle('d-none');
                });

                // Alternar o ícone
                button.classList.toggle('bi-eye');
                button.classList.toggle('bi-eye-slash');
            });
        });
    </script>


    {{-- Passa o array PHP para o JS --}}
    <script>
        // Transforma em JSON seguro para JS
        const pedidos = @json($pedidos);

        function exportarCSV() {
            if (!pedidos || pedidos.length === 0) {
                showToast('warning', 'Não exitem dados para ser exportados!')
                return;
            }

            // Extrai os cabeçalhos (keys)
            const headers = Object.keys(pedidos[0]);

            // Monta as linhas
            const csvRows = [];
            csvRows.push(headers.join(',')); // Cabeçalho

            pedidos.forEach(pedido => {
                const values = headers.map(header => {
                    let val = pedido[header];

                    // Se for objeto ou string JSON, mantém como string pura
                    if (typeof val === 'object') {
                        val = JSON.stringify(val);
                    }

                    // Escapa aspas duplas e coloca entre aspas se necessário
                    val = String(val).replace(/"/g, '""');
                    if (val.includes(',') || val.includes('"') || val.includes('\n')) {
                        val = `"${val}"`;
                    }
                    return val;
                });
                csvRows.push(values.join(','));
            });

            // Junta linhas com quebra de linha
            const csvString = csvRows.join('\n');

            // Cria blob e força download
            const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement("a");
            link.href = url;
            link.setAttribute("download", "pedidos.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            showToast('success', 'Lista exportada com sucesso!')
        }

        function exportarXLS() {
            if (!pedidos || pedidos.length === 0) {
                showToast('warning', 'Não exitem dados para ser exportados!')
                return;
            }

            const headers = Object.keys(pedidos[0]);
            let tabela = '<table border="1"><tr>';

            // Cabeçalho
            headers.forEach(header => {
                tabela += `<th>${header}</th>`;
            });
            tabela += '</tr>';

            // Linhas
            pedidos.forEach(pedido => {
                tabela += '<tr>';
                headers.forEach(header => {
                    let val = pedido[header];
                    if (typeof val === 'object') {
                        val = JSON.stringify(val);
                    }
                    tabela += `<td>${val ?? ''}</td>`;
                });
                tabela += '</tr>';
            });

            tabela += '</table>';

            // Monta arquivo
            const blob = new Blob([tabela], { type: "application/vnd.ms-excel" });
            const url = URL.createObjectURL(blob);
            const link = document.createElement("a");
            link.href = url;
            link.download = "pedidos.xls";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            showToast('success', 'Lista exportada com sucesso!')
        }
    </script>

    <script>

        function recuperarVenda(venda) {
            venda = JSON.parse(venda);
            apenasNumeros = (venda?.comprador?.phone || '').replace(/\D/g, '');
            let mensagem = `Olá, ${venda.comprador.name}, vi que demonstrou interesse no nosso produto ${venda.produto.name}. Tem alguma dúvida? Caso haja, posso te ajudar?`;
            window.open(`https://api.whatsapp.com/send?phone=55${apenasNumeros}&text=${encodeURIComponent(mensagem)}`, '_blank');
        }


        document.addEventListener('DOMContentLoaded', function () {
            // Ativa a tab correta com base no hash da URL
            const hash = window.location.hash;
            if (hash) {
                const trigger = document.querySelector(`a.nav-link[href="${hash}"]`);
                if (trigger) {
                    new bootstrap.Tab(trigger).show();
                }
            }

            // Atualiza o hash ao mudar de tab
            const links = document.querySelectorAll('a[data-bs-toggle="tab"]');
            links.forEach(link => {
                link.addEventListener('shown.bs.tab', function (event) {
                    const newHash = event.target.getAttribute('href');
                    history.replaceState(null, null, newHash);
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var table = $("#table-pedidos").DataTable({
                responsive: true,
                ordering: false,
                lengthChange: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                    search: ''
                },
                hover: true // habilita classe 'hover' padrão do DataTables
            });

            table.on('draw', function () {
                $('#table-pedidos tbody tr').each(function () {
                    $(this).find('td').css('border-bottom', 'none');
                });
            });

            // Garante que o evento draw também seja executado na primeira renderização
            table.draw();
        });


    </script>
@endsection