@php
    $alunos = \App\Models\Pedido::where('produto_id', $produto->id)
        ->where('status', 'pago')
        ->with('aluno')
        ->get()
        ->pluck('aluno')
        ->unique('id')
        ->filter();
    
    $totalAlunos = $alunos->count();
    $alunosComProgresso = 0;
    $progressoMedio = 0;
    
    foreach ($alunos as $aluno) {
        $progresso = $aluno->progressoProduto($produto->id);
        if ($progresso > 0) {
            $alunosComProgresso++;
            $progressoMedio += $progresso;
        }
    }
    
    $progressoMedio = $totalAlunos > 0 ? round($progressoMedio / $totalAlunos, 1) : 0;
@endphp

<style>
/* Área de Membros - Tela de Alunos - Design Clean e Minimalista */
.alunos-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
}

.alunos-stat-card {
    background: #fafafa;
    border: 1px solid #f0f0f0;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    transition: all 0.2s ease;
}

.alunos-stat-card:hover {
    border-color: rgba(11, 104, 86, 0.3);
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
}

.alunos-stat-value {
    font-size: 32px;
    font-weight: 700;
    color: var(--gateway-primary-color, #0b6856);
    line-height: 1.2;
}

.alunos-stat-label {
    font-size: 13px;
    color: #6b7280;
    margin-top: 4px;
}

/* Lista de alunos - cards responsivos */
.alunos-list-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.alunos-list-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.alunos-search-wrap {
    position: relative;
    flex: 1;
    min-width: 200px;
    max-width: 320px;
}

.alunos-search-wrap input {
    width: 100%;
    padding: 10px 16px 10px 40px;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    font-size: 14px;
    background: #fff;
    transition: all 0.2s;
}

.alunos-search-wrap input:focus {
    outline: none;
    border-color: var(--gateway-primary-color, #0b6856);
    box-shadow: 0 0 0 3px rgba(11, 104, 86, 0.12);
}

.alunos-search-wrap i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 14px;
}

.aluno-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    transition: all 0.2s ease;
}

.aluno-card:hover {
    border-color: #d1d5db;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.aluno-card-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

.aluno-card-initial {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 18px;
    color: #fff;
    background: var(--gateway-primary-color, #0b6856);
    flex-shrink: 0;
}

.aluno-card-main {
    flex: 1;
    min-width: 0;
}

.aluno-card-name {
    font-weight: 600;
    font-size: 15px;
    color: #111827;
    margin-bottom: 2px;
}

.aluno-card-email {
    font-size: 13px;
    color: #6b7280;
}

.aluno-card-meta {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.aluno-card-progress-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 120px;
}

.aluno-card-progress-bar {
    flex: 1;
    height: 6px;
    background: #e5e7eb;
    border-radius: 3px;
    overflow: hidden;
    max-width: 80px;
}

.aluno-card-progress-fill {
    height: 100%;
    border-radius: 3px;
    background: var(--gateway-primary-color, #0b6856);
    transition: width 0.3s ease;
}

.aluno-card-progress-pct {
    font-size: 12px;
    font-weight: 600;
    color: #374151;
    min-width: 36px;
}

.aluno-card-badge {
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 20px;
    font-weight: 500;
}

.aluno-card-badge-success {
    background: #d1fae5;
    color: #065f46;
}

.aluno-card-badge-info {
    background: #dbeafe;
    color: #1e40af;
}

.aluno-card-actions {
    flex-shrink: 0;
}

.aluno-card-btn {
    padding: 8px 16px;
    font-size: 13px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    background: #fff;
    color: #374151;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.aluno-card-btn:hover {
    background: var(--gateway-primary-color, #0b6856);
    color: #fff;
    border-color: var(--gateway-primary-color, #0b6856);
}

.alunos-empty {
    text-align: center;
    padding: 48px 24px;
}

.alunos-empty-icon {
    font-size: 48px;
    opacity: 0.2;
    margin-bottom: 16px;
    color: var(--gateway-primary-color, #0b6856);
}

.alunos-empty-text {
    color: #6b7280;
    font-size: 15px;
}

/* Modal de detalhes - layout compacto: avatar à esquerda, info à direita */
#detalhesAlunoModal .modal-content {
    border-radius: 12px;
    overflow: hidden;
}

#detalhesAlunoModal .modal-body {
    max-height: 75vh;
    overflow-y: auto;
    border-radius: 0 0 12px 12px;
}

.aluno-modal-content {
    padding: 4px 0;
}

/* Layout horizontal: avatar + ring à esquerda, info à direita */
.aluno-modal-top-row {
    display: flex;
    align-items: flex-start;
    gap: 24px;
    padding-bottom: 20px;
}

/* Circular progress avatar - esquerda */
.aluno-modal-avatar-col {
    flex-shrink: 0;
}

.aluno-avatar-ring {
    position: relative;
    width: 88px;
    height: 88px;
}

.aluno-avatar-ring-svg {
    transform: rotate(-90deg);
    width: 100%;
    height: 100%;
}

.aluno-avatar-ring-bg {
    fill: none;
    stroke: #e5e7eb;
    stroke-width: 4;
}

.aluno-avatar-ring-progress {
    fill: none;
    stroke: var(--gateway-primary-color, #0b6856);
    stroke-width: 4;
    stroke-linecap: round;
    transition: stroke-dasharray 0.5s ease;
}

.aluno-avatar-inner {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 72px;
    height: 72px;
    border-radius: 50%;
    overflow: hidden;
    background: #f3f4f6;
}

.aluno-avatar-inner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.aluno-avatar-inner-initial {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: 700;
    color: #fff;
    background: var(--gateway-primary-color, #0b6856);
}

/* Info à direita */
.aluno-modal-info-col {
    flex: 1;
    min-width: 0;
}

.aluno-modal-name {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 2px;
}

.aluno-modal-email {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 14px;
}

.aluno-modal-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
}

.aluno-modal-stat {
    text-align: center;
    padding: 10px 8px;
    background: #f9fafb;
    border-radius: 8px;
    border: 1px solid #f0f0f0;
}

.aluno-modal-stat-value {
    font-size: 15px;
    font-weight: 700;
    color: #111827;
    display: block;
}

.aluno-modal-stat-label {
    font-size: 10px;
    color: #6b7280;
    margin-top: 2px;
}

.aluno-modal-section-title {
    font-size: 12px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 10px;
}

.aluno-modal-modulos {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.aluno-modal-modulo-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    background: #f9fafb;
    border-radius: 8px;
    border: 1px solid #f0f0f0;
}

.aluno-modal-modulo-name {
    flex: 1;
    font-size: 13px;
    font-weight: 500;
    color: #111827;
}

.aluno-modal-modulo-progress {
    flex: 0 0 70px;
    height: 5px;
    background: #e5e7eb;
    border-radius: 3px;
    overflow: hidden;
}

.aluno-modal-modulo-fill {
    height: 100%;
    background: var(--gateway-primary-color, #0b6856);
    border-radius: 3px;
}

.aluno-modal-modulo-pct {
    font-size: 11px;
    font-weight: 600;
    color: #374151;
    min-width: 32px;
}

/* Dark Mode */
body.dark-mode .alunos-stat-card {
    background: #1e293b;
    border-color: #334155;
}

body.dark-mode .alunos-stat-card:hover {
    border-color: rgba(34, 197, 94, 0.4);
}

body.dark-mode .alunos-stat-value {
    color: var(--gateway-primary-color, #22c55e);
}

body.dark-mode .alunos-stat-label {
    color: #94a3b8;
}

body.dark-mode .alunos-search-wrap input {
    background: #1e293b;
    border-color: #334155;
    color: #e2e8f0;
}

body.dark-mode .alunos-search-wrap input::placeholder {
    color: #64748b;
}

body.dark-mode .alunos-search-wrap i {
    color: #64748b;
}

body.dark-mode .aluno-card {
    background: #1e293b;
    border-color: #334155;
}

body.dark-mode .aluno-card:hover {
    border-color: #475569;
}

body.dark-mode .aluno-card-name {
    color: #f1f5f9;
}

body.dark-mode .aluno-card-email {
    color: #94a3b8;
}

body.dark-mode .aluno-card-progress-bar {
    background: #334155;
}

body.dark-mode .aluno-card-progress-pct {
    color: #e2e8f0;
}

body.dark-mode .aluno-card-badge-success {
    background: rgba(34, 197, 94, 0.2);
    color: #4ade80;
}

body.dark-mode .aluno-card-badge-info {
    background: rgba(59, 130, 246, 0.2);
    color: #60a5fa;
}

body.dark-mode .aluno-card-btn {
    background: #334155;
    border-color: #475569;
    color: #e2e8f0;
}

body.dark-mode .aluno-card-btn:hover {
    background: var(--gateway-primary-color, #0b6856);
    border-color: var(--gateway-primary-color, #0b6856);
    color: #fff;
}

body.dark-mode .alunos-empty-text {
    color: #94a3b8;
}

body.dark-mode .aluno-avatar-ring-bg {
    stroke: #334155;
}

body.dark-mode .aluno-modal-name {
    color: #f1f5f9;
}

body.dark-mode .aluno-modal-email {
    color: #94a3b8;
}

body.dark-mode .aluno-modal-stat {
    background: #1e293b;
    border-color: #334155;
}

body.dark-mode .aluno-modal-stat-value {
    color: #f1f5f9;
}

body.dark-mode .aluno-modal-stat-label {
    color: #94a3b8;
}

body.dark-mode .aluno-modal-section-title {
    color: #e2e8f0;
}

body.dark-mode .aluno-modal-modulo-item {
    background: #1e293b;
    border-color: #334155;
}

body.dark-mode .aluno-modal-modulo-name {
    color: #f1f5f9;
}

body.dark-mode .aluno-modal-modulo-progress {
    background: #334155;
}

body.dark-mode .aluno-modal-modulo-pct {
    color: #e2e8f0;
}

.aluno-modal-cpf-wrap {
    border-top: 1px solid #e5e7eb;
}

body.dark-mode .aluno-modal-cpf-wrap {
    border-top-color: #334155;
}

/* Modal dark mode - garante legibilidade dos textos */
body.dark-mode #detalhesAlunoModal .modal-content {
    background: #0f172a;
    border-color: #1e293b;
}

body.dark-mode #detalhesAlunoModal .modal-header {
    border-bottom-color: #1e293b;
}

body.dark-mode #detalhesAlunoModal .modal-title {
    color: #f1f5f9 !important;
}

body.dark-mode #detalhesAlunoModal .modal-body {
    background: #0f172a;
}

body.dark-mode #detalhesAlunoModal .btn-close {
    filter: invert(1);
}

body.dark-mode #detalhesAlunoModal .aluno-modal-name,
body.dark-mode #detalhesAlunoModal .aluno-modal-email,
body.dark-mode #detalhesAlunoModal .aluno-modal-stat-value,
body.dark-mode #detalhesAlunoModal .aluno-modal-stat-label,
body.dark-mode #detalhesAlunoModal .aluno-modal-section-title,
body.dark-mode #detalhesAlunoModal .aluno-modal-modulo-name,
body.dark-mode #detalhesAlunoModal .aluno-modal-modulo-pct,
body.dark-mode #detalhesAlunoModal .aluno-modal-cpf-wrap,
body.dark-mode #detalhesAlunoModal .aluno-modal-cpf-wrap small,
body.dark-mode #detalhesAlunoModal .text-muted {
    color: #94a3b8 !important;
}

body.dark-mode #detalhesAlunoModal .aluno-modal-name,
body.dark-mode #detalhesAlunoModal .aluno-modal-stat-value {
    color: #f1f5f9 !important;
}

/* Responsivo */
@media (max-width: 768px) {
    .aluno-card {
        flex-wrap: wrap;
    }

    .aluno-card-meta {
        width: 100%;
        order: 3;
        margin-top: 8px;
        padding-top: 12px;
        border-top: 1px solid #e5e7eb;
    }

    body.dark-mode .aluno-card-meta {
        border-top-color: #334155;
    }

    .aluno-modal-top-row {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .aluno-modal-stats {
        grid-template-columns: 1fr;
    }

    .alunos-stats-grid {
        grid-template-columns: 1fr;
    }
}

/* Dark mode - aba Alunos */
body.dark-mode .alunos-stat-card {
    background: rgba(15, 23, 42, 0.5);
    border-color: rgba(30, 41, 59, 0.8);
}
body.dark-mode .alunos-stat-card:hover {
    border-color: var(--gateway-primary-color);
    box-shadow: none;
}
body.dark-mode .alunos-stat-label { color: #94a3b8; }
body.dark-mode .alunos-search-wrap input {
    background: rgba(15, 23, 42, 0.5);
    border-color: rgba(30, 41, 59, 0.8);
    color: var(--gateway-text-color);
}
body.dark-mode .aluno-card {
    background: rgba(15, 23, 42, 0.3);
    border-color: rgba(30, 41, 59, 0.8);
}
body.dark-mode .aluno-card:hover { border-color: var(--gateway-primary-color); box-shadow: none; }
body.dark-mode .aluno-card-name { color: var(--gateway-text-color); }
body.dark-mode .aluno-card-email { color: #94a3b8; }
body.dark-mode .aluno-card-progress-bar { background: rgba(30, 41, 59, 0.8); }
body.dark-mode .aluno-card-btn {
    background: transparent;
    border-color: rgba(30, 41, 59, 0.8);
    color: var(--gateway-text-color);
}
body.dark-mode .alunos-empty-text { color: #94a3b8; }
</style>

<div class="alunos-stats-grid">
    <div class="alunos-stat-card">
        <div class="alunos-stat-value">{{ $totalAlunos }}</div>
        <div class="alunos-stat-label">Total de Alunos</div>
    </div>
    <div class="alunos-stat-card">
        <div class="alunos-stat-value">{{ $alunosComProgresso }}</div>
        <div class="alunos-stat-label">Com Progresso</div>
    </div>
    <div class="alunos-stat-card">
        <div class="alunos-stat-value">{{ $progressoMedio }}%</div>
        <div class="alunos-stat-label">Progresso Médio</div>
    </div>
</div>

<div class="alunos-list-header">
    <h5 class="mb-0" style="font-weight: 600; color: inherit;">Lista de Alunos</h5>
    <div class="alunos-search-wrap">
        <i class="fa-solid fa-search"></i>
        <input type="text" id="searchAluno" placeholder="Buscar por nome ou email...">
    </div>
</div>

<div class="alunos-list-container">
    @if($alunos->count() > 0)
        @foreach($alunos as $aluno)
            @php
                $pedido = \App\Models\Pedido::where('produto_id', $produto->id)
                    ->where('aluno_id', $aluno->id)
                    ->where('status', 'pago')
                    ->first();
                $progresso = $aluno->progressoProduto($produto->id);
                
                $videosConcluidos = 0;
                $totalVideos = 0;
                foreach ($produto->modulosAtivos as $modulo) {
                    foreach ($modulo->sessoesAtivas as $sessao) {
                        foreach ($sessao->videosAtivos as $video) {
                            $totalVideos++;
                            $progressoVideo = $aluno->progressoVideo($video->id);
                            if ($progressoVideo && $progressoVideo->concluido) {
                                $videosConcluidos++;
                            }
                        }
                    }
                }
            @endphp
            <div class="aluno-card" data-search="{{ strtolower($aluno->name . ' ' . $aluno->email) }}">
                @if($aluno->avatar)
                    <img src="{{ asset($aluno->avatar) }}" alt="{{ $aluno->name }}" class="aluno-card-avatar" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="aluno-card-initial" style="display: none;">{{ strtoupper(substr($aluno->name, 0, 1)) }}</div>
                @else
                    <div class="aluno-card-initial">{{ strtoupper(substr($aluno->name, 0, 1)) }}</div>
                @endif
                <div class="aluno-card-main">
                    <div class="aluno-card-name">{{ $aluno->name }}</div>
                    <div class="aluno-card-email">{{ $aluno->email }}</div>
                    <div class="aluno-card-meta">
                        <div class="aluno-card-progress-wrap">
                            <div class="aluno-card-progress-bar">
                                <div class="aluno-card-progress-fill" style="width: {{ $progresso }}%;"></div>
                            </div>
                            <span class="aluno-card-progress-pct">{{ $progresso }}%</span>
                        </div>
                        <span class="aluno-card-badge {{ $videosConcluidos == $totalVideos && $totalVideos > 0 ? 'aluno-card-badge-success' : 'aluno-card-badge-info' }}">
                            {{ $videosConcluidos }}/{{ $totalVideos }} vídeos
                        </span>
                        <span style="font-size: 12px; color: #9ca3af;">{{ $pedido ? $pedido->created_at->format('d/m/Y') : '-' }}</span>
                    </div>
                </div>
                <div class="aluno-card-actions">
                    <button type="button" class="aluno-card-btn" onclick="verDetalhesAluno({{ $aluno->id }}, {{ $produto->id }})">
                        <i class="fa-solid fa-eye"></i> Ver detalhes
                    </button>
                </div>
            </div>
        @endforeach
    @else
        <div class="alunos-empty">
            <i class="fa-solid fa-users alunos-empty-icon"></i>
            <p class="alunos-empty-text">Nenhum aluno encontrado ainda.</p>
        </div>
    @endif
</div>

<!-- Modal Detalhes do Aluno -->
<div class="modal fade" id="detalhesAlunoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">Detalhes do Aluno</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body pt-0" id="detalhesAlunoContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('searchAluno')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase().trim();
    const cards = document.querySelectorAll('.aluno-card[data-search]');
    
    cards.forEach(card => {
        const searchText = card.getAttribute('data-search') || '';
        card.style.display = searchText.includes(searchTerm) ? '' : 'none';
    });
});

function verDetalhesAluno(alunoId, produtoId) {
    const modal = new bootstrap.Modal(document.getElementById('detalhesAlunoModal'));
    const content = document.getElementById('detalhesAlunoContent');
    
    content.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Carregando...</span></div></div>';
    modal.show();
    
    fetch(`/produtos/${produtoId}/aluno/${alunoId}/detalhes`)
        .then(response => response.json())
        .then(data => {
            const progresso = data.progresso_geral || 0;
            const r = 40;
            const circumference = 2 * Math.PI * r;
            const offset = circumference - (progresso / 100) * circumference;
            
            const avatarUrl = data.aluno.avatar_url || (data.aluno.avatar ? '/storage/' + String(data.aluno.avatar).replace(/^\/?storage\//, '') : '');
            const avatarHtml = avatarUrl
                ? `<img src="${avatarUrl}" alt="${(data.aluno.name || '').replace(/"/g, '&quot;')}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"><div class="aluno-avatar-inner-initial" style="display:none;">${(data.aluno.name || 'A').charAt(0).toUpperCase()}</div>`
                : `<div class="aluno-avatar-inner-initial">${(data.aluno.name || 'A').charAt(0).toUpperCase()}</div>`;
            
            let html = `
                <div class="aluno-modal-content">
                    <div class="aluno-modal-top-row">
                        <div class="aluno-modal-avatar-col">
                            <div class="aluno-avatar-ring">
                                <svg class="aluno-avatar-ring-svg" viewBox="0 0 88 88">
                                    <circle class="aluno-avatar-ring-bg" cx="44" cy="44" r="${r}"></circle>
                                    <circle class="aluno-avatar-ring-progress" cx="44" cy="44" r="${r}"
                                        stroke-dasharray="${circumference}"
                                        stroke-dashoffset="${offset}"></circle>
                                </svg>
                                <div class="aluno-avatar-inner">${avatarHtml}</div>
                            </div>
                        </div>
                        <div class="aluno-modal-info-col">
                            <h4 class="aluno-modal-name">${data.aluno.name}</h4>
                            <p class="aluno-modal-email">${data.aluno.email}</p>
                            <div class="aluno-modal-stats">
                                <div class="aluno-modal-stat">
                                    <span class="aluno-modal-stat-value">${data.progresso_geral}%</span>
                                    <span class="aluno-modal-stat-label">Progresso</span>
                                </div>
                                <div class="aluno-modal-stat">
                                    <span class="aluno-modal-stat-value">${data.videos_concluidos}/${data.total_videos}</span>
                                    <span class="aluno-modal-stat-label">Vídeos concluídos</span>
                                </div>
                                <div class="aluno-modal-stat">
                                    <span class="aluno-modal-stat-value">${data.data_compra}</span>
                                    <span class="aluno-modal-stat-label">Data de compra</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="aluno-modal-section-title">Progresso por Módulo</div>
                    <div class="aluno-modal-modulos">
            `;
            
            (data.modulos || []).forEach(modulo => {
                html += `
                    <div class="aluno-modal-modulo-item">
                        <span class="aluno-modal-modulo-name">${modulo.nome}</span>
                        <div class="aluno-modal-modulo-progress">
                            <div class="aluno-modal-modulo-fill" style="width: ${modulo.progresso}%;"></div>
                        </div>
                        <span class="aluno-modal-modulo-pct">${modulo.progresso}%</span>
                    </div>
                `;
            });
            
            html += `
                    </div>
                    <div class="mt-3 pt-3 aluno-modal-cpf-wrap">
                        <small class="text-muted"><strong>CPF:</strong> ${data.aluno.cpf || '-'}</small>
                    </div>
                </div>
            `;
            
            content.innerHTML = html;
        })
        .catch(error => {
            content.innerHTML = '<div class="alert alert-danger mb-0">Erro ao carregar detalhes do aluno.</div>';
        });
}
</script>
