@php
    $produto->load(['modulos.sessoes.videos', 'categories.files']);
@endphp

<style>
/* Design Minimalista e Clean para Módulos */
.module-item {
    background: transparent;
    border: none;
    border-bottom: 1px solid #e5e7eb;
    border-radius: 0;
    padding: 16px 0;
    margin-bottom: 0;
    transition: background-color 0.2s ease;
}

body.dark-mode .module-item {
    border-bottom-color: #1e293b;
}

.module-item:hover {
    background: #f9fafb;
}

body.dark-mode .module-item:hover {
    background: #0f172a;
}

.module-item.sortable-ghost {
    opacity: 0.5;
    background: #f3f4f6;
    border: 1px dashed #9ca3af;
}

body.dark-mode .module-item.sortable-ghost {
    background: #1e293b;
    border-color: #475569;
}

.module-item.sortable-drag {
    opacity: 0.8;
}

.module-header {
    cursor: default;
    padding: 0;
}

/* Garante que apenas o drag-handle inicia o arraste */
.module-header-content > *:not(.drag-handle) {
    pointer-events: auto;
}

.module-header-content {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.drag-handle {
    cursor: grab;
    color: #9ca3af;
    font-size: 18px;
    padding: 8px;
    border-radius: 6px;
    transition: all 0.2s;
    flex-shrink: 0;
}

.drag-handle:hover {
    background: #f3f4f6;
    color: #6b7280;
}

.drag-handle:active {
    cursor: grabbing;
    background: #e5e7eb;
}

.collapse-btn {
    border: none;
    background: transparent;
    padding: 4px 8px;
    color: #6b7280;
    font-size: 14px;
    border-radius: 0;
    transition: color 0.2s;
    flex-shrink: 0;
    cursor: pointer;
    min-width: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

body.dark-mode .collapse-btn {
    color: #94a3b8;
}

.collapse-btn:hover {
    color: #111827;
}

body.dark-mode .collapse-btn:hover {
    color: #e2e8f0;
}

.collapse-btn i {
    color: inherit;
    transition: transform 0.2s;
}

.collapse-btn[aria-expanded="true"] i {
    transform: rotate(90deg);
}

.module-icon {
    width: 24px;
    height: 24px;
    flex-shrink: 0;
    color: #6366f1;
}

.module-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin: 0;
    flex: 1;
    min-width: 0;
}

.module-badge {
    font-size: 11px;
    padding: 4px 10px;
    border-radius: 12px;
    font-weight: 500;
    flex-shrink: 0;
}

.module-description {
    font-size: 14px;
    color: #6b7280;
    margin: 8px 0 0 0;
    line-height: 1.5;
}

.module-cover {
    margin-top: 12px;
    border-radius: 8px;
    max-height: 120px;
    width: auto;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.module-actions {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
}

.module-actions .btn {
    padding: 6px 12px;
    font-size: 13px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    transition: all 0.2s;
}

.module-actions .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Cor dos ícones nos botões - IMPORTANTE para evitar branco no branco */
.module-actions .btn i,
.session-actions .btn i,
.video-actions .btn i {
    color: inherit !important;
}

.module-actions .btn-outline-primary i {
    color: #3b82f6 !important;
}

.module-actions .btn-outline-primary:hover i {
    color: #ffffff !important;
}

.module-actions .btn-outline-danger i {
    color: #ef4444 !important;
}

.module-actions .btn-outline-danger:hover i {
    color: #ffffff !important;
}

.module-actions .btn-outline-success i {
    color: #10b981 !important;
}

.module-actions .btn-outline-success:hover i {
    color: #ffffff !important;
}

.session-actions .btn-outline-primary i {
    color: #3b82f6 !important;
}

.session-actions .btn-outline-primary:hover i {
    color: #ffffff !important;
}

.session-actions .btn-outline-danger i {
    color: #ef4444 !important;
}

.session-actions .btn-outline-danger:hover i {
    color: #ffffff !important;
}

.session-actions .btn-outline-success i {
    color: #10b981 !important;
}

.session-actions .btn-outline-success:hover i {
    color: #ffffff !important;
}

.video-actions .btn-outline-primary i {
    color: #3b82f6 !important;
}

.video-actions .btn-outline-primary:hover i {
    color: #ffffff !important;
}

.video-actions .btn-outline-danger i {
    color: #ef4444 !important;
}

.video-actions .btn-outline-danger:hover i {
    color: #ffffff !important;
}

.module-content {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid #f3f4f6;
}

/* Dark Mode Support */
[data-theme="dark"] .module-item,
.dark .module-item,
body.dark-mode .module-item {
    background: #1f2937;
    border-color: #374151;
    color: #f9fafb;
}

[data-theme="dark"] .module-item:hover,
.dark .module-item:hover,
body.dark-mode .module-item:hover {
    border-color: #4b5563;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

[data-theme="dark"] .module-item.sortable-ghost,
.dark .module-item.sortable-ghost,
body.dark-mode .module-item.sortable-ghost {
    background: #374151;
    border-color: #6b7280;
}

[data-theme="dark"] .drag-handle,
.dark .drag-handle,
body.dark-mode .drag-handle {
    color: #9ca3af;
}

[data-theme="dark"] .drag-handle:hover,
.dark .drag-handle:hover,
body.dark-mode .drag-handle:hover {
    background: #374151;
    color: #d1d5db;
}

[data-theme="dark"] .collapse-btn,
.dark .collapse-btn,
body.dark-mode .collapse-btn {
    color: #9ca3af;
}

[data-theme="dark"] .collapse-btn:hover,
.dark .collapse-btn:hover,
body.dark-mode .collapse-btn:hover {
    background: #374151;
    color: #d1d5db;
}

[data-theme="dark"] .module-title,
.dark .module-title,
body.dark-mode .module-title {
    color: #f9fafb;
}

[data-theme="dark"] .module-description,
.dark .module-description,
body.dark-mode .module-description {
    color: #d1d5db;
}

[data-theme="dark"] .module-content,
.dark .module-content,
body.dark-mode .module-content {
    border-top-color: #374151;
}

[data-theme="dark"] .module-actions .btn,
.dark .module-actions .btn,
body.dark-mode .module-actions .btn {
    border-color: #4b5563;
    background: #374151;
    color: #f9fafb;
}

[data-theme="dark"] .module-actions .btn:hover,
.dark .module-actions .btn:hover,
body.dark-mode .module-actions .btn:hover {
    background: #4b5563;
    border-color: #6b7280;
}

/* Dark mode - cores dos ícones nos botões */
[data-theme="dark"] .module-actions .btn-outline-primary i,
.dark .module-actions .btn-outline-primary i,
body.dark-mode .module-actions .btn-outline-primary i {
    color: #60a5fa !important;
}

[data-theme="dark"] .module-actions .btn-outline-primary:hover i,
.dark .module-actions .btn-outline-primary:hover i,
body.dark-mode .module-actions .btn-outline-primary:hover i {
    color: #ffffff !important;
}

[data-theme="dark"] .module-actions .btn-outline-danger i,
.dark .module-actions .btn-outline-danger i,
body.dark-mode .module-actions .btn-outline-danger i {
    color: #f87171 !important;
}

[data-theme="dark"] .module-actions .btn-outline-danger:hover i,
.dark .module-actions .btn-outline-danger:hover i,
body.dark-mode .module-actions .btn-outline-danger:hover i {
    color: #ffffff !important;
}

[data-theme="dark"] .module-actions .btn-outline-success i,
.dark .module-actions .btn-outline-success i,
body.dark-mode .module-actions .btn-outline-success i {
    color: #34d399 !important;
}

[data-theme="dark"] .module-actions .btn-outline-success:hover i,
.dark .module-actions .btn-outline-success:hover i,
body.dark-mode .module-actions .btn-outline-success:hover i {
    color: #ffffff !important;
}

[data-theme="dark"] .session-actions .btn-outline-primary i,
.dark .session-actions .btn-outline-primary i,
body.dark-mode .session-actions .btn-outline-primary i {
    color: #60a5fa !important;
}

[data-theme="dark"] .session-actions .btn-outline-primary:hover i,
.dark .session-actions .btn-outline-primary:hover i,
body.dark-mode .session-actions .btn-outline-primary:hover i {
    color: #ffffff !important;
}

[data-theme="dark"] .session-actions .btn-outline-danger i,
.dark .session-actions .btn-outline-danger i,
body.dark-mode .session-actions .btn-outline-danger i {
    color: #f87171 !important;
}

[data-theme="dark"] .session-actions .btn-outline-danger:hover i,
.dark .session-actions .btn-outline-danger:hover i,
body.dark-mode .session-actions .btn-outline-danger:hover i {
    color: #ffffff !important;
}

[data-theme="dark"] .session-actions .btn-outline-success i,
.dark .session-actions .btn-outline-success i,
body.dark-mode .session-actions .btn-outline-success i {
    color: #34d399 !important;
}

[data-theme="dark"] .session-actions .btn-outline-success:hover i,
.dark .session-actions .btn-outline-success:hover i,
body.dark-mode .session-actions .btn-outline-success:hover i {
    color: #ffffff !important;
}

[data-theme="dark"] .video-actions .btn-outline-primary i,
.dark .video-actions .btn-outline-primary i,
body.dark-mode .video-actions .btn-outline-primary i {
    color: #60a5fa !important;
}

[data-theme="dark"] .video-actions .btn-outline-primary:hover i,
.dark .video-actions .btn-outline-primary:hover i,
body.dark-mode .video-actions .btn-outline-primary:hover i {
    color: #ffffff !important;
}

[data-theme="dark"] .video-actions .btn-outline-danger i,
.dark .video-actions .btn-outline-danger i,
body.dark-mode .video-actions .btn-outline-danger i {
    color: #f87171 !important;
}

[data-theme="dark"] .video-actions .btn-outline-danger:hover i,
.dark .video-actions .btn-outline-danger:hover i,
body.dark-mode .video-actions .btn-outline-danger:hover i {
    color: #ffffff !important;
}

/* Sessões e Vídeos - Design Minimalista */
.sessions-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.session-item {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 16px;
    transition: all 0.2s;
}

.session-item:hover {
    border-color: #d1d5db;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.session-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.session-info {
    flex: 1;
}

.session-title {
    font-size: 15px;
    font-weight: 600;
    color: #111827;
    display: block;
    margin-bottom: 4px;
}

.session-count {
    font-size: 12px;
    color: #6b7280;
}

.session-actions {
    display: flex;
    gap: 6px;
}

.session-actions .btn {
    padding: 4px 10px;
    font-size: 12px;
}

.videos-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid #e5e7eb;
}

.video-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 12px;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    transition: all 0.2s;
}

.video-item:hover {
    border-color: #d1d5db;
    background: #f9fafb;
}

.video-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.video-title {
    font-size: 13px;
    font-weight: 500;
    color: #111827;
}

.video-url {
    font-size: 11px;
    color: #9ca3af;
    word-break: break-all;
}

.video-actions {
    display: flex;
    gap: 6px;
    flex-shrink: 0;
}

.video-actions .btn {
    padding: 4px 8px;
    font-size: 11px;
    border: 1px solid #d1d5db;
    background: #ffffff;
    color: #374151;
}

.video-actions .btn-outline-primary {
    border-color: #3b82f6;
    color: #3b82f6;
}

.video-actions .btn-outline-primary:hover {
    background: #3b82f6;
    color: #ffffff;
}

.video-actions .btn-outline-danger {
    border-color: #ef4444;
    color: #ef4444;
}

.video-actions .btn-outline-danger:hover {
    background: #ef4444;
    color: #ffffff;
}

.empty-sessions {
    text-align: center;
    padding: 24px;
    color: #6b7280;
}

/* Dark Mode para Sessões e Vídeos */
[data-theme="dark"] .session-item,
.dark .session-item,
body.dark-mode .session-item {
    background: #374151;
    border-color: #4b5563;
}

[data-theme="dark"] .session-title,
.dark .session-title,
body.dark-mode .session-title {
    color: #f9fafb;
}

[data-theme="dark"] .session-count,
.dark .session-count,
body.dark-mode .session-count {
    color: #9ca3af;
}

[data-theme="dark"] .videos-list,
.dark .videos-list,
body.dark-mode .videos-list {
    border-top-color: #4b5563;
}

[data-theme="dark"] .video-item,
.dark .video-item,
body.dark-mode .video-item {
    background: #1f2937;
    border-color: #374151;
}

[data-theme="dark"] .video-item:hover,
.dark .video-item:hover,
body.dark-mode .video-item:hover {
    background: #374151;
    border-color: #4b5563;
}

[data-theme="dark"] .video-title,
.dark .video-title,
body.dark-mode .video-title {
    color: #f9fafb;
}

[data-theme="dark"] .video-url,
.dark .video-url,
body.dark-mode .video-url {
    color: #9ca3af;
}

[data-theme="dark"] .empty-sessions,
.dark .empty-sessions,
body.dark-mode .empty-sessions {
    color: #9ca3af;
}

/* Responsive */
@media (max-width: 768px) {
    .module-header-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .module-actions {
        width: 100%;
        justify-content: flex-end;
    }
    
    .session-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .session-actions {
        width: 100%;
        justify-content: flex-end;
    }
}
</style>

<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Configurações da Área de Membros</h5>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label">Texto de Boas-vindas</label>
                <textarea class="form-control" name="area_member_welcome_text" rows="3">{{ $produto->area_member_welcome_text ?? '' }}</textarea>
                <small class="form-text text-muted">Texto exibido na área de membros do aluno</small>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label d-block mb-2"><strong>Modo da Área de Membros</strong></label>
                <small class="form-text text-muted d-block mb-2">Define o tema das telas que o aluno vê ao acessar o curso.</small>
                <div class="d-flex flex-wrap gap-4 align-items-center">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="area_member_white_mode" id="area_member_white_mode" value="1"
                            {{ ($produto->area_member_white_mode ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="area_member_white_mode">
                            <i class="fa-solid fa-sun text-warning me-1"></i> Modo claro (telas brancas)
                        </label>
                    </div>
                    <span class="text-muted small">Desmarque para usar modo escuro.</span>
                </div>
                <small class="form-text text-muted mt-1">Assinalado = área de membros com fundo branco e visual claro. Desassinalado = modo escuro (padrão).</small>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">
                    <strong>Cor Primária</strong>
                    <small class="text-muted d-block">Cor principal usada em botões, links e destaques</small>
                </label>
                <div class="d-flex align-items-center gap-2">
                    <input type="color" class="form-control form-control-color" name="area_member_color_primary" 
                           id="colorPrimaryPicker" value="{{ $produto->area_member_color_primary ?? '#0b6856' }}" 
                           style="width: 80px; height: 50px; cursor: pointer;">
                    <input type="text" class="form-control" id="colorPrimaryText" 
                           value="{{ $produto->area_member_color_primary ?? '#0b6856' }}" 
                           placeholder="#0b6856" pattern="^#[0-9A-Fa-f]{6}$" 
                           style="max-width: 150px;">
                </div>
                <small class="form-text text-muted">Use o seletor de cor ou digite o código hexadecimal</small>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label">
                    <strong>Banner do Curso</strong> 
                    <small class="text-muted">(Recomendado: 1920x400px — será aberto um recorte para escolher a área)</small>
                </label>
                <input type="file" class="form-control" name="area_member_banner" accept="image/*" id="bannerImageInput" style="max-width: 400px;">
                <input type="hidden" name="area_member_banner_cropped" id="area_member_banner_cropped" value="">
                <small class="form-text text-muted d-block mt-1">Ao selecionar uma imagem, abrirá um modal para recortar a área do banner.</small>
                <div id="bannerImagePreview" class="mt-3">
                    @if($produto->area_member_banner)
                        <div class="position-relative">
                            <img src="/storage/{{ ltrim($produto->area_member_banner, '/') }}" alt="Banner" 
                                 style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 8px; border: 2px solid #e5e7eb; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-success">Banner Ativo</span>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fa-solid fa-info-circle"></i> Nenhum banner definido. O banner padrão será exibido.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label">
                    <strong>Banner do Curso (Mobile)</strong>
                    <small class="text-muted">(Recomendado: 768x400px — exibido em celulares e tablets)</small>
                </label>
                <input type="file" class="form-control" name="area_member_banner_mobile" accept="image/*" id="bannerMobileImageInput" style="max-width: 400px;">
                <input type="hidden" name="area_member_banner_mobile_cropped" id="area_member_banner_mobile_cropped" value="">
                <small class="form-text text-muted d-block mt-1">Opcional. Se não definir, o banner desktop será usado. Em mobile, este banner substitui o desktop.</small>
                <div id="bannerMobileImagePreview" class="mt-3">
                    @if(!empty($produto->area_member_banner_mobile))
                        <div class="position-relative">
                            <img src="/storage/{{ ltrim($produto->area_member_banner_mobile, '/') }}" alt="Banner Mobile"
                                 style="width: 100%; max-height: 150px; object-fit: cover; border-radius: 8px; border: 2px solid #e5e7eb;">
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-info"><i class="fa-solid fa-mobile-screen me-1"></i> Mobile</span>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-secondary mb-0 py-2">
                            <i class="fa-solid fa-mobile-screen me-1"></i> Nenhum banner mobile definido. Usará o banner desktop em mobile.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label">
                    <strong>Background dentro do curso</strong>
                    <small class="text-muted">(Recomendado: 1920x1080, fullscreen para o aluno)</small>
                </label>
                <input type="file" class="form-control" name="area_member_course_background" accept="image/*" id="courseBackgroundInput" style="max-width: 400px;">
                <small class="form-text text-muted d-block mt-1">Imagem de fundo fullscreen exibida atrás do conteúdo quando o aluno está dentro do curso.</small>
                @if(!empty($produto->area_member_course_background))
                    <div class="mt-2">
                        <img src="/storage/{{ ltrim($produto->area_member_course_background, '/') }}" alt="Background curso" style="max-height: 120px; border-radius: 8px; border: 2px solid #e5e7eb;">
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Recorte do Banner -->
<div class="modal fade" id="bannerCropModal" tabindex="-1" aria-labelledby="bannerCropModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bannerCropModalLabel">Recortar Banner do Curso (Desktop)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar" onclick="cancelBannerCrop()"></button>
            </div>
            <div class="modal-body p-2">
                <div class="img-container" style="max-height: 70vh;">
                    <img id="bannerCropImage" src="" alt="Banner" style="max-width: 100%; display: block;">
                </div>
                <p class="small text-muted mt-2 mb-0">Arraste para posicionar e use os cantos para ajustar a área. Proporção recomendada: 1920×400.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cancelBannerCrop()">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="applyBannerCrop()"><i class="fa-solid fa-check me-1"></i> Aplicar recorte</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Recorte do Banner Mobile -->
<div class="modal fade" id="bannerMobileCropModal" tabindex="-1" aria-labelledby="bannerMobileCropModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bannerMobileCropModalLabel">Recortar Banner Mobile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar" onclick="cancelBannerMobileCrop()"></button>
            </div>
            <div class="modal-body p-2">
                <div class="img-container" style="max-height: 70vh;">
                    <img id="bannerMobileCropImage" src="" alt="Banner Mobile" style="max-width: 100%; display: block;">
                </div>
                <p class="small text-muted mt-2 mb-0">Arraste para posicionar e use os cantos para ajustar a área. Proporção recomendada: 768×400 (mobile).</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cancelBannerMobileCrop()">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="applyBannerMobileCrop()"><i class="fa-solid fa-check me-1"></i> Aplicar recorte</button>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Módulos do Curso</h5>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModuloModal">
            <i class="fa-solid fa-plus"></i> Adicionar Módulo
        </button>
    </div>
    <div class="card-body">
        @if($produto->modulos->count() > 0 || $produto->categories->count() > 0)
            <div id="modulosList">
                {{-- Módulos Novos --}}
                @foreach($produto->modulos->sortBy('ordem') as $modulo)
                    <div class="module-item" data-modulo-id="{{ $modulo->id }}" data-ordem="{{ $modulo->ordem }}">
                        <div class="module-header">
                            <div class="module-header-content">
                                <i class="fa-solid fa-grip-vertical drag-handle" title="Arrastar para reordenar"></i>
                                <button type="button" class="collapse-btn" data-bs-toggle="collapse" data-bs-target="#moduloCollapse{{ $modulo->id }}" aria-expanded="false" aria-label="Expandir/Colapsar">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </button>
                                @if($modulo->icone)
                                    <i data-lucide="{{ $modulo->icone }}" class="module-icon"></i>
                                @endif
                                <h6 class="module-title">{{ $modulo->nome }}</h6>
                                <span class="module-badge badge bg-{{ $modulo->status ? 'success' : 'secondary' }}">
                                    {{ $modulo->status ? 'Ativo' : 'Inativo' }}
                                </span>
                                <div class="module-actions">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="editModulo({{ $modulo->id }}, '{{ addslashes($modulo->nome) }}', '{{ addslashes($modulo->descricao ?? '') }}', '{{ addslashes($modulo->icone ?? '') }}', {{ $modulo->status ? 'true' : 'false' }}, {{ $modulo->ordem }})"
                                            title="Editar módulo">
                                        <i class="fa-solid fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteModulo({{ $modulo->id }})"
                                            title="Excluir módulo">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            
                            @if($modulo->descricao)
                                <p class="module-description">{{ $modulo->descricao }}</p>
                            @endif
                        </div>
                        
                        <div class="collapse module-content" id="moduloCollapse{{ $modulo->id }}">
                            @if($modulo->sessoes->count() > 0)
                                <div class="sessions-container">
                                    @foreach($modulo->sessoes->sortBy('ordem') as $sessao)
                                        <div class="session-item">
                                            <div class="session-header">
                                                <div class="session-info">
                                                    <strong class="session-title">{{ $sessao->nome }}</strong>
                                                    @if($sessao->videos->count() > 0)
                                                        <span class="session-count">{{ $sessao->videos->count() }} vídeo(s)</span>
                                                    @endif
                                                </div>
                                                <div class="session-actions">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                            onclick="editSessao({{ $sessao->id }}, '{{ addslashes($sessao->nome) }}', '{{ addslashes($sessao->descricao ?? '') }}', {{ $sessao->status ? 'true' : 'false' }})"
                                                            title="Editar sessão">
                                                        <i class="fa-solid fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                                            onclick="addVideo({{ $sessao->id }})"
                                                            title="Adicionar vídeo">
                                                        <i class="fa-solid fa-video"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="deleteSessao({{ $sessao->id }})"
                                                            title="Excluir sessão">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            @if($sessao->videos->count() > 0)
                                                <div class="videos-list">
                                                    @foreach($sessao->videos->sortBy('ordem') as $video)
                                                        <div class="video-item">
                                                            <div class="video-info">
                                                                <span class="video-title">{{ $video->titulo }}</span>
                                                                <span class="video-url">{{ $video->url_youtube }}</span>
                                                            </div>
                                                            <div class="video-actions">
                                                                <button type="button" class="btn btn-xs btn-outline-primary" 
                                                                        onclick="editVideo({{ $video->id }}, '{{ addslashes($video->titulo) }}', '{{ addslashes($video->descricao ?? '') }}', '{{ addslashes($video->url_youtube) }}', {{ $video->duracao ?? 0 }}, {{ $video->status ? 'true' : 'false' }})"
                                                                        title="Editar vídeo">
                                                                    <i class="fa-solid fa-edit"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-xs btn-outline-danger" 
                                                                        onclick="deleteVideo({{ $video->id }})"
                                                                        title="Excluir vídeo">
                                                                    <i class="fa-solid fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-3" 
                                            onclick="addSessao({{ $modulo->id }})">
                                        <i class="fa-solid fa-plus"></i> Adicionar Sessão
                                    </button>
                                </div>
                            @else
                                <div class="empty-sessions">
                                    <p class="text-muted mb-3">Nenhuma sessão criada ainda.</p>
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="addSessao({{ $modulo->id }})">
                                        <i class="fa-solid fa-plus"></i> Adicionar Primeira Sessão
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
                
                {{-- Categorias Antigas (para migração) --}}
                @if($produto->categories->count() > 0 && $produto->modulos->count() == 0)
                    <div class="alert alert-warning mt-3">
                        <strong>⚠️ Módulos da Área Antiga Encontrados:</strong>
                        <p class="mb-2">Você tem {{ $produto->categories->count() }} módulo(s) criados na área antiga.</p>
                        <form action="{{ route('area-membros.migrate') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="produto_id" value="{{ $produto->id }}">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fa-solid fa-arrow-right"></i> Migrar para Nova Estrutura
                            </button>
                        </form>
                        <small class="d-block mt-2 text-muted">Ou crie novos módulos usando o botão acima.</small>
                    </div>
                @endif
            </div>
        @else
            <p class="text-muted text-center py-4">Nenhum módulo criado ainda. Clique em "Adicionar Módulo" para começar.</p>
        @endif
    </div>
</div>

<!-- Modal Adicionar Módulo -->
<div class="modal fade" id="addModuloModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            {{-- 100% IGUAL à área antiga --}}
            <div class="modal-header">
                <h5 class="modal-title">Adicionar Módulo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="form-content-modulo">
                <div class="row">
                    <input type="hidden" name="produto_id" value="{{ $produto->id }}" />
                    <div class="mb-3 col-12">
                        <label for="name">Nome</label>
                        <input type="text" autofocus class="form-control form-control-md" id="name" name="name" required>
                    </div>
                    <div class="mb-3 col-12">
                        <label for="description">Descrição</label>
                        <textarea class="form-control form-control-md" id="description" name="description"></textarea>
                    </div>
                    <div class="mb-3 col-12">
                        <label for="icone">Ícone</label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-md" name="icone" id="moduloIconeInput" placeholder="Selecione um ícone" readonly>
                            <button type="button" class="btn btn-outline-primary" onclick="openIconPicker('moduloIconeInput')">
                                <i class="fa-solid fa-icons"></i> Escolher Ícone
                            </button>
                        </div>
                        <small class="text-muted">Escolha um ícone para representar este módulo</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button id="btn-form-modulo" type="button" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
// 100% IGUAL à área antiga - cria form dinamicamente
document.addEventListener('DOMContentLoaded', function() {
    const btnSubmitModulo = document.getElementById('btn-form-modulo');
    const formContainerModulo = document.getElementById('form-content-modulo');

    if (btnSubmitModulo && formContainerModulo) {
        // Criar <form> e envolver o conteúdo
        const formModulo = document.createElement('form');
        formModulo.method = 'POST';
        formModulo.action = "{{ route('produtos.modulos.store') }}";

        // Adicionar token CSRF
        const csrfModulo = document.createElement('input');
        csrfModulo.type = 'hidden';
        csrfModulo.name = '_token';
        csrfModulo.value = '{{ csrf_token() }}';
        formModulo.appendChild(csrfModulo);

        // Move todos os elementos filhos para dentro do form (preserva referências de arquivos)
        while (formContainerModulo.firstChild) {
            formModulo.appendChild(formContainerModulo.firstChild);
        }
        
        // Adiciona o form de volta ao container
        formContainerModulo.appendChild(formModulo);

        // Ao clicar no botão "Salvar"
        btnSubmitModulo.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Validação básica
            const nameInput = formModulo.querySelector('input[name="name"]');
            if (nameInput && !nameInput.value.trim()) {
                alert('Por favor, preencha o nome do módulo.');
                nameInput.focus();
                return;
            }
            
            // Submete o formulário
            formModulo.submit();
        });
    }
});
</script>

<!-- Modal Editar Módulo -->
<div class="modal fade" id="editModuloModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editModuloForm" method="POST" enctype="multipart/form-data" action="{{ route('produtos.modulos.update') }}">
                @csrf
                <input type="hidden" name="id" id="editModuloId" value="">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Módulo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nome do Módulo *</label>
                        <input type="text" class="form-control" name="nome" id="editModuloNome" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea class="form-control" name="descricao" id="editModuloDescricao" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ícone</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="icone" id="editModuloIcone" placeholder="Selecione um ícone" readonly>
                            <button type="button" class="btn btn-outline-primary" onclick="openIconPicker('editModuloIcone')">
                                <i class="fa-solid fa-icons"></i> Escolher Ícone
                            </button>
                        </div>
                        <small class="text-muted">Escolha um ícone para representar este módulo</small>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="editModuloStatus">
                            <label class="form-check-label" for="editModuloStatus">Módulo Ativo</label>
                        </div>
                        <small class="text-muted">Módulos inativos não aparecem para os alunos</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
// Limpa formulário ao fechar modal de editar módulo
document.getElementById('editModuloModal')?.addEventListener('hidden.bs.modal', function() {
    document.getElementById('editModuloForm')?.reset();
});

// Preview do Banner do Curso (modal de recorte)
document.addEventListener('DOMContentLoaded', function() {
    // Banner: abre modal de recorte (Cropper.js)
    const bannerInput = document.getElementById('bannerImageInput');
    const bannerCroppedInput = document.getElementById('area_member_banner_cropped');
    const bannerPreview = document.getElementById('bannerImagePreview');
    let bannerCropper = null;
    
    if (bannerInput) {
        bannerInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file || !file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = function(ev) {
                const modal = document.getElementById('bannerCropModal');
                const img = document.getElementById('bannerCropImage');
                if (!modal || !img) return;
                img.src = ev.target.result;
                img.dataset.original = ev.target.result;
                if (bannerCropper) { bannerCropper.destroy(); bannerCropper = null; }
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
                modal.addEventListener('shown.bs.modal', function onShown() {
                    modal.removeEventListener('shown.bs.modal', onShown);
                    bannerCropper = new Cropper(img, { aspectRatio: 1920/400, viewMode: 1, dragMode: 'move' });
                });
            };
            reader.readAsDataURL(file);
        });
    }
    
    window.applyBannerCrop = function() {
        if (!bannerCropper) return;
        const canvas = bannerCropper.getCroppedCanvas({ width: 1920, height: 400 });
        const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
        if (bannerCroppedInput) bannerCroppedInput.value = dataUrl;
        if (bannerPreview) {
            bannerPreview.innerHTML = '<div class="position-relative"><img src="' + dataUrl + '" alt="Banner" style="width:100%;max-height:200px;object-fit:cover;border-radius:8px;border:2px solid #e5e7eb;"><span class="badge bg-success position-absolute top-0 end-0 m-2">Banner recortado</span></div>';
        }
        if (bannerInput) bannerInput.value = '';
        bootstrap.Modal.getInstance(document.getElementById('bannerCropModal')).hide();
        bannerCropper.destroy();
        bannerCropper = null;
    };
    
    window.cancelBannerCrop = function() {
        if (bannerCropper) { bannerCropper.destroy(); bannerCropper = null; }
        if (bannerInput) bannerInput.value = '';
        bootstrap.Modal.getInstance(document.getElementById('bannerCropModal')).hide();
    };
    
    // Banner Mobile: abre modal de recorte (Cropper.js)
    const bannerMobileInput = document.getElementById('bannerMobileImageInput');
    const bannerMobileCroppedInput = document.getElementById('area_member_banner_mobile_cropped');
    const bannerMobilePreview = document.getElementById('bannerMobileImagePreview');
    let bannerMobileCropper = null;
    
    if (bannerMobileInput) {
        bannerMobileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file || !file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = function(ev) {
                const modal = document.getElementById('bannerMobileCropModal');
                const img = document.getElementById('bannerMobileCropImage');
                if (!modal || !img) return;
                img.src = ev.target.result;
                if (bannerMobileCropper) { bannerMobileCropper.destroy(); bannerMobileCropper = null; }
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
                modal.addEventListener('shown.bs.modal', function onShown() {
                    modal.removeEventListener('shown.bs.modal', onShown);
                    bannerMobileCropper = new Cropper(img, { aspectRatio: 768/400, viewMode: 1, dragMode: 'move' });
                });
            };
            reader.readAsDataURL(file);
        });
    }
    
    window.applyBannerMobileCrop = function() {
        if (!bannerMobileCropper) return;
        const canvas = bannerMobileCropper.getCroppedCanvas({ width: 768, height: 400 });
        const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
        if (bannerMobileCroppedInput) bannerMobileCroppedInput.value = dataUrl;
        if (bannerMobilePreview) {
            bannerMobilePreview.innerHTML = '<div class="position-relative"><img src="' + dataUrl + '" alt="Banner Mobile" style="width:100%;max-height:150px;object-fit:cover;border-radius:8px;border:2px solid #e5e7eb;"><span class="badge bg-info position-absolute top-0 end-0 m-2"><i class="fa-solid fa-mobile-screen me-1"></i> Mobile recortado</span></div>';
        }
        if (bannerMobileInput) bannerMobileInput.value = '';
        bootstrap.Modal.getInstance(document.getElementById('bannerMobileCropModal')).hide();
        bannerMobileCropper.destroy();
        bannerMobileCropper = null;
    };
    
    window.cancelBannerMobileCrop = function() {
        if (bannerMobileCropper) { bannerMobileCropper.destroy(); bannerMobileCropper = null; }
        if (bannerMobileInput) bannerMobileInput.value = '';
        bootstrap.Modal.getInstance(document.getElementById('bannerMobileCropModal')).hide();
    };
    
    // Sincronização da Cor Primária entre color picker e input de texto
    const colorPrimaryPicker = document.getElementById('colorPrimaryPicker');
    const colorPrimaryText = document.getElementById('colorPrimaryText');
    
    if (colorPrimaryPicker && colorPrimaryText) {
        // Quando o color picker muda, atualiza o texto
        colorPrimaryPicker.addEventListener('change', function() {
            colorPrimaryText.value = this.value.toUpperCase();
        });
        
        // Quando o texto muda, atualiza o color picker (com validação)
        colorPrimaryText.addEventListener('input', function() {
            let value = this.value.trim().toUpperCase();
            // Adiciona # se não tiver
            if (value && !value.startsWith('#')) {
                value = '#' + value;
            }
            // Valida formato hexadecimal
            if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                colorPrimaryPicker.value = value;
                this.value = value;
            } else if (value.length > 7) {
                // Limita a 7 caracteres (# + 6 hex)
                this.value = value.substring(0, 7);
            }
        });
        
        // Sincroniza ao carregar a página
        if (colorPrimaryPicker.value) {
            colorPrimaryText.value = colorPrimaryPicker.value.toUpperCase();
        }
    }
    
    // Garantir que o formulário principal está configurado corretamente
    const mainForm = document.querySelector('form[action*="produtos.edit"]');
    if (mainForm) {
        // Garantir que o método é PUT e tem enctype para uploads
        if (!mainForm.hasAttribute('enctype') || mainForm.getAttribute('enctype') !== 'multipart/form-data') {
            mainForm.setAttribute('enctype', 'multipart/form-data');
        }
        
        // Validação customizada do formulário principal (já que adicionamos novalidate)
        mainForm.addEventListener('submit', function(e) {
            // Remove temporariamente o atributo 'required' de todos os campos dentro de modais
            // Isso evita erro "An invalid form control with name='nome' is not focusable"
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                const inputs = modal.querySelectorAll('input[required], textarea[required], select[required]');
                inputs.forEach(input => {
                    input.removeAttribute('required');
                    input.setAttribute('data-was-required', 'true');
                });
            });
            
            // Valida apenas os campos do formulário principal (não dos modais)
            let isValid = true;
            const mainFormInputs = mainForm.querySelectorAll('input[required]:not(.modal input), textarea[required]:not(.modal textarea), select[required]:not(.modal select)');
            
            mainFormInputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos obrigatórios.');
                // Reabilita campos dos modais
                modals.forEach(modal => {
                    const inputs = modal.querySelectorAll('input[data-was-required], textarea[data-was-required], select[data-was-required]');
                    inputs.forEach(input => {
                        input.setAttribute('required', '');
                        input.removeAttribute('data-was-required');
                    });
                });
                return false;
            }
            
            console.log('Formulário sendo enviado...');
            console.log('Banner:', document.getElementById('area_member_banner_cropped')?.value ? '(recortado)' : document.getElementById('bannerImageInput')?.files[0]?.name);
        });
        
        // Reabilita campos required dos modais quando a página carregar (caso haja erro)
        window.addEventListener('pageshow', function() {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                const inputs = modal.querySelectorAll('input[data-was-required], textarea[data-was-required], select[data-was-required]');
                inputs.forEach(input => {
                    input.setAttribute('required', '');
                    input.removeAttribute('data-was-required');
                });
            });
        });
    }
});
</script>

<!-- Modal Adicionar Sessão -->
<div class="modal fade" id="addSessaoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addSessaoForm" method="POST">
                @csrf
                <input type="hidden" name="modulo_id" id="addSessaoModuloId">
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar Sessão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nome da Sessão *</label>
                        <input type="text" class="form-control" name="nome" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea class="form-control" name="descricao" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" value="1" checked>
                            <label class="form-check-label">Ativo</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Limpa formulário ao abrir modal de criar sessão
document.getElementById('addSessaoModal')?.addEventListener('show.bs.modal', function() {
    document.getElementById('addSessaoForm')?.reset();
    const statusInput = document.querySelector('#addSessaoModal input[name="status"]');
    if (statusInput) statusInput.checked = true;
});
</script>

<!-- Modal Editar Sessão -->
<div class="modal fade" id="editSessaoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editSessaoForm" method="POST">
                @csrf
                <input type="hidden" name="id" id="editSessaoId" value="">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Sessão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nome da Sessão *</label>
                        <input type="text" class="form-control" name="nome" id="editSessaoNome" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea class="form-control" name="descricao" id="editSessaoDescricao" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="editSessaoStatus">
                            <label class="form-check-label">Ativo</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Limpa formulário ao abrir modal de editar sessão
document.getElementById('editSessaoModal')?.addEventListener('hidden.bs.modal', function() {
    document.getElementById('editSessaoForm')?.reset();
});
</script>

<!-- Modal Adicionar Vídeo -->
<div class="modal fade" id="addVideoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addVideoForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="sessao_id" id="addVideoSessaoId">
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar Vídeo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Título do Vídeo *</label>
                        <input type="text" class="form-control" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL do YouTube *</label>
                        <input type="url" class="form-control" name="url_youtube" 
                               placeholder="https://www.youtube.com/watch?v=..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea class="form-control" name="descricao" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Duração (segundos)</label>
                                <input type="number" class="form-control" name="duracao" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Thumbnail (opcional)</label>
                                <input type="file" class="form-control" name="thumbnail" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" value="1" checked>
                            <label class="form-check-label">Ativo</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Limpa formulário ao abrir modal de criar vídeo
document.getElementById('addVideoModal')?.addEventListener('show.bs.modal', function() {
    document.getElementById('addVideoForm')?.reset();
    const statusInput = document.querySelector('#addVideoModal input[name="status"]');
    if (statusInput) statusInput.checked = true;
});
</script>

<!-- Modal Editar Vídeo -->
<div class="modal fade" id="editVideoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editVideoForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="editVideoId" value="">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Vídeo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Título do Vídeo *</label>
                        <input type="text" class="form-control" name="titulo" id="editVideoTitulo" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL do YouTube *</label>
                        <input type="url" class="form-control" name="url_youtube" id="editVideoUrl" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea class="form-control" name="descricao" id="editVideoDescricao" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Duração (segundos)</label>
                                <input type="number" class="form-control" name="duracao" id="editVideoDuracao" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Thumbnail (opcional)</label>
                                <input type="file" class="form-control" name="thumbnail" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="editVideoStatus">
                            <label class="form-check-label">Ativo</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editModulo(id, nome, descricao, icone, status, ordem) {
    console.log('editModulo chamado:', { id, nome, descricao, icone, status, ordem });
    
    const editModal = document.getElementById('editModuloModal');
    const editForm = document.getElementById('editModuloForm');
    
    if (!editModal || !editForm) {
        console.error('Modal ou formulário não encontrado');
        alert('Erro: Modal de edição não encontrado. Recarregue a página.');
        return;
    }
    
    editForm.action = '{{ route("produtos.modulos.update") }}';
    const idInput = document.getElementById('editModuloId');
    const nomeInput = document.getElementById('editModuloNome');
    const descInput = document.getElementById('editModuloDescricao');
    const iconInput = document.getElementById('editModuloIcone');
    const statusInput = document.getElementById('editModuloStatus');
    
    if (idInput) idInput.value = id;
    if (nomeInput) nomeInput.value = nome || '';
    if (descInput) descInput.value = descricao || '';
    if (iconInput) iconInput.value = icone || '';
    if (statusInput) {
        statusInput.checked = status === true || status === 'true' || status === 1 || status === '1';
    }
    
    try {
        const modal = new bootstrap.Modal(editModal);
        modal.show();
        console.log('Modal aberto com sucesso');
    } catch (error) {
        console.error('Erro ao abrir modal:', error);
        alert('Erro ao abrir modal de edição. Verifique se o Bootstrap está carregado.');
    }
}

function deleteModulo(id) {
    if (confirm('Tem certeza que deseja excluir este módulo? Todas as sessões e vídeos serão excluídos também.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("produtos.modulos.destroy") }}';
        
        // Adiciona CSRF token corretamente
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        // Adiciona ID do módulo
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'id';
        idInput.value = id;
        form.appendChild(idInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function addSessao(moduloId) {
    document.getElementById('addSessaoModuloId').value = moduloId;
    document.getElementById('addSessaoForm').action = '{{ route("produtos.sessoes.store") }}';
    // Limpa o formulário
    document.getElementById('addSessaoForm').reset();
    document.getElementById('addSessaoModuloId').value = moduloId;
    new bootstrap.Modal(document.getElementById('addSessaoModal')).show();
}

function editSessao(id, nome, descricao, status) {
    document.getElementById('editSessaoForm').action = '{{ route("produtos.sessoes.update") }}';
    const idInput = document.getElementById('editSessaoId');
    const nomeInput = document.getElementById('editSessaoNome');
    const descInput = document.getElementById('editSessaoDescricao');
    const statusInput = document.getElementById('editSessaoStatus');
    
    if (idInput) idInput.value = id;
    if (nomeInput) nomeInput.value = nome || '';
    if (descInput) descInput.value = descricao || '';
    if (statusInput) {
        statusInput.checked = status === true || status === 'true' || status === 1 || status === '1';
    }
    new bootstrap.Modal(document.getElementById('editSessaoModal')).show();
}

function deleteSessao(id) {
    if (confirm('Tem certeza que deseja excluir esta sessão? Todos os vídeos serão excluídos também.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("produtos.sessoes.destroy") }}';
        
        // Adiciona CSRF token corretamente
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        // Adiciona ID da sessão
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'id';
        idInput.value = id;
        form.appendChild(idInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function addVideo(sessaoId) {
    document.getElementById('addVideoSessaoId').value = sessaoId;
    document.getElementById('addVideoForm').action = '{{ route("produtos.videos.store") }}';
    // Limpa o formulário
    document.getElementById('addVideoForm').reset();
    document.getElementById('addVideoSessaoId').value = sessaoId;
    new bootstrap.Modal(document.getElementById('addVideoModal')).show();
}

function editVideo(id, titulo, descricao, url, duracao, status) {
    document.getElementById('editVideoForm').action = '{{ route("produtos.videos.update") }}';
    const idInput = document.getElementById('editVideoId');
    const tituloInput = document.getElementById('editVideoTitulo');
    const descInput = document.getElementById('editVideoDescricao');
    const urlInput = document.getElementById('editVideoUrl');
    const duracaoInput = document.getElementById('editVideoDuracao');
    const statusInput = document.getElementById('editVideoStatus');
    
    if (idInput) idInput.value = id;
    if (tituloInput) tituloInput.value = titulo || '';
    if (descInput) descInput.value = descricao || '';
    if (urlInput) urlInput.value = url || '';
    if (duracaoInput) duracaoInput.value = duracao || 0;
    if (statusInput) {
        statusInput.checked = status === true || status === 'true' || status === 1 || status === '1';
    }
    new bootstrap.Modal(document.getElementById('editVideoModal')).show();
}

function deleteVideo(id) {
    if (confirm('Tem certeza que deseja excluir este vídeo?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("produtos.videos.destroy") }}';
        
        // Adiciona CSRF token corretamente
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        // Adiciona ID do vídeo
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'id';
        idInput.value = id;
        form.appendChild(idInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Variável global para rastrear qual campo está sendo editado
let currentIconField = null;

function openIconPicker(fieldId) {
    currentIconField = fieldId;
    new bootstrap.Modal(document.getElementById('iconPickerModal')).show();
}

// Seleção de ícone
function selectIcon(iconName) {
    if (currentIconField) {
        const input = document.getElementById(currentIconField);
        if (input) {
            input.value = iconName;
        }
    }
    
    // Fecha o modal de seleção
    const modal = bootstrap.Modal.getInstance(document.getElementById('iconPickerModal'));
    if (modal) {
        modal.hide();
    }
    currentIconField = null;
}
</script>

<!-- Modal Seletor de Ícones -->
<div class="modal fade" id="iconPickerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Escolher Ícone</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="iconSearch" placeholder="Buscar ícone...">
                </div>
                <div class="row g-2" id="iconsGrid" style="max-height: 400px; overflow-y: auto;">
                    @php
                        $icons = [
                            'book', 'book-open', 'graduation-cap', 'school', 'award', 'trophy',
                            'video', 'play-circle', 'film', 'youtube', 'tv', 'monitor',
                            'settings', 'cog', 'wrench', 'tool', 'hammer', 'gear',
                            'file-text', 'file', 'folder', 'archive', 'download', 'upload',
                            'check-circle', 'circle-check', 'star', 'heart', 'thumbs-up', 'flag',
                            'compass', 'map', 'navigation', 'target', 'crosshair', 'radar',
                            'zap', 'bolt', 'flame', 'fire', 'sun', 'moon',
                            'rocket', 'plane', 'car', 'bike', 'ship', 'train',
                            'code', 'terminal', 'laptop', 'smartphone', 'tablet', 'desktop',
                            'users', 'user', 'user-plus', 'user-check', 'user-x', 'users-round',
                            'chart-line', 'chart-bar', 'trending-up', 'activity', 'pulse', 'gauge',
                            'lock', 'unlock', 'shield', 'shield-check', 'key', 'fingerprint',
                            'bell', 'mail', 'message-circle', 'phone', 'headphones', 'mic',
                            'image', 'images', 'camera', 'palette', 'brush', 'paintbrush',
                            'music', 'headphones', 'radio', 'disc', 'waveform', 'volume-2',
                            'gamepad', 'dice', 'puzzle', 'chess', 'cards', 'joystick',
                            'coffee', 'utensils', 'cake', 'wine', 'beer', 'cocktail',
                            'home', 'building', 'store', 'shopping-cart', 'credit-card', 'wallet',
                            'calendar', 'clock', 'timer', 'alarm-clock', 'hourglass', 'stopwatch',
                            'globe', 'map-pin', 'navigation-2', 'compass-2', 'world', 'earth',
                            'cloud', 'cloud-rain', 'cloud-snow', 'sun', 'moon', 'star',
                            'heart', 'heart-handshake', 'smile', 'laugh', 'wink', 'thumbs-up',
                            'lightbulb', 'idea', 'bulb', 'lamp', 'flashlight', 'candle',
                            'gift', 'package', 'box', 'inbox', 'archive', 'folder',
                            'search', 'filter', 'sliders', 'grid', 'list', 'layout',
                            'eye', 'eye-off', 'lock', 'unlock', 'shield', 'shield-off',
                            'plus', 'minus', 'x', 'check', 'arrow-right', 'arrow-left',
                            'chevron-right', 'chevron-left', 'chevron-up', 'chevron-down', 'arrow-up', 'arrow-down'
                        ];
                    @endphp
                    @foreach($icons as $icon)
                        <div class="col-3 col-md-2 col-lg-1 text-center p-2 icon-item" 
                             onclick="selectIcon('{{ $icon }}')"
                             style="cursor: pointer; border: 1px solid #e0e0e0; border-radius: 6px; margin: 4px; transition: all 0.2s; padding: 10px; background: #f8f9fa;"
                             onmouseover="this.style.background='#e9ecef'; this.style.borderColor='var(--gateway-primary-color)'; this.style.transform='scale(1.05)'"
                             onmouseout="this.style.background='#f8f9fa'; this.style.borderColor='#e0e0e0'; this.style.transform='scale(1)'"
                             data-icon-name="{{ $icon }}">
                            <i data-lucide="{{ $icon }}" style="width: 28px; height: 28px; color: var(--gateway-primary-color);"></i>
                            <small class="d-block mt-1" style="font-size: 9px; word-break: break-all; color: #666;">{{ $icon }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    // Inicializa ícones Lucide
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        
        // Aguarda um pouco para garantir que o DOM está totalmente carregado
        setTimeout(() => {
            // Inicializa drag and drop para módulos
            initSortableModulos();
            
            // Garante que os botões funcionem corretamente
            document.querySelectorAll('.module-actions button, .session-actions button, .video-actions button').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    // Permite que o clique funcione normalmente
                    e.stopPropagation();
                });
            });
        }, 500);
        
        // Atualiza ícone de collapse (módulos vêm minimizados por padrão)
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(btn => {
            const target = document.querySelector(btn.getAttribute('data-bs-target'));
            if (target) {
                // Módulos vêm minimizados (sem classe 'show')
                const isExpanded = target.classList.contains('show');
                const icon = btn.querySelector('i');
                if (icon) {
                    icon.className = isExpanded ? 'fa-solid fa-chevron-down' : 'fa-solid fa-chevron-right';
                }
                
                target.addEventListener('shown.bs.collapse', function() {
                    const icon = btn.querySelector('i');
                    if (icon) icon.className = 'fa-solid fa-chevron-down';
                });
                target.addEventListener('hidden.bs.collapse', function() {
                    const icon = btn.querySelector('i');
                    if (icon) icon.className = 'fa-solid fa-chevron-right';
                });
            }
        });
    });
    
    // Função para inicializar drag and drop
    function initSortableModulos() {
        const modulosList = document.getElementById('modulosList');
        if (!modulosList) {
            console.warn('modulosList não encontrado');
            return;
        }
        
        // Verifica se já existe uma instância do Sortable e destrói
        if (modulosList.sortableInstance) {
            modulosList.sortableInstance.destroy();
        }
        
        if (typeof Sortable === 'undefined') {
            console.error('SortableJS não foi carregado. Verifique se o script está incluído.');
            // Tenta carregar novamente
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js';
            script.onload = function() {
                initSortableModulos();
            };
            document.head.appendChild(script);
            return;
        }
        
        try {
            const sortable = new Sortable(modulosList, {
                handle: '.drag-handle',
                animation: 200,
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                chosenClass: 'sortable-chosen',
                forceFallback: false,
                fallbackTolerance: 3,
                swapThreshold: 0.65,
                group: 'modulos',
                // Remove filter para permitir cliques nos botões - apenas o handle inicia o drag
                onStart: function(evt) {
                    evt.item.style.opacity = '0.6';
                    evt.item.style.cursor = 'grabbing';
                    // Desabilita cliques em botões durante o drag
                    const buttons = evt.item.querySelectorAll('button, a');
                    buttons.forEach(btn => {
                        btn.style.pointerEvents = 'none';
                    });
                },
                onEnd: function(evt) {
                    evt.item.style.opacity = '1';
                    evt.item.style.cursor = '';
                    // Reabilita cliques em botões após drag
                    const buttons = evt.item.querySelectorAll('button, a');
                    buttons.forEach(btn => {
                        btn.style.pointerEvents = '';
                    });
                    
                    // Atualiza ordem no banco
                    const modulos = Array.from(modulosList.querySelectorAll('.module-item'));
                    const ordem = [];
                    
                    modulos.forEach((item, index) => {
                        const moduloId = item.getAttribute('data-modulo-id');
                        if (moduloId) {
                            ordem.push({
                                id: parseInt(moduloId),
                                ordem: index + 1
                            });
                        }
                    });
                    
                    if (ordem.length === 0) return;
                    
                    // Envia para o servidor
                    fetch('{{ route("produtos.modulos.reorder") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ modulos: ordem })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erro na resposta do servidor: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Atualiza atributos data-ordem
                            modulos.forEach((item, index) => {
                                item.setAttribute('data-ordem', index + 1);
                            });
                            
                            // Feedback visual de sucesso
                            const toast = document.createElement('div');
                            toast.className = 'alert alert-success position-fixed top-0 end-0 m-3';
                            toast.style.zIndex = '9999';
                            toast.style.minWidth = '250px';
                            toast.innerHTML = '<i class="fa-solid fa-check"></i> Ordem atualizada com sucesso!';
                            document.body.appendChild(toast);
                            setTimeout(() => {
                                toast.style.transition = 'opacity 0.3s';
                                toast.style.opacity = '0';
                                setTimeout(() => toast.remove(), 300);
                            }, 2000);
                        } else {
                            throw new Error('Resposta do servidor indicou falha');
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao reordenar:', error);
                        alert('Erro ao salvar ordem. A página será recarregada.');
                        setTimeout(() => location.reload(), 1000);
                    });
                }
            });
            
            // Salva a instância para poder destruir depois se necessário
            modulosList.sortableInstance = sortable;
            
            console.log('✅ Drag and drop inicializado com sucesso para', modulosList.querySelectorAll('.module-item').length, 'módulos');
        } catch (error) {
            console.error('❌ Erro ao inicializar Sortable:', error);
        }
    }
    
    // Observa mudanças no tema (dark mode)
    function observeThemeChanges() {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    // Re-inicializa ícones Lucide quando tema muda
                    setTimeout(() => {
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    }, 100);
                }
            });
        });
        
        // Observa mudanças no body e html
        observer.observe(document.body, { attributes: true, attributeFilter: ['class'] });
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class', 'data-theme'] });
    }
    
    // Inicia observação de mudanças de tema
    observeThemeChanges();
    
    // Busca de ícones
    const iconSearch = document.getElementById('iconSearch');
    if (iconSearch) {
        iconSearch.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const items = document.querySelectorAll('.icon-item');
            
            items.forEach(item => {
                const iconName = item.getAttribute('data-icon-name').toLowerCase();
                if (iconName.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
    
    // Reinicializa ícones quando o modal é aberto
    const iconModal = document.getElementById('iconPickerModal');
    if (iconModal) {
        iconModal.addEventListener('shown.bs.modal', function() {
            lucide.createIcons();
            // Limpa busca ao abrir
            if (iconSearch) {
                iconSearch.value = '';
                document.querySelectorAll('.icon-item').forEach(item => {
                    item.style.display = '';
                });
            }
        });
    }
</script>
