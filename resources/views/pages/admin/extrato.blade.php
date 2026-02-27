@extends('layouts.app')

@section('title', 'Extrato financeiro')

@section('content')
<style>
.page-extrato { max-width: 1200px; margin: 0 auto; }
.page-extrato .page-title { font-size: 1.35rem; font-weight: 600; color: var(--gateway-sidebar-color, #1a1d21); margin-bottom: 1.25rem; letter-spacing: -0.02em; }
.page-extrato .filters { display: flex; flex-wrap: wrap; gap: .75rem; align-items: flex-end; margin-bottom: 1.5rem; }
.page-extrato .filters label { font-size: .75rem; font-weight: 500; color: var(--body-color); opacity: .85; display: block; margin-bottom: .35rem; }
.page-extrato .filters select, .page-extrato .filters input { border-radius: 8px; border: 1px solid rgba(0,0,0,.1); padding: .5rem .75rem; font-size: .875rem; min-width: 140px; }
.page-extrato .filters .btn { border-radius: 8px; padding: .5rem 1rem; font-size: .875rem; }
.page-extrato .panel { background: var(--gateway-bg-card, #fff); border: 1px solid rgba(0,0,0,.06); border-radius: 12px; overflow: hidden; }
.page-extrato .table-extrato { width: 100%; border-collapse: collapse; font-size: .875rem; }
.page-extrato .table-extrato th { text-align: left; padding: .75rem 1rem; font-weight: 600; color: var(--body-color); opacity: .9; border-bottom: 1px solid rgba(0,0,0,.06); background: rgba(0,0,0,.02); }
.page-extrato .table-extrato td { padding: .75rem 1rem; border-bottom: 1px solid rgba(0,0,0,.05); }
.page-extrato .table-extrato tbody tr { cursor: pointer; transition: background .15s ease; }
.page-extrato .table-extrato tbody tr:hover { background: rgba(0,0,0,.03); }
.page-extrato .badge-entrada { background: rgba(34, 197, 94, 0.12); color: #16a34a; padding: .25rem .5rem; border-radius: 6px; font-size: .75rem; font-weight: 500; }
.page-extrato .badge-saida { background: rgba(220, 53, 69, 0.12); color: #dc3545; padding: .25rem .5rem; border-radius: 6px; font-size: .75rem; font-weight: 500; }
.page-extrato .valor-entrada { color: #16a34a; font-weight: 500; }
.page-extrato .valor-saida { color: #dc3545; font-weight: 500; }
.page-extrato .empty-state { padding: 3rem 1.5rem; text-align: center; color: var(--body-color); opacity: .7; font-size: .9rem; }
.page-extrato .modal-content { border-radius: 12px; border: 1px solid rgba(0,0,0,.08); }
.page-extrato .modal-body .descricao-text { padding: .75rem; background: rgba(0,0,0,.04); border-radius: 8px; font-size: .9rem; }
@media (max-width: 768px) { .page-extrato .filters { flex-direction: column; align-items: stretch; } .page-extrato .table-extrato th:nth-child(n+4), .page-extrato .table-extrato td:nth-child(n+4) { display: none; } }

/* Dark mode - extrato completo */
body.dark-mode .page-extrato .page-title { color: #e2e8f0 !important; }
body.dark-mode .page-extrato .panel { background-color: #0f172a !important; border: 1px solid #1e293b !important; color: #e2e8f0 !important; }
body.dark-mode .page-extrato .filters label { color: #e2e8f0 !important; }
body.dark-mode .page-extrato .filters select,
body.dark-mode .page-extrato .filters input { background-color: #0f172a !important; border: 1px solid #1e293b !important; color: #e2e8f0 !important; }
body.dark-mode .page-extrato .filters .btn-primary { background-color: var(--gateway-primary-color) !important; color: #ffffff !important; }
body.dark-mode .page-extrato .table-extrato th { background-color: #1e293b !important; color: #e2e8f0 !important; border-bottom: 1px solid #334155 !important; }
body.dark-mode .page-extrato .table-extrato td { color: #e2e8f0 !important; border-bottom: 1px solid #1e293b !important; }
body.dark-mode .page-extrato .table-extrato tbody tr:hover { background: #1e293b !important; }
body.dark-mode .page-extrato .empty-state { color: #94a3b8 !important; }
body.dark-mode .page-extrato .modal-content { background-color: #0f172a !important; border: 1px solid #1e293b !important; }
body.dark-mode .page-extrato .modal-header { border-bottom: 1px solid #1e293b !important; }
body.dark-mode .page-extrato .modal-title { color: #e2e8f0 !important; }
body.dark-mode .page-extrato .modal-body .text-muted { color: #94a3b8 !important; }
body.dark-mode .page-extrato .modal-body .descricao-text { background: #1e293b !important; color: #e2e8f0 !important; }
body.dark-mode .page-extrato .badge-entrada { background: rgba(34,197,94,.25); color: #86efac !important; }
body.dark-mode .page-extrato .badge-saida { background: rgba(220,53,69,.25); color: #fca5a5 !important; }
body.dark-mode .page-extrato .valor-entrada { color: #86efac !important; }
body.dark-mode .page-extrato .valor-saida { color: #fca5a5 !important; }
body.dark-mode .page-extrato code { color: #e2e8f0 !important; background: #1e293b !important; }
</style>

<div class="page-extrato">
    <h1 class="page-title">Extrato financeiro</h1>

    <form method="GET" action="{{ route('admin.extrato') }}" id="form-filtro">
        <div class="filters">
            <div>
                <label>Tipo</label>
                <select name="tipo" class="form-select">
                    <option value="todos" {{ $tipo === 'todos' ? 'selected' : '' }}>Todos</option>
                    <option value="entradas" {{ $tipo === 'entradas' ? 'selected' : '' }}>Entradas</option>
                    <option value="saidas" {{ $tipo === 'saidas' ? 'selected' : '' }}>Saídas</option>
                </select>
            </div>
            <div>
                <label>Cliente</label>
                <select name="cliente" class="form-select">
                    <option value="">Todos os clientes</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ $cliente == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Período</label>
                <select name="periodo" id="periodo" class="form-select">
                    <option value="ontem" {{ $periodo === 'ontem' ? 'selected' : '' }}>Ontem</option>
                    <option value="dia" {{ $periodo === 'dia' ? 'selected' : '' }}>Hoje</option>
                    <option value="semana" {{ $periodo === 'semana' ? 'selected' : '' }}>Semana</option>
                    <option value="mes" {{ $periodo === 'mes' ? 'selected' : '' }}>Mês</option>
                    <option value="tudo" {{ $periodo === 'tudo' ? 'selected' : '' }}>Tudo</option>
                    <option value="custom" {{ ($start && $end) ? 'selected' : '' }}>Personalizado</option>
                </select>
            </div>
            <div id="wrap-datas" style="{{ ($start && $end) ? '' : 'display:none;' }}">
                <label>De</label>
                <input type="date" name="start" value="{{ $start ?? '' }}" class="form-control">
            </div>
            <div id="wrap-dataf" style="{{ ($start && $end) ? '' : 'display:none;' }}">
                <label>Até</label>
                <input type="date" name="end" value="{{ $end ?? '' }}" class="form-control">
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>

    <div class="panel">
        @if($movimentos->isEmpty())
            <div class="empty-state">Nenhum movimento encontrado para os filtros selecionados.</div>
        @else
            <table class="table-extrato">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Tipo</th>
                        <th>Cliente</th>
                        <th>Valor</th>
                        <th>Taxa</th>
                        <th>ID Transação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movimentos as $m)
                        <tr data-descricao="{{ e($m->descricao) }}" data-tipo="{{ $m->tipo }}" data-valor="{{ number_format($m->valor, 2, ',', '.') }}" data-data="{{ $m->data->format('d/m/Y H:i') }}" data-cliente="{{ e($m->cliente) }}" data-taxa="{{ isset($m->taxa) ? number_format($m->taxa, 2, ',', '.') : '—' }}" data-liquido="{{ isset($m->liquido) ? number_format($m->liquido, 2, ',', '.') : '—' }}">
                            <td>{{ $m->data->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($m->tipo === 'entrada')
                                    <span class="badge-entrada">Entrada</span>
                                @else
                                    <span class="badge-saida">Saída</span>
                                @endif
                            </td>
                            <td>{{ $m->cliente }}</td>
                            <td class="{{ $m->tipo === 'entrada' ? 'valor-entrada' : 'valor-saida' }}">
                                {{ $m->tipo === 'entrada' ? '+' : '-' }} R$ {{ number_format($m->valor, 2, ',', '.') }}
                            </td>
                            <td>
                                @if($m->tipo === 'entrada' && isset($m->taxa))
                                    @if((float)$m->taxa > 0)
                                        R$ {{ number_format($m->taxa, 2, ',', '.') }}
                                    @else
                                        <span class="text-muted">Isenção</span>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td><code class="small">{{ $m->idTransaction }}</code></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

{{-- Modal descrição --}}
<div class="modal fade" id="modalDescricao" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalhe do movimento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2 small text-muted"><span id="md-data"></span> · <span id="md-tipo"></span> · <span id="md-valor"></span></p>
                <p class="mb-1 small"><strong>Cliente:</strong> <span id="md-cliente"></span></p>
                <div id="md-taxa-block" class="mb-1 small" style="display:none;"><strong>Valor bruto:</strong> <span id="md-valor-bruto"></span> · <strong>Taxa:</strong> <span id="md-taxa"></span> · <strong>Valor líquido:</strong> <span id="md-liquido"></span></div>
                <p class="mb-0"><strong>Descrição:</strong></p>
                <div class="descricao-text mt-1" id="md-descricao">—</div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var modal = new bootstrap.Modal(document.getElementById('modalDescricao'));
    document.querySelectorAll('.table-extrato tbody tr').forEach(function(tr) {
        tr.addEventListener('click', function() {
            document.getElementById('md-data').textContent = this.dataset.data || '—';
            document.getElementById('md-tipo').textContent = this.dataset.tipo === 'entrada' ? 'Entrada' : 'Saída';
            document.getElementById('md-valor').textContent = (this.dataset.tipo === 'entrada' ? '+' : '-') + ' R$ ' + (this.dataset.valor || '0,00');
            document.getElementById('md-cliente').textContent = this.dataset.cliente || '—';
            document.getElementById('md-descricao').textContent = this.dataset.descricao || '—';
            var taxaBlock = document.getElementById('md-taxa-block');
            if (this.dataset.tipo === 'entrada' && (this.dataset.taxa !== undefined && this.dataset.taxa !== '—')) {
                taxaBlock.style.display = 'block';
                document.getElementById('md-valor-bruto').textContent = 'R$ ' + (this.dataset.valor || '0,00');
                document.getElementById('md-taxa').textContent = this.dataset.taxa === '0,00' ? 'Isenção' : 'R$ ' + this.dataset.taxa;
                document.getElementById('md-liquido').textContent = 'R$ ' + (this.dataset.liquido || this.dataset.valor || '0,00');
            } else {
                taxaBlock.style.display = 'none';
            }
            modal.show();
        });
    });

    var sel = document.getElementById('periodo');
    var wrapDe = document.getElementById('wrap-datas');
    var wrapAte = document.getElementById('wrap-dataf');
    if (sel) {
        sel.addEventListener('change', function() {
            var show = this.value === 'custom';
            if (wrapDe) wrapDe.style.display = show ? 'block' : 'none';
            if (wrapAte) wrapAte.style.display = show ? 'block' : 'none';
        });
    }
})();
</script>
@endsection
