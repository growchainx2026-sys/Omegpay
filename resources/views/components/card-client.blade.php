@props(['client'])
<div class="card shadow-sm" style="margin: 0 auto; position: relative; z-index: 1;">
    <div class="row g-0">
        <div class="col-md-4 p-3 text-center">
            <img src="{{ asset('' . $client->avatar) }}"
                style="width: 80px;height:80px;border-radius:80px;object-fit:cover;"
                alt="Profile {{ $client->name ?? '' }}">
            <div class="mt-2">
                @if ($client->status === 'aguardando')
                    <span class="badge bg-warning text-white">Pendente</span>
                @elseif ($client->status === 'analise')
                    <span class="badge bg-secondary text-white">Em analise</span>
                @elseif ($client->status === 'aprovado')
                    <span class="badge bg-success text-white">Aprovado</span>
                @elseif ($client->status === 'reprovado')
                    <span class="badge bg-danger text-white">Reprovado</span>
                @endif
            </div>
            <div class="mt-2">
                @if ($client->banido === 1)
                    <span class="badge bg-danger text-white">Banido</span>
                @else
                    <span class="badge bg-success text-white">Ativo</span>
                @endif
            </div>
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <h5 class="card-title d-flex justify-content-between align-items-center">
                    <p class="text-truncate">{{ $client->name }}</p>
                    <div class="btn-group" style="position: relative;">
                        <button type="button" class="btn btn-sm button-more" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fa-solid fa-ellipsis text info"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="z-index: 9999 !important; position: absolute;">
                            <li>
                                <a href="{{ route('admin.clientes.edit', ['id' => $client->id]) }}">
                                    <button class="dropdown-item btn-visualizar">
                                        <i class="fa-solid fa-circle-info text-info"></i>&nbsp;Detalhes
                                    </button>
                                </a>
                            </li>
                            @if ($client->status === 'analise')
                                <li>
                                    <form method="POST" action="{{ route('admin.clientes.status') }}">
                                        @csrf
                                        <input hidden name="id" value="{{ $client->id }}">
                                        <input hidden name="status" value="aprovado">
                                        <button type="submit" class="dropdown-item"><i
                                                class="fa-solid fa-circle-check text-success"></i>&nbsp;Aprovar</button>
                                    </form>
                                </li>
                            @elseif ($client->status === 'reprovado')
                                <li>
                                    <form method="POST" action="{{ route('admin.clientes.status') }}">
                                        @csrf
                                        <input hidden name="id" value="{{ $client->id }}">
                                        <input hidden name="status" value="aprovado">
                                        <button type="submit" class="dropdown-item"><i
                                                class="fa-solid fa-circle-check text-success"></i>&nbsp;Aprovar</button>
                                    </form>
                                </li>
                            @else
                                <li>
                                    <form method="POST" action="{{ route('admin.clientes.status') }}">
                                        @csrf
                                        <input hidden name="id" value="{{ $client->id }}">
                                        <input hidden name="status" value="reprovado">
                                        <button type="submit" class="dropdown-item"><i
                                                class="fa-solid fa-circle-xmark text-danger"></i>&nbsp;Reprovar</button>
                                    </form>
                                </li>
                            @endif
                            @if ($client->banido == 0)
                                <li>
                                    <form method="POST" action="{{ route('admin.clientes.status') }}">
                                        @csrf
                                        <input hidden name="id" value="{{ $client->id }}">
                                        <input hidden name="banido" value="1">
                                        <button type="submit" class="dropdown-item"><i
                                                class="fa-regular fa-circle-xmark text-warning"></i>&nbsp;Banir</button>
                                    </form>
                                </li>
                            @elseif ($client->banido == 1)
                                <li>
                                    <form method="POST" action="{{ route('admin.clientes.status') }}">
                                        @csrf
                                        <input hidden name="id" value="{{ $client->id }}">
                                        <input hidden name="banido" value="0">
                                        <button type="submit" class="dropdown-item"><i
                                                class="fa-solid fa-circle-check text-success"></i>&nbsp;Desbanir</button>
                                    </form>
                                </li>
                            @endif
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a href="{{ route('admin.clientes.edit', ['id' => $client->id]) }}">
                                    <button class="dropdown-item btn-editar" {{-- data-bs-toggle="modal"
                                        data-bs-target="#modalEditar{{ $client->id }}" --}}>
                                        <i class="fa-solid fa-edit text-secondary"></i>&nbsp;Editar
                                    </button>
                                </a>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                    data-bs-target="#delCliente{{ $client->id }}">
                                    <i class="fa-regular fa-circle-xmark text-danger"></i>
                                    &nbsp;Excluir
                                </button>
                            </li>
                        </ul>
                    </div>
                </h5>
                <p class="card-text text-muted">
                    @if ($client->permission == 'admin')
                        <i class="fas fa-briefcase text-warning"></i>&nbsp;
                        <span class="text-warning">
                            {{ 'Administrador' }}
                        </span>
                    @else
                        <i class="fas fa-user"></i>&nbsp;
                        <span class="text-success">
                            {{ 'Cliente' }}
                        </span>
                    @endif
                </p>
                <p class="card-text">
                    <small class="text-muted">
                        <i class="fas fa-map-marker-alt"></i> {{ $client->cidade ?? 'Cidade' }},
                        {{ $client->estado ?? 'UF' }}
                    </small>
                </p>
                <div class="border-top pt-2">
                    <div class="row text-center">
                        <div class="col">
                            <h6 style="font-size: 10px">Saldo disponível</h6>
                            <strong>R$ {{ number_format($client->saldo, 2, ',', '.') }}</strong>
                        </div>
                        <div class="col border-start">
                            <h6 style="font-size: 10px">Saldo bloqueado</h6>
                            <strong>R$ {{ number_format($client->saldo_a_liberar, 2, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
