@php
    $produto->load(['modulos.sessoes.videos', 'categories.files']);
@endphp

<form action="{{ route('produtos.edit', ['id' => $produto->id]) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')
    
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Customização da Área de Membros</h5>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('produtos.preview-area-membros', ['uuid' => $produto->uuid]) }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-eye me-1"></i> Ver prévia
                </a>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-save"></i> Salvar Alterações
                </button>
            </div>
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
                            <input class="form-check-input" type="checkbox" name="area_member_white_mode" id="area_member_white_mode_alt" value="1"
                                {{ ($produto->area_member_white_mode ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="area_member_white_mode_alt">
                                <i class="fa-solid fa-sun text-warning me-1"></i> Modo claro (telas brancas)
                            </label>
                        </div>
                        <span class="text-muted small">Desmarque para usar modo escuro.</span>
                    </div>
                    <small class="form-text text-muted mt-1">Assinalado = área de membros com fundo branco e visual claro. Desassinalado = modo escuro (padrão).</small>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-12">
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
                    <input type="file" class="form-control" name="area_member_banner" accept="image/*" id="bannerImageInputCustom" style="max-width: 400px;">
                    <input type="hidden" name="area_member_banner_cropped" id="area_member_banner_cropped_custom" value="">
                    <small class="form-text text-muted d-block mt-1">Ao selecionar uma imagem, abrirá um modal para recortar a área do banner.</small>
                    <div id="bannerImagePreviewCustom" class="mt-3">
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
                                <i class="fa-solid fa-info-circle"></i> Nenhum banner definido.
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
                    <input type="file" class="form-control" name="area_member_banner_mobile" accept="image/*" id="bannerMobileImageInputCustom" style="max-width: 400px;">
                    <input type="hidden" name="area_member_banner_mobile_cropped" id="area_member_banner_mobile_cropped_custom" value="">
                    <small class="form-text text-muted d-block mt-1">Opcional. Se não definir, o banner desktop será usado. Em mobile, este banner substitui o desktop.</small>
                    <div id="bannerMobileImagePreviewCustom" class="mt-3">
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
                        <small class="text-muted">(Recomendado: 1920x1080, fullscreen)</small>
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
</form>

<!-- Modal Recorte do Banner -->
<div class="modal fade" id="bannerCropModal" tabindex="-1" aria-labelledby="bannerCropModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bannerCropModalLabel">Recortar Banner do Curso (Desktop)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar" onclick="cancelBannerCropCustom()"></button>
            </div>
            <div class="modal-body p-2">
                <div class="img-container" style="max-height: 70vh;">
                    <img id="bannerCropImageCustom" src="" alt="Banner" style="max-width: 100%; display: block;">
                </div>
                <p class="small text-muted mt-2 mb-0">Arraste para posicionar e use os cantos para ajustar a área. Proporção recomendada: 1920×400.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cancelBannerCropCustom()">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="applyBannerCropCustom()"><i class="fa-solid fa-check me-1"></i> Aplicar recorte</button>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar" onclick="cancelBannerMobileCropCustom()"></button>
            </div>
            <div class="modal-body p-2">
                <div class="img-container" style="max-height: 70vh;">
                    <img id="bannerMobileCropImageCustom" src="" alt="Banner Mobile" style="max-width: 100%; display: block;">
                </div>
                <p class="small text-muted mt-2 mb-0">Arraste para posicionar e use os cantos para ajustar a área. Proporção recomendada: 768×400 (mobile).</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cancelBannerMobileCropCustom()">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="applyBannerMobileCropCustom()"><i class="fa-solid fa-check me-1"></i> Aplicar recorte</button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Banner: abre modal de recorte (Cropper.js)
    const bannerInputCustom = document.getElementById('bannerImageInputCustom');
    const bannerCroppedInputCustom = document.getElementById('area_member_banner_cropped_custom');
    const bannerPreviewCustom = document.getElementById('bannerImagePreviewCustom');
    let bannerCropperCustom = null;
    
    if (bannerInputCustom) {
        bannerInputCustom.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file || !file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = function(ev) {
                const modal = document.getElementById('bannerCropModal');
                const img = document.getElementById('bannerCropImageCustom');
                if (!modal || !img) return;
                img.src = ev.target.result;
                if (bannerCropperCustom) { bannerCropperCustom.destroy(); bannerCropperCustom = null; }
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
                modal.addEventListener('shown.bs.modal', function onShown() {
                    modal.removeEventListener('shown.bs.modal', onShown);
                    bannerCropperCustom = new Cropper(img, { aspectRatio: 1920/400, viewMode: 1, dragMode: 'move' });
                });
            };
            reader.readAsDataURL(file);
        });
    }
    
    window.applyBannerCropCustom = function() {
        if (!bannerCropperCustom) return;
        const canvas = bannerCropperCustom.getCroppedCanvas({ width: 1920, height: 400 });
        const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
        if (bannerCroppedInputCustom) bannerCroppedInputCustom.value = dataUrl;
        if (bannerPreviewCustom) {
            bannerPreviewCustom.innerHTML = '<div class="position-relative"><img src="' + dataUrl + '" alt="Banner" style="width:100%;max-height:200px;object-fit:cover;border-radius:8px;border:2px solid #e5e7eb;"><span class="badge bg-success position-absolute top-0 end-0 m-2">Banner recortado</span></div>';
        }
        if (bannerInputCustom) bannerInputCustom.value = '';
        bootstrap.Modal.getInstance(document.getElementById('bannerCropModal')).hide();
        bannerCropperCustom.destroy();
        bannerCropperCustom = null;
    };
    
    window.cancelBannerCropCustom = function() {
        if (bannerCropperCustom) { bannerCropperCustom.destroy(); bannerCropperCustom = null; }
        if (bannerInputCustom) bannerInputCustom.value = '';
        bootstrap.Modal.getInstance(document.getElementById('bannerCropModal')).hide();
    };
    
    // Banner Mobile: abre modal de recorte (Cropper.js)
    const bannerMobileInputCustom = document.getElementById('bannerMobileImageInputCustom');
    const bannerMobileCroppedInputCustom = document.getElementById('area_member_banner_mobile_cropped_custom');
    const bannerMobilePreviewCustom = document.getElementById('bannerMobileImagePreviewCustom');
    let bannerMobileCropperCustom = null;
    
    if (bannerMobileInputCustom) {
        bannerMobileInputCustom.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file || !file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = function(ev) {
                const modal = document.getElementById('bannerMobileCropModal');
                const img = document.getElementById('bannerMobileCropImageCustom');
                if (!modal || !img) return;
                img.src = ev.target.result;
                if (bannerMobileCropperCustom) { bannerMobileCropperCustom.destroy(); bannerMobileCropperCustom = null; }
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
                modal.addEventListener('shown.bs.modal', function onShown() {
                    modal.removeEventListener('shown.bs.modal', onShown);
                    bannerMobileCropperCustom = new Cropper(img, { aspectRatio: 768/400, viewMode: 1, dragMode: 'move' });
                });
            };
            reader.readAsDataURL(file);
        });
    }
    
    window.applyBannerMobileCropCustom = function() {
        if (!bannerMobileCropperCustom) return;
        const canvas = bannerMobileCropperCustom.getCroppedCanvas({ width: 768, height: 400 });
        const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
        if (bannerMobileCroppedInputCustom) bannerMobileCroppedInputCustom.value = dataUrl;
        if (bannerMobilePreviewCustom) {
            bannerMobilePreviewCustom.innerHTML = '<div class="position-relative"><img src="' + dataUrl + '" alt="Banner Mobile" style="width:100%;max-height:150px;object-fit:cover;border-radius:8px;border:2px solid #e5e7eb;"><span class="badge bg-info position-absolute top-0 end-0 m-2"><i class="fa-solid fa-mobile-screen me-1"></i> Mobile recortado</span></div>';
        }
        if (bannerMobileInputCustom) bannerMobileInputCustom.value = '';
        bootstrap.Modal.getInstance(document.getElementById('bannerMobileCropModal')).hide();
        bannerMobileCropperCustom.destroy();
        bannerMobileCropperCustom = null;
    };
    
    window.cancelBannerMobileCropCustom = function() {
        if (bannerMobileCropperCustom) { bannerMobileCropperCustom.destroy(); bannerMobileCropperCustom = null; }
        if (bannerMobileInputCustom) bannerMobileInputCustom.value = '';
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
});
</script>
