@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
    <div class="header">
        <h1 class="header-title">
            Produtos
        </h1>
    </div>

    <div class="row mb-3">
        <div class="card">
            <div class="card-body">
                <table class="table" id="table-admin-produtos">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Produtor</th>
                            <th></th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produtos as $produto)
                            <tr>
                                <td>
                                    <img loading="lazy"
                                        class="color-box"
                                        style="width: 35px;height: 35px;"
                                        alt="{{ $produto->name }}"
                                        @if ($produto->image == "produtos/box_default.svg")
                                            src="{{ url('/produto-image-default') }}"
                                        @else
                                            src="/storage/{{ $produto->image }}"
                                        @endif
                                    />       
                                </td>
                                <td>{{ $produto->name }}</td>
                                <td>{{ $produto->description }}</td>
                                <td>R$ {{ number_format($produto->price, 2 , ',', '.') }}</td>
                                <td>{{ $produto->user->name }}</td>
                                <td>
                                    <a href="{{ url('produto/'.$produto->checkouts()->where('default', 1)->first()->uuid) }}" target="_blank" class="btn btn-primary btn-sm">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <script>
document.addEventListener("DOMContentLoaded", function() {
    var table = $("#table-admin-produtos").DataTable({
        responsive: true,
        ordering: false,
        lengthChange: false,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
            search: ''
        }
    });

    table.on('draw', function() {
        $('#table-admin-produtos tbody tr').each(function() {
            $(this).find('td').css('border-bottom', 'none');
        });
    });

    // Garante que o evento draw também seja executado na primeira renderização
    table.draw();
});

  </script>
@endsection