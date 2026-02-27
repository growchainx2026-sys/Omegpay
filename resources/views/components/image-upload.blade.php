@props(['id', 'name', 'value' => null,  'label', 'mimetypes' => '.gif,.svg,.png,.jpg,.jpeg', 'height' => '350px'])

@php
    // Resolve caminho da imagem apenas se for necessário
    $src = $value;
    if ($value && !Str::startsWith($value, ['http', 'asset'])) {
        $src = asset('storage/' . ltrim($value, '/'));
    }
@endphp

<div class="form-group">
    <label for="{{ $id }}">{{ $label }}</label>

    <div id="dropzone-{{ $id }}" class="dropzone-container" data-id="{{ $id }}">
        <input type="file" id="file-{{ $id }}" name="{{ $name }}" accept=".gif,.svg,.png,.jpg,.jpeg" hidden>

        <div class="dropzone-area" onclick="document.getElementById('file-{{ $id }}').click();">
            <img id="preview-{{ $id }}" src="{{ $src ?? '' }}" class="image-preview" style="display: {{ $src ? 'block' : 'none' }}">
            <p id="dropzone-text-{{ $id }}" class="dropzone-text text-center" style="display: {{ $src ? 'none' : 'block' }}">
             <i class="fa-solid fa-cloud-arrow-up" style="font-size: 64px"></i> <br/>
                Arraste e solte sua imagem aqui ou clique para selecionar
            </p>
        </div>

        <div class="file-info" id="file-info-{{ $id }}" style="display: {{ $src ? 'flex' : 'none' }}">
            <span style="color:white;" id="file-name-{{ $id }}">{{ $src ? basename($src) : 'Nome do arquivo' }}</span>
            <button type="button" class="delete-btn" onclick="removeImage('{{ $id }}')">✖</button>
        </div>

        <div class="progress-bar-container" id="progress-bar-{{ $id }}" style="display: none;">
            <div class="progress-bar" id="progress-bar-fill-{{ $id }}"></div>
        </div>
    </div>
</div>


<style>
    .dropzone-container {
        width: 100%;
        text-align: center;
        position: relative;
    }
    .dropzone-area {
        width: 100%;
        max-width: 100%;
        height: {{ $height }};
        max-height: {{ $height }};
        padding: 10px;
        border: 2px dashed rgba(124, 124, 124, 0.36);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border-radius: 10px;
        transition: 0.3s;
        flex-direction: column;
    }
    .dropzone-area:hover {
        background-color:rgba(196, 196, 196, 0.07);
    }
    .dropzone-text {
        position:absolute;
        top: 35%;
        left: 0%;
        right:0;
        color: rgba(124, 124, 124, 0.36);
        font-weight: bold;
        margin: 0;
    }
    .image-preview {
        width: auto;
        height: auto;
        max-width: 90%;
        max-height: 90%;
        border-radius: 10px;
        margin-top: 10px;
        display: none;
        z-index: 10;
    }
    .file-info {
        position:absolute;
        top: -10px;
        left: 0%;
        right:0;
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
        padding: 5px 10px;
        background: #1262f78f;
        border-radius: 2px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        font-size: 14px;
        z-index:11;
    }
    .delete-btn {
        background: transparent;
        border: none;
        color: rgb(255, 255, 255);
        font-size: 18px;
        cursor: pointer;
    }
    .progress-bar-container {
        position:absolute;
        top: 8%;
        left: 0%;
        right:0;
        width: 100%;
        background: #ddd;
        border-radius: 5px;
        margin-top: 10px;
        overflow: hidden;
        height: 5px;
        display: none;
        z-index: 9999;
    }
    .progress-bar {
        width: 0;
        height: 5px;
        background: red;
        transition: width 1s;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.dropzone-container').forEach(dropzone => {
        const id = dropzone.dataset.id;
        const fileInput = document.getElementById(`file-${id}`);
        const preview = document.getElementById(`preview-${id}`);
        const fileName = document.getElementById(`file-name-${id}`);
        const fileInfo = document.getElementById(`file-info-${id}`);
        const progressBarContainer = document.getElementById(`progress-bar-${id}`);
        const progressBarFill = document.getElementById(`progress-bar-fill-${id}`);
        const dropzoneText = document.getElementById(`dropzone-text-${id}`);

        // Eventos de drag & drop
        dropzone.addEventListener('dragover', e => {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('dragover');
        });

        dropzone.addEventListener('drop', e => {
            e.preventDefault();
            dropzone.classList.remove('dragover');
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                handleFile(fileInput.files[0]);
            }
        });

        // Ao selecionar arquivo
        fileInput.addEventListener('change', e => {
            if (e.target.files.length > 0) {
                handleFile(e.target.files[0]);
            }
        });

        function handleFile(file) {
            if (!file || !file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                dropzoneText.style.display = 'none';
                fileName.innerText = file.name;
                fileInfo.style.display = 'flex';
                simulateProgressBar();
            };
            reader.readAsDataURL(file);
        }

        function simulateProgressBar() {
            progressBarContainer.style.display = 'block';
            progressBarFill.style.width = '0%';

            let steps = [10, 25, 50, 75, 100];
            steps.forEach((percent, i) => {
                setTimeout(() => {
                    progressBarFill.style.width = percent + '%';
                }, i * 200);
            });

            setTimeout(() => {
                progressBarContainer.style.display = 'none';
            }, 1200);
        }

        window.removeImage = function (id) {
            const input = document.getElementById(`file-${id}`);
            const preview = document.getElementById(`preview-${id}`);
            const text = document.getElementById(`dropzone-text-${id}`);
            const info = document.getElementById(`file-info-${id}`);

            input.value = '';
            preview.src = '';
            preview.style.display = 'none';
            text.style.display = 'block';
            info.style.display = 'none';
        };
    });
});
</script>
