@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
    <div class="header mb-3" style="display:flex;align-items:center;justify-content:space-between;">
        <h1 class="header-title">
            Produtos
        </h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProdutoModal"><i
                class="fa-solid fa-plus"></i>&nbsp;Adicionar</button>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table" style="width:100%;" id="table-produtos">
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produtos as $produto)
                        <tr>
                            <td>

                                <img @if ($produto->image == "produtos/box_default.svg") src="{{ url('/produto-image-default') }}"
                                @else src="/storage/{{ $produto->image }}" @endif
                                    style="height: 40px;width:auto;border-radius:4px;">
                            </td>
                            <td>{{ $produto->name }}</td>
                            <td>R$ {{ number_format($produto->price, 2, ',', '.') }}</td>
                            <td>
                                @if ($produto->status)
                                    <span class="badge rounded-pill bg-success">Ativo</span>
                                @else
                                    <span class="badge rounded-pill bg-danger">Inativo</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-outline-primary btn-sm button-more"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical text info"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="/produtos/{{ $produto->uuid }}/edit#links">
                                            <button class="dropdown-item btn-visualizar">
                                                <i class="fa-solid fa-link"></i>&nbsp;Ver links
                                            </button>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/produtos/{{ $produto->uuid }}/edit#geral">
                                            <button class="dropdown-item btn-visualizar">
                                                <i class="fa-solid fa-edit"></i>&nbsp;Editar
                                            </button>
                                        </a>
                                    </li>
                                    <li>
                                        <button class="dropdown-item btn-visualizar" data-bs-toggle="modal"
                                            data-bs-target="#delProduto{{ $produto->id }}">
                                            <i class="fa-solid fa-trash"></i>&nbsp;Excluir
                                        </button>
                                    </li>
                                </ul>
                            </td>


                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @foreach ($produtos as $produto)
        <div class="modal fade" id="delProduto{{ $produto->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="delProduto{{ $produto->id }}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="delProduto{{ $produto->id }}Label">Excluir Produto
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Você tem certeza que deseja excluir o produto?</h6>
                        <p>{{ $produto->name }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form method="POST" action="{{ route('produtos.delete', ['id' => $produto->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-primary">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <form method="POST" action="{{ route('produtos.store') }}" enctype="multipart/form-data">
        @csrf
        <!-- Modal -->
        <div class="modal fade" id="addProdutoModal" tabindex="-1" aria-labelledby="addProdutoModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addProdutoModalLabel">Adicionar produto</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3 col-12">
                            <label for="type">Tipo de pagamento</label>
                            <select class="form-control form-control-md" id="type" name="type">
                                <option value="unique" selected>Pagamento único
                                </option>
                                <option value="subscription">Assinatura
                                    recorrente</option>
                            </select>
                        </div>

                        <div class="mb-3 col-12">
                            <label for="name">Nome</label>
                            <input type="text" autofocus class="form-control form-control-md" id="name" name="name"
                                value="{{ old('name') }}">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-12">
                            <label for="description">Descrição</label>
                            <textarea class="form-control" id="description" name="description"
                                rows="5">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-12">
                            <label for="price">Valor</label>
                            <input type="text" class="form-control form-control-md" id="price" name="price"
                                value="{{ old('price', 0.0) }}">
                            @error('price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-12">
                            <label for="coproducao">Deseja adicionar um Coprodutor?</label>
                            <select type="text" autofocus class="form-control form-control-md" id="coproducao"
                                name="coproducao" value="{{ old('cooproducao') }}" onchange="changeCoprodutor(this)">
                                <option>--selecione--</option>
                                <option value="sim">Sim</option>
                                <option value="nao">Não</option>
                            </select>
                        </div>

                        <div id="cop-content" class="d-none card">
                            <div class="card-body row">
                                <div class="mb-3 col-12">
                                    <h5>Dados para coprodução</h5>
                                </div>
                                <div class="mb-3 col-12">
                                    <label for="coprodutor_email">Email</label>
                                    <input type="text" autofocus class="form-control form-control-md" id="coprodutor_email"
                                        name="coprodutor_email" value="{{ old('coprodutor_email') }}">
                                    <small class="text-warning">Digite ou cole aqui o email do produtor</small>
                                    @error('coprodutor_email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-12">
                                    <label for="coprodutor_percentage">Porcentagem</label>
                                    <input type="text" autofocus class="form-control form-control-md"
                                        id="coprodutor_percentage" name="coprodutor_percentage"
                                        value="{{ old('coprodutor_percentage') }}">
                                    <small class="text-warning">Defina um valor de 0 a 99</small>
                                    @error('coprodutor_percentage')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-12">
                                    <label for="coprodutor_periodo">Período</label>
                                    <select type="text" autofocus class="form-control form-control-md"
                                        id="coprodutor_periodo" name="coprodutor_periodo"
                                        value="{{ old('coprodutor_periodo') }}">
                                        <option>--selecione--</option>
                                        <option value="30">1 Mês</option>
                                        <option value="60">2 Meses</option>
                                        <option value="90">3 Meses</option>
                                        <option value="120">4 Meses</option>
                                        <option value="150">5 Meses</option>
                                        <option value="180">6 Meses</option>
                                        <option value="210">7 Meses</option>
                                        <option value="240">8 Meses</option>
                                        <option value="270">9 Meses</option>
                                        <option value="300">10 Meses</option>
                                        <option value="330">11 Meses</option>
                                        <option value="365">1 Ano</option>
                                        <option value="sempre">Sempre</option>
                                    </select>
                                    @error('coprodutor_periodo')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>



                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Adicionar</button>
                        </div>
                    </div>
                </div>
            </div>
    </form>


    <script>

        function changeCoprodutor(data) {
            console.log(data.value)
            let copContainer = document.getElementById('cop-content');
            copContainer.classList.toggle('d-none');
        }

        document.addEventListener("DOMContentLoaded", function () {
            var table = $("#table-produtos").DataTable({
                responsive: true,
                ordering: false,
                lengthChange: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                    search: ''
                }
            });

            table.on('draw', function () {
                $('#table-produtos tbody tr').each(function () {
                    $(this).find('td').css('border-bottom', 'none');
                });
            });

            // Garante que o evento draw também seja executado na primeira renderização
            table.draw();
        });

        $(document).ready(function () {
            $('#price').inputmask('decimal', {
                radixPoint: ',', // separador decimal
                groupSeparator: '.', // separador de milhar
                digits: 2, // duas casas decimais
                autoGroup: true,
                rightAlign: true,
                prefix: 'R$ ', // sem prefixo R$
                removeMaskOnSubmit: true // remove máscara ao enviar o formulário
            });
        });
    </script>

    @if(session('modal'))

        @if (session('coproducao'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    document.getElementById('coproducao').value = 'sim';
                    let copContainer = document.getElementById('cop-content');
                    copContainer.classList.toggle('d-none');

                    const modal = new bootstrap.Modal(document.getElementById('addProdutoModal'));
                    modal.show();
                });
            </script>
        @else
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const modal = new bootstrap.Modal(document.getElementById('addProdutoModal'));
                    modal.show();
                });
            </script>
        @endif

    @endif



@endsection