@props(['produtos', 'produto'])

<style>
.pe-ob-section { margin-bottom: 1.75rem; }
.pe-ob-title { font-size: 0.9375rem; font-weight: 600; color: var(--gateway-text-color); margin: 0 0 0.25rem 0; }
.pe-ob-desc { font-size: 0.8125rem; color: #64748b; margin: 0 0 0.75rem 0; }
body.dark-mode .pe-ob-desc { color: #94a3b8; }
.pe-ob-body { border: 1px solid rgba(165,170,177,0.2); border-radius: 10px; padding: 1rem; }
body.dark-mode .pe-ob-body { border-color: rgba(30,41,59,0.8); }
.pe-ob-list { display: flex; flex-direction: column; gap: 0.5rem; }
.pe-ob-item { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid rgba(165,170,177,0.25); background: transparent; transition: border-color 0.2s; }
.pe-ob-item:hover { border-color: var(--gateway-primary-color); }
body.dark-mode .pe-ob-item { border-color: rgba(30,41,59,0.6); }
.pe-ob-item-text { font-size: 0.875rem; color: var(--gateway-text-color); margin: 0; }
.pe-ob-item-text .text-de { text-decoration: line-through; color: #dc3545; }
.pe-ob-item-text .text-por { font-weight: 600; color: #198754; }
.pe-ob-item-actions { flex-shrink: 0; }
.pe-ob-empty { font-size: 0.875rem; color: #64748b; padding: 0.5rem 0; }
.pe-ob-footer { margin-top: 0.75rem; display: flex; align-items: center; gap: 0.5rem; }
</style>

<div class="pe-ob-section">
    <h3 class="pe-ob-title">Order Bump</h3>
    <p class="pe-ob-desc">Adicione até 5 order bumps ao seu produto.</p>
    <div class="pe-ob-body">
        <div class="pe-ob-list">
            @if($produto->bumps->count() > 0)
                @foreach($produto->bumps as $bump)
                    <div class="pe-ob-item">
                        <p class="pe-ob-item-text">{{ $bump->product_name }} — de <span class="text-de">R$ {{ number_format($bump->valor_de, 2, ',','.') }}</span> por <span class="text-por">R$ {{ number_format($bump->valor_por, 2, ',','.') }}</span></p>
                        <div class="pe-ob-item-actions">
                            <button type="button" class="btn btn-outline-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editBumpModal{{ $bump->id }}" title="Editar"><i class="fa-solid fa-pen"></i></button>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="delBump('{{ $bump->id }}')" title="Excluir"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>

                            <div class="modal fade" id="editBumpModal{{ $bump->id }}" tabindex="-1" aria-labelledby="editBumpModal{{ $bump->id }}Label"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="editBumpModal{{ $bump->id }}Label">Order Bump</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        
                                        <div id="dados-order-bump" class="modal-body">
                                            <div class="mb-3 col-12">
                                                <label for="produto_id">Produto</label>
                                                <select class="form-control form-control-md" id="produto_id" name="produto_id" required>
                                                    <option value="null">--Selecione--</option>
                                                    @foreach($produtos as $key => $prod)
                                                    <option value="{{ $prod->id }}" {{ $bump->id == $prod->id ? 'selected' : '' }}>{{ $prod->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3 col-12">
                                                <label for="valor_de">De R$</label>
                                                <input type="text" class="form-control form-control-md" id="valor_de" name="valor_de" value="{{ $bump->valor_de }}" readonly>
                                                @error('valor_de')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3 col-12">
                                                <label for="valor_por">Por&nbsp;R$</label>
                                                <input type="text" class="form-control form-control-md" id="valor_por" name="valor_por" value="{{ $bump->valor_por }}">
                                                @error('valor_por')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3 col-12">
                                                <label for="call_to_action">Call to Action</label>
                                                <input type="text" autofocus class="form-control form-control-md" id="call_to_action" name="call_to_action"
                                                     value="{{ $bump->call_to_action }}">
                                                @error('call_to_action')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3 col-12">
                                                <label for="product_name">Titulo</label>
                                                <input type="text" autofocus class="form-control form-control-md" id="product_name" name="product_name"
                                                     value="{{ $bump->product_name }}">
                                                @error('product_name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3 col-12">
                                                <input type="text" class="form-control form-control-md" id="product_description"
                                                    name="product_description"  value="{{ $bump->product_description }}">
                                                @error('product_description')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3 col-12">
                                                <div class="border border-secondary rounded p-3" style="background-color: #f8f9fa; border-style: dashed;">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <strong class="text-muted" id="call_to_action_text">{{  $bump->call_to_action }}</strong>
                                                        <i class="bi bi-check-circle-fill text-secondary"></i>
                                                    </div>

                                                    <div class="d-flex justify-content-between align-items-center bg-white rounded p-3 shadow-sm mb-2">
                                                        <div>
                                                        <div class="fw-bold text-muted" id="product_name_text">{{  $bump->call_to_action }}</div>
                                                        <div class="text-muted small" id="product_description_text">Adicione a compra</div>
                                                        </div>
                                                        <div class="text-end">
                                                        <div class="text-muted text-decoration-line-through" id="valor_de_text">R$ {{  number_format($bump->valor_de, 2, ',', '.') }}</div>
                                                        <span class="badge bg-success mb-1" id="percentage_text">0% OFF</span>
                                                        <div class="fw-bold text-success"  id="valor_por_text">R$ 0,00</div>
                                                        </div>
                                                    </div>

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="addProduct">
                                                        <label class="form-check-label fw-bold text-muted" for="addProduct">
                                                        Adicionar Produto
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                            <button type="button" class="btn btn-primary">Adicionar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
            @else
                    <p class="pe-ob-empty">Nenhum order bump adicionado.</p>
            @endif
        </div>
        @if($produto->bumps->count() < 5)
            <div class="pe-ob-footer">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBumpModal"><i class="fa-solid fa-plus me-1"></i> Adicionar</button>
                <span class="small text-muted">{{ $produto->bumps->count() }}/5</span>
            </div>
        @endif
    </div>
</div>

        <div class="modal fade" id="addBumpModal" tabindex="-1" aria-labelledby="addBumpModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addBumpModalLabel">Order Bump</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div id="dados-order-bump" class="modal-body">
                        <div class="mb-3 col-12">
                            <label for="produto_id">Produto</label>
                            <select class="form-control form-control-md" id="produto_id" name="produto_id" required>
                                <option value="null">--Selecione--</option>
                                @foreach($produtos as $key => $prod)
                                <option value="{{ $prod->id }}" >{{ $prod->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-12">
                            <label for="valor_de">De R$</label>
                            <input type="text" class="form-control form-control-md" id="valor_de" name="valor_de" readonly>
                            @error('valor_de')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-12">
                            <label for="valor_por">Por&nbsp;R$</label>
                            <input type="text" class="form-control form-control-md" id="valor_por" name="valor_por">
                            @error('valor_por')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-12">
                            <label for="call_to_action">Call to Action</label>
                            <input type="text" autofocus class="form-control form-control-md" id="call_to_action" name="call_to_action"
                                value="SIM, EU ACEITO ESSA OFERTA ESPECIAL!">
                            @error('call_to_action')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-12">
                            <label for="product_name">Titulo</label>
                            <input type="text" autofocus class="form-control form-control-md" id="product_name" name="product_name"
                                value="Nome do seu produto">
                            @error('product_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-12">
                            <input type="text" class="form-control form-control-md" id="product_description"
                                name="product_description" value="Adicione à compra">
                            @error('product_description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-12">
                            <div class="border border-secondary rounded p-3" style="background-color: #f8f9fa; border-style: dashed;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong class="text-muted" id="call_to_action_text">SIM, EU ACEITO ESSA OFERTA ESPECIAL!</strong>
                                    <i class="bi bi-check-circle-fill text-secondary"></i>
                                </div>

                                <div class="d-flex justify-content-between align-items-center bg-white rounded p-3 shadow-sm mb-2">
                                    <div>
                                    <div class="fw-bold text-muted" id="product_name_text">Nome do seu produto</div>
                                    <div class="text-muted small" id="product_description_text">Adicione a compra</div>
                                    </div>
                                    <div class="text-end">
                                    <div class="text-muted text-decoration-line-through" id="valor_de_text">R$ 0,00</div>
                                    <span class="badge bg-success mb-1" id="percentage_text">0% OFF</span>
                                    <div class="fw-bold text-success"  id="valor_por_text">R$ 0,00</div>
                                    </div>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="addProduct">
                                    <label class="form-check-label fw-bold text-muted" for="addProduct">
                                    Adicionar Produto
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button id="btn-adicionar-form-order-bump" type="button" class="btn btn-primary">Adicionar</button>
                    </div>
                </div>
            </div>
        </div>
       <script>
            window.addEventListener('DOMContentLoaded', () => {

                /* ========= REFERÊNCIAS ========= */
                const elSelectProduto   = document.getElementById('produto_id');

                const elValorDeInput    = document.getElementById('valor_de');
                const elValorPorInput   = document.getElementById('valor_por');

                const elValorDeText     = document.getElementById('valor_de_text');
                const elValorPorText    = document.getElementById('valor_por_text');
                const elPercentageText  = document.getElementById('percentage_text');

                const elCallToActionInp = document.getElementById('call_to_action');
                const elProdNameInp     = document.getElementById('product_name');
                const elProdDescInp     = document.getElementById('product_description');

                const elCallToActionTxt = document.getElementById('call_to_action_text');
                const elProdNameTxt     = document.getElementById('product_name_text');
                const elProdDescTxt     = document.getElementById('product_description_text');

                /* ========= UTILITÁRIOS ========= */
                const fmt = n => Number(n || 0).toLocaleString(
                'pt-BR', { style: 'currency', currency: 'BRL' }
                );

                function atualizaValores(deValor, porValor) {
                const de  = Number(deValor)  || 0;
                const por = Number(porValor) || 0;

                // Títulos formatados
                elValorDeText.innerText  = fmt(de);
                elValorPorText.innerText = fmt(por);

                // Percentual de desconto
                const p = de ? Math.round((de - por) / de * 100) : 0;
                elPercentageText.innerText = `${p}% OFF`;
                }

                /* ========= DADOS DO BLADE ========= */
                const produtos = @json($produtos);

                /* ========= EVENTOS ========= */
                // 1) Escolheu um produto → popular valores & preview
                elSelectProduto.addEventListener('change', function () {
                const prod = produtos.find(p => p.id == this.value);
                if (!prod) {                          // nenhum produto
                    atualizaValores(0, 0);
                    return;
                }

                // Preenche inputs numéricos
                elValorDeInput.value  = prod.price;
                elValorPorInput.value = prod.price;   // pode trocar depois no desconto

                atualizaValores(prod.price, prod.price);
                });

                // 2) Alterou manualmente os valores “De/Por” (para aplicar desconto custom)
                [elValorDeInput, elValorPorInput].forEach(el =>
                el.addEventListener('input', () =>
                    atualizaValores(elValorDeInput.value, elValorPorInput.value)
                )
                );

                // 3) Sincroniza texto dos campos editáveis com o preview
                elCallToActionInp.addEventListener('input', e => elCallToActionTxt.innerText = e.target.value);
                elProdNameInp.addEventListener('input', e => elProdNameTxt.innerText = e.target.value);
                elProdDescInp.addEventListener('input', e => elProdDescTxt.innerText = e.target.value);

                /* ========= ESTADO INICIAL ========= */
                atualizaValores(elValorDeInput.value, elValorPorInput.value);
            });
        </script>

<script>
(function () {
  // 1) só executa quando TUDO (inclusive o modal) estiver no DOM
  window.addEventListener('DOMContentLoaded', init);

  function init () {
    const btn   = document.getElementById('btn-adicionar-form-order-bump');
    const dados = document.getElementById('dados-order-bump');

    // defesa: se não achar, para aqui → evita erro silencioso
    if (!btn || !dados) return console.error('Botão ou #dados-order-bump não encontrado.');

    btn.addEventListener('click', function (e) {
      e.preventDefault();

      // 2) Monta <form>
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = "{{ route('produtos.bump.store') }}";
      form.style.display = 'none'; // não pisca na tela

      // 3) Token CSRF
      const token = document.querySelector('meta[name="csrf-token"]')?.content;
      if (!token) return alert('CSRF token não encontrado no <head>.');

      form.append(criaCampo('_token', token));

      // 4) Copia campos da div
      dados.querySelectorAll('input, select, textarea').forEach(el => {
        // ignora sem name
        if (!el.name) return;

        // ignore checkbox/radio não marcados
        if ((el.type === 'checkbox' || el.type === 'radio') && !el.checked) return;

        // select-multiple: envia um hidden por opção marcada
        if (el.tagName === 'SELECT' && el.multiple) {
          [...el.selectedOptions].forEach(opt => {
            form.append(criaCampo(el.name, opt.value));
          });
          return;
        }

        // demais elementos
        form.append(criaCampo(el.name, el.value));
      });

      // 5) Envia
      document.body.appendChild(form);
      form.submit();
    });

    /** Cria <input type="hidden">  */
    function criaCampo (name, value) {
      const h = document.createElement('input');
      h.type  = 'hidden';
      h.name  = name;
      h.value = value;
      return h;
    }
  }
})();

function delBump(id){
    fetch('/produtos/delete/bump/'+id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(function (response) {
        showToast('success', 'Order bump excluído com sucesso.');
        //location.reload();
    })
    .catch(function (error) {
        showToast('error', 'Erro ao excluir order bump.');
    });
}
</script> 