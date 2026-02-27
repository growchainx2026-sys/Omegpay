@props(['produto', 'checkouts' => []])
<div class="row">
    <div class="col-12">
        <x-alert :color="'green'" :text="'Os arquivos/link inseridos aqui, serão entregues aos compradores após a confirmação do pagamento.'" />
        <x-alert :color="'green'" :text="'No caso de link externo, o link será enviado ao comprador via email.'" />
        <x-alert :color="'green'" :text="'No caso de arquivos, será criando um acesso e enviado ao email do comprador e estarão disponíveis na área de membros.'" />
    </div>

    <div class="accordion accordion-flush">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseOne" aria-expanded="true" aria-controls="flush-collapseOne">
                    Customização
                </button>
            </h2>
            <div id="flush-collapseOne" class="accordion-collapse show" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body row">

                    <div class="col-12 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="area_member_shop_show" name="area_member_shop_show"
                                {{ $produto->area_member_shop_show ? 'checked' : '' }}>
                            <label class="form-check-label" for="area_member_shop_show">Ativar exibição em shop na área de membros</label>
                        </div>
                    </div>
                    @php
                        $nomeCores = ['Cor dos botões', 'Cor de fundo', 'Cor do sidebar/cards', 'Cor dos textos'];
                    @endphp
                    <div class="col-12 col-xl-6">
                        <div class="card w-100">
                            <div class="card-body w-100">
                                <div class="row">
                                    @foreach (['area_member_color_primary', 'area_member_color_background', 'area_member_color_sidebar', 'area_member_color_text'] as $key => $cor)
                                        <div class="col-12">
                                            <div class="form-floating mb-3">
                                                <input type="color" class="form-control" id="{{ $cor }}"
                                                    name="{{ $cor }}" value="{{ $produto->{$cor} }}"
                                                    placeholder="">
                                                <label for="{{ $cor }}">{{ $nomeCores[$key] }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-6">
                        <div class="card w-100">
                            <div class="card-body w-100">
                                <div class="mb-3 w-100">
                                    <x-image-upload :height="'110px'" id="area_member_background_image"
                                        name="area_member_background_image" label="Imagem de fundo" :value="$produto->area_member_background_image" />
                                </div>
                                <div class="mb-3 w-100">
                                    <x-image-upload :height="'110px'" id="area_member_banner" name="area_member_banner"
                                        label="Banner" :value="$produto->area_member_banner" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseTwo" aria-expanded="true" aria-controls="flush-collapseTwo">
                    Produtos / Módulos
                </button>
            </h2>
            <div id="flush-collapseTwo" class="accordion-collapse show" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body row ">
                    <!-- Categorias -->
                    <div class="col-12 col-xl-6">
                        <div class="card w-100">
                            <div class="card-body w-100">
                                <div class="card-title d-flex align-items-center justify-content-between">
                                    <h5>Módulos</h5>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addCategoryModal">+ Módulo</button>
                                </div>
                                <table class="table w-100" id="table-categories-files-checkout">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Descrição</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($produto->categories as $category)
                                            <tr>
                                                <td>{{ $category->name }}</td>
                                                <td>{{ $category->description }}</td>
                                                <td class="gap-2">
                                                    <button class="btn btn-info text-white btn-sm" type="button"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editCategoryModal{{ $category->id }}">
                                                        <x-lucide-icon :icon="'square-pen'" :color="'white'" />
                                                    </button>
                                                    <button class="btn btn-danger text-white btn-sm" type="button"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#delCategoryModal{{ $category->id }}">
                                                        <x-lucide-icon :icon="'trash'" :color="'white'" />
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Arquivos -->
                    <div class="col-12 col-xl-6">
                        <div class="card w-100">
                            <div class="card-body w-100">
                                <div class="card-title d-flex align-items-center justify-content-between">
                                    <h5>Arquivos</h5>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addFileModal">+ Arquivo</button>
                                </div>
                                <table class="table w-100" id="table-files-checkout">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Descrição</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($produto->files as $file)
                                            @php
                                                $link = url('/storage' . $file->file);
                                                if ($file->type == 'file') {
                                                    $arquivo = explode('/', $file->file);
                                                    $arquivo = end($arquivo);
                                                } else {
                                                    $link = $file->file;
                                                    $arquivo = $file->file;
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $file->name }}</td>
                                                <td>{{ $file->description }}</td>
                                                <td class="gap-2">
                                                    <button class="btn btn-info text-white btn-sm" type="button"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editFileModal{{ $file->id }}">
                                                        <x-lucide-icon :icon="'square-pen'" :color="'white'" />
                                                    </button>
                                                    <button class="btn btn-danger text-white btn-sm" type="button"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#delFileModal{{ $file->id }}">
                                                        <x-lucide-icon :icon="'trash'" :color="'white'" />
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modais Categorias -->
@foreach ($produto->categories as $category)
    <!-- Modal delete categoria -->
    <div class="modal fade" id="delCategoryModal{{ $category->id }}" data-bs-backdrop="static"
        data-bs-keyboard="false" tabindex="-1" aria-labelledby="delCategoryModal{{ $category->id }}Label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="delCategoryModal{{ $category->id }}Label">Excluir Módulo</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p> Tem certeza que deseja excluir o módulo <span
                            class="text-danger">{{ $category->name }}</span>? </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button onclick="fileDeleteCategory('{{ $category->id }}')" type="button"
                        class="btn btn-danger text-white">Excluir</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal edit categoria-->
    <div class="modal fade" id="editCategoryModal{{ $category->id }}" data-bs-backdrop="static"
        data-bs-keyboard="false" tabindex="-1" aria-labelledby="editCategoryModal{{ $category->id }}Label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editCategoryModal{{ $category->id }}Label">Editando Módulo
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="form-content-edit-category-{{ $category->id }}">
                    <div class="row">
                        <input name="id" value="{{ $category->id }}" hidden />
                        <div class="mb-3 col-12">
                            <label for="category_name">Nome</label>
                            <input type="text" autofocus class="form-control form-control-md" id="category_name"
                                value="{{ $category->name }}" name="category_name">
                        </div>
                        <div class="mb-3 col-12">
                            <label for="category_description">Descrição</label>
                            <textarea class="form-control form-control-md" id="category_description" name="category_description">{{ $category->description }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button id="btn-form-edit-category-{{ $category->id }}" type="button" class="btn btn-primary"
                        onclick="fileEditCategory('{{ $category->id }}')">Salvar</button>
                </div>
            </div>
        </div>
    </div>
@endforeach


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
                            <label for="type">Tipo de entrega</label>
                            <select autofocus class="form-control form-control-md" id="select-type-{{ $file->id }}" name="type">
                                <option value="link" {{ $file->type == 'link' ? 'selected' : '' }}>Link externo
                                </option>
                                <option value="file" {{ $file->type == 'file' ? 'selected' : '' }}>Arquivo</option>
                            </select>
                        </div>

                        <div class="mb-3 col-12" id="categoria-wrapper-{{ $file->id }}">
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

                        <div class="mb-3 col-12" id="file-type-wrapper-{{ $file->id }}">
                            <label for="type">Tipo de arquivo</label>
                            <select class="form-control form-control-md" id="select-file-type" name="file_type">
                                <option>--Selecione--</option>
                                @foreach (['audio', 'video', 'zip', 'pdf', 'txt'] as $tipo)
                                    <option value="{{ $tipo }}"
                                        {{ $file->file_type == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="tipo-file-cover-{{ $file->id }}" class="{{ $file->file_type != 'video' ? 'd-none' : '' }}">
                            <x-image-upload id="{{ uniqid() }}" name="cover" label="Capa"
                                :value="$file->cover" />
                        </div>
                        <div id="tipo-opcao-file-{{ $file->id }}" class="d-none">
                            <x-file-upload id="{{ uniqid() }}" name="file" label="Arquivo"
                                :value="$file->file" />
                        </div>
                        <div id="tipo-opcao-link-{{ $file->id }}" class="mb-3 col-12 d-block">
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


<!-- Modal Modulo-->
<div class="modal fade" id="addCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addCategoryModalLabel">Adicionar Módulo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="form-content-category">
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
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button id="btn-form-category" type="button" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>

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
                        <select  class="form-control form-control-md" id="select-input-type" name="type">
                            <option value="link">Link externo</option>
                            <option value="file">Arquivo</option>
                        </select>
                    </div>
                    <div id="categoria-wrapper" class="mb-3 col-12 d-none">
                        <label for="categoria_id">Módulo</label>
                        <select  class="form-control form-control-md" id="select-category"
                            name="categoria_id" required>
                            <option>--Selecione--</option>
                            @foreach ($produto->categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ isset($file->categoria_id) && $file->categoria_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    

                    <div id="file-type-wrapper" class="mb-3 col-12 d-none">
                        <label for="type">Tipo de arquivo</label>
                        <select class="form-control form-control-md" id="select-file-type" name="file_type"
                            >
                            <option>--Selecione--</option>
                            @foreach (['audio', 'video', 'zip', 'pdf', 'txt'] as $tipo)
                                <option value="{{ $tipo }}">{{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="tipo-file-cover-wrapper" class="d-none">
                        <x-image-upload id="{{ uniqid() }}" name="cover" label="Capa" :value="null" />
                    </div>
                    <div id="tipo-opcao-file-wrapper" class="d-none">
                        <x-file-upload id="{{ uniqid() }}" name="file" label="Arquivo" :value="null" />
                    </div>

                    <div id="tipo-opcao-link-wrapper" class="mb-3 col-12 d-block">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const selectType = document.getElementById("select-input-type");
    const fileDiv = document.getElementById("tipo-opcao-file-wrapper");
    const linkDiv = document.getElementById("tipo-opcao-link-wrapper");
    const coverDiv = document.getElementById("tipo-file-cover-wrapper");
    const category = document.getElementById('categoria-wrapper');
    const fileType = document.getElementById('file-type-wrapper');

    function toggleEntrega() {
    const isFile = selectType.value === "file";
    
    linkDiv.classList.toggle("d-none", isFile);
    fileDiv.classList.toggle("d-none", !isFile);
    category.classList.toggle("d-none", !isFile);
    fileType.classList.toggle("d-none", !isFile);
    coverDiv.classList.toggle("d-none", !isFile);

    // Desabilita selects ocultos
    category.querySelector('select').disabled = !isFile;
    fileType.querySelector('select').disabled = !isFile;
}

    // Verifica sempre que mudar
    selectType.addEventListener("change", toggleEntrega);

    // Verifica ao abrir modal
    const addFileModal = document.getElementById("addFileModal");
    if (addFileModal) {
        addFileModal.addEventListener("shown.bs.modal", toggleEntrega);
    }

    // Executa na carga inicial também
    toggleEntrega();


    function toggleEditEntrega(fileId) {
        const selectType = document.getElementById(`select-type-${fileId}`);
        const fileDiv = document.getElementById(`tipo-opcao-file-${fileId}`);
        const linkDiv = document.getElementById(`tipo-opcao-link-${fileId}`);
        const coverDiv = document.getElementById(`tipo-file-cover-${fileId}`);
        const category = document.getElementById(`categoria-wrapper-${fileId}`);
        const fileType = document.getElementById(`file-type-wrapper-${fileId}`);

        if (selectType.value === "file") {
            linkDiv.classList.add("d-none");
            fileDiv.classList.remove("d-none");
            category.classList.remove("d-none");
            fileType.classList.remove("d-none");
            coverDiv.classList.remove("d-none");
        } else {
            linkDiv.classList.remove("d-none");
            fileDiv.classList.add("d-none");
            category.classList.add("d-none");
            fileType.classList.add("d-none");
            coverDiv.classList.add("d-none");
        }
    }

     @foreach ($produto->files as $file)
        const modal{{ $file->id }} = document.getElementById('editFileModal{{ $file->id }}');
        if (modal{{ $file->id }}) {
            modal{{ $file->id }}.addEventListener('shown.bs.modal', function () {
                toggleEditEntrega('{{ $file->id }}');
            });

            const selectType{{ $file->id }} = document.getElementById('select-type-{{ $file->id }}');
            selectType{{ $file->id }}.addEventListener('change', function () {
                toggleEditEntrega('{{ $file->id }}');
            });

            // Executa na carga inicial
            toggleEditEntrega('{{ $file->id }}');
        }
    @endforeach
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        /* INIT SUBMIT ARQUIVO */
        const btnSubmit = document.getElementById('btn-form-file');
        const formContainer = document.getElementById('form-content-file');

        // Criar <form> e envolver o conteúdo
        const form = document.createElement('form');
        form.method = 'POST'; // ou 'GET'
        form.action = "{{ route('produto.files.add') }}"; // coloque a rota de envio
        form.enctype = 'multipart/form-data'; // importante para uploads

        // Adicionar token CSRF
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);

        // Move todos os elementos filhos para dentro do form
        while (formContainer.firstChild) {
            form.appendChild(formContainer.firstChild);
        }

        // Adiciona o form de volta ao container
        formContainer.appendChild(form);

        // Ao clicar no botão "Salvar"
        btnSubmit.addEventListener('click', function() {
            form.submit();
        });
        /* FINISH SUBMIT ARQUIVO */

        /* INIT SUBMIT CATEGORY */
        const btnSubmitCategory = document.getElementById('btn-form-category');
        const formContainerCategory = document.getElementById('form-content-category');

        // Criar <form> e envolver o conteúdo
        const formCategory = document.createElement('form');
        formCategory.method = 'POST'; // ou 'GET'
        formCategory.action = "{{ route('produto.category.add') }}"; // coloque a rota de envio
        formCategory.enctype = 'multipart/form-data'; // importante para uploads

        // Adicionar token CSRF
        const csrfcategory = document.createElement('input');
        csrfcategory.type = 'hidden';
        csrfcategory.name = '_token';
        csrfcategory.value = '{{ csrf_token() }}';
        formCategory.appendChild(csrfcategory);

        // Move todos os elementos filhos para dentro do form
        while (formContainerCategory.firstChild) {
            formCategory.appendChild(formContainerCategory.firstChild);
        }

        // Adiciona o form de volta ao container
        formContainerCategory.appendChild(formCategory);

        // Ao clicar no botão "Salvar"
        btnSubmitCategory.addEventListener('click', function() {
            formCategory.submit();
        });
        /* FINISH SUBMIT CATEGORY */
    });
</script>

<script>
    function fileDeleteArquivo(id) {
        const form = document.createElement('form');
        form.method = 'POST'; // ou 'GET'
        form.action = `{{ route('produto.file.delete') }}`; // coloque a rota de envio

        // Adicionar token CSRF
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);

        const inputid = document.createElement('input');
        inputid.type = 'hidden';
        inputid.name = 'id';
        inputid.value = id;
        form.appendChild(inputid);

        document.body.appendChild(form);

        form.submit();
    }

    function fileEditArquivo(id) {
        const btnSubmit = document.getElementById(`btn-form-edit-file-${id}`);
        const formContainer = document.getElementById(`form-content-edit-file-${id}`);

        // Criar <form> e envolver o conteúdo
        const form = document.createElement('form');
        form.method = 'POST'; // ou 'GET'
        form.action = "{{ route('produto.files.edit') }}"; // coloque a rota de envio
        form.enctype = 'multipart/form-data'; // importante para uploads

        const inputid = document.createElement('input');
        inputid.type = 'hidden';
        inputid.name = 'id';
        inputid.value = id;
        form.appendChild(inputid);

        // Adicionar token CSRF
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);

        // Move todos os elementos filhos para dentro do form
        while (formContainer.firstChild) {
            form.appendChild(formContainer.firstChild);
        }
        console.log({
            form
        })
        // Adiciona o form de volta ao container
        formContainer.appendChild(form);

        // Ao clicar no botão "Salvar"
        btnSubmit.addEventListener('click', function() {
            form.submit();
        });
    }

    function fileEditCategory(id) {
        const btnSubmit = document.getElementById(`btn-form-edit-category-${id}`);
        const formContainer = document.getElementById(`form-content-edit-category-${id}`);

        // Criar <form> e envolver o conteúdo
        const form = document.createElement('form');
        form.method = 'POST'; // ou 'GET'
        form.action = "{{ route('produto.category.edit') }}"; // coloque a rota de envio
        form.enctype = 'multipart/form-data'; // importante para uploads

        const inputid = document.createElement('input');
        inputid.type = 'hidden';
        inputid.name = 'id';
        inputid.value = id;
        form.appendChild(inputid);

        // Adicionar token CSRF
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);

        // Move todos os elementos filhos para dentro do form
        while (formContainer.firstChild) {
            form.appendChild(formContainer.firstChild);
        }

        // Adiciona o form de volta ao container
        formContainer.appendChild(form);

        // Ao clicar no botão "Salvar"
        btnSubmit.addEventListener('click', function() {
            form.submit();
        });
    }

    function fileDeleteCategory(id) {
        const form = document.createElement('form');
        form.method = 'POST'; // ou 'GET'
        form.action = `{{ route('produto.category.delete') }}`; // coloque a rota de envio

        // Adicionar token CSRF
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);

        const inputid = document.createElement('input');
        inputid.type = 'hidden';
        inputid.name = 'id';
        inputid.value = id;
        form.appendChild(inputid);

        document.body.appendChild(form);

        form.submit();
    }
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        var table = $("#table-files-checkout").DataTable({
            responsive: true,
            ordering: false,
            lengthChange: false,
            dom: 't', // Remove o search padrão do DataTables
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
            }
        });

        // Aplicar busca personalizada
        $('#custom-search').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Ajuste visual após renderização
        table.on('draw', function() {
            $('#table-files-checkout tbody tr').each(function() {
                $(this).find('td').css('border-bottom', 'none');
            });
        });

        table.draw(); // Inicializa com ajustes

        


        var tableCat = $("#table-categories-files-checkout").DataTable({
            responsive: true,
            ordering: false,
            lengthChange: false,
            dom: 't', // Remove o search padrão do DataTables
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
            }
        });

        // Aplicar busca personalizada
        $('#custom-search').on('keyup', function() {
            tableCat.search(this.value).draw();
        });

        // Ajuste visual após renderização
        tableCat.on('draw', function() {
            $('#table-categories-files-checkout tbody tr').each(function() {
                $(this).find('td').css('border-bottom', 'none');
            });
        });

        tableCat.draw(); // Inicializa com ajustes
    });
</script>



<!-- CSS opcional para alinhamento -->
<style>
    #custom-search {
        max-width: 180px;
    }

    @media screen and (max-width: 540px) {
        #custom-toolbar {
            display: inline;
        }

        #custom-search {
            width: 100%;
        }
    }

    /* <-- esta chave final estava faltando */
</style>
