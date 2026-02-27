@extends('layouts.app')

@section('title', 'Banners')

@section('content')
<div class="header mb-3">
    <h1 class="header-title">
        Banners
    </h1>
</div>
<div class="card">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
        <h5 class="card-title">Lista de banners</h5>
        <div class="text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBannerModal">Adicionar</button>
        </div>
    </div>
    <div class="card-body">
            <table class="table" style="width:100%;" id="table-banners">
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Titulo</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($banners as $banner)
                <tr>
                    <td>
                        <image src="/storage/{{ $banner->image }}" style="height:45px;width:auto;">
                    </td>
                    <td>{{ $banner->title }}</td>
                    <td>{{ $banner->description }}</td>
                    <td>
                        @if($banner->status)
                            <span class="badge rounded-pill bg-success">Ativo</span>
                        @else
                            <span class="badge rounded-pill bg-danger">Inativo</span>
                        @endif
                    </td>
                    <td>

                    <div class="btn-group">
                                <button type="button" class="btn btn-outline-primary btn-sm button-more" data-bs-toggle="dropdown" aria-expanded="false">
                                  <i class="fa-solid fa-ellipsis-vertical text info"></i>
                                </button>
                                <ul class="dropdown-menu">
                                  <li>
                                    <button class="dropdown-item btn-editar"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditar{{ $banner->id }}">
                                        <i class="fa-solid fa-edit text-secondary"></i>&nbsp;Editar
                                    </button>
                                </li>
                                 <li><hr class="dropdown-divider"></li>
                                <li>
                                    <button 
                                        type="button" 
                                        class="dropdown-item" 
                                         data-bs-toggle="modal"
                                        data-bs-target="#delCliente{{ $banner->id }}">
                                        <i class="fa-solid fa-trash text-danger"></i>
                                        &nbsp;Excluir
                                    </button>
                                </ul>
                              </div>
                    </td>
                </tr>

                                    <div class="modal fade" id="delCliente{{ $banner->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delCliente{{ $banner->id }}Label" aria-hidden="true">
                                      <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="delCliente{{ $banner->id }}Label">Excluir Cliente</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                          </div>
                                          <div class="modal-body">
                                              <h6>Você tem certeza que deseja excluir o banner?</h6>
                                               <image src="/storage/{{ $banner->image }}" style="height:125px;width:auto;">
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <form method="POST" action="{{ route('banner.delete', ['id' => $banner->id]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-primary">Excluir</button>
                                            </form>
                                          </div>
                                        </div>
                                      </div>
                                    </div>

                                   <div class="modal fade" id="modalEditar{{ $banner->id }}" tabindex="-1" aria-labelledby="modalEditarLabel{{ $banner->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered">
                                        <form method="POST" action="{{ route('banner.edit', ['id' => $banner->id]) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Editar Banner</h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" id="title" name="title" placeholder="Digite um titulo" value="{{ $banner->title }}">
                                                        <label for="title">Titulo</label>
                                                        @error('title')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3 col-12">
                                                        <div class="form-floating">
                                                            <textarea class="form-control" id="description"  name="description" placeholder="Digite uma descrição">{{ $banner->description }}</textarea>
                                                            <label for="title">Descrição</label>
                                                            @error('title')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    <div class="col-12 mb-3 mt-3">
                                                        <x-image-upload id="{{ uniqid() }}" name="image" label="Imagem (1280x176)" :value="$banner->image" />
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-success">Salvar alterações</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<form method="POST" action="{{ route('banners.add') }}" enctype="multipart/form-data">
    @csrf
    <!-- Modal -->
    <div class="modal fade" id="addBannerModal" tabindex="-1" aria-labelledby="addBannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="addBannerModalLabel">Adicionar banner</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3 col-12">
                <div class="form-floating">
                    <input type="text" class="form-control" id="title"  name="title" placeholder="Digite um titulo" value="{{ old('title') }}">
                    <label for="title">Titulo</label>
                </div>
                @error('title')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 col-12">
                <div class="form-floating">
                    <textarea class="form-control" id="description"  name="description" placeholder="Digite uma descrição"></textarea>
                    <label for="title">Descrição</label>
                </div>
                @error('title')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 mb-3">
                <x-image-upload id="{{ uniqid() }}" name="image" label="Imagem (1280x176)" :value="NULL" />
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
document.addEventListener("DOMContentLoaded", function() {
    var table = $("#table-banners").DataTable({
        responsive: true,
        ordering: false,
        lengthChange: false,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
            search: ''
        }
    });

    table.on('draw', function() {
        $('#table-banners tbody tr').each(function() {
            $(this).find('td').css('border-bottom', 'none');
        });
    });

    // Garante que o evento draw também seja executado na primeira renderização
    table.draw();
});

  </script>
@endsection
