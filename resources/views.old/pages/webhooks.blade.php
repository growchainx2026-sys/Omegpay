@extends('layouts.app')

@section('title', 'Webhooks')

@section('content')

    <div class="header mb-3 d-flex align-items-center justify-content-between">
        <h1 class="header-title">
            Webhooks
        </h1>
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addWebhookModal">
            + Adicionar
        </button>
    </div>

    <div class="card card-dash">
        <div class="card-body">
            <table class="table" id="table-webhooks">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Tipo</th>
                        <th>Metódo</th>
                        <th>Url</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($webhooks as $webhook)
                        <tr>
                            <td>{{ $webhook->name }}</td>
                            <td>
                                {{ $webhook->type }}
                                @if ($webhook->type == 'produto' && $webhook->produto)
                                    {{ ' - (' . $webhook->produto->name . ')' }}
                                @endif
                            </td>
                            <td>
                                @if($webhook->method == 'GET')
                                    <span class="badge text-bg-info text-white">GET</span>
                                @elseif ($webhook->method == 'POST')
                                    <span class="badge text-bg-success text-white">POST</span>
                                @elseif ($webhook->method == 'PUT')
                                    <span class="badge text-bg-warning text-white">PUT</span>
                                @endif
                            </td>
                            <td>{{ $webhook->url }}</td>
                            
                            <td>{{ $webhook->status }}</td>
                            <td>
                                <button type="button" class="btn btn-outline-primary btn-sm button-more"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical text info"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <button type="button" class="dropdown-item btn-visualizar" data-bs-toggle="modal"
                                            data-bs-target="#editWebhookModal{{ $webhook->id }}">
                                            <i data-lucide="square-pen" class="me-2"></i>Editar
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item btn-visualizar" data-bs-toggle="modal"
                                            data-bs-target="#deleteWebhookModal{{ $webhook->id }}">
                                            <i data-lucide="trash-2" class="me-2"></i>Excluir
                                        </button>
                                    </li>
                                </ul>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade edit-webhook-modal" id="editWebhookModal{{ $webhook->id }}" tabindex="-1"
                            aria-labelledby="editWebhookModal{{ $webhook->id }}Label" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-md">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('webhooks.update', $webhook->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="editWebhookModal{{ $webhook->id }}Label">
                                                Editar webhook</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body row">
                                            <div class="mb-3 col-12">
                                                <label for="name-{{ $webhook->id }}">Nome</label>
                                                <input type="text" autofocus class="form-control form-control-md"
                                                    id="name-{{ $webhook->id }}" name="name"
                                                    value="{{ $webhook->name }}">
                                            </div>
                                            <div class="mb-3 col-12">
                                                <label for="url-{{ $webhook->id }}">Url</label>
                                                <input type="text" class="form-control form-control-md"
                                                    id="url-{{ $webhook->id }}" name="url"
                                                    value="{{ $webhook->url }}">
                                            </div>
                                            <div class="mb-3 col-12">
                                                <label for="method-{{ $webhook->id }}">Método</label>
                                                <select autofocus class="form-control form-control-md" name="method">
                                                    <option value="GET" {{  $webhook->method == 'GET' ? 'selected' : '' }}>GET</option>
                                                    <option value="POST" {{ $webhook->method == 'POST' ? 'selected' : '' }}>POST</option>
                                                    <option value="PUT" {{ $webhook->method == 'PUT' ? 'selected' : '' }}>PUT</option>
                                                </select>
                                                @error('method')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3 col-12">
                                                <label for="select-type-{{ $webhook->id }}">Tipo</label>
                                                <select autofocus class="form-control form-control-md select-type"
                                                    id="select-type-{{ $webhook->id }}" name="type">
                                                    <option value="geral"
                                                        {{ $webhook->type == 'geral' ? 'selected' : '' }}>Depósitos
                                                    </option>
                                                    <option value="produto"
                                                        {{ $webhook->type == 'produto' ? 'selected' : '' }}>Produto
                                                    </option>
                                                </select>
                                            </div>

                                            <div id="select-produto-{{ $webhook->id }}"
                                                class="mb-3 col-12 select-produto {{ $webhook->type === 'produto' ? '' : 'd-none' }}">
                                                <label for="produto_id-{{ $webhook->id }}">Produto</label>
                                                <select autofocus class="form-control form-control-md"
                                                    id="produto_id-{{ $webhook->id }}" name="produto_id">
                                                    <option value="">-- selecione --</option>
                                                    @foreach ($produtos as $produto)
                                                        <option value="{{ $produto->id }}"
                                                            {{ $webhook->produto_id == $produto->id ? 'selected' : '' }}>
                                                            {{ $produto->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Salvar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteWebhookModal{{ $webhook->id }}" tabindex="-1"
                            aria-labelledby="deleteWebhookModal{{ $webhook->id }}Label" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('webhooks.destroy', $webhook->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteWebhookModal{{ $webhook->id }}Label">
                                                Confirmar exclusão</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Tem certeza que deseja excluir o webhook
                                                <strong>{{ $webhook->name }}</strong>?
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-danger text-white">Excluir</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addWebhookModal" tabindex="-1" aria-labelledby="addWebhookModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <form method="POST" action="{{ route('webhooks.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addWebhookModalLabel">Adicionar webhook</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body row">
                        <div class="mb-3 col-12">
                            <label for="name">Nome</label>
                            <input type="text" autofocus class="form-control form-control-md" id="name"
                                name="name" value="{{ old('name') }}">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-12">
                            <label for="url">Url</label>
                            <input type="text" class="form-control form-control-md" id="url" name="url"
                                value="{{ old('url') }}">
                            @error('url')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-12">
                            <label for="method">Método</label>
                            <select autofocus class="form-control form-control-md" name="method">
                                <option value="GET" {{ old('method', 'GET') == 'GET' ? 'selected' : '' }}>GET</option>
                                <option value="POST" {{ old('method') == 'POST' ? 'selected' : '' }}>POST</option>
                                <option value="PUT" {{ old('method') == 'PUT' ? 'selected' : '' }}>PUT</option>
                            </select>
                            @error('method')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-12">
                            <label for="select-type">Tipo</label>
                            <select autofocus class="form-control form-control-md" id="select-type" name="type">
                                <option value="geral" {{ old('type', 'geral') == 'geral' ? 'selected' : '' }}>Depósitos
                                </option>
                                <option value="produto" {{ old('type') == 'produto' ? 'selected' : '' }}>Produto</option>
                            </select>
                            @error('type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="select-produto" class="mb-3 col-12 d-none">
                            <label for="produto_id">Produto</label>
                            <select autofocus class="form-control form-control-md" id="produto_id" name="produto_id">
                                <option value="">-- selecione --</option>
                                @foreach ($produtos as $produto)
                                    <option value="{{ $produto->id }}"
                                        {{ old('produto_id', $produto->id) == $produto->id ? 'selected' : '' }}>
                                        {{ $produto->name }}</option>
                                @endforeach
                            </select>
                            @error('produto_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">+ Adicionar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @php
        $depositData = [
            'status' => 'paid',
            'typeTransaction' => 'PIX',
            'idTransaction' => "4f6d5a4fd-54sd5f46-we498e4-4654sdf",
            'client' => [
                'cpf' => '123.456.789-00',
                'name' => 'Fulano de tal',
            ]
        ];

        $productData = [
            'product' => [
                'name' => "Produto X",
                'price' => 299.90,
            ],
            'status' => 'paid',
            'typeTransaction' => 'PIX',
            'idTransaction' => "4f6d5a4fd-54sd5f46-we498e4-4654sdf",
            'client' => [
                'cpf' => '123.456.789-00',
                'name' => 'Fulano de tal',
                'email' => 'fulano@gmail.com',
                'phone' => '(11) 90000-0000'
            ]
        ];

        $jsonDeposito = json_encode($depositData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $jsonProduto = json_encode($productData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    @endphp


    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                    aria-expanded="true" aria-controls="collapseOne">
                    Como funciona?
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <strong class="text-gateway fs-6">Tipo Depósito: </strong>
                    Para todos as transações de entrada em sua conta,
                    quando é confirmado o pagamento, a url cadastrada
                    recebe uma requisição do tipo <strong>POST</strong> com o seguinte conteúdo
                    no corpo da requisição "body":<br />
                    <pre class="my-3"><code class="language-json">{{ $jsonDeposito }}</code></pre>
                    <br/>
                    <strong class="text-gateway fs-6">Tipo Produto: </strong>
                    Para todos os seus produtos que forem vendidos e efetivamente receber a 
                    confirmação de pagamento, a url cadastrada
                    recebe uma requisição do tipo <strong>POST</strong> com o seguinte conteúdo
                    no corpo da requisição "body":<br />
                    <pre class="my-3"><code class="language-json">{{ $jsonProduto }}</code></pre>
                </div>
            </div>

        </div>

        <!-- SCRIPTS -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                // 1) ADD modal: toggle produto select
                const addSelectType = document.getElementById('select-type');
                const addSelectProdutoContainer = document.getElementById('select-produto');

                if (addSelectType && addSelectProdutoContainer) {
                    const toggleAddType = () => {
                        addSelectProdutoContainer.classList.toggle('d-none', addSelectType.value !== 'produto');
                    };
                    addSelectType.addEventListener('change', toggleAddType);
                    toggleAddType();
                }

                // 2) EDIT modals: attach listeners to each modal (safer than buscar por id global)
                const editModals = document.querySelectorAll('.edit-webhook-modal');

                editModals.forEach(modalEl => {
                    // on modal shown, set visibility based on the select value
                    modalEl.addEventListener('shown.bs.modal', function() {
                        const selectType = modalEl.querySelector('.select-type');
                        const produtoContainer = modalEl.querySelector('.select-produto');
                        if (selectType && produtoContainer) {
                            produtoContainer.classList.toggle('d-none', selectType.value !== 'produto');
                        }
                    });

                    // listen change on the select inside the modal
                    const selectTypeField = modalEl.querySelector('.select-type');
                    if (selectTypeField) {
                        const produtoContainer = modalEl.querySelector('.select-produto');
                        selectTypeField.addEventListener('change', function() {
                            if (produtoContainer) {
                                produtoContainer.classList.toggle('d-none', selectTypeField.value !==
                                    'produto');
                            }
                        });

                        // set initial visibility (in case modal is in DOM and visible before showing)
                        if (produtoContainer) {
                            produtoContainer.classList.toggle('d-none', selectTypeField.value !== 'produto');
                        }
                    }
                });

                // 3) DataTable init (verifica se jQuery + DataTables existem)
                if (window.jQuery && $.fn.dataTable) {
                    var table = $("#table-webhooks").DataTable({
                        responsive: true,
                        ordering: false,
                        lengthChange: false,
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                            search: ''
                        }
                    });

                    table.on('draw', function() {
                        $('#table-webhooks tbody tr').each(function() {
                            $(this).find('td').css('border-bottom', 'none');
                        });
                    });

                    // Garante que o evento draw também seja executado na primeira renderização
                    table.draw();
                }
            });
        </script>

        @if (session('modal-webhooks'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const modal = new bootstrap.Modal(document.getElementById('addWebhookModal'));
                    modal.show();

                    const addSelectType = document.getElementById('select-type');
                    const addSelectProdutoContainer = document.getElementById('select-produto');

                    if (addSelectType && addSelectProdutoContainer) {
                        const toggleAddType = () => {
                            addSelectProdutoContainer.classList.toggle('d-none', addSelectType.value !== 'produto');
                        };
                        addSelectType.addEventListener('change', toggleAddType);
                        toggleAddType();
                    }
                });
            </script>
        @endif

    @endsection
