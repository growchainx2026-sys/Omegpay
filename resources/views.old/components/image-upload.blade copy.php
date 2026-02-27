@props(compact('id','name','value','label'))


<div class="form-group">
@php
   $search = ['asset', 'banner', 'http'];
    $valor = $value;

    $hasMatch = false;
    foreach ($search as $word) {
        if (strpos($value, $word) !== false) {
            $hasMatch = true;
            break;
        }
    }

    

    if (!$hasMatch && $value) {
        $valor = asset('storage/' . $value);
    }

    $valor = str_replace('storage/storage/','storage/',$valor);
@endphp
    <label for="{{ $id }}">{{ $label }}</label>

    <div id="dropzone-{{ $id }}" class="dropzone-container">
        <input type="file" id="file-{{ $id }}" name="{{ $name }}" accept="{{ $mimetypes ?? 'image/*' }}" hidden>

        <div class="dropzone-area" onclick="document.getElementById('file-{{ $id }}').click();">
            @if(isset($value) && !empty($value))
                <img id="preview-{{ $id }}" src="{{ $valor }}" class="image-preview" style="display: block;">
            @else
                <p id="dropzone-text-{{ $id }}" class="dropzone-text">Arraste e solte sua imagem aqui ou clique para selecionar</p>
            @endif
        </div>

        <div class="file-info" id="file-info-{{ $id }}" style="display: {{ $value ? 'flex' : 'none' }};">
            <span style="color:white;" id="file-name-{{ $id }}">{{ $valor ? basename($valor) : 'Nome do arquivo' }}</span>
            <button type="button" class="delete-btn" onclick="removeImage('{{ $id }}')">✖</button>
        </div>

        <div class="progress-bar-container" id="progress-bar-{{ $id }}" style="display: none;">
            <div id="progress-bar" class="progress-bar"></div>
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
        height: 350px;
        padding: 10px;
        border: 2px solid #d8d8d85b;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border-radius: 10px;
        background-color: #d4d4d485;
        transition: 0.3s;
        flex-direction: column;
    }
    .dropzone-area:hover {
        background-color: #c4c4c4b6;
    }
    .dropzone-text {
        position:absolute;
        top: 50%;
        left: 0%;
        right:0;
        color: #007bff;
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
        z-index: 2;
    }
    .progress-bar {
        width: 0;
        height: 5px;
        background: red;
        transition: width 1s;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const dropzones = document.querySelectorAll('.dropzone-container');

    dropzones.forEach(function(dropzone) {
        const fileInput = dropzone.querySelector('input[type="file"]');
        const id = fileInput.id.replace('file-', '');

        const preview = dropzone.querySelector(`#preview-${id}`);
        const fileInfo = dropzone.querySelector(`#file-info-${id}`);
        const fileName = dropzone.querySelector(`#file-name-${id}`);
        const progressBarContainer = dropzone.querySelector(`#progress-bar-${id}`);
        const progressBar = progressBarContainer.querySelector('.progress-bar');
        const dztext = dropzone.querySelector(`#dropzone-text-${id}`);

        // Mostra preview e esconde texto caso preview tenha src válido
        if (preview && preview.src && preview.src.trim() !== '') {
            dztext.style.display = 'none';
            preview.style.display = 'block';
            fileInfo.style.display = 'flex';
        } else {
            dztext.style.display = 'block';
            if (preview) preview.style.display = 'none';
            if (fileInfo) fileInfo.style.display = 'none';
        }

        // Eventos drag & drop
        dropzone.addEventListener('dragover', (event) => {
            event.preventDefault();
            dropzone.classList.add('dragover');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('dragover');
        });

        dropzone.addEventListener('drop', (event) => {
            event.preventDefault();
            dropzone.classList.remove('dragover');

            if (event.dataTransfer.files.length > 0) {
                fileInput.files = event.dataTransfer.files;
                previewImage(fileInput.files[0]);
            }
        });

        // Quando escolher arquivo via input
        fileInput.addEventListener('change', function(event) {
            if (event.target.files.length > 0) {
                previewImage(event.target.files[0]);
            }
        });

        function previewImage(file) {
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    fileName.innerText = file.name;
                    fileInfo.style.display = 'flex';
                    dztext.style.display = 'none';
                    simulateProgressBar();
                };
                reader.readAsDataURL(file);
            }
        }

        function simulateProgressBar() {
            progressBarContainer.style.display = 'block';
            progressBar.style.width = '0%';

            setTimeout(() => { progressBar.style.background = "#ff2f00"; progressBar.style.width = '10%'; }, 200);
            setTimeout(() => { progressBar.style.background = "#ff7700"; progressBar.style.width = '25%'; }, 400);
            setTimeout(() => { progressBar.style.background = "#ffd000"; progressBar.style.width = '50%'; }, 600);
            setTimeout(() => { progressBar.style.background = "#c8ff00"; progressBar.style.width = '75%'; }, 800);
            setTimeout(() => { progressBar.style.background = "#00ff11"; progressBar.style.width = '100%'; }, 900);
            setTimeout(() => {
                progressBar.style.background = "#ff0000";
                progressBarContainer.style.display = 'none';
            }, 1000);
        }

        window.removeImage = function(name) {
            const dz = document.getElementById(`dropzone-${name}`);
            const input = dz.querySelector(`#file-${name}`);
            const imgPreview = dz.querySelector(`#preview-${name}`);
            const info = dz.querySelector(`#file-info-${name}`);
            const text = dz.querySelector(`#dropzone-text-${name}`);

            text.style.display = 'block';
            input.value = '';
            imgPreview.src = '';
            imgPreview.style.display = 'none';
            info.style.display = 'none';
        };
    });
});

</script>
