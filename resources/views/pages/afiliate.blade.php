@php
use App\Helpers\Helper;
$setting = Helper::settings();
$link = env('APP_URL').'/auth/jwt/register?ref='.auth()->user()->codigo_referencia;

@endphp
@extends('layouts.app')

@section('title', 'Indicações')

@section('content')
<div class="header mb-3 d-flex align-items-center justify-content-between">
    <h1 class="header-title">
        Indicações
    </h1>
    <form id="form-filter" action="{{ route('afiliate') }}" method="GET">
        <select class="form-select" name="periodo" onchange="document.getElementById('form-filter').submit()" style="border-color:transparent;color:white;border-radius:10px;background:var(--gateway-sidebar-color)!important">
            @php $periodo = request()->input('periodo', 'dia'); @endphp
            <option value="dia" {{ $periodo == 'dia' ? 'selected' : '' }}>Hoje</option>
            <option value="semana" {{ $periodo == 'semana' ? 'selected' : '' }}>Semana</option>
            <option value="mes" {{ $periodo == 'mes' ? 'selected' : '' }}>Mês</option>
            <option value="tudo" {{ $periodo == 'tudo' ? 'selected' : '' }}>Todos</option>
        </select>
    </form>
</div>
<div class="row mb-3">
    <div class="col-lg-8 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Meu link de indicação</h5>
            </div>
            <div class="card-body d-flex align-items-baseline justify-content-between gs-3">
                <div class="input-group mb-3 mr-3">
                    <input type="text" id="refLink" class="form-control" value="{{$link}}" onclick="shareLink()" readonly>
                </div>
                <button class="btn btn-primary btn-xs me-1  mr-3" id="btnShare">
                    <i class="fa-solid fa-share-from-square"></i>
                </button>
                <button class="btn btn-primary btn-xs  mr-3" id="btnCopy">
                    <i class="fa-solid fa-copy"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-3">
        <div class="card" style="height: 135.5px">
            <div class="card-body">
                <div class="row">
                    <div class="col mt-0">
                        <h5 class="card-title text-start">Ganhos</h5>
                    </div>

                    <div class="col-auto">
                        <div class="text-success icone-card mt-4" style="font-size:36px"><i class="fa-solid fa-wallet"></i></div>
                    </div>
                    <h4 class="text-start display-5" style="margin-top:-18px">
                        R$ {{ number_format($totalGanhos, 2, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Splits</h5>
            </div>
            <div class="card-body">
                <table class="table " id="table-splits">
                    <thead>
                        <tr>
                            <th>Transação ID</th>
                            <th>Valor (R$)</th>
                            <th>Data</th>
                            <th>Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($splits as $split)
                        <tr>
                            <td>{{ $split->idTransaction }}</td>
                            <td>{{ "R$ ".number_format($split->amount, 2, ',', '.') }}</td>
                            <td>{{ $split->created_at->format('d/m/Y \à\s H:i:s') }}</td>
                            <td>
                                @if ($split->status === 'pendente')
                                <button class="btn btn-sm btn-outline-warning pendente" disabled="">Pendente</button>
                                @elseif ($split->status === 'pago')
                                <button class="btn btn-sm btn-outline-success pago" disabled="">Pago</button>
                                @elseif ($split->status === 'cancelado')
                                <button class="btn btn-sm btn-outline-danger cancelado" disabled="">Cancelado</button>
                                @elseif ($split->status === 'revisao')
                                <button class="btn btn-sm btn-outline-secondary padrao" disabled="">Em revisão</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Indicações</h5>
            </div>
            <div class="card-body">
                <table class="table " id="table-indicados">
                    <thead>
                        <tr>
                            <th>Indicado</th>
                            <th>Indicado em</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($indicados as $indicado)
                        <tr>
                            <td>{{ $indicado->name }}</td>
                            <td>{{ $indicado->created_at->format('d/m/Y \à\s H:i:s') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var table = $("#table-indicados").DataTable({
                responsive: true,
                ordering: false,
                lengthChange: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                    search: ''
                }
            });

            table.on('draw', function() {
                $('#table-indicados tbody tr').each(function() {
                    $(this).find('td').css('border-bottom', 'none');
                });
            });

            // Garante que o evento draw também seja executado na primeira renderização
            table.draw();


            var splits = $("#table-splits").DataTable({
                responsive: true,
                ordering: false,
                lengthChange: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                    search: ''
                }
            });

            splits.on('draw', function() {
                $('#table-splits tbody tr').each(function() {
                    $(this).find('td').css('border-bottom', 'none');
                });
            });

            // Garante que o evento draw também seja executado na primeira renderização
            splits.draw();

            window.addEventListener('resize', function () {
        table.columns.adjust().responsive.recalc();
        splits.columns.adjust().responsive.recalc();
    });
        });
    </script>

    <script>
        function shareLink() {
            const input = document.getElementById('refLink');
            input.select();
            input.setSelectionRange(0, 99999); // para mobile
            document.execCommand('copy');

            // Alerta visual opcional
            showToast('success', 'Link copiado para a área de transferência!');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Verifica suporte ao Web Share API
            if (!navigator.share) {
                const btnShare = document.getElementById('btnShare');
                if (btnShare) {
                    btnShare.style.display = 'none';
                }
            }

            // Copiar link
            document.getElementById('btnCopy').addEventListener('click', function() {
                const input = document.getElementById('refLink');
                input.select();
                input.setSelectionRange(0, 99999); // para mobile
                document.execCommand('copy');
                showToast('success', 'Link copiado para a área de transferência!');
            });

            // Compartilhar link
            document.getElementById('btnShare')?.addEventListener('click', async function() {
                const text = document.getElementById('refLink').value;

                try {
                    await navigator.share({
                        title: "Meu link de indicação da {{ env('APP_NAME') }}.",
                        text: "Cadastre-se agora na {{ env('APP_NAME') }} e alavanque suas vendas!",
                        url: text,
                    });
                } catch (error) {
                    alert('Erro ao compartilhar: ' + error.message);
                }
            });
        });
    </script>
    @endsection