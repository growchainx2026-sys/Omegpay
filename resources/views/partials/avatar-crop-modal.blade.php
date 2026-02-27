{{-- Modal de recorte de foto de perfil (Cropper.js) --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />

<style>
    /* Ajustes específicos só para o conteúdo do modal de recorte */
    #avatarCropModal .avatar-crop-container {
        width: 100%;
        height: 400px;
        background: #1a1a1a;
        overflow: hidden;
    }
    #avatarCropModal .avatar-crop-container img {
        display: block;
        max-width: none;
        max-height: none;
    }
</style>

<div class="modal fade" id="avatarCropModal" tabindex="-1" aria-labelledby="avatarCropModalLabel" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="avatarCropModalLabel">Recortar foto de perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body p-0">
                <div class="avatar-crop-container">
                    <img id="avatarCropImage" src="" alt="Recortar">
                </div>
                <p class="small text-muted p-2 mb-0">Arraste a imagem ou a área de seleção. Ajuste os cantos para recortar em quadrado.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="avatarCropApply">Aplicar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
(function() {
    var avatarInput = document.getElementById('avatarInput');
    var formAvatar = document.getElementById('form-avatar');
    if (!avatarInput || !formAvatar) return;

    var modalEl = document.getElementById('avatarCropModal');
    var cropImageEl = document.getElementById('avatarCropImage');
    var applyBtn = document.getElementById('avatarCropApply');
    var cropper = null;
    var currentObjectUrl = null;

    avatarInput.removeAttribute('onchange');
    avatarInput.addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (!file || !file.type.startsWith('image/')) return;
        if (currentObjectUrl) URL.revokeObjectURL(currentObjectUrl);
        currentObjectUrl = URL.createObjectURL(file);
        cropImageEl.src = currentObjectUrl;

        var modal = new bootstrap.Modal(modalEl);
        modal.show();

        var onShown = function() {
            modalEl.removeEventListener('shown.bs.modal', onShown);

            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            cropImageEl.onload = function() {
                cropImageEl.onload = null;
                var container = cropImageEl.parentElement;
                var w = container.offsetWidth;
                var h = container.offsetHeight;
                cropper = new Cropper(cropImageEl, {
                    aspectRatio: 1,
                    viewMode: 2,
                    dragMode: 'move',
                    autoCropArea: 0.85,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                    minContainerWidth: w,
                    minContainerHeight: h,
                    minCanvasWidth: 100,
                    minCanvasHeight: 100,
                });
            };
            if (cropImageEl.complete && cropImageEl.naturalWidth) {
                cropImageEl.onload();
            }
        };
        modalEl.addEventListener('shown.bs.modal', onShown);
    });

    modalEl.addEventListener('hidden.bs.modal', function() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        if (currentObjectUrl) {
            URL.revokeObjectURL(currentObjectUrl);
            currentObjectUrl = null;
        }
        cropImageEl.src = '';
        avatarInput.value = '';
    });

    applyBtn.addEventListener('click', function() {
        if (!cropper) return;
        cropper.getCroppedCanvas({
            width: 400,
            height: 400,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        }).toBlob(function(blob) {
            if (!blob) return;
            var file = new File([blob], 'avatar.jpg', { type: 'image/jpeg' });
            var dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            avatarInput.files = dataTransfer.files;
            bootstrap.Modal.getInstance(modalEl).hide();
            formAvatar.submit();
        }, 'image/jpeg', 0.92);
    });
})();
</script>
