@props(['produto'])
<!-- Modal Arquivo-->
<div class="modal fade" id="addFileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="addFileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addFileModalLabel">Adicionar Arquivo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="form-content-file">
                <div class="row">
                    <input name="produto_id" value="{{ $produto->id }}" hidden />
                    <div class="mb-3 col-12">
                        <label for="name">Nome</label>
                        <input type="text" autofocus class="form-control form-control-md" id="name"
                            name="name">
                    </div>
                    <div class="mb-3 col-12">
                        <label for="name">Descrição</label>
                        <textarea class="form-control form-control-md" id="description" name="description"></textarea>
                    </div>
                    <div class="mb-3 col-12">
                        <label for="name">Tipo de entrega</label>
                        <select autofocus class="form-control form-control-md" id="select-type" name="type">
                            <option value="link">Link externo</option>
                            <option value="file">Arquivo</option>
                        </select>
                    </div>

                    <div class="mb-3 col-12" id="categoria-wrapper" style="display: none;">
                        <label for="categoria_id">Módulo</label>
                        <select class="form-control form-control-md" id="select-category"
                            name="categoria_id">
                            <option>--Selecione--</option>
                            @foreach ($produto->categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ isset($file->categoria_id) && $file->categoria_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-12" id="file-type-wrapper" style="display: none;">
                        <label for="type">Tipo de arquivo</label>
                        <select class="form-control form-control-md" id="select-file-type" name="file_type">
                            <option>--Selecione--</option>
                            @foreach (['audio', 'video', 'zip', 'pdf', 'txt'] as $tipo)
                                <option value="{{ $tipo }}">{{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="tipo-file-cover" style="display: none;">
                        <x-file-upload id="{{ uniqid() }}" name="cover" label="Capa" :value="null" />
                    </div>
                    <div id="tipo-opcao-file" class="d-none">
                        <x-file-upload id="{{ uniqid() }}" name="file" label="Arquivo" :value="null" />
                    </div>

                    <div id="tipo-opcao-link" class="mb-3 col-12 d-block">
                        <label for="name">Link</label>
                        <input type="text" class="form-control form-control-md" id="link" name="link">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button id="btn-form-file" type="button" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modais Arquivos -->
@foreach ($produto->files as $file)
    <!-- Modal delete file-->
    <div class="modal fade" id="delFileModal{{ $file->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="delFileModal{{ $file->id }}Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="delFileModal{{ $file->id }}Label">Excluir Arquivo </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p> Tem certeza que deseja excluir o arquivo <span class="text-danger">{{ $file->name }}</span>?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button onclick="fileDeleteArquivo('{{ $file->id }}')" type="button"
                        class="btn btn-danger text-white">Excluir</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal edit file-->
    <div class="modal fade" id="editFileModal{{ $file->id }}" data-bs-backdrop="static"
        data-bs-keyboard="false" tabindex="-1" aria-labelledby="editFileModal{{ $file->id }}Label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editFileModal{{ $file->id }}Label">Editando Arquivo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="form-content-edit-file-{{ $file->id }}">
                    <div class="row">
                        <input name="produto_id" value="{{ $produto->id }}" hidden />
                        <div class="mb-3 col-12">
                            <label for="file_name">Nome</label>
                            <input type="text" autofocus class="form-control form-control-md" id="file_name"
                                value="{{ $file->name }}" name="file_name">
                        </div>
                        <div class="mb-3 col-12">
                            <label for="file_description">Descrição</label>
                            <textarea class="form-control form-control-md" id="file_description" name="file_description">{{ $file->description }}</textarea>
                        </div>
                        <div class="mb-3 col-12">
                            <label for="categoria_id">Módulo</label>
                            <select autofocus class="form-control form-control-md" id="select-category"
                                name="categoria_id" required>
                                <option>--Selecione--</option>
                                @foreach ($produto->categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $file->categoria_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-12">
                            <label for="type">Tipo de entrega</label>
                            <select autofocus class="form-control form-control-md" id="select-type" name="type">
                                <option value="link" {{ $file->type == 'link' ? 'selected' : '' }}>Link externo
                                </option>
                                <option value="file" {{ $file->type == 'file' ? 'selected' : '' }}>Arquivo</option>
                            </select>
                        </div>

                        <div class="mb-3 col-12">
                            <label for="type">Tipo de arquivo</label>
                            <select class="form-control form-control-md" id="select-file-type" name="file_type">
                                <option>--Selecione--</option>
                                @foreach (['audio', 'video', 'zip', 'pdf', 'txt'] as $tipo)
                                    <option value="{{ $tipo }}"
                                        {{ $file->file_type == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="tipo-file-cover" class="{{ $file->file_type != 'video' ? 'd-none' : '' }}">
                            <x-file-upload id="{{ uniqid() }}" name="cover" label="Capa"
                                :value="$file->cover" />
                        </div>
                        <div id="tipo-opcao-file" class="d-none">
                            <x-file-upload id="{{ uniqid() }}" name="file" label="Arquivo"
                                :value="$file->file" />
                        </div>
                        <div id="tipo-opcao-link" class="mb-3 col-12 d-block">
                            <label for="link">Link</label>
                            <input type="text" class="form-control form-control-md" id="link" name="link"
                                value="{{ $file->file }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button id="btn-form-edit-file-{{ $file->id }}" type="button" class="btn btn-primary"
                        onclick="fileEditArquivo('{{ $file->id }}')">Salvar</button>
                </div>
            </div>
        </div>
    </div>
@endforeach