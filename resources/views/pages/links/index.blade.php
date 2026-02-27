@extends('layouts.app')

@section('title', 'Links de pagamento')

@section('content')
    <div class="header mb-3" style="display:flex;align-items:center;justify-content:space-between;">
        <h1 class="header-title">
            Links de pagamento
            <br/>
            <small class="text-muted mt-n2" style="font-size: 10px;font-weight:normal;">Boleto e cartão utiliza apenas a adquirente pagar.me</small>
        </h1>
        {{-- Botão Adicionar que abre a modal --}}
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLinkModal">
            <i class="fa-solid fa-plus"></i>&nbsp;Adicionar
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table" style="width:100%;" id="table-links-pagamento">
                <thead>
                    <tr>
                        <th>Valor</th>
                        <th>Meios</th>
                        <th>Descrição</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (auth()->user()->links as $link)
                        <tr>
                            <td>
                                @if (floatval($link->valor) == 0)
                                    <span class="badge bg-info py-1">Qualquer</span>
                                @else
                                    {{ 'R$ '.number_format($link->valor, 2, ',', '.') }}
                                @endif
                            </td>
                            <td>
                                @php
                                    $lbls = ['pix' => 'PIX', 'billet' => 'BOLETO', 'card' => 'CARTÃO'];
                                    $meios = $link->meios ?? [];
                                @endphp
                                <div class="d-flex align-item-center gap-2">
                                    {{-- Exibe os meios de pagamento como badges --}}
                                    @foreach ($meios as $key => $meio)
                                        <span class="badge bg-success py-1">{{ $lbls[$meio] }}</span>
                                    @endforeach
                                </div>
                            </td>
                            
                            <td>
                                <span class="text-truncated">{{ $link->descricao }}</span>
                            </td>
                            <td class="d-flex align-item-center justify-content-end">
                                {{-- BOTÕES DE AÇÕES --}}
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-info text-white btn-edit-link" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#edit-link-modal-{{ $link->id }}">
                                        Editar
                                    </button>
                                    <button class="btn btn-sm btn-danger btn-delete-link" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#delete-link-modal-{{ $link->id }}">
                                        Excluir
                                    </button>

                                    <a class="btn btn-sm btn-primary" href="{{ route('links.payment', ['id' => $link->codigo]) }}" target="_blank">
                                        Ver
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if(session('modal'))
        <script>
            document.addEventListener('DOMContentLoaded', function(){
                let button = document.querySelector(`[data-bs-target="#{{ session('modal') }}"]`);
                if(button){
                    button.click()
                }
            })
        </script>
    @endif

{{-- ================================================================= --}}
{{-- 1. MODAL ADICIONAR LINK DE PAGAMENTO --}}
{{-- ================================================================= --}}
<div class="modal fade" id="addLinkModal" tabindex="-1" aria-labelledby="addLinkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLinkModalLabel">Adicionar Novo Link de Pagamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.links.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="addValor" class="form-label">Valor (R$)</label>
                        <input type="text" step="0.01" class="form-control money-input" id="addValor" name="valor" required>      
                        <small class="text-muted mt-1">Se definido como 0, receberá qualquer valor.</small>
                    </div>
                    <div class="mb-3">
                        <label for="addDescricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="addDescricao" name="descricao" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label d-block">Meios de Pagamento</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="addMeioPix" name="meios[]" value="pix">
                            <label class="form-check-label" for="addMeioPix">PIX</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="addMeioBoleto" name="meios[]" value="billet">
                            <label class="form-check-label" for="addMeioBoleto">Boleto</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="addMeioCartao" name="meios[]" value="card">
                            <label class="form-check-label" for="addMeioCartao">Cartão</label>
                        </div>
                    </div>
                    {{-- Código e Status são gerados automaticamente na criação, mas mantive o campo para fins de demonstração --}}
                    <input type="hidden" name="status" value="gerado">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar Link</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach (auth()->user()->links as $link)
{{-- ================================================================= --}}
{{-- 2. MODAL EDITAR LINK DE PAGAMENTO --}}
{{-- ================================================================= --}}
<div class="modal fade" id="edit-link-modal-{{ $link->id }}" tabindex="-1" aria-labelledby="edit-link-modal-{{ $link->id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-link-modal-{{ $link->id }}Label">Editar Link de Pagamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- A action será atualizada via JS para incluir o ID do link --}}
            <form action="{{ route('user.links.edit', ['id' => $link->id]) }}" method="POST">
                @csrf
                <div class="modal-body">                    
                    <div class="mb-3">
                        <label for="editCodigo" class="form-label">Código</label>
                        <input type="text" class="form-control" name="codigo" value="{{ $link->codigo }}" readonly>
                        @error('codigo')
                            <small class="text-danger mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="editValor" class="form-label">Valor (R$)</label>
                        <input type="text" step="0.01" class="form-control money-input" name="valor" value="{{ number_format($link->valor, '2' ,',', '.') }}" required>
                        @error('valor')
                            <small class="text-danger mt-1">{{ $message }}</small>
                        @enderror
                        <small class="text-muted mt-1">Se definido como 0, receberá qualquer valor.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editDescricao" class="form-label">Descrição</label>
                        <textarea class="form-control" name="descricao" rows="3" required>{{ $link->descricao }}</textarea>
                        @error('descricao')
                            <small class="text-danger mt-1">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label d-block">Meios de Pagamento</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="meios[]" value="pix" @checked(in_array('pix', $link->meios ?? [], true))>
                            <label class="form-check-label" for="editMeioPix">PIX</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="meios[]" value="billet" @checked(in_array('billet', $link->meios ?? [], true))>
                            <label class="form-check-label" for="editMeioBoleto">Boleto</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="meios[]" value="card" @checked(in_array('card', $link->meios ?? [], true))>
                            <label class="form-check-label" for="editMeioCartao">Cartão</label>
                        </div>
                    </div>
                    @error('meios')
                        <small class="text-danger mt-n2">{{ $message }}</small>
                    @enderror
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ================================================================= --}}
{{-- 3. MODAL EXCLUIR LINK DE PAGAMENTO --}}
{{-- ================================================================= --}}
<div class="modal fade" id="delete-link-modal-{{ $link->id }}" tabindex="-1" aria-labelledby="delete-link-modal-{{ $link->id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delete-link-modal-{{ $link->id }}Label">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.links.delete', ['id' => $link->id]) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Tem certeza de que deseja excluir o link de pagamento?</p>
                    <p class="text-danger">Esta ação é irreversível.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Sim, Excluir</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
{{-- ================================================================= --}}
{{-- JAVASCRIPT PARA POPULAR MODAIS --}}
{{-- ================================================================= --}}

<script>
    $(document).ready(function () {
            $('.money-input').inputmask('decimal', {
                radixPoint: ',', // separador decimal
                groupSeparator: '.', // separador de milhar
                digits: 2, // duas casas decimais
                autoGroup: true,
                rightAlign: true,
                prefix: 'R$ ', // sem prefixo R$
                removeMaskOnSubmit: true // remove máscara ao enviar o formulário
            });
        });

    document.addEventListener("DOMContentLoaded", function () {
            var table = $("#table-links-pagamento").DataTable({
                responsive: true,
                ordering: false,
                lengthChange: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                    search: ''
                }
            });

            table.on('draw', function () {
                $('#table-links-pagamento tbody tr').each(function () {
                    $(this).find('td').css('border-bottom', 'none');
                });
            });

            // Garante que o evento draw também seja executado na primeira renderização
            table.draw();
        });
</script>
@endsection