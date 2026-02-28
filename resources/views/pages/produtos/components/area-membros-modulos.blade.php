@php
    $produto->load(['modulos.sessoes.videos', 'categories.files']);
@endphp

<style>
/* Estilos para Modais - Design Minimalista e Intuitivo */
/* Garantir que modais sejam renderizados no body, não dentro de containers */
.modal {
    z-index: 9999 !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    overflow: hidden !important;
}

.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.4) !important;
    z-index: 9998 !important;
    opacity: 1 !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
}

body.dark-mode .modal-backdrop {
    background-color: rgba(0, 0, 0, 0.6) !important;
}

.modal-backdrop.show {
    opacity: 1 !important;
}

.modal-backdrop.fade {
    opacity: 0;
}

.modal-backdrop.fade.show {
    opacity: 1;
}

.modal-dialog {
    margin: 1.75rem auto;
    max-width: 600px;
    z-index: 10000 !important;
    position: relative !important;
}

.modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    background: #ffffff;
    position: relative !important;
    overflow: visible !important;
    z-index: 10001 !important;
}

body.dark-mode .modal-content {
    background: #0f172a;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
}

.modal-header {
    border-bottom: 1px solid #e5e7eb;
    padding: 20px 24px;
    border-radius: 12px 12px 0 0;
    background: #ffffff;
}

body.dark-mode .modal-header {
    border-bottom-color: #1e293b;
    background: #0f172a;
}

.modal-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

body.dark-mode .modal-title {
    color: #e2e8f0;
}

.btn-close {
    opacity: 0.5;
    transition: opacity 0.2s;
}

.btn-close:hover {
    opacity: 1;
}

body.dark-mode .btn-close {
    filter: invert(1);
}

.modal-body {
    padding: 24px;
    background: #ffffff;
}

body.dark-mode .modal-body {
    background: #0f172a;
}

.modal-footer {
    border-top: 1px solid #e5e7eb;
    padding: 16px 24px;
    border-radius: 0 0 12px 12px;
    background: #ffffff;
}

body.dark-mode .modal-footer {
    border-top-color: #1e293b;
    background: #0f172a;
}

.modal-footer .btn {
    border-radius: 8px;
    padding: 8px 20px;
    font-weight: 500;
}

/* Modais compactos - módulos e sessões */
.modal-compact .modal-dialog { max-width: 420px; }
.modal-compact .modal-header { padding: 12px 18px; border-radius: 10px 10px 0 0; }
.modal-compact .modal-title { font-size: 15px; font-weight: 600; }
.modal-compact .modal-body { padding: 16px 18px; }
.modal-compact .modal-footer { padding: 12px 18px; border-radius: 0 0 10px 10px; }
.modal-compact .modal-footer .btn { padding: 6px 16px; font-size: 13px; }
.modal-compact .mb-3 { margin-bottom: 10px !important; }
.modal-compact .mb-3:last-child { margin-bottom: 0 !important; }
.modal-compact .form-label { font-size: 12px; margin-bottom: 4px; font-weight: 500; opacity: 0.95; }
.modal-compact .form-control { padding: 8px 12px; font-size: 13px; border-radius: 6px; }
.modal-compact textarea.form-control { min-height: 56px; resize: vertical; max-height: 80px; }
.modal-compact small.text-muted { font-size: 11px; display: block; margin-top: 2px; line-height: 1.3; }
.modal-compact .input-group .form-control { padding: 8px 12px; font-size: 13px; }
.modal-compact .input-group .btn { padding: 8px 12px; font-size: 13px; }
.modal-compact .form-check-label { font-size: 13px; }
.modal-compact .preview-thumb,
.modal-compact .preview-wrap img,
.modal-compact #capaPreview img,
.modal-compact #addSessaoCapaPreviewImg,
.modal-compact #editSessaoCapaPreview img { max-width: 72px; max-height: 48px; object-fit: cover; border-radius: 6px; border: 1px solid rgba(0,0,0,0.08); }
body.dark-mode .modal-compact .preview-thumb,
body.dark-mode .modal-compact .preview-wrap img { border-color: rgba(255,255,255,0.1); }
.modal-compact .preview-wrap { display: inline-block; margin-top: 6px; }

.form-label {
    font-weight: 500;
    color: #111827;
    margin-bottom: 8px;
    font-size: 14px;
}

body.dark-mode .form-label {
    color: #e2e8f0;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 10px 14px;
    font-size: 14px;
    transition: all 0.2s;
    background: #ffffff;
    color: #111827;
}

body.dark-mode .form-control,
body.dark-mode .form-select {
    background: #1e293b;
    border-color: #334155;
    color: #e2e8f0;
}

.form-control:focus, .form-select:focus {
    border-color: var(--gateway-primary-color, #0b6856);
    box-shadow: 0 0 0 3px rgba(11, 104, 86, 0.1);
    outline: none;
}

body.dark-mode .form-control:focus,
body.dark-mode .form-select:focus {
    border-color: var(--gateway-primary-color, #0b6856);
    box-shadow: 0 0 0 3px rgba(11, 104, 86, 0.2);
}

.form-control::placeholder {
    color: #9ca3af;
}

body.dark-mode .form-control::placeholder {
    color: #64748b;
}

.input-group .btn {
    border-radius: 0 8px 8px 0;
    border-left: none;
}

.input-group .form-control {
    border-radius: 8px 0 0 8px;
}

.form-check-input {
    border-radius: 4px;
    border: 1px solid #e5e7eb;
}

body.dark-mode .form-check-input {
    background: #1e293b;
    border-color: #334155;
}

.form-check-input:checked {
    background-color: var(--gateway-primary-color, #0b6856);
    border-color: var(--gateway-primary-color, #0b6856);
}

.form-check-label {
    color: #111827;
    font-weight: 400;
}

body.dark-mode .form-check-label {
    color: #e2e8f0;
}

.text-muted {
    color: #6b7280;
    font-size: 13px;
}

body.dark-mode .text-muted {
    color: #94a3b8;
}

/* Lista de vídeos no modal */
.list-group-item {
    border: 1px solid #e5e7eb;
    background: #ffffff;
}

body.dark-mode .list-group-item {
    border-color: #334155;
    background: #1e293b;
    color: #e2e8f0;
}

body.dark-mode .video-title-item {
    color: #e2e8f0;
}

body.dark-mode .video-url-item {
    color: #94a3b8;
}

body.dark-mode .list-group-item:hover {
    background: #334155;
}

body.dark-mode .btn-group .btn {
    border-color: #475569;
}

body.dark-mode .btn-group .btn-outline-primary {
    color: #60a5fa !important;
    border-color: #60a5fa !important;
    background: transparent !important;
}

body.dark-mode .btn-group .btn-outline-primary:hover {
    background: #60a5fa !important;
    color: #0f172a !important;
    border-color: #60a5fa !important;
}

body.dark-mode .btn-group .btn-outline-danger {
    color: #f87171 !important;
    border-color: #f87171 !important;
    background: transparent !important;
}

body.dark-mode .btn-group .btn-outline-danger:hover {
    background: #f87171 !important;
    color: #0f172a !important;
    border-color: #f87171 !important;
}

/* Estilos específicos para botões na lista de vídeos */
.video-list-item .btn-group .btn {
    border-width: 1px;
    font-weight: 500;
}

.video-list-item .btn-group .btn-outline-primary {
    color: #2563eb;
    border-color: #2563eb;
    background: transparent;
}

.video-list-item .btn-group .btn-outline-primary:hover {
    background: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
}

.video-list-item .btn-group .btn-outline-danger {
    color: #dc2626;
    border-color: #dc2626;
    background: transparent;
}

.video-list-item .btn-group .btn-outline-danger:hover {
    background: #dc2626;
    color: #ffffff;
    border-color: #dc2626;
}

body.dark-mode .video-list-item .btn-group .btn-outline-primary {
    color: #60a5fa !important;
    border-color: #60a5fa !important;
    background: transparent !important;
}

body.dark-mode .video-list-item .btn-group .btn-outline-primary:hover {
    background: #60a5fa !important;
    color: #0f172a !important;
    border-color: #60a5fa !important;
}

body.dark-mode .video-list-item .btn-group .btn-outline-danger {
    color: #f87171 !important;
    border-color: #f87171 !important;
    background: transparent !important;
}

body.dark-mode .video-list-item .btn-group .btn-outline-danger:hover {
    background: #f87171 !important;
    color: #0f172a !important;
    border-color: #f87171 !important;
}

/* Estilos específicos para ícones dos botões de vídeo */
.video-edit-btn i,
.video-delete-btn i {
    color: inherit;
}

.video-list-item .btn-group .btn-outline-primary i {
    color: #2563eb;
}

.video-list-item .btn-group .btn-outline-primary:hover i {
    color: #ffffff;
}

.video-list-item .btn-group .btn-outline-danger i {
    color: #dc2626;
}

.video-list-item .btn-group .btn-outline-danger:hover i {
    color: #ffffff;
}

body.dark-mode .video-list-item .btn-group .btn-outline-primary i {
    color: #60a5fa !important;
}

body.dark-mode .video-list-item .btn-group .btn-outline-primary:hover i {
    color: #0f172a !important;
}

body.dark-mode .video-list-item .btn-group .btn-outline-danger i {
    color: #f87171 !important;
}

body.dark-mode .video-list-item .btn-group .btn-outline-danger:hover i {
    color: #0f172a !important;
}

/* Garantir que ícones sempre tenham cor visível */
.video-list-item .btn-group .btn i {
    display: inline-block;
}

/* Light mode - garantir visibilidade */
.video-list-item .btn-group .btn-outline-primary:not(:hover) i {
    color: #2563eb !important;
}

.video-list-item .btn-group .btn-outline-danger:not(:hover) i {
    color: #dc2626 !important;
}

/* Dark mode - garantir visibilidade */
body.dark-mode .video-list-item .btn-group .btn-outline-primary:not(:hover) i {
    color: #60a5fa !important;
}

body.dark-mode .video-list-item .btn-group .btn-outline-danger:not(:hover) i {
    color: #f87171 !important;
}

body.dark-mode #sessionVideosList {
    border-color: #334155 !important;
    background: #0f172a;
}

body.dark-mode .list-group-item {
    margin-bottom: 8px;
    border-radius: 6px;
}

/* Estilos para drag and drop de vídeos */
.sortable-ghost {
    opacity: 0.4;
    background: #e5e7eb;
}

body.dark-mode .sortable-ghost {
    background: #334155;
}

.video-list-item {
    transition: all 0.2s;
}

.video-list-item:hover {
    background: #f9fafb;
}

body.dark-mode .video-list-item:hover {
    background: #1e293b;
}

/* Responsividade - Área de Membros */
@media (max-width: 768px) {
    .modal-dialog {
        margin: 10px;
        max-width: calc(100% - 20px);
    }
    
    .modal-body {
        padding: 16px;
    }
    
    .modal-header {
        padding: 16px;
    }
    
    .modal-footer {
        padding: 12px 16px;
    }
    
    .netflix-sessions-row {
        gap: 10px;
        padding: 16px 8px 16px 8px;
    }
    
    .netflix-session-card {
        width: 110px;
    }
    
    .netflix-session-cover {
        height: 150px;
    }
    
    .video-list-item {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .video-list-item .btn-group {
        margin-top: 12px;
        margin-left: 0 !important;
        width: 100%;
    }
    
    .video-list-item .btn-group .btn {
        flex: 1;
    }
    
    .list-group-item {
        padding: 12px;
    }
    
    #sessionVideosList {
        max-height: 250px;
    }
}

@media (max-width: 480px) {
    .modal-dialog {
        margin: 5px;
        max-width: calc(100% - 10px);
    }
    
    .netflix-session-card {
        width: 100px;
    }
    
    .netflix-session-cover {
        height: 130px;
    }
    
    .modal-title {
        font-size: 16px;
    }
    
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        width: 100%;
        margin-bottom: 4px;
    }
}

/* Garantir que modais apareçam acima de tudo */
.modal.show {
    display: block !important;
    z-index: 9999 !important;
}

.modal.show .modal-dialog {
    z-index: 10000 !important;
}

/* Garantir que o modal-content seja clicável */
.modal-content {
    position: relative;
    z-index: 10001 !important;
    pointer-events: auto;
    overflow: visible !important;
}

/* Garantir que elementos dentro do modal sejam interativos */
.modal-content * {
    pointer-events: auto;
}

/* Garantir que containers pais não afetem o modal */
.card,
.card-body,
.tab-content,
.tab-pane,
.area-membros-content,
.area-membros-container {
    overflow: visible !important;
    position: relative;
}

/* Quando modal está aberto, desabilita overflow no body */
body.modal-open {
    overflow: hidden !important;
    padding-right: 0 !important;
}

/* Garantir que modais não sejam afetados por overflow de containers */
.modal,
.modal.show {
    overflow: visible !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    display: none !important;
}

.modal.show {
    display: block !important;
}

.modal-dialog {
    overflow: visible !important;
    max-height: calc(100vh - 3.5rem);
    overflow-y: auto;
}

.modal-body {
    overflow: visible !important;
    max-height: calc(100vh - 200px);
    overflow-y: auto;
}

/* Ajustes para inputs de arquivo */
input[type="file"] {
    padding: 8px;
}

body.dark-mode input[type="file"] {
    background: #1e293b;
    color: #e2e8f0;
}

/* Estilos para o seletor de ícones */
.icon-item {
    cursor: pointer;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    margin: 4px;
    padding: 12px 8px;
    background: #f9fafb;
    transition: all 0.2s;
    text-align: center;
}

body.dark-mode .icon-item {
    border-color: #334155;
    background: #1e293b;
}

.icon-item:hover {
    background: #f3f4f6;
    border-color: var(--gateway-primary-color, #0b6856);
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

body.dark-mode .icon-item:hover {
    background: #334155;
    border-color: var(--gateway-primary-color, #0b6856);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.icon-item small {
    color: #6b7280;
    font-size: 9px;
    word-break: break-all;
    display: block;
    margin-top: 4px;
}

body.dark-mode .icon-item small {
    color: #94a3b8;
}

#iconsGrid {
    max-height: 400px;
    overflow-y: auto;
    padding: 8px;
}

body.dark-mode #iconsGrid {
    background: transparent;
}

#iconSearch {
    border-radius: 8px;
    margin-bottom: 16px;
}
/* Design Clean e Bem Espaçado para Módulos */
.module-item {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 16px;
    transition: all 0.2s ease;
}

body.dark-mode .module-item {
    background: #0f172a;
    border-color: #1e293b;
}

.module-item:hover {
    border-color: #d1d5db;
}

body.dark-mode .module-item:hover {
    border-color: #334155;
}

.module-item.sortable-ghost {
    opacity: 0.5;
    background: #f3f4f6;
    border: 2px dashed #9ca3af;
}

body.dark-mode .module-item.sortable-ghost {
    background: #1e293b;
    border-color: #475569;
}

.module-item.sortable-drag {
    opacity: 0.8;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
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

body.dark-mode .drag-handle {
    color: #64748b;
}

.drag-handle:hover {
    background: #f3f4f6;
    color: #6b7280;
}

body.dark-mode .drag-handle:hover {
    background: #1e293b;
    color: #94a3b8;
}

.drag-handle:active {
    cursor: grabbing;
    background: #e5e7eb;
}

body.dark-mode .drag-handle:active {
    background: #334155;
}

.collapse-btn {
    border: none;
    background: #f3f4f6;
    padding: 6px 10px;
    color: #4b5563;
    font-size: 14px;
    border-radius: 6px;
    transition: all 0.2s;
    flex-shrink: 0;
    cursor: pointer;
    min-width: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e5e7eb;
}

body.dark-mode .collapse-btn {
    background: #1e293b;
    border-color: #334155;
    color: #94a3b8;
}

.collapse-btn:hover {
    background: #e5e7eb;
    color: #111827;
    border-color: #d1d5db;
}

body.dark-mode .collapse-btn:hover {
    background: #334155;
    color: #e2e8f0;
    border-color: #475569;
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
    color: var(--gateway-primary-color, #0b6856);
}

body.dark-mode .module-icon {
    color: var(--gateway-primary-color, #0b6856);
}

.module-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin: 0;
    flex: 1;
    min-width: 0;
}

body.dark-mode .module-title {
    color: #e2e8f0;
}

.module-badge {
    font-size: 11px;
    padding: 4px 10px;
    border-radius: 12px;
    font-weight: 500;
    flex-shrink: 0;
}

.module-description {
    font-size: 13px;
    color: #6b7280;
    margin: 8px 0 0 0;
    line-height: 1.5;
}

body.dark-mode .module-description {
    color: #94a3b8;
}

.module-cover {
    margin-top: 12px;
    border-radius: 0;
    max-height: 120px;
    width: auto;
}

.module-actions {
    display: flex;
    gap: 6px;
    flex-shrink: 0;
}

.module-actions .btn {
    padding: 6px 12px;
    font-size: 13px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    transition: all 0.2s;
    background: #ffffff;
}

body.dark-mode .module-actions .btn {
    border-color: #1e293b;
    background: #0f172a;
    color: #94a3b8;
}

.module-actions .btn:hover {
    border-color: #d1d5db;
}

body.dark-mode .module-actions .btn:hover {
    border-color: #475569;
    color: #e2e8f0;
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
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

body.dark-mode .module-content {
    border-top-color: #1e293b;
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

/* Sessões e Vídeos - Design Clean */
.sessions-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

body.dark-mode .sessions-container {
    border-top-color: #1e293b;
}

.session-item {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 16px;
    transition: all 0.2s;
}

body.dark-mode .session-item {
    background: #1e293b;
    border-color: #334155;
}

.session-item:hover {
    border-color: #d1d5db;
}

body.dark-mode .session-item:hover {
    border-color: #475569;
}

.session-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
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

body.dark-mode .session-title {
    color: #e2e8f0;
}

.session-count {
    font-size: 12px;
    color: #6b7280;
}

body.dark-mode .session-count {
    color: #94a3b8;
}

.session-actions {
    display: flex;
    gap: 6px;
}

.session-actions .btn {
    padding: 4px 10px;
    font-size: 12px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    background: #ffffff;
}

body.dark-mode .session-actions .btn {
    border-color: #334155;
    background: #1e293b;
    color: #94a3b8;
}

.videos-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid #e5e7eb;
}

body.dark-mode .videos-list {
    border-top-color: #334155;
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

body.dark-mode .video-item {
    background: #0f172a;
    border-color: #1e293b;
}

.video-item:hover {
    border-color: #d1d5db;
    background: #f9fafb;
}

body.dark-mode .video-item:hover {
    background: #1e293b;
    border-color: #334155;
}

.video-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.video-title {
    font-size: 13px;
    font-weight: 500;
    color: #111827;
}

body.dark-mode .video-title {
    color: #e2e8f0;
}

.video-url {
    font-size: 11px;
    color: #9ca3af;
    word-break: break-all;
}

body.dark-mode .video-url {
    color: #64748b;
}

.video-actions {
    display: flex;
    gap: 6px;
    flex-shrink: 0;
}

.video-actions .btn {
    padding: 4px 8px;
    font-size: 11px;
    border: 1px solid #e5e7eb;
    background: #ffffff;
    color: #6b7280;
    border-radius: 6px;
}

body.dark-mode .video-actions .btn {
    border-color: #1e293b;
    background: #0f172a;
    color: #94a3b8;
}

.video-actions .btn-outline-primary {
    border-color: #e5e7eb;
    color: #3b82f6;
}

body.dark-mode .video-actions .btn-outline-primary {
    border-color: #1e293b;
    color: #60a5fa;
}

.video-actions .btn-outline-primary:hover {
    background: transparent;
    border-color: #3b82f6;
}

body.dark-mode .video-actions .btn-outline-primary:hover {
    border-color: #60a5fa;
    color: #60a5fa;
}

.video-actions .btn-outline-danger {
    border-color: #e5e7eb;
    color: #ef4444;
}

body.dark-mode .video-actions .btn-outline-danger {
    border-color: #1e293b;
    color: #f87171;
}

.video-actions .btn-outline-danger:hover {
    background: transparent;
    border-color: #ef4444;
}

body.dark-mode .video-actions .btn-outline-danger:hover {
    border-color: #f87171;
    color: #f87171;
}

.empty-sessions {
    text-align: center;
    padding: 24px;
    color: #6b7280;
}

body.dark-mode .empty-sessions {
    color: #94a3b8;
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


/* Layout Estilo Netflix */
.netflix-module-row {
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid #e5e7eb;
    overflow: visible;
    position: relative;
}

body.dark-mode .netflix-module-row {
    border-top-color: #1e293b;
}

.netflix-sessions-row {
    overflow: visible;
}

.netflix-module-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.netflix-module-title {
    font-size: 20px;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

body.dark-mode .netflix-module-title {
    color: #e2e8f0;
}

.netflix-sessions-row {
    display: flex;
    gap: 16px;
    overflow-x: auto;
    overflow-y: visible;
    padding: 20px 8px 8px 8px;
    margin: -20px -8px -8px -8px;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}

body.dark-mode .netflix-sessions-row {
    scrollbar-color: #475569 transparent;
}

.netflix-sessions-row::-webkit-scrollbar {
    height: 8px;
}

.netflix-sessions-row::-webkit-scrollbar-track {
    background: transparent;
}

.netflix-sessions-row::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

body.dark-mode .netflix-sessions-row::-webkit-scrollbar-thumb {
    background: #475569;
}

.netflix-session-card {
    flex: 0 0 auto;
    width: 120px;
    cursor: grab;
    transition: transform 0.2s;
    position: relative;
    z-index: 1;
}

.netflix-session-card:active {
    cursor: grabbing;
}

.netflix-session-card:hover {
    transform: scale(1.05);
    z-index: 10;
}

.netflix-session-card.sortable-ghost {
    opacity: 0.5;
}

.netflix-session-card.sortable-drag {
    cursor: grabbing;
    z-index: 100;
}

.netflix-session-number {
    position: absolute;
    top: 4px;
    left: 4px;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #1a1a1a;
    color: #ffffff !important;
    font-size: 11px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    z-index: 5;
    text-shadow: 0 0 1px #000, 0 1px 2px rgba(0,0,0,0.8);
    border: 1px solid rgba(255,255,255,0.3);
}

body.dark-mode .netflix-session-number,
[data-theme="dark"] .netflix-session-number {
    color: #ffffff !important;
    background: #0f0f0f;
    border-color: rgba(255,255,255,0.25);
}

.netflix-session-badge-release {
    position: absolute;
    bottom: 4px;
    left: 4px;
    right: 4px;
    padding: 4px 6px;
    border-radius: 4px;
    background: rgba(0,0,0,0.85);
    color: #fcd34d !important;
    font-size: 10px;
    line-height: 1.2;
    z-index: 5;
    display: flex;
    align-items: center;
    gap: 4px;
    flex-wrap: wrap;
}

.netflix-session-badge-release i {
    flex-shrink: 0;
    color: #fcd34d !important;
}

body.dark-mode .netflix-session-badge-release,
[data-theme="dark"] .netflix-session-badge-release {
    color: #fcd34d !important;
}

body.dark-mode .netflix-session-badge-release i,
[data-theme="dark"] .netflix-session-badge-release i {
    color: #fcd34d !important;
}

.netflix-session-cover {
    width: 100%;
    height: 160px;
    border-radius: 8px;
    overflow: hidden;
    position: relative;
    background: #f3f4f6;
    margin-bottom: 8px;
    z-index: 1;
}

body.dark-mode .netflix-session-cover {
    background: #1e293b;
}

.netflix-session-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.netflix-session-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.2s;
}

.netflix-session-card:hover .netflix-session-overlay {
    opacity: 1;
}

.netflix-session-overlay i {
    font-size: 28px;
    color: #ffffff;
}

/* Admin - Ícone de configuração no overlay */
.netflix-session-card .netflix-session-overlay i.fa-cog {
    font-size: 24px;
}

.netflix-session-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    border: 2px dashed #d1d5db;
}

body.dark-mode .netflix-session-placeholder {
    color: #64748b;
    border-color: #334155;
}

.netflix-session-placeholder i {
    font-size: 28px;
    margin-bottom: 4px;
}

.netflix-session-title {
    font-size: 12px;
    font-weight: 500;
    color: #111827;
    margin-top: 6px;
    text-align: center;
    line-height: 1.3;
}

body.dark-mode .netflix-session-title {
    color: #e2e8f0;
}

.netflix-session-actions {
    display: flex;
    gap: 6px;
    justify-content: center;
    margin-top: 12px;
    opacity: 0;
    transition: opacity 0.2s;
    position: relative;
    z-index: 20;
}

.netflix-session-card:hover .netflix-session-actions {
    opacity: 1;
}

.netflix-session-actions .btn {
    padding: 6px 12px;
    font-size: 12px;
    border-width: 1.5px;
    font-weight: 500;
    transition: all 0.2s;
}

.netflix-session-actions .btn-outline-primary {
    color: #0b6856;
    border-color: #0b6856;
    background: #ffffff;
}

.netflix-session-actions .btn-outline-primary:hover {
    background: #0b6856;
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(11, 104, 86, 0.3);
}

body.dark-mode .netflix-session-actions .btn-outline-primary {
    color: #34d399;
    border-color: #34d399;
    background: #1e293b;
}

body.dark-mode .netflix-session-actions .btn-outline-primary:hover {
    background: #34d399;
    color: #0f172a;
}

.netflix-session-actions .btn-outline-success {
    color: #10b981;
    border-color: #10b981;
    background: #ffffff;
}

.netflix-session-actions .btn-outline-success:hover {
    background: #10b981;
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
}

body.dark-mode .netflix-session-actions .btn-outline-success {
    color: #34d399;
    border-color: #34d399;
    background: #1e293b;
}

body.dark-mode .netflix-session-actions .btn-outline-success:hover {
    background: #34d399;
    color: #0f172a;
}

.netflix-session-actions .btn-outline-danger {
    color: #ef4444;
    border-color: #ef4444;
    background: #ffffff;
}

.netflix-session-actions .btn-outline-danger:hover {
    background: #ef4444;
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
}

body.dark-mode .netflix-session-actions .btn-outline-danger {
    color: #f87171;
    border-color: #f87171;
    background: #1e293b;
}

body.dark-mode .netflix-session-actions .btn-outline-danger:hover {
    background: #f87171;
    color: #0f172a;
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
    
    .netflix-module-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .netflix-session-card {
        width: 110px;
    }
    
    .netflix-session-cover {
        height: 140px;
    }
}
</style>

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
                                            onclick="editModulo({{ $modulo->id }}, '{{ addslashes($modulo->nome) }}', '{{ addslashes($modulo->descricao ?? '') }}', '{{ addslashes($modulo->icone ?? '') }}', {{ $modulo->status ? 'true' : 'false' }}, {{ $modulo->ordem }}, '{{ $modulo->liberar_em ? $modulo->liberar_em->format('Y-m-d\TH:i') : '' }}', {{ $modulo->liberar_em_dias ?? 'null' }})"
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
                                <div class="netflix-module-row">
                                    <div class="netflix-module-header">
                                        <h3 class="netflix-module-title">{{ $modulo->nome }}</h3>
                                        <div class="netflix-module-actions">
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="addSessao({{ $modulo->id }})"
                                                    title="Adicionar sessão">
                                                <i class="fa-solid fa-plus"></i> Adicionar Sessão
                                            </button>
                                        </div>
                                    </div>
                                    <div class="netflix-sessions-row sessoes-sortable-row" id="sessoesRowModulo{{ $modulo->id }}" data-modulo-id="{{ $modulo->id }}">
                                        @foreach($modulo->sessoes->sortBy('ordem') as $sessao)
                                            <div class="netflix-session-card" 
                                                 data-sessao-id="{{ $sessao->id }}"
                                                 data-sessao-nome="{{ addslashes($sessao->nome) }}"
                                                 data-sessao-descricao="{{ addslashes($sessao->descricao ?? '') }}"
                                                 data-sessao-status="{{ $sessao->status ? '1' : '0' }}"
                                                 data-sessao-liberar-em="{{ $sessao->liberar_em ? $sessao->liberar_em->format('Y-m-d\TH:i') : '' }}"
                                                 data-sessao-liberar-em-dias="{{ $sessao->liberar_em_dias ?? '' }}"
                                                 data-sessao-videos="{{ $sessao->videos->count() }}">
                                                <span class="netflix-session-number">{{ $loop->iteration }}</span>
                                                @if($sessao->liberar_em || $sessao->liberar_em_dias)
                                                    <div class="netflix-session-badge-release">
                                                        @if($sessao->liberar_em)
                                                            <i class="fa-regular fa-calendar-check"></i> {{ $sessao->liberar_em->format('d/m/Y') }}
                                                        @elseif($sessao->liberar_em_dias)
                                                            <i class="fa-solid fa-clock"></i> Libera em {{ $sessao->liberar_em_dias }} dia(s)
                                                        @endif
                                                    </div>
                                                @endif
                                                @if($sessao->capa)
                                                    <div class="netflix-session-cover" onclick="openSessionModal({{ $sessao->id }}, {{ $modulo->id }}, '{{ addslashes($sessao->nome) }}', {{ $sessao->videos->count() }})">
                                                        <img src="/storage/{{ ltrim($sessao->capa, '/') }}" alt="{{ $sessao->nome }}">
                                                        <div class="netflix-session-overlay">
                                                            <i class="fa-solid fa-cog"></i>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="netflix-session-cover netflix-session-placeholder" onclick="openSessionModal({{ $sessao->id }}, {{ $modulo->id }}, '{{ addslashes($sessao->nome) }}', {{ $sessao->videos->count() }})">
                                                        <i class="fa-solid fa-image"></i>
                                                        <span>Sem capa</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
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
<div class="modal fade modal-compact" id="addModuloModal" tabindex="-1" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Novo Módulo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="form-content-modulo">
                <input type="hidden" name="produto_id" value="{{ $produto->id }}" />
                <div class="mb-3">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" autofocus class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                </div>
                <div class="mb-3">
                    <label for="icone" class="form-label">Ícone</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="icone" id="moduloIconeInput" placeholder="Ícone" readonly>
                        <button type="button" class="btn btn-outline-primary" onclick="openIconPicker('moduloIconeInput')"><i class="fa-solid fa-icons"></i></button>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="liberar_em_modulo" class="form-label">Liberar em (data/hora)</label>
                    <input type="datetime-local" class="form-control" id="liberar_em_modulo" name="liberar_em">
                    <small class="form-text text-muted">Ou use "Liberar em X dias" abaixo.</small>
                </div>
                <div class="mb-3">
                    <label for="liberar_em_dias_modulo" class="form-label">Liberar em X dias</label>
                    <input type="number" class="form-control" id="liberar_em_dias_modulo" name="liberar_em_dias" min="0" placeholder="Ex: 8" style="max-width: 120px;">
                    <small class="form-text text-muted">Liberar este módulo X dias após o aluno ter acesso ao curso.</small>
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
// Cria form dinamicamente para adicionar módulo
document.addEventListener('DOMContentLoaded', function() {
    const btnSubmitModulo = document.getElementById('btn-form-modulo');
    const formContainerModulo = document.getElementById('form-content-modulo');

    if (btnSubmitModulo && formContainerModulo) {
        // Criar <form> e envolver o conteúdo
        const formModulo = document.createElement('form');
        formModulo.method = 'POST';
        formModulo.action = "{{ route('produtos.modulos.store') }}";
        formModulo.enctype = 'multipart/form-data';

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
<div class="modal fade modal-compact" id="editModuloModal" tabindex="-1" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered">
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
                        <label class="form-label">Nome</label>
                        <input type="text" class="form-control" name="nome" id="editModuloNome" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea class="form-control" name="descricao" id="editModuloDescricao" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ícone</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="icone" id="editModuloIcone" placeholder="Ícone" readonly>
                            <button type="button" class="btn btn-outline-primary" onclick="openIconPicker('editModuloIcone')"><i class="fa-solid fa-icons"></i></button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Liberar em (data/hora)</label>
                        <input type="datetime-local" class="form-control" name="liberar_em" id="editModuloLiberarEm">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Liberar em X dias</label>
                        <input type="number" class="form-control" name="liberar_em_dias" id="editModuloLiberarEmDias" min="0" placeholder="Ex: 8" style="max-width: 120px;">
                        <small class="form-text text-muted">Liberar este módulo X dias após o aluno ter acesso ao curso.</small>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="editModuloStatus">
                            <label class="form-check-label" for="editModuloStatus">Ativo</label>
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
// Limpa formulário ao fechar modal de editar módulo
document.getElementById('editModuloModal')?.addEventListener('hidden.bs.modal', function() {
    document.getElementById('editModuloForm')?.reset();
});

// Garantir z-index dos modais - usa valores do fix global (10060+) para ficar acima do backdrop
document.addEventListener('DOMContentLoaded', function() {
    var BASE_MODAL = 10060;
    function fixModalsZIndex() {
        document.querySelectorAll('.modal.show').forEach(function(modal, i) {
            modal.style.zIndex = (BASE_MODAL + i * 10).toString();
            modal.style.position = 'fixed';
            var dialog = modal.querySelector('.modal-dialog');
            var content = modal.querySelector('.modal-content');
            if (dialog) dialog.style.zIndex = '1';
            if (content) content.style.zIndex = '2';
        });
        document.querySelectorAll('.modal-backdrop').forEach(function(b, i) {
            b.style.zIndex = (10050 + i * 10).toString();
        });
    }
    document.querySelectorAll('.modal').forEach(function(modal) {
        if (modal.parentElement !== document.body) document.body.appendChild(modal);
        modal.addEventListener('show.bs.modal', function() {
            if (modal.parentElement !== document.body) document.body.appendChild(modal);
        }, { once: false });
        modal.addEventListener('shown.bs.modal', fixModalsZIndex, { once: false });
    });
    document.body.addEventListener('shown.bs.modal', fixModalsZIndex, true);
});
</script>

<!-- Modal Adicionar Sessão -->
<div class="modal fade modal-compact" id="addSessaoModal" tabindex="-1" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="addSessaoForm" method="POST" action="{{ route('produtos.sessoes.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="modulo_id" id="addSessaoModuloId">
                <div class="modal-header">
                    <h5 class="modal-title">Nova Sessão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" class="form-control" name="nome" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea class="form-control" name="descricao" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Capa *</label>
                        <input type="file" class="form-control" name="capa" accept="image/*" required>
                        <div id="addSessaoCapaPreview" class="preview-wrap" style="display: none;"><img id="addSessaoCapaPreviewImg" src="" alt="" class="preview-thumb"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Liberar em (data/hora)</label>
                        <input type="datetime-local" class="form-control" name="liberar_em" id="addSessaoLiberarEm">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Liberar em X dias</label>
                        <input type="number" class="form-control" name="liberar_em_dias" id="addSessaoLiberarEmDias" min="0" placeholder="Ex: 8" style="max-width: 120px;">
                        <small class="form-text text-muted">Liberar esta sessão X dias após o aluno ter acesso ao curso.</small>
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
    const form = document.getElementById('addSessaoForm');
    if (form) {
        form.reset();
        form.action = '{{ route("produtos.sessoes.store") }}';
    }
    const statusInput = document.querySelector('#addSessaoModal input[name="status"]');
    if (statusInput) statusInput.checked = true;
    const preview = document.getElementById('addSessaoCapaPreview');
    if (preview) preview.style.display = 'none';
});

// Handler para submissão do formulário de adicionar sessão
document.addEventListener('DOMContentLoaded', function() {
    const addSessaoForm = document.getElementById('addSessaoForm');
    if (addSessaoForm) {
        addSessaoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const moduloId = document.getElementById('addSessaoModuloId').value;
            const nome = form.querySelector('input[name="nome"]').value.trim();
            const capa = form.querySelector('input[name="capa"]').files[0];
            
            // Validação
            if (!nome) {
                alert('Por favor, preencha o nome da sessão.');
                form.querySelector('input[name="nome"]').focus();
                return;
            }
            
            if (!capa) {
                alert('Por favor, selecione uma capa para a sessão.');
                form.querySelector('input[name="capa"]').focus();
                return;
            }
            
            if (!moduloId) {
                alert('Erro: Módulo não identificado.');
                return;
            }
            
            // Desabilita botão de submit para evitar duplo submit
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Salvando...';
            }
            
            // Cria FormData para enviar arquivo
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('modulo_id', moduloId);
            formData.append('nome', nome);
            
            const descricao = form.querySelector('textarea[name="descricao"]').value;
            if (descricao) {
                formData.append('descricao', descricao);
            }
            
            formData.append('capa', capa);
            
            const liberarEmInput = form.querySelector('input[name="liberar_em"]');
            if (liberarEmInput && liberarEmInput.value) {
                formData.append('liberar_em', liberarEmInput.value);
            }
            
            const statusInput = form.querySelector('input[name="status"]');
            if (statusInput && statusInput.checked) {
                formData.append('status', '1');
            }
            
            // Envia via fetch
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                redirect: 'follow' // Segue redirects normalmente
            })
            .then(response => {
                // Se a resposta é um redirect, o fetch já seguiu
                // Verifica se a resposta é HTML (redirect do Laravel)
                const contentType = response.headers.get('content-type');
                
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // Se não for JSON, provavelmente é HTML (redirect do Laravel)
                    // O Laravel retorna redirect, então seguimos o redirect
                    // Fecha modal primeiro
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addSessaoModal'));
                    if (modal) modal.hide();
                    // Recarrega a página - o Laravel já redirecionou
                    window.location.reload();
                    return null;
                }
            })
            .then(data => {
                if (data) {
                    if (data.success) {
                        // Fecha modal e recarrega página
                        const modal = bootstrap.Modal.getInstance(document.getElementById('addSessaoModal'));
                        if (modal) modal.hide();
                        window.location.href = window.location.pathname + '#modulos';
                        setTimeout(() => {
                            window.location.reload();
                        }, 100);
                    } else {
                        alert(data.error || 'Erro ao criar sessão.');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = 'Salvar';
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                // Em caso de erro, tenta submit normal como fallback
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Salvar';
                }
                // Remove o listener e faz submit normal
                form.removeEventListener('submit', arguments.callee);
                form.submit();
            });
        });
    }
});
</script>

<!-- Modal Editar Sessão -->
<div class="modal fade modal-compact" id="editSessaoModal" tabindex="-1" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editSessaoForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="editSessaoId" value="">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Sessão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" class="form-control" name="nome" id="editSessaoNome" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea class="form-control" name="descricao" id="editSessaoDescricao" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Capa</label>
                        <input type="file" class="form-control" name="capa" accept="image/*">
                        <div id="editSessaoCapaPreview" class="preview-wrap" style="margin-top: 6px; min-height: 0;"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Liberar em (data/hora)</label>
                        <input type="datetime-local" class="form-control" name="liberar_em" id="editSessaoLiberarEm">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Liberar em X dias</label>
                        <input type="number" class="form-control" name="liberar_em_dias" id="editSessaoLiberarEmDias" min="0" placeholder="Ex: 8" style="max-width: 120px;">
                        <small class="form-text text-muted">Liberar esta sessão X dias após o aluno ter acesso ao curso.</small>
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
// Preview de capa ao adicionar sessão
document.addEventListener('DOMContentLoaded', function() {
    const addSessaoForm = document.getElementById('addSessaoForm');
    if (addSessaoForm) {
        const capaInput = addSessaoForm.querySelector('input[name="capa"]');
        if (capaInput) {
            capaInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('addSessaoCapaPreview');
                        const previewImg = document.getElementById('addSessaoCapaPreviewImg');
                        if (preview && previewImg) {
                            previewImg.src = e.target.result;
                            preview.style.display = 'block';
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    }
});

// Limpa formulário ao fechar modal de editar sessão
document.getElementById('editSessaoModal')?.addEventListener('hidden.bs.modal', function() {
    document.getElementById('editSessaoForm')?.reset();
    const capaPreview = document.getElementById('editSessaoCapaPreview');
    if (capaPreview) capaPreview.innerHTML = '';
});
</script>

<!-- Modal Adicionar Vídeo -->
<div class="modal fade" id="addVideoModal" tabindex="-1" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered">
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
<div class="modal fade" id="editVideoModal" tabindex="-1" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered">
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
// Função auxiliar para garantir que modal esteja no body e com z-index correto
function ensureModalInBody(modalElement) {
    if (modalElement && modalElement.parentElement !== document.body) {
        document.body.appendChild(modalElement);
    }
    if (modalElement) {
        modalElement.style.zIndex = '9999';
        modalElement.style.position = 'fixed';
    }
}

function editModulo(id, nome, descricao, icone, status, ordem, liberarEm, liberarEmDias) {
    const editModal = document.getElementById('editModuloModal');
    const editForm = document.getElementById('editModuloForm');
    
    if (!editModal || !editForm) {
        alert('Erro: Modal de edição não encontrado. Recarregue a página.');
        return;
    }
    
    editForm.action = '{{ route("produtos.modulos.update") }}';
    const idInput = document.getElementById('editModuloId');
    const nomeInput = document.getElementById('editModuloNome');
    const descInput = document.getElementById('editModuloDescricao');
    const iconInput = document.getElementById('editModuloIcone');
    const statusInput = document.getElementById('editModuloStatus');
    const liberarEmInput = document.getElementById('editModuloLiberarEm');
    const liberarEmDiasInput = document.getElementById('editModuloLiberarEmDias');
    
    if (idInput) idInput.value = id;
    if (nomeInput) nomeInput.value = nome || '';
    if (descInput) descInput.value = descricao || '';
    if (iconInput) iconInput.value = icone || '';
    if (liberarEmInput) liberarEmInput.value = liberarEm || '';
    if (liberarEmDiasInput) liberarEmDiasInput.value = (liberarEmDias !== null && liberarEmDias !== undefined && liberarEmDias !== '') ? liberarEmDias : '';
    if (statusInput) {
        statusInput.checked = status === true || status === 'true' || status === 1 || status === '1';
    }
    
    
    // Remove qualquer backdrop existente
    const existingBackdrops = document.querySelectorAll('.modal-backdrop');
    existingBackdrops.forEach(b => b.remove());
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    
    // Garante que modal está no body
    if (editModal && editModal.parentElement !== document.body) {
        document.body.appendChild(editModal);
    }
    if (editModal) {
        editModal.style.zIndex = '9999';
        editModal.style.position = 'fixed';
    }
    
    // Abre o modal corretamente
    const modalInstance = bootstrap.Modal.getOrCreateInstance(editModal, {
        backdrop: true,
        keyboard: true,
        focus: true
    });
    
    modalInstance.show();
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
    const modal = document.getElementById('addSessaoModal');
    const form = document.getElementById('addSessaoForm');
    
    // Garante que modal está no body
    if (modal && modal.parentElement !== document.body) {
        document.body.appendChild(modal);
    }
    if (modal) {
        modal.style.zIndex = '9999';
        modal.style.position = 'fixed';
    }
    
    // Configura formulário
    if (form) {
        form.action = '{{ route("produtos.sessoes.store") }}';
        form.enctype = 'multipart/form-data';
        document.getElementById('addSessaoModuloId').value = moduloId;
        form.reset();
        // Garante que o modulo_id não seja perdido no reset
        document.getElementById('addSessaoModuloId').value = moduloId;
        
        // Limpa preview
        const preview = document.getElementById('addSessaoCapaPreview');
        if (preview) preview.style.display = 'none';
    }
    
    const modalInstance = bootstrap.Modal.getOrCreateInstance(modal, {
        backdrop: true,
        keyboard: true,
        focus: true
    });
    modalInstance.show();
}

function editSessao(id, nome, descricao, status, liberarEm, liberarEmDias) {
    const modal = document.getElementById('editSessaoModal');
    // Garante que modal está no body
    if (modal && modal.parentElement !== document.body) {
        document.body.appendChild(modal);
    }
    if (modal) {
        modal.style.zIndex = '9999';
        modal.style.position = 'fixed';
    }
    document.getElementById('editSessaoForm').action = '{{ route("produtos.sessoes.update") }}';
    const idInput = document.getElementById('editSessaoId');
    const nomeInput = document.getElementById('editSessaoNome');
    const descInput = document.getElementById('editSessaoDescricao');
    const statusInput = document.getElementById('editSessaoStatus');
    const liberarEmInput = document.getElementById('editSessaoLiberarEm');
    const liberarEmDiasInput = document.getElementById('editSessaoLiberarEmDias');
    
    if (idInput) idInput.value = id;
    if (nomeInput) nomeInput.value = nome || '';
    if (descInput) descInput.value = descricao || '';
    if (liberarEmInput) liberarEmInput.value = liberarEm || '';
    if (liberarEmDiasInput) liberarEmDiasInput.value = (liberarEmDias !== null && liberarEmDias !== undefined && liberarEmDias !== '') ? liberarEmDias : '';
    if (statusInput) {
        statusInput.checked = status === true || status === 'true' || status === 1 || status === '1';
    }
    
    // Mostra capa atual se existir
    const capaPreview = document.getElementById('editSessaoCapaPreview');
    if (capaPreview) {
        const sessaoCard = document.querySelector(`[data-sessao-id="${id}"]`);
        if (sessaoCard) {
            const capaImg = sessaoCard.querySelector('.netflix-session-cover img');
            if (capaImg && capaImg.src && !capaImg.src.includes('data:image')) {
                const borderColor = document.body.classList.contains('dark-mode') ? '#334155' : '#e5e7eb';
                const bgColor = document.body.classList.contains('dark-mode') ? '#1e293b' : '#f9fafb';
                capaPreview.innerHTML = `<img src="${capaImg.src}" alt="Capa" class="preview-thumb">`;
            } else {
                capaPreview.innerHTML = '';
            }
        }
    }
    
    // Preview ao mudar arquivo
    const capaInput = document.querySelector('#editSessaoForm input[name="capa"]');
    if (capaInput) {
        // Remove listeners antigos
        const newCapaInput = capaInput.cloneNode(true);
        capaInput.parentNode.replaceChild(newCapaInput, capaInput);
        
        newCapaInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('editSessaoCapaPreview');
                    if (preview) {
                        const borderColor = document.body.classList.contains('dark-mode') ? '#334155' : '#e5e7eb';
                        const bgColor = document.body.classList.contains('dark-mode') ? '#1e293b' : '#f9fafb';
                        preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="preview-thumb">`;
                    }
                };
                reader.readAsDataURL(file);
            } else {
                // Limpa preview se não houver arquivo
                const preview = document.getElementById('editSessaoCapaPreview');
                if (preview) {
                    preview.innerHTML = '';
                }
            }
        });
    }
    
    const modalInstance = bootstrap.Modal.getOrCreateInstance(modal, {
        backdrop: true,
        keyboard: true,
        focus: true
    });
    modalInstance.show();
}

// Função para abrir modal de gerenciamento da sessão (admin)
function openSessionModal(sessaoId, moduloId, sessaoNome, videoCount) {
    if (window._sessaoDragging) return;
    document.getElementById('sessionModalSessaoId').value = sessaoId;
    document.getElementById('sessionModalModuloId').value = moduloId;
    document.getElementById('sessionModalNome').textContent = sessaoNome;
    document.getElementById('sessionModalVideoCount').textContent = videoCount + ' vídeo(s)';
    
    // Carrega lista de vídeos da sessão
    loadVideosList(sessaoId);
    
    const modal = document.getElementById('sessionManagementModal');
    // Garante que modal está no body
    if (modal && modal.parentElement !== document.body) {
        document.body.appendChild(modal);
    }
    if (modal) {
        modal.style.zIndex = '9999';
        modal.style.position = 'fixed';
    }
    
    const modalInstance = bootstrap.Modal.getOrCreateInstance(modal, {
        backdrop: true,
        keyboard: true,
        focus: true
    });
    modalInstance.show();
}

// Função para carregar lista de vídeos
function loadVideosList(sessaoId) {
    const videosContainer = document.getElementById('sessionVideosList');
    if (!videosContainer) return;
    
    // Busca vídeos da sessão no DOM
    const sessaoCard = document.querySelector(`[data-sessao-id="${sessaoId}"]`);
    if (!sessaoCard) {
        videosContainer.innerHTML = '<p class="text-muted">Carregando vídeos...</p>';
        return;
    }
    
    // Busca vídeos via atributos data ou via fetch
    videosContainer.innerHTML = '<p class="text-muted">Carregando vídeos...</p>';
    
    // Busca vídeos via fetch
    fetch(`/produtos/sessoes/${sessaoId}/videos`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na resposta do servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.videos) {
            renderVideosList(data.videos);
        } else {
            videosContainer.innerHTML = '<p class="text-muted">Nenhum vídeo encontrado.</p>';
        }
    })
    .catch(error => {
        console.error('Erro ao carregar vídeos:', error);
        videosContainer.innerHTML = '<p class="text-muted">Erro ao carregar vídeos. Recarregue a página.</p>';
    });
}

// Função para renderizar lista de vídeos
function renderVideosList(videos) {
    const videosContainer = document.getElementById('sessionVideosList');
    if (!videosContainer) return;
    
    if (videos.length === 0) {
        videosContainer.innerHTML = '<p class="text-muted">Nenhum vídeo adicionado ainda.</p>';
        return;
    }
    
    // Ordena vídeos por ordem
    videos.sort((a, b) => (a.ordem || 0) - (b.ordem || 0));
    
    let html = '<div class="list-group" id="videosSortableList">';
    videos.forEach((video, index) => {
        const statusClass = video.status ? 'success' : 'secondary';
        const statusIcon = video.status ? 'check' : 'xmark';
        const statusText = video.status ? 'Ativo' : 'Inativo';
        html += `
            <div class="list-group-item d-flex justify-content-between align-items-center video-list-item" data-video-id="${video.id}" style="cursor: move;">
                <div class="d-flex align-items-center flex-grow-1">
                    <i class="fa-solid fa-grip-vertical me-2 text-muted" style="cursor: grab;"></i>
                    <div class="flex-grow-1">
                        <div class="fw-bold video-title-item">${video.titulo || 'Sem título'}</div>
                        <small class="text-muted video-url-item">${video.url_youtube || 'Sem URL'}</small>
                        <div class="mt-1">
                            <span class="badge bg-${statusClass}">${statusText}</span>
                        </div>
                    </div>
                </div>
                <div class="btn-group ms-3">
                    <button class="btn btn-sm btn-outline-primary video-edit-btn" onclick="handleEditVideoFromModal(${video.id}, '${(video.titulo || '').replace(/'/g, "\\'")}', '${(video.descricao || '').replace(/'/g, "\\'")}', '${(video.url_youtube || '').replace(/'/g, "\\'")}', ${video.duracao || 0}, ${video.status ? 1 : 0})" title="Editar vídeo">
                        <i class="fa-solid fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-${statusClass}" onclick="toggleVideoStatus(${video.id}, ${video.status ? 0 : 1})" title="${video.status ? 'Desativar' : 'Ativar'} vídeo">
                        <i class="fa-solid fa-${statusIcon}"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger video-delete-btn" onclick="handleDeleteVideoFromModal(${video.id})" title="Excluir vídeo">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
    });
    html += '</div>';
    videosContainer.innerHTML = html;
    
    // Inicializa SortableJS para reordenar vídeos
    if (typeof Sortable !== 'undefined') {
        const sortableList = document.getElementById('videosSortableList');
        if (sortableList) {
            new Sortable(sortableList, {
                handle: '.fa-grip-vertical',
                animation: 150,
                ghostClass: 'sortable-ghost',
                onEnd: function(evt) {
                    const videoItems = Array.from(sortableList.querySelectorAll('[data-video-id]'));
                    const videoIds = videoItems.map(item => parseInt(item.getAttribute('data-video-id')));
                    saveVideoOrder(videoIds);
                }
            });
        }
    }
}

function saveVideoOrder(videoIds) {
    fetch('{{ route("produtos.videos.reorder") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            videos: videoIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Recarrega lista para atualizar ordem
            const sessaoId = document.getElementById('sessionModalSessaoId').value;
            loadVideosList(sessaoId);
        } else {
            alert('Erro ao salvar ordem dos vídeos.');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao salvar ordem dos vídeos.');
    });
}

function toggleVideoStatus(videoId, newStatus) {
    const produtoUuid = '{{ $produto->uuid }}';
    fetch(`/produtos/${produtoUuid}/videos/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            id: videoId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Recarrega lista de vídeos
            const sessaoId = document.getElementById('sessionModalSessaoId').value;
            loadVideosList(sessaoId);
        } else {
            alert('Erro ao alterar status do vídeo.');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao alterar status do vídeo.');
    });
}

function handleEditVideoFromModal(videoId, titulo, descricao, url, duracao, status) {
    const modal = bootstrap.Modal.getInstance(document.getElementById('sessionManagementModal'));
    if (modal) modal.hide();
    setTimeout(() => {
        editVideo(videoId, titulo, descricao, url, duracao, status);
    }, 300);
}

function handleDeleteVideoFromModal(videoId) {
    if (confirm('Tem certeza que deseja excluir este vídeo?')) {
        const modal = bootstrap.Modal.getInstance(document.getElementById('sessionManagementModal'));
        if (modal) modal.hide();
        setTimeout(() => {
            deleteVideo(videoId);
        }, 300);
    }
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
    const modal = document.getElementById('addVideoModal');
    // Garante que modal está no body
    if (modal && modal.parentElement !== document.body) {
        document.body.appendChild(modal);
    }
    if (modal) {
        modal.style.zIndex = '9999';
        modal.style.position = 'fixed';
    }
    document.getElementById('addVideoSessaoId').value = sessaoId;
    document.getElementById('addVideoForm').action = '{{ route("produtos.videos.store") }}';
    // Limpa o formulário
    document.getElementById('addVideoForm').reset();
    document.getElementById('addVideoSessaoId').value = sessaoId;
    
    const modalInstance = bootstrap.Modal.getOrCreateInstance(modal, {
        backdrop: true,
        keyboard: true,
        focus: true
    });
    modalInstance.show();
}

function editVideo(id, titulo, descricao, url, duracao, status) {
    const modal = document.getElementById('editVideoModal');
    // Garante que modal está no body
    if (modal && modal.parentElement !== document.body) {
        document.body.appendChild(modal);
    }
    if (modal) {
        modal.style.zIndex = '9999';
        modal.style.position = 'fixed';
    }
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
    
    const modalInstance = bootstrap.Modal.getOrCreateInstance(modal, {
        backdrop: true,
        keyboard: true,
        focus: true
    });
    modalInstance.show();
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
    const modal = document.getElementById('iconPickerModal');
    // Garante que modal está no body
    if (modal && modal.parentElement !== document.body) {
        document.body.appendChild(modal);
    }
    if (modal) {
        modal.style.zIndex = '9999';
        modal.style.position = 'fixed';
    }
    const modalInstance = bootstrap.Modal.getOrCreateInstance(modal, {
        backdrop: true,
        keyboard: true,
        focus: true
    });
    modalInstance.show();
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
<div class="modal fade" id="iconPickerModal" tabindex="-1" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
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
                        <div class="col-3 col-md-2 col-lg-1 text-center icon-item" 
                             onclick="selectIcon('{{ $icon }}')"
                             data-icon-name="{{ $icon }}">
                            <i data-lucide="{{ $icon }}" style="width: 28px; height: 28px; color: var(--gateway-primary-color);"></i>
                            <small class="d-block mt-1">{{ $icon }}</small>
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
            // Inicializa drag and drop para sessões (capas) dentro de cada módulo
            initSortableSessoes();
            
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
    
    function initSortableSessoes() {
        if (typeof Sortable === 'undefined') return;
        document.querySelectorAll('.sessoes-sortable-row').forEach(function(row) {
            const moduloId = row.getAttribute('data-modulo-id');
            if (!moduloId || row.sortableSessoesInstance) return;
            const cards = row.querySelectorAll('.netflix-session-card');
            if (cards.length === 0) return;
            try {
                const sortable = new Sortable(row, {
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    dragClass: 'sortable-drag',
                    chosenClass: 'sortable-chosen',
                    onStart: function() {
                        window._sessaoDragging = true;
                    },
                    onEnd: function(evt) {
                        setTimeout(function() { window._sessaoDragging = false; }, 100);
                        const cards = row.querySelectorAll('.netflix-session-card');
                        const ordem = [];
                        cards.forEach(function(item, index) {
                            const sessaoId = item.getAttribute('data-sessao-id');
                            if (sessaoId) {
                                ordem.push({ id: parseInt(sessaoId), ordem: index + 1 });
                            }
                        });
                        ordem.forEach(function(o, i) {
                            const card = cards[i];
                            if (card) {
                                const numEl = card.querySelector('.netflix-session-number');
                                if (numEl) numEl.textContent = i + 1;
                            }
                        });
                        fetch('{{ route("produtos.sessoes.reorder") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ modulo_id: parseInt(moduloId), sessoes: ordem })
                        })
                        .then(function(r) { return r.json(); })
                        .then(function(data) {
                            if (data.success) {
                                const toast = document.createElement('div');
                                toast.className = 'alert alert-success position-fixed top-0 end-0 m-3';
                                toast.style.zIndex = '9999';
                                toast.style.minWidth = '250px';
                                toast.innerHTML = '<i class="fa-solid fa-check"></i> Ordem das sessões atualizada!';
                                document.body.appendChild(toast);
                                setTimeout(function() {
                                    toast.style.transition = 'opacity 0.3s';
                                    toast.style.opacity = '0';
                                    setTimeout(function() { toast.remove(); }, 300);
                                }, 2000);
                            }
                        })
                        .catch(function(err) {
                            console.error('Erro ao reordenar sessões:', err);
                            location.reload();
                        });
                    }
                });
                row.sortableSessoesInstance = sortable;
            } catch (e) {
                console.error('Erro initSortableSessoes módulo ' + moduloId, e);
            }
        });
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

<!-- Modal de Gerenciamento de Sessão (Admin) -->
<div class="modal fade" id="sessionManagementModal" tabindex="-1" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gerenciar Sessão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="sessionModalSessaoId" value="">
                <input type="hidden" id="sessionModalModuloId" value="">
                <div class="text-center mb-4">
                    <h6 id="sessionModalNome" style="font-size: 18px; font-weight: 600; margin-bottom: 8px;"></h6>
                    <p id="sessionModalVideoCount" class="text-muted mb-0"></p>
                </div>
                
                <!-- Lista de Vídeos -->
                <div class="mb-4">
                    <h6 class="mb-3" style="font-size: 14px; font-weight: 600;">Vídeos da Sessão</h6>
                    <div id="sessionVideosList" style="max-height: 300px; overflow-y: auto; border: 1px solid #e5e7eb; border-radius: 8px; padding: 8px;">
                        <p class="text-muted mb-0">Carregando vídeos...</p>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success" onclick="handleAddVideoFromModal()">
                        <i class="fa-solid fa-video me-2"></i> Adicionar Vídeo
                    </button>
                    <button type="button" class="btn btn-primary" onclick="handleEditSessionFromModal()">
                        <i class="fa-solid fa-edit me-2"></i> Editar Sessão
                    </button>
                    <button type="button" class="btn btn-danger" onclick="handleDeleteSessionFromModal()">
                        <i class="fa-solid fa-trash me-2"></i> Excluir Sessão
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
function handleAddVideoFromModal() {
    const sessaoId = document.getElementById('sessionModalSessaoId').value;
    const modal = bootstrap.Modal.getInstance(document.getElementById('sessionManagementModal'));
    if (modal) modal.hide();
    setTimeout(() => {
        addVideo(parseInt(sessaoId));
    }, 300);
}

function handleEditSessionFromModal() {
    const sessaoId = document.getElementById('sessionModalSessaoId').value;
    const modal = bootstrap.Modal.getInstance(document.getElementById('sessionManagementModal'));
    if (modal) modal.hide();
    
    // Busca dados da sessão via atributos data
    const sessaoCard = document.querySelector(`[data-sessao-id="${sessaoId}"]`);
    if (!sessaoCard) {
        alert('Erro ao encontrar sessão');
        return;
    }
    
    const sessaoNome = sessaoCard.getAttribute('data-sessao-nome') || '';
    const sessaoDescricao = sessaoCard.getAttribute('data-sessao-descricao') || '';
    const sessaoStatus = sessaoCard.getAttribute('data-sessao-status') === '1';
    const sessaoLiberarEm = sessaoCard.getAttribute('data-sessao-liberar-em') || '';
    const sessaoLiberarEmDias = sessaoCard.getAttribute('data-sessao-liberar-em-dias') || '';
    
    setTimeout(() => {
        editSessao(parseInt(sessaoId), sessaoNome, sessaoDescricao, sessaoStatus, sessaoLiberarEm, sessaoLiberarEmDias);
    }, 300);
}

function handleDeleteSessionFromModal() {
    const sessaoId = document.getElementById('sessionModalSessaoId').value;
    const modal = bootstrap.Modal.getInstance(document.getElementById('sessionManagementModal'));
    if (modal) modal.hide();
    setTimeout(() => {
        deleteSessao(parseInt(sessaoId));
    }, 300);
}
</script>
