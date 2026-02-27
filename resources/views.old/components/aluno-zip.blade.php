@props(['file'])

@php
    $zipPath = storage_path('app/public/' . $file->file);
    $zip = new ZipArchive();
    $files = [];

    if ($zip->open($zipPath) === true) {
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $files[] = $zip->getNameIndex($i);
        }
        $zip->close();
    }
@endphp

@if (!empty($files))
    <div class="my-2">
        <a class="btn btn-primary" href="/storage/{{ $file->file }}" target="_blank">
            Download do arquivo
        </a>
    </div>
    <div class="accordion accordion-flush" id="accordionFlushExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                    Visualização dos arquivos
                </button>
            </h2>
            <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <code>
                        <ul class="list-group">
                            @foreach ($files as $f)
                                <li class="list-group-item">{{ $f }}</li>
                            @endforeach
                        </ul>
                    </code>
                </div>
            </div>
        </div>
    </div>
@else
    <p>Não foi possível abrir o arquivo ZIP ou ele está vazio.</p>
@endif
