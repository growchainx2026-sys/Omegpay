@extends('layouts.app')

@section('title', 'Saques solicitados')

@section('content')
<style>
    /* estilos específicos da página (sem sobrescrever z-index global dos modais) */
</style>
    <div class="header mb-3">
        <h3 class="header-title">
            Saques solicitados
        </h3>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="card-title">Solicitações pendentes</h6>
        </div>
        <div class="card-body">
            <table class="table" style="width:100%;" id="table-admin-aprovar-saques">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data</th>
                        <th>Cliente</th>
                        <th>Valor Solicitado</th>
                        <th>Taxas</th>
                        <th>Valor Liquido</th>
                        <th>Nome do recebedor</th>
                        <th>CPF do recebedor</th>
                        <th>Chave</th>
                        <th>Tipo de chave</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($saques as $saque)
                        <tr>
                            <td>{{ $saque->id }}</td>
                            <td>{{ $saque->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $saque->user->name }}</td>
                            <td>R$ {{ number_format($saque->amount, '2', ',', '.') }}</td>
                            <td>R$ {{ number_format($saque->taxa_cash_out, '2', ',', '.') }}</td>
                            <td>R$ {{ number_format($saque->cash_out_liquido, '2', ',', '.') }}</td>
                            <td>{{ $saque->recebedor_name }}</td>
                            <td>{{ $saque->recebedor_cpf}}</td>
                            <td>{{ $saque->pixKey}}</td>
                            <td>{{ $saque->pixKeyType}}</td>
                            <td>
                                @php
                                    $setting = App\Models\Setting::first();
                                    // Normaliza o valor do adquirente (lowercase e remove espaços)
                                    $adquirencia = strtolower(trim($setting->adquirencia ?? ''));
                                    
                                    // Lista de adquirentes que NÃO suportam cash_out automatizado
                                    $adquirentesSemCashOut = ['pagarme', 'pagar.me'];
                                    
                                    // Verifica se o adquirente atual está na lista sem suporte
                                    $temCashOut = !in_array($adquirencia, $adquirentesSemCashOut);
                                @endphp
                                
                                <div class="btn-group">
                                    @if($temCashOut)
                                        {{-- Adquirente COM suporte a cash_out automatizado (efi, cashtime, sixxpayments, witetec) --}}
                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#aprovar{{ $saque->id }}">
                                            <i class="fa-solid fa-check"></i>&nbsp;Aprovar
                                        </button>
                                    @else
                                        {{-- Adquirente SEM suporte a cash_out automatizado (pagarme) --}}
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#aprovarManual{{ $saque->id }}">
                                            <i class="fa-solid fa-hand"></i>&nbsp;Aprovar
                                        </button>
                                    @endif
                                    
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#recusar{{ $saque->id }}">
                                        <i class="fa-solid fa-xmark"></i>&nbsp;Recusar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('modals')
    @foreach ($saques as $saque)
        @php
            $setting = App\Models\Setting::first();
            $adquirencia = strtolower(trim($setting->adquirencia ?? ''));
            $adquirentesSemCashOut = ['pagarme', 'pagar.me'];
            $temCashOut = !in_array($adquirencia, $adquirentesSemCashOut);
        @endphp

        @if($temCashOut)
            {{-- Modal de Aprovação Automática (para efi, cashtime, sixxpayments, witetec) --}}
            <div class="modal fade" id="aprovar{{ $saque->id }}" tabindex="-1" aria-labelledby="aprovar{{ $saque->id }}Label"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('admin.aprovar.saques.aprovar') }}">
                            @csrf
                            <input hidden name="id" value="{{ $saque->id }}">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="aprovar{{ $saque->id }}Label">
                                    Aprovar saque (Automático)
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Você tem certeza que deseja aprovar o saque de <strong>R$
                                        {{ number_format($saque->amount, '2', ',', '.') }}</strong> do
                                    cliente <strong>{{ $saque->user->name }}</strong>?</p>
                                <p><strong>Nome do recebedor:</strong> {{ $saque->recebedor_name }}</p>
                                <p><strong>CPF do recebedor:</strong> {{ $saque->recebedor_cpf }}</p>
                                <p><strong>Chave PIX:</strong> {{ $saque->pixKey }}</p>
                                <div class="alert alert-info mt-3">
                                    <i class="fa-solid fa-info-circle"></i> Este saque será processado automaticamente pelo adquirente <strong>{{ strtoupper($adquirencia) }}</strong>.
                                </div>
                                
                                <div class="mb-3 mt-4">
                                    <label for="master_password_auto_{{ $saque->id }}" class="form-label">
                                        <i class="fa-solid fa-lock me-1"></i> <strong>Senha Master</strong>
                                    </label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="master_password_auto_{{ $saque->id }}" 
                                           name="master_password" 
                                           placeholder="Digite a senha master para confirmar"
                                           required>
                                    <small class="text-muted">Esta ação requer autenticação de segurança</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Aprovar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            {{-- Modal de Aprovação Manual (para pagarme) --}}
            <div class="modal fade" id="aprovarManual{{ $saque->id }}" tabindex="-1" aria-labelledby="aprovarManual{{ $saque->id }}Label"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('admin.aprovar.saques.manual') }}">
                            @csrf
                            <input hidden name="id" value="{{ $saque->id }}">
                            <div class="modal-header bg-warning">
                                <h1 class="modal-title fs-5" id="aprovarManual{{ $saque->id }}Label">
                                    <i class="fa-solid fa-hand"></i> Aprovação Manual
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning">
                                    <i class="fa-solid fa-exclamation-triangle"></i> 
                                    <strong>ATENÇÃO:</strong> O adquirente <strong>{{ strtoupper($adquirencia) }}</strong> não suporta cash_out automatizado.
                                </div>
                                <p>Você tem certeza que deseja aprovar MANUALMENTE o saque de <strong>R$
                                        {{ number_format($saque->amount, '2', ',', '.') }}</strong> do
                                    cliente <strong>{{ $saque->user->name }}</strong>?</p>
                                <p><strong>Nome do recebedor:</strong> {{ $saque->recebedor_name }}</p>
                                <p><strong>CPF do recebedor:</strong> {{ $saque->recebedor_cpf }}</p>
                                <p><strong>Chave PIX:</strong> {{ $saque->pixKey }}</p>
                                <div class="alert alert-info mt-3">
                                    <strong>Importante:</strong> Após confirmar, o status será alterado para "pago" no sistema, mas você precisará realizar o pagamento manualmente através do painel do adquirente.
                                </div>
                                
                                <div class="mb-3 mt-4">
                                    <label for="master_password_manual_{{ $saque->id }}" class="form-label">
                                        <i class="fa-solid fa-lock me-1"></i> <strong>Senha Master</strong>
                                    </label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="master_password_manual_{{ $saque->id }}" 
                                           name="master_password" 
                                           placeholder="Digite a senha master para confirmar"
                                           required>
                                    <small class="text-muted">Esta ação requer autenticação de segurança</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-warning">Confirmar Aprovação Manual</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
        
        {{-- Modal de Recusa (sempre disponível para qualquer adquirente) --}}
        <div class="modal fade" id="recusar{{ $saque->id }}" tabindex="-1" aria-labelledby="recusar{{ $saque->id }}Label"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.aprovar.saques.rejeitar') }}">
                        @csrf
                        <input hidden name="id" value="{{ $saque->id }}">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="recusar{{ $saque->id }}Label">Recusar saque</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Você tem certeza que deseja recusar o saque de <strong>R$
                                    {{ number_format($saque->amount, '2', ',', '.') }}</strong> do
                                cliente <strong>{{ $saque->user->name }}</strong>?</p>
                            <p><strong>Nome do recebedor:</strong> {{ $saque->recebedor_name }}</p>
                            <p><strong>CPF do recebedor:</strong> {{ $saque->recebedor_cpf }}</p>
                            <p><strong>Chave PIX:</strong> {{ $saque->pixKey }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Recusar</button>
                        </div>
                    </form>
                </div>
        </div>
    </div>
    @endforeach
    @endpush

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var table = $("#table-admin-aprovar-saques").DataTable({
                responsive: true,
                ordering: false,
                lengthChange: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                    search: ''
                }
            });

            table.on('draw', function () {
                $('#table-admin-aprovar-saques tbody tr').each(function () {
                    $(this).find('td').css('border-bottom', 'none');
                });
            });

            // Garante que o evento draw também seja executado na primeira renderização
            table.draw();
        });
    </script>
@endsection