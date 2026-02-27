@php
    $id = request()->get('id');
    $user = auth()->user();
    $notificacoes = $user->unreadNotifications();
    $quantidade = (clone $notificacoes)->count();

    foreach ($user->unreadNotifications as $notification) {
        $notification->markAsRead();
    }

@endphp

@extends('layouts.app')

@section('title', 'Notificações')

@section('content')
    <div class="header mb-3">
        <h1 class="header-title">
            Coproduções
        </h1>
    </div>

    <div class="card card-dash">
        <div class="card-body">
            <table class="table" id="table-notificacoes">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Produto</th>
                        <th>Produtor</th>
                        <th>Valor do produto</th>
                        <th>Comissão (%)</th>
                        <th>Período</th>
                        <th>Status</th>
                        <th></th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($coproducoes as $coproducao)

                        <tr style="background-color: {{ isset($id) && $id == $coproducao->id ? '#f563633d' : 'transparent' }};">
                            <td>{{ $coproducao->created_at->format('d/m/Y \à\s H:i:s') }}</td>
                            <td>{{ $coproducao->produto->name }}</td>
                            <td>{{ $coproducao->produto->name_exibition ?? explode(' ', $coproducao->produto->user->name)[0] }}
                            </td>
                            <td>R$ {{ number_format($coproducao->produto->price, 2, ',', '.') }}</td>
                            <td>{{ number_format($coproducao->percentage, '2') }}%</td>
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
                                    $periodo = $periodos[$coproducao->periodo];
                                @endphp
                                <span class="pago">{{ $periodo }}</span>
                            </td>
                            <td>
                                @if($coproducao->accept == 'accept')
                                    <span class="badge text-bg-success text-white">Aceito</span>
                                @else
                                    <span class="badge text-bg-warning text-white">Pendente</span>
                                @endif
                            </td>
                            <td>

                                <button type="button" class="btn btn-outline-primary btn-sm button-more"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical text info"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    @if($coproducao->accept == 'pending')
                                        <li>
                                            <a data-bs-toggle="modal"
                                                data-bs-target="#acceptCoproducao{{ $coproducao->id }}">
                                                <button class="dropdown-item btn-visualizar">
                                                    <i data-lucide="square-check-big" class="me-2"></i>Aceitar
                                                </button>
                                            </a>
                                        </li>
                                    @endif

                                    <li>
                                        <a  data-bs-toggle="modal"
                                            data-bs-target="#recuseCoproducao{{ $coproducao->id }}">
                                            <button class="dropdown-item btn-visualizar">
                                                <i data-lucide="square-x" class="me-2"></i>Recusar
                                            </button>
                                        </a>
                                    </li>
                                </ul>
                            </td>
                        </tr>


                        <div class="modal fade" id="acceptCoproducao{{ $coproducao->id }}" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="acceptCoproducao{{ $coproducao->id }}Label"
                            aria-hidden="true">
                            <div class="modal-dialog modal-md modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="acceptCoproducao{{ $coproducao->id }}Label">Aceitar coprodução
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h6>Você tem certeza que deseja aceitar a coprodução?</h6>
                                        <h6>Produto: {{ $coproducao->produto->name }}</h6>
                                        <h6>Valor do produto: R$ {{ number_format($coproducao->produto->price, 2, ',', '.') }}</h6>
                                        <h6>Comissão: {{ number_format($coproducao->percentage, '2') }}%</h6>
                                        <h6>Produtor: {{ $coproducao->produto->name_exibition ?? explode(' ', $coproducao->produto->user->name)[0] }}</h6>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancelar</button>
                                        <form method="POST" action="{{ route('coproducao.accept') }}">
                                            @csrf
                                            <input style="display: none;" name="id" value="{{ $coproducao->id }}">
                                            <button type="submit" class="btn btn-success text-white">Aceitar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="modal fade" id="recuseCoproducao{{ $coproducao->id }}" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="recuseCoproducao{{ $coproducao->id }}Label"
                            aria-hidden="true">
                            <div class="modal-dialog modal-md modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="recuseCoproducao{{ $coproducao->id }}Label">Recusar coprodução
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h6 class="text-danger">Você tem certeza que deseja recusar a coprodução?</h6>
                                        <h6 class="text-danger">Produto: {{ $coproducao->produto->name }}</h6>
                                        <h6 class="text-danger">Valor do produto: R$ {{ number_format($coproducao->produto->price, 2, ',', '.') }}</h6>
                                        <h6 class="text-danger">Comissão: {{ number_format($coproducao->percentage, '2') }}%</h6>
                                        <h6 class="text-danger">Produtor: {{ $coproducao->produto->name_exibition ?? explode(' ', $coproducao->produto->user->name)[0] }}</h6>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancelar</button>
                                        <form method="POST" action="{{ route('coproducao.recuse') }}">
                                            @csrf
                                            <input style="display: none;" name="id" value="{{ $coproducao->id }}">
                                            <button type="submit" class="btn btn-danger text-white">Recusar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var table = $("#table-notificacoes").DataTable({
                responsive: true,
                ordering: false,
                lengthChange: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                    search: ''
                }
            });

            table.on('draw', function () {
                $('#table-notificacoes tbody tr').each(function () {
                    $(this).find('td').css('border-bottom', 'none');
                });
            });

            // Garante que o evento draw também seja executado na primeira renderização
            table.draw();
        });
    </script>
@endsection