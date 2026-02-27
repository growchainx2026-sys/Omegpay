@extends('layouts.app')

@section('title', 'Minhas Afiliações')

@section('content')
    <div class="header mb-3">
        <h1 class="header-title">
            Minhas Afiliações
        </h1>
    </div>

    <div class="card card-dash">
        <div class="card-body">
            <table class="table" id="table-afiliacoes">
                <thead>
                    <tr>
                        <th>Início</th>
                        <th>Produto</th>
                        <th>Produtor</th>
                        <th>Valor do produto</th>
                        <th>Comissão (%)</th>
                        <th></th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($afiliacoes as $afiliacao)
                                    <tr>
                                        <td>{{ $afiliacao?->created_at->format('d/m/Y \à\s H:i:s') ?? '--' }}</td>
                                        <td>
                                            <img @if ($afiliacao?->produto?->image == "produtos/box_default.svg") src="{{ url('/produto-image-default') }}"
                                                @else src="/storage/{{ $afiliacao?->produto?->image }}" @endif
                                                style="height: 40px;width:auto;border-radius:4px;">&nbsp;
                                            {{ $afiliacao?->produto?->name ?? '--' }}
                                        </td>
                                        <td>{{ $afiliacao?->produto?->name_exibition ?? explode(' ', $afiliacao?->produto?->user->name)[0] ?? '--' }}
                                        </td>
                                        <td>{{ $afiliacao?->produto ? 'R$ ' . number_format($afiliacao?->produto?->price, 2, ',', '.') : '--' }}
                                        </td>
                                        <td>{{ $afiliacao?->percentage ? number_format($afiliacao?->percentage, 2) . "%" : '--' }}</td>
                                        <td>

                                            <button type="button" class="btn btn-outline-primary btn-sm button-more"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa-solid fa-ellipsis-vertical text info"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="/affiliates/view/{{ uniqid().'-af'.$afiliacao?->id.'op-'.uniqid() }}">
                                                        <button class="dropdown-item btn-visualizar">
                                                            <i data-lucide="eye" class="me-2"></i>Visualizar
                                                        </button>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a data-bs-toggle="modal" data-bs-target="#sairAfiliacao{{ $afiliacao?->id }}">
                                                        <button class="dropdown-item btn-visualizar">
                                                            <i data-lucide="square-x" class="me-2"></i>Desafiliar-me
                                                        </button>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>


                                    <div class="modal fade" id="sairAfiliacao{{ $afiliacao?->id }}" data-bs-backdrop="static"
                                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="sairAfiliacao{{ $afiliacao?->id }}Label"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-md modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="sairAfiliacao{{ $afiliacao?->id }}Label">Desafiliar-me
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <h6 class="text-danger">Você tem certeza que deseja desafiliar-se do produto?</h6>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancelar</button>
                                                    <form method="POST" action="{{ route('affiliates.desaffiliate.me') }}">
                                                        @csrf
                                                        <input style="display: none;" name="id" value="{{ $afiliacao?->id }}">
                                                        <button type="submit" class="btn btn-danger text-white">Desafiliar-me</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        </div>

                    @endforeach
        </tbody>
        </table>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var table = $("#table-afiliacoes").DataTable({
                responsive: true,
                ordering: false,
                lengthChange: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                    search: ''
                }
            });

            table.on('draw', function () {
                $('#table-afiliacoes tbody tr').each(function () {
                    $(this).find('td').css('border-bottom', 'none');
                });
            });

            // Garante que o evento draw também seja executado na primeira renderização
            table.draw();
        });
    </script>
@endsection