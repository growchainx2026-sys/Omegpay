@props(['id', 'name', 'value' => null, 'label', 'mimetypes' => '.pdf,.doc,.docx,.txt,.mp4,.png,.jpg,.jpeg,.xls,.xlsx,.ppt,.pptx'])

@php
    $srcs = [];
    if ($value) {
        $files = is_array($value) ? $value : [$value];
        foreach ($files as $file) {
           if ($file && !Str::startsWith($file, ['http', 'asset'])) {
                $srcs[] = asset('storage/' . ltrim($file, '/'));
            } else {
                $srcs[] = $file;
            }
        }
    }

@endphp

<div class="form-group">
    <label for="{{ $id }}">{{ $label }}</label>

    <div id="dropzone-{{ $id }}" class="dropzone-container" data-id="{{ $id }}">
        <input type="file" id="file-{{ $id }}" name="{{ $name }}[]" accept="{{ $mimetypes }}"
            multiple hidden>


        <div class="dropzone-area" onclick="document.getElementById('file-{{ $id }}').click();">
            <p id="dropzone-text-{{ $id }}" class="dropzone-text text-center">
                <i class="fa-solid fa-cloud-arrow-up" style="font-size: 64px"></i> <br />
                Arraste e solte seus arquivos aqui ou clique para selecionar
            </p>
        </div>

        <div id="file-list-{{ $id }}" class="file-list">
            {{-- Renderiza arquivos existentes --}}
            @foreach ($srcs as $src)
                @php
                    $ext = strtolower(pathinfo($src, PATHINFO_EXTENSION));
                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                    $icons = [
                        'pdf' => '/assets/icons/pdf.png',
                        'zip' => '/assets/icons/zip.png',
                        'rar' => '/assets/icons/rar.png',
                        'doc' => '/assets/icons/doc.png',
                        'docx' => '/assets/icons/doc.png',
                        'xls' => '/assets/icons/xls.png',
                        'xlsx' => '/assets/icons/xls.png',
                        'ppt' => '/assets/icons/ppt.png',
                        'pptx' => '/assets/icons/ppt.png',
                        'txt' => '/assets/icons/txt.png',
                        'cdr' => '/assets/icons/cdr.png',
                        'psd' => '/assets/icons/psd.png',
                        'mp4' => '/assets/icons/mp4.png',
                        'mp3' => '/assets/icons/mp3.png',
                        'avi' => '/assets/icons/avi.png',
                    ];
                    $thumb = $isImage ? $src : $icons[$ext] ?? '/icons/file.png';
                @endphp
                <div class="file-item">
                    <img src="{{ $thumb }}" class="file-thumb">
                    <div class="file-name">{{ basename($src) }}</div>
                    <button type="button" class="delete-btn">✖</button>
                </div>
            @endforeach
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
        height: 200px;
        border: 2px dashed rgba(124, 124, 124, 0.36);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border-radius: 10px;
        transition: 0.3s;
    }

    .dropzone-area:hover {
        background-color: rgba(196, 196, 196, 0.07);
    }

    .dropzone-text {
        color: rgba(124, 124, 124, 0.7);
        font-weight: bold;
        margin: 0;
    }

    .file-list {
        margin-top: 10px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .file-item {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f5f5f5;
        padding: 8px;
        border-radius: 6px;
        position: relative;
    }

    .file-thumb {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
        background: #ddd;
    }

    .file-name {
        flex: 1;
        text-align: left;
        font-size: 14px;
        word-break: break-word;
    }

    .delete-btn {
        background: transparent;
        border: none;
        color: #f00;
        font-size: 16px;
        cursor: pointer;
    }

    .progress-bar-container {
        width: 100%;
        background: #ddd;
        border-radius: 5px;
        overflow: hidden;
        height: 5px;
    }

    .progress-bar {
        width: 0;
        height: 5px;
        background: #1262f7;
        transition: width 0.3s;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    let dropzoneArea = document.querySelector('#dropzone-{{ $id }} .dropzone-area');
    const previews = Number("{{ count($srcs) }}");
    const dropzone = document.getElementById('dropzone-{{ $id }}');
    const fileInput = document.getElementById('file-{{ $id }}');
    const fileList = document.getElementById('file-list-{{ $id }}');
    const dropzoneText = document.getElementById('dropzone-text-{{ $id }}');

    const icons = {
        pdf: '/assets/icons/pdf.png',
        zip: '/assets/icons/zip.png',
        rar: '/assets/icons/rar.png',
        doc: '/assets/icons/doc.png',
        docx: '/assets/icons/doc.png',
        xls: '/assets/icons/xls.png',
        xlsx: '/assets/icons/xls.png',
        ppt: '/assets/icons/ppt.png',
        pptx: '/assets/icons/ppt.png',
        txt: '/assets/icons/txt.png',
        cdr: '/assets/icons/cdr.png',
        psd: '/assets/icons/psd.png',
        mp4: '/assets/icons/mp4.png',
        avi: '/assets/icons/avi.png',
    };

    // Se já tem arquivos, esconde a dropzone
    if (previews > 0) {
        dropzoneArea.classList.add('d-none');
    }

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
            handleFiles(e.dataTransfer.files);
        }
    });
    fileInput.addEventListener('change', e => {
        if (e.target.files.length > 0) {
            handleFiles(e.target.files);
        }
    });

    function handleFiles(files) {
        if (files.length > 0) {
            dropzoneArea.classList.add('d-none'); // Oculta dropzone
        }

        dropzoneText.classList.add('d-none');
        Array.from(files).forEach(file => {
            const ext = file.name.split('.').pop().toLowerCase();
            const isImage = file.type.startsWith('image/');
            let thumbSrc = icons[ext] || '/icons/file.png';

            const reader = new FileReader();
            if (isImage) {
                reader.onload = e => createFileItem(file.name, e.target.result);
                reader.readAsDataURL(file);
            } else {
                createFileItem(file.name, thumbSrc);
            }
        });
    }

    function createFileItem(name, src) {
        const item = document.createElement('div');
        item.classList.add('file-item');

        const thumb = document.createElement('img');
        thumb.src = src;
        thumb.classList.add('file-thumb');

        const fileName = document.createElement('div');
        fileName.classList.add('file-name');
        fileName.textContent = name;

        const delBtn = document.createElement('button');
        delBtn.type = 'button';
        delBtn.classList.add('delete-btn');
        delBtn.innerHTML = '✖';

        const progressContainer = document.createElement('div');
        progressContainer.classList.add('progress-bar-container');

        const progressFill = document.createElement('div');
        progressFill.classList.add('progress-bar');
        progressContainer.appendChild(progressFill);

        item.appendChild(thumb);
        item.appendChild(fileName);
        item.appendChild(delBtn);
        item.appendChild(progressContainer);

        fileList.appendChild(item);

        simulateProgress(progressFill);
    }

    function simulateProgress(bar) {
        let percent = 0;
        const interval = setInterval(() => {
            percent += 20;
            bar.style.width = percent + '%';
            if (percent >= 100) clearInterval(interval);
        }, 200);
    }

    // Delegado: remove itens existentes também
    fileList.addEventListener('click', function(e) {
        if (e.target.classList.contains('delete-btn')) {
            const item = e.target.closest('.file-item');
            item.remove();

            if (fileList.children.length === 0) {
                dropzoneArea.classList.remove('d-none'); // Mostra novamente
                dropzoneText.classList.remove('d-none');
            }
        }
    });
});

</script>
