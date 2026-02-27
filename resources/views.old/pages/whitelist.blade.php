@extends('layouts.app')

@section('title', 'IP Whitelist')

@section('content')
<div class="header mb-3">
    <h1 class="header-title">
        IP Whitelist
    </h1>
</div>
<div class="card">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
        <h5 class="card-title">IP's autorizados</h5>
        <div class="text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addIpModal">Adicionar</button>
        </div>
    </div>
    <div class="card-body">
            <table class="table" style="width:100%;" id="table-whitelist">
            <thead>
                <tr>
                    <th>IP</th>
                    <th>Data da inclusão</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach (auth()->user()->whitelist ?? [] as $white)
                <tr>
                    <td><button class="btn btn-outline-success" disabled="">{{ $white->ip }}</button></td>
                    <td>{{ $white->created_at->format('d/m/Y \à\s H:i:s') }}</td>
                    <td>
                        <form method="POST" action="{{ route('auth.whitelist.remove', ['id' => $white->id]) }}">
                            @csrf
                            @method('PUT')
                            <button class="btn btn-danger" type="submit">Remover</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<form method="POST" action="{{ route('auth.whitelist.add') }}">
    @csrf
    <!-- Modal -->
    <div class="modal fade" id="addIpModal" tabindex="-1" aria-labelledby="addIpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="addIpModalLabel">Adicionar IP</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3 mt-3 col-12">
                <div class="form-floating">
                    <input type="text" class="form-control" id="ip"  name="ip" placeholder="Digite seu ip" value="{{ old('ip') }}">
                    <label for="ip">Digite o IP</label>

                </div>
                @error('ip')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
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
  $(document).ready(function () {
        $("#table-whitelist").DataTable({
			responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
            }
		});
        $('#ip').inputmask({ alias: 'ip' });

    });


</script>
@endsection
