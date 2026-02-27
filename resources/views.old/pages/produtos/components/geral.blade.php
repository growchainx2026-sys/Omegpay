@props([
'produto',
'produtos'
])

<div class="row">
    <div class="col-lg-4">
        <h4 class="texto-branco">Produto</h4>
        <p class="texto-branco">A aprovação do produto é instantânea, ou seja, você pode cadastrar e já começar a vender. A imagem do produto será exibida na área de membros.</p>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="mb-3 col-12">
                    <label for="type">Tipo de pagamento</label>
                    <select class="form-control form-control-md" id="type" name="type">
                        <option value="unique" {{ $produto->type == 'unique' ? 'selected' : '' }}>Pagamento único
                        </option>
                        <option value="subscription" {{ $produto->type == 'subscription' ? 'selected' : '' }}>Assinatura
                            recorrente</option>
                    </select>
                </div>

                <div class="mb-3 col-12">
                    <label for="name">Nome</label>
                    <input type="text" autofocus class="form-control form-control-md" id="name" name="name"
                        value="{{ $produto->name }}">
                    @error('name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 col-12">
                    <label for="description">Descrição</label>
                    <textarea class="form-control" id="description" name="description"
                        rows="5">{{ $produto->description }}</textarea>
                    @error('description')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                    @php
                        $url = url('/produto-image-default'); 
                    @endphp
                    @if ($produto->image == "produtos/box_default.svg")
                    <x-image-upload id="{{ uniqid() }}" name="image" label="Imagem do Produto" :value="$url"></x-image-upload>
                    @else
                        <x-image-upload id="{{ uniqid() }}" name="image" label="Imagem do Produto" :value="$produto->image"></x-image-upload>
                    @endif
                <div class="my-3">
                    <x-alert :text="'Tamanho recomendado: 300x250 pixels'" />
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <h4 class="texto-branco">Preços</h4>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-lg-6">
                        <label for="price">Valor</label>
                        <input type="text" class="form-control form-control-md" id="price" name="price"
                            value="{{ number_format($produto->price, 2, '.', ',') }}">
                        @error('price')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-lg-6">
                        <label for="garantia">Garantia</label>
                        <select type="text" class="form-control form-control-md" id="garantia" name="garantia"
                            value="{{ $produto->garantia }}">
                            <option value="7" {{ $produto->garantia == 7 ? 'selected' : '' }}>7 dias</option>
                            <option value="14" {{ $produto->garantia == 14 ? 'selected' : '' }}>14 dias</option>
                            <option value="21" {{ $produto->garantia == 21 ? 'selected' : '' }}>21 dias</option>
                            <option value="30" {{ $produto->garantia == 30 ? 'selected' : '' }}>30 dias</option>
                        </select>
                        @error('garantia')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <h4 class="texto-branco">Suporte ao cliente</h4>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-12">
                        <label for="email_support">Email de suporte</label>
                        <input type="text" class="form-control form-control-md" id="email_support" name="email_support"
                            value="{{ $produto->email_support }}">
                        @error('email_support')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-12">
                        <label for="name_exibition">Nomde de exibição do produtor</label>
                        <input type="text" class="form-control form-control-md" id="name_exibition" name="name_exibition"
                            value="{{ $produto->name_exibition }}">
                        @error('name_exibition')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-4">
        <h4 class="texto-branco">Funil</h4>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-12">
                        <label for="email_support">Página de obrigado</label>
                        <input type="text" class="form-control form-control-md" id="thankyou_page" name="thankyou_page"
                            value="{{ $produto->thankyou_page }}">
                        @error('thankyou_page')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 col-12">
                        <button 
                        type="button"
                        class="btn btn-primary" 
                        data-bs-toggle="modal" 
                        data-bs-target="#gerarUpsell">
                            <i data-lucide="code-xml" class="me-2" style="stroke: white !important;"></i>
                            Gerador de upsell
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <h4 class="texto-branco">Afiliação</h4>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-12 col-lg-6">
                        <label for="email_support">Aceitar afiliação?</label>
                        <select class="form-control form-control-md" id="accept_affiliate" name="accept_affiliate"
                            value="{{ $produto->accept_affiliate }}" onchange="onChangeAcceptAffiliate()">
                            <option value="1" {{ $produto->accept_affiliate ? 'selected' : '' }}>Sim</option>
                            <option value="0" {{ $produto->accept_affiliate ? '' : 'selected' }}>Não</option>
                        </select>
                        <small>Será exibido na vitrine para membros que queiram afiliar-se</small>
                        @error('accept_affiliate')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div id="affiliate-percentage" class="mb-3 col-12 col-lg-6 d-none">
                        <label for="affiliate_percentage">Porcentagem</label>
                        <input type="text" class="form-control form-control-md" id="affiliate_percentage" name="affiliate_percentage"
                            value="{{ $produto->affiliate_percentage }}">
                        @error('affiliate_percentage')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="gerarUpsell" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="gerarUpsellLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h1 class="modal-title fs-5" id="gerarUpsellLabel">Gerador de Upsell</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">

        <div class="row g-3 align-items-center w-100">
          <div class="col-12 col-lg-4">
            <label for="produto-upsell" class="col-form-label">Produto upsell</label>
          </div>
          <div class="col-12 col-lg-8">
            <select class="form-control" id="produto-upsell">
              @foreach($produtos as $produto)
                <option value="{{ $produto->uuid }}">
                  {{ $produto->name }} {{ "R$ ".number_format($produto->price, 2, ',', '.') }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <!-- Inputs -->
        <div class="row g-3 align-items-center w-100">
          <div class="col-12 col-lg-4">
            <label for="accept-upsell-redirect-to" class="col-form-label">Ao aceitar a upsell</label>
            <p><small>Redirecionar para</small></p>
          </div>
          <div class="col-12 col-lg-8">
            <input 
              type="text"
              class="form-control"
              id="accept-upsell-redirect-to"
              placeholder="Digite a url a redirecionar após o pagamento">
          </div>
        </div>

        <div class="row g-3 align-items-center w-100">
          <div class="col-12 col-lg-4">
            <label for="recused-upsell-redirect-to" class="col-form-label">Ao recusar a upsell</label>
            <p><small>Redirecionar para</small></p>
          </div>
          <div class="col-12 col-lg-8">
            <input 
              type="text"
              class="form-control"
              id="recused-upsell-redirect-to"
              placeholder="Digite a url a redirecionar após recusar">
          </div>
        </div>

        <div class="row g-3 mb-3 align-items-center w-100">
          <div class="col-12 col-lg-4">
            <label for="text-accept-upsell" class="col-form-label">Texto aceitar upsell</label>
          </div>
          <div class="col-12 col-lg-8">
            <input 
              type="text"
              class="form-control"
              id="text-accept-upsell"
              value="Sim, eu aceito essa oferta especial!">
          </div>
        </div>

        <div class="row g-3 mb-3 align-items-center w-100">
          <div class="col-12 col-lg-4">
            <label for="text-recused-upsell" class="col-form-label">Texto recusar upsell</label>
          </div>
          <div class="col-12 col-lg-8">
            <input 
              type="text"
              class="form-control"
              id="text-recused-upsell"
              value="Não, eu gostaria de recusar essa oferta!">
          </div>
        </div>

        <div class="row g-3 mb-3 align-items-center w-100">
          <div class="col-12 col-lg-4">
            <label for="buttons-color-upsell" class="col-form-label">Cor</label>
          </div>
          <div class="col-12 col-lg-8">
            <input 
              type="color"
              class="form-control form-control-color"
              id="buttons-color-upsell"
              value="#0d6efd">
          </div>
        </div>

        <!-- Preview -->
        <hr>
        <h5 style="margin-top: 20px; font-size: 16px;">Prévia</h5>
        <div id="upsell-preview" style="text-align: center; padding: 20px; border: 1px solid #ddd; border-radius: 6px;">

          <button id="preview-accept" 
            style="display: block; width: 100%; padding: 12px; margin-bottom: 10px;
              background-color: #0d6efd; color: white !important; font-size: 16px; font-weight: bold;
              border: none; border-radius: 4px; cursor: pointer;">
          </button>

          <a id="preview-recuse"  
            style="display: inline-block; font-size: 14px; color: #0d6efd; text-decoration: underline; cursor: pointer;">
          </a>

        </div>

        <hr>
        <div style="text-align: center; padding: 5px; border: 1px solid #ddd; border-radius: 6px;">
          <h5 style="font-size: 16px;">Adicione o script abaixo antes do {{ "</body>" }} da sua página: </h5>
          <input class="form-control my-3 text-center" readonly value="{{ '<script src="'.url('api/upsell/upsellminjs').'"></script>' }}" />
          <h5 style="font-size: 12px;">Posteriormente clique em Copiar HTML e adicione ao seu código.</h5>
        </div>

      </div> <!-- /.modal-body -->

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button type="button" id="copiar-container-upsell" class="btn btn-primary">
          <i data-lucide="code-xml" class="me-2" style="stroke: white !important;"></i> Copiar HTML
        </button>
      </div>

    </div> <!-- /.modal-content -->
  </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->


<script>
document.addEventListener("DOMContentLoaded", function () {
  const produtoUpsell = document.getElementById("produto-upsell");
  const acceptText = document.getElementById("text-accept-upsell");
  const recuseText = document.getElementById("text-recused-upsell");
  const colorInput = document.getElementById("buttons-color-upsell");
  const recusedUrl = document.getElementById("recused-upsell-redirect-to");
  const acceptUrl = document.getElementById("accept-upsell-redirect-to");

  const previewAccept = document.getElementById("preview-accept");
  const previewRecuse = document.getElementById("preview-recuse");
  const copyBtn = document.getElementById("copiar-container-upsell");

  function updatePreview() {
    previewAccept.textContent = acceptText.value || "Sim, eu aceito essa oferta especial!";
    previewRecuse.textContent = recuseText.value || "Não, eu gostaria de recusar essa oferta!";
    previewAccept.style.backgroundColor = colorInput.value || "#0d6efd";
    previewRecuse.style.color = colorInput.value || "#0d6efd";

    previewRecuse.setAttribute("href", recusedUrl.value || "#");
    previewAccept.setAttribute('data-redirect-to', acceptUrl.value || "#"); 
    previewAccept.setAttribute('data-produto-id', produtoUpsell.value); 
  }

  // Atualiza sempre que mudar
  [acceptText, recuseText, colorInput, recusedUrl, acceptUrl, produtoUpsell].forEach(el => {
    el.addEventListener("input", updatePreview);
  });

  // Inicializa
  updatePreview();

  // Função copiar
  copyBtn.addEventListener("click", function () {
    const container = document.getElementById("upsell-preview");
    const html = container.outerHTML; // pega o container + conteúdo
    navigator.clipboard.writeText(html).then(() => {
      showToast('success', 'Configurações upsell copiada com sucesso.');
    }).catch(err => {
      showToast('error', 'Erro ao copiar: ' + err);
    });
  });
});

function onChangeAcceptAffiliate(){
  let sel = document.getElementById('accept_affiliate');
  console.log(sel.value)
  if(sel.value == 1){
    document.getElementById('affiliate-percentage').classList.remove('d-none')
    document.getElementById('affiliate-percentage').classList.add('d-flex')
  } else {
    document.getElementById('affiliate-percentage').classList.add('d-none')
    document.getElementById('affiliate-percentage').classList.remove('d-flex')

  }
}
</script>

