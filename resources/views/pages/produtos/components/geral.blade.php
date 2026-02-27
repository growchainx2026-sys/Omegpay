@props(['produto', 'produtos' => []])

<style>
.pe-section { margin-bottom: 1.75rem; }
.pe-section:last-child { margin-bottom: 0; }
.pe-section-header { margin-bottom: 0.75rem; }
.pe-section-title { font-size: 0.9375rem; font-weight: 600; color: var(--gateway-text-color); margin: 0 0 0.25rem 0; }
.pe-section-desc { font-size: 0.8125rem; color: var(--pe-text-muted, #64748b); margin: 0; line-height: 1.4; }
.pe-section-body { background: transparent; border: 1px solid var(--pe-border, rgba(165,170,177,0.2)); border-radius: 10px; padding: 1rem 1.25rem; }
body.dark-mode .pe-section-body { border-color: rgba(30,41,59,0.8); }
.pe-field { margin-bottom: 1rem; }
.pe-field:last-child { margin-bottom: 0; }
.pe-field label { display: block; font-size: 0.8125rem; font-weight: 500; color: var(--gateway-text-color); margin-bottom: 0.375rem; }
.pe-field .form-control, .pe-field .form-select { font-size: 0.875rem; border-radius: 8px; border: 1px solid var(--pe-border); }
.pe-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(0, 1fr)); gap: 1rem; }
.pe-hint { font-size: 0.75rem; color: var(--pe-text-muted); margin-top: 0.25rem; }
</style>

<div class="row g-0">
    <div class="col-12">
        {{-- Produto --}}
        <section class="pe-section">
            <div class="pe-section-header">
                <h3 class="pe-section-title">Produto</h3>
                <p class="pe-section-desc">Aprovação instantânea. A imagem será exibida na área de membros.</p>
            </div>
            <div class="pe-section-body">
                <div class="pe-field">
                    <label for="type">Tipo de pagamento</label>
                    <select class="form-select form-control-md" id="type" name="type">
                        <option value="unique" {{ $produto->type == 'unique' ? 'selected' : '' }}>Pagamento único</option>
                        <option value="subscription" {{ $produto->type == 'subscription' ? 'selected' : '' }}>Assinatura recorrente</option>
                    </select>
                </div>
                <div class="pe-field">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control form-control-md" id="name" name="name" value="{{ $produto->name }}" autofocus>
                    @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="pe-field">
                    <label for="description">Descrição</label>
                    <textarea class="form-control" id="description" name="description" rows="4">{{ $produto->description }}</textarea>
                    @error('description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                @php $url = url('/produto-image-default'); @endphp
                @if ($produto->image == "produtos/box_default.svg")
                    <x-image-upload id="{{ uniqid() }}" name="image" label="Imagem do Produto" :value="$url"></x-image-upload>
                @else
                    <x-image-upload id="{{ uniqid() }}" name="image" label="Imagem do Produto" :value="$produto->image"></x-image-upload>
                @endif
                <div class="pe-hint mt-2">Recomendado: 300×250 px</div>
            </div>
        </section>

        {{-- Preços --}}
        <section class="pe-section">
            <div class="pe-section-header">
                <h3 class="pe-section-title">Preços</h3>
            </div>
            <div class="pe-section-body">
                <div class="pe-row">
                    <div class="pe-field">
                        <label for="price">Valor</label>
                        <input type="text" class="form-control form-control-md" id="price" name="price" value="{{ number_format($produto->price, 2, '.', ',') }}">
                        @error('price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="pe-field">
                        <label for="garantia">Garantia</label>
                        <select class="form-select form-control-md" id="garantia" name="garantia">
                            <option value="7" {{ $produto->garantia == 7 ? 'selected' : '' }}>7 dias</option>
                            <option value="14" {{ $produto->garantia == 14 ? 'selected' : '' }}>14 dias</option>
                            <option value="21" {{ $produto->garantia == 21 ? 'selected' : '' }}>21 dias</option>
                            <option value="30" {{ $produto->garantia == 30 ? 'selected' : '' }}>30 dias</option>
                        </select>
                        @error('garantia')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </section>

        {{-- Suporte --}}
        <section class="pe-section">
            <div class="pe-section-header">
                <h3 class="pe-section-title">Suporte ao cliente</h3>
            </div>
            <div class="pe-section-body">
                <div class="pe-row">
                    <div class="pe-field">
                        <label for="email_support">Email de suporte</label>
                        <input type="text" class="form-control form-control-md" id="email_support" name="email_support" value="{{ $produto->email_support }}">
                        @error('email_support')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="pe-field">
                        <label for="name_exibition">Nome de exibição do produtor</label>
                        <input type="text" class="form-control form-control-md" id="name_exibition" name="name_exibition" value="{{ $produto->name_exibition }}">
                        @error('name_exibition')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </section>

        {{-- Funil --}}
        <section class="pe-section">
            <div class="pe-section-header">
                <h3 class="pe-section-title">Funil</h3>
            </div>
            <div class="pe-section-body">
                <div class="pe-field">
                    <label for="thankyou_page">Página de obrigado</label>
                    <input type="text" class="form-control form-control-md" id="thankyou_page" name="thankyou_page" value="{{ $produto->thankyou_page }}">
                    @error('thankyou_page')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#gerarUpsell">
                    <i class="fa-solid fa-code me-1"></i> Gerador de upsell
                </button>
            </div>
        </section>

        {{-- Afiliação --}}
        <section class="pe-section">
            <div class="pe-section-header">
                <h3 class="pe-section-title">Afiliação</h3>
                <p class="pe-section-desc">Exibido na vitrine para afiliados.</p>
            </div>
            <div class="pe-section-body">
                <div class="pe-row">
                    <div class="pe-field">
                        <label for="accept_affiliate">Aceitar afiliação?</label>
                        <select class="form-select form-control-md" id="accept_affiliate" name="accept_affiliate" onchange="onChangeAcceptAffiliate()">
                            <option value="1" {{ $produto->accept_affiliate ? 'selected' : '' }}>Sim</option>
                            <option value="0" {{ !$produto->accept_affiliate ? 'selected' : '' }}>Não</option>
                        </select>
                    </div>
                    <div id="affiliate-percentage" class="pe-field {{ $produto->accept_affiliate ? '' : 'd-none' }}">
                        <label for="affiliate_percentage">Porcentagem (%)</label>
                        <input type="text" class="form-control form-control-md" id="affiliate_percentage" name="affiliate_percentage" value="{{ $produto->affiliate_percentage }}">
                        @error('affiliate_percentage')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

{{-- Modal Gerador Upsell --}}
<div class="modal fade" id="gerarUpsell" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="gerarUpsellLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gerarUpsellLabel">Gerador de Upsell</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-4"><label for="produto-upsell" class="col-form-label">Produto upsell</label></div>
                    <div class="col-12 col-md-8">
                        <select class="form-select" id="produto-upsell">
                            @foreach($produtos ?? [] as $p)
                                <option value="{{ $p->uuid }}">{{ $p->name }} — R$ {{ number_format($p->price, 2, ',', '.') }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-4"><label for="accept-upsell-redirect-to" class="col-form-label">Ao aceitar</label><p class="small text-muted mb-0">Redirecionar para</p></div>
                    <div class="col-12 col-md-8"><input type="text" class="form-control" id="accept-upsell-redirect-to" placeholder="URL após pagamento"></div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-4"><label for="recused-upsell-redirect-to" class="col-form-label">Ao recusar</label><p class="small text-muted mb-0">Redirecionar para</p></div>
                    <div class="col-12 col-md-8"><input type="text" class="form-control" id="recused-upsell-redirect-to" placeholder="URL após recusar"></div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-4"><label for="text-accept-upsell" class="col-form-label">Texto aceitar</label></div>
                    <div class="col-12 col-md-8"><input type="text" class="form-control" id="text-accept-upsell" value="Sim, eu aceito essa oferta especial!"></div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-4"><label for="text-recused-upsell" class="col-form-label">Texto recusar</label></div>
                    <div class="col-12 col-md-8"><input type="text" class="form-control" id="text-recused-upsell" value="Não, eu gostaria de recusar essa oferta!"></div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-4"><label for="buttons-color-upsell" class="col-form-label">Cor dos botões</label></div>
                    <div class="col-12 col-md-8"><input type="color" class="form-control form-control-color" id="buttons-color-upsell" value="#0d6efd"></div>
                </div>
                <hr>
                <p class="small fw-semibold mb-2">Prévia</p>
                <div id="upsell-preview" class="p-3 rounded border" style="border-color: var(--pe-border) !important;">
                    <button type="button" id="preview-accept" class="btn w-100 mb-2 text-white"></button>
                    <a href="#" id="preview-recuse" class="small"></a>
                </div>
                <hr>
                <p class="small mb-2">Adicione antes do &lt;/body&gt;:</p>
                <input class="form-control form-control-sm font-monospace" readonly value="{{ '<script src="'.url('api/upsell/upsellminjs').'"></script>' }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" id="copiar-container-upsell" class="btn btn-primary"><i class="fa-solid fa-copy me-1"></i> Copiar HTML</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var produtoUpsell = document.getElementById("produto-upsell");
    var acceptText = document.getElementById("text-accept-upsell");
    var recuseText = document.getElementById("text-recused-upsell");
    var colorInput = document.getElementById("buttons-color-upsell");
    var recusedUrl = document.getElementById("recused-upsell-redirect-to");
    var acceptUrl = document.getElementById("accept-upsell-redirect-to");
    var previewAccept = document.getElementById("preview-accept");
    var previewRecuse = document.getElementById("preview-recuse");
    var copyBtn = document.getElementById("copiar-container-upsell");
    function updatePreview() {
        if (!previewAccept || !previewRecuse) return;
        previewAccept.textContent = acceptText ? acceptText.value : "Sim, eu aceito";
        previewRecuse.textContent = recuseText ? recuseText.value : "Não, recusar";
        previewAccept.style.backgroundColor = colorInput ? colorInput.value : "#0d6efd";
        previewRecuse.style.color = colorInput ? colorInput.value : "#0d6efd";
        previewRecuse.setAttribute("href", recusedUrl ? recusedUrl.value || "#" : "#");
        if (previewAccept) { previewAccept.setAttribute("data-redirect-to", acceptUrl ? acceptUrl.value || "#" : "#"); previewAccept.setAttribute("data-produto-id", produtoUpsell ? produtoUpsell.value : ""); }
    }
    [acceptText, recuseText, colorInput, recusedUrl, acceptUrl, produtoUpsell].forEach(function(el) { if (el) el.addEventListener("input", updatePreview); });
    updatePreview();
    if (copyBtn) copyBtn.addEventListener("click", function () {
        var container = document.getElementById("upsell-preview");
        if (container) navigator.clipboard.writeText(container.outerHTML).then(function() { showToast('success', 'Copiado.'); }).catch(function(err) { showToast('error', err); });
    });
});
function onChangeAcceptAffiliate() {
    var sel = document.getElementById('accept_affiliate');
    var el = document.getElementById('affiliate-percentage');
    if (!el) return;
    if (sel && sel.value == '1') { el.classList.remove('d-none'); el.classList.add('d-flex'); } else { el.classList.add('d-none'); el.classList.remove('d-flex'); }
}
</script>
