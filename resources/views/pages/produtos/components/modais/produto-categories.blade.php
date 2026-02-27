@props(['produto'])
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
