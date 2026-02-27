@extends('layouts.app')

@section('title', 'Balanceamento de saldo')

@section('content')
<style>
.page-balance { max-width: 960px; margin: 0 auto; }
/* Modo claro: títulos e textos sempre escuros para ler no fundo branco */
.page-balance .page-title { font-size: 1.35rem; font-weight: 600; color: #1a1d21 !important; margin-bottom: 1.5rem; letter-spacing: -0.02em; }
.page-balance .panel { background: #fff; border: 1px solid rgba(0,0,0,.06); border-radius: 12px; padding: 1.5rem; transition: border-color .2s ease, background .2s ease; }
.page-balance .panel:hover { border-color: rgba(0,0,0,.1); }
.page-balance .panel-title { font-size: .95rem; font-weight: 600; color: #1a1d21 !important; margin-bottom: .25rem; }
.page-balance .panel-sub { font-size: .8rem; color: #374151 !important; opacity: .9; margin-bottom: 1.25rem; }
.page-balance label { font-size: .8rem; font-weight: 500; color: #374151 !important; display: block; margin-bottom: .4rem; }
body:not(.dark-mode) .page-balance .page-title,
body:not(.dark-mode) .page-balance .panel-title,
body:not(.dark-mode) .page-balance .panel-sub,
body:not(.dark-mode) .page-balance label { color: #1a1d21 !important; }
body:not(.dark-mode) .page-balance .panel-sub { color: #4b5563 !important; }
body:not(.dark-mode) .page-balance label { color: #374151 !important; }
.page-balance .form-control, .page-balance .form-select { border-radius: 8px; border: 1px solid rgba(0,0,0,.1); padding: .6rem .85rem; font-size: .9rem; transition: border-color .2s ease; background-color: #fff; color: #1a1d21; }
.page-balance .form-control:focus, .page-balance .form-select:focus { border-color: var(--gateway-primary-color, #6366f1); outline: none; }
.page-balance .form-control::placeholder { opacity: .6; }
.page-balance .btn-balance { border-radius: 8px; padding: .6rem 1.25rem; font-size: .875rem; font-weight: 500; transition: opacity .2s ease, transform .02s ease; border: none; }
.page-balance .btn-balance:hover { opacity: .92; }
.page-balance .btn-balance:active { transform: scale(0.98); }
.page-balance .btn-entrada { background: #4f46e5 !important; color: #ffffff !important; border: none !important; }
.page-balance .btn-entrada:hover { color: #ffffff !important; }
.page-balance .btn-saida { background: #dc3545 !important; color: #ffffff !important; border: none !important; }
.page-balance .btn-saida:hover { color: #ffffff !important; }
.page-balance button.btn-balance { color: #ffffff !important; }
.page-balance .modal-content { border: 1px solid rgba(0,0,0,.08); border-radius: 12px; }
.page-balance .modal-header { border-bottom: 1px solid rgba(0,0,0,.06); padding: 1rem 1.25rem; }
.page-balance .modal-body { padding: 1.25rem; }
.page-balance .modal-footer { border-top: 1px solid rgba(0,0,0,.06); padding: 1rem 1.25rem; }
.page-balance .alert { border-radius: 8px; }
@media (max-width: 768px) { .page-balance .row-cols-md-2 > * { flex: 0 0 100%; max-width: 100%; } }

/* Dark mode - cores fixas para não herdar tema claro */
body.dark-mode .page-balance .page-title { color: #e2e8f0 !important; }
body.dark-mode .page-balance .panel { background-color: #0f172a !important; border: 1px solid #1e293b !important; color: #e2e8f0 !important; }
body.dark-mode .page-balance .panel:hover { border-color: #334155 !important; }
body.dark-mode .page-balance .panel-title { color: #e2e8f0 !important; }
body.dark-mode .page-balance .panel-sub { color: #94a3b8 !important; }
body.dark-mode .page-balance label { color: #e2e8f0 !important; }
body.dark-mode .page-balance .form-control,
body.dark-mode .page-balance .form-select { background-color: #0f172a !important; border: 1px solid #1e293b !important; color: #e2e8f0 !important; }
body.dark-mode .page-balance .form-control:focus,
body.dark-mode .page-balance .form-select:focus { border-color: var(--gateway-primary-color) !important; }
body.dark-mode .page-balance .btn-entrada,
body.dark-mode .page-balance button.btn-entrada { background-color: #4f46e5 !important; color: #ffffff !important; border: none !important; }
body.dark-mode .page-balance .btn-entrada:hover,
body.dark-mode .page-balance button.btn-entrada:hover { color: #ffffff !important; background-color: #6366f1 !important; }
body.dark-mode .page-balance .btn-saida,
body.dark-mode .page-balance button.btn-saida { background-color: #dc3545 !important; color: #ffffff !important; border: none !important; }
body.dark-mode .page-balance .btn-saida:hover,
body.dark-mode .page-balance button.btn-saida:hover { color: #ffffff !important; }
body.dark-mode .page-balance .modal-content { background-color: #0f172a !important; border: 1px solid #1e293b !important; }
body.dark-mode .page-balance .modal-header { border-bottom: 1px solid #1e293b !important; }
body.dark-mode .page-balance .modal-title { color: #e2e8f0 !important; }
body.dark-mode .page-balance .modal-body .text-muted { color: #94a3b8 !important; }
body.dark-mode .page-balance .modal-footer { border-top: 1px solid #1e293b !important; }
body.dark-mode .page-balance .modal-body .form-control { background-color: #1e293b !important; color: #e2e8f0 !important; border: 1px solid #334155 !important; }
body.dark-mode .page-balance .btn-secondary { background: #1e293b !important; border: 1px solid #334155 !important; color: #e2e8f0 !important; }
body.dark-mode .page-balance .btn-primary { color: #ffffff !important; }
body.dark-mode .page-balance .alert-success { background: rgba(34,197,94,.2); color: #86efac; border-color: rgba(34,197,94,.3); }
body.dark-mode .page-balance .alert-danger { background: rgba(220,53,69,.2); color: #fca5a5; border-color: rgba(220,53,69,.3); }
</style>

<div class="page-balance">
    <h1 class="page-title">Balanceamento de saldo</h1>

    @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mb-3">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="row row-cols-1 row-cols-md-2 g-4">
        <div class="col">
            <div class="panel h-100">
                <div class="panel-title">Adicionar saldo</div>
                <div class="panel-sub">Entrada manual na carteira do cliente</div>
                <form id="form-entrada" action="{{ route('admin.balance.addentrada') }}" method="POST">
                    @csrf
                    <input type="hidden" name="master_password" id="master_password_entrada" value="">
                    <div class="mb-3">
                        <label for="client_id">Cliente</label>
                        <select name="deposito_id" id="client_id" class="form-select" required>
                            <option value="">Selecione um cliente</option>
                            @foreach($users as $client)
                                <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount">Valor (R$)</label>
                        <input type="number" name="deposito_amount" id="amount" class="form-control" step="0.01" min="0.01" placeholder="0,00" required>
                    </div>
                    <div class="mb-3">
                        <label for="deposito_taxa">Taxa (R$)</label>
                        <input type="number" name="deposito_taxa" id="deposito_taxa" class="form-control" step="0.01" min="0" value="0" placeholder="0 para isenção">
                        <small class="text-muted">Use 0 para isenção de taxa. O valor líquido creditado será: valor − taxa.</small>
                    </div>
                    <div class="mb-3">
                        <label for="deposito_descricao">Descrição</label>
                        <input type="text" name="deposito_descricao" id="deposito_descricao" class="form-control" placeholder="Ex: Ajuste de cortesia, estorno, bônus..." required maxlength="191">
                    </div>
                    <button type="submit" class="btn btn-balance btn-entrada" id="btn-entrada" style="background-color:#4f46e5;color:#ffffff;">Adicionar saldo</button>
                </form>
            </div>
        </div>

        <div class="col">
            <div class="panel h-100">
                <div class="panel-title">Remover saldo</div>
                <div class="panel-sub">Saída manual da carteira do cliente</div>
                <form id="form-saida" action="{{ route('admin.balance.addsaida') }}" method="POST">
                    @csrf
                    <input type="hidden" name="master_password" id="master_password_saida" value="">
                    <div class="mb-3">
                        <label for="client_id_withdraw">Cliente</label>
                        <select name="saque_id" id="client_id_withdraw" class="form-select" required>
                            <option value="">Selecione um cliente</option>
                            @foreach($users as $client)
                                <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount_withdraw">Valor (R$)</label>
                        <input type="number" name="saque_amount" id="amount_withdraw" class="form-control" step="0.01" min="0.01" placeholder="0,00" required>
                    </div>
                    <div class="mb-3">
                        <label for="saque_descricao">Descrição</label>
                        <input type="text" name="saque_descricao" id="saque_descricao" class="form-control" placeholder="Ex: Ajuste, correção, cobrança..." required maxlength="191">
                    </div>
                    <button type="submit" class="btn btn-balance btn-saida" id="btn-saida" style="background-color:#dc3545;color:#ffffff;">Remover saldo</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal confirmação senha mestre --}}
<div class="modal fade" id="modalSenhaMestre" tabindex="-1" aria-labelledby="modalSenhaMestreLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSenhaMestreLabel">Confirmar ação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-2">Digite a senha mestre para confirmar esta alteração de saldo.</p>
                <input type="password" class="form-control" id="inputSenhaMestre" placeholder="Senha mestre" autocomplete="off">
                <div id="senhaErro" class="text-danger small mt-2" style="display:none;">Senha incorreta.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnConfirmarSenha">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var modalEl = document.getElementById('modalSenhaMestre');
    var formEntrada = document.getElementById('form-entrada');
    var formSaida = document.getElementById('form-saida');
    if (!modalEl || !formEntrada || !formSaida) return;

    var modal = new bootstrap.Modal(modalEl);
    var inputSenha = document.getElementById('inputSenhaMestre');
    var senhaErro = document.getElementById('senhaErro');
    var formPending = null;

    function openModal(form) {
        formPending = form;
        if (inputSenha) inputSenha.value = '';
        if (senhaErro) { senhaErro.style.display = 'none'; senhaErro.textContent = ''; }
        modal.show();
        setTimeout(function() { if (inputSenha) inputSenha.focus(); }, 300);
    }

    formEntrada.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (!this.checkValidity()) { this.reportValidity(); return; }
        openModal(this);
        return false;
    });

    formSaida.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (!this.checkValidity()) { this.reportValidity(); return; }
        openModal(this);
        return false;
    });

    document.getElementById('btnConfirmarSenha').addEventListener('click', function() {
        var senha = inputSenha ? inputSenha.value.trim() : '';
        if (!senha) {
            if (senhaErro) { senhaErro.textContent = 'Informe a senha.'; senhaErro.style.display = 'block'; }
            return;
        }
        if (formPending) {
            var hidden = formPending.querySelector('input[name="master_password"]');
            if (hidden) hidden.value = senha;
            modal.hide();
            formPending.submit();
        }
    });

    modalEl.addEventListener('hidden.bs.modal', function() {
        formPending = null;
        if (inputSenha) inputSenha.value = '';
        if (senhaErro) senhaErro.style.display = 'none';
    });
});
</script>
@endsection
