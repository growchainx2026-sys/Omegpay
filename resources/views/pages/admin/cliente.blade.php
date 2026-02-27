@extends('layouts.app')

@section('title', 'Editar cliente')

@section('content')
    <form method="POST" action="{{ route('admin.clientes.update') }}">
        @csrf
        <input type="hidden" name="id" value="{{ $client->id }}">
        <input type="hidden" name="clientId" value="{{ $client->clientId }}">
        <input type="hidden" name="secret" value="{{ $client->secret }}">
        <div class="row my-3">
            <div class="col d-flex align-items-center justify-content-between">
                <a href="#" onclick="history.back()" class="text-primary"
                    style="color: var(--gateway-primary-color)!important;"><i
                        class="fa-solid fa-arrow-left"></i>&nbsp;Voltar</a>
                <button class="btn btn-md btn-primary">Salvar alterações</button>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body text-center my-4" style="padding-bottom: 65px;">
                        <img src="{{ asset($client->avatar) }}" alt="avatar"
                            style="width: 150px; height:150px; object-fit:cover;border-radius:150px;margin-bottom:10px;">
                        <h5 class="my-3">{{ $client->name }}</h5>
                        <p class="text-muted mb-1">
                            @if ($client->permission == 'admin')
                                {{ 'Administrador' }}
                            @elseif($client->permission == 'user')
                                {{ 'Cliente' }}
                            @endif
                        </p>
                        <p class="text-muted mb-4">{{ $client->bairro ?? 'Bairro' }}, {{ $client->cidade ?? 'Cidade' }} -
                            {{ $client->estado ?? 'UF' }}
                        </p>
                        <div class="d-flex justify-content-center mb-2">
                            <a href="tel:{{ str_replace(['.', ',', '(', ')', ' ', '-'], '', $client->telefone) }}"
                                target="_blank">
                                <button type="button" data-mdb-button-init data-mdb-ripple-init
                                    class="btn btn-primary">Telefone</button>
                            </a>
                            <a href="https://wa.me/55{{ str_replace(['.', ',', '(', ')', ' ', '-'], '', $client->telefone) }}"
                                target="_blank">
                                <button type="button" data-mdb-button-init data-mdb-ripple-init
                                    class="btn btn-primary ms-1">Whatsapp</button>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card mb-4 mb-lg-0">
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush rounded-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <div class="gap-3">
                                    <i class="fas fa-wallet fa-lg text-success"></i>
                                    <span>Saldo disponível</span>
                                </div>
                                <p class="mb-0">R$ {{ number_format($client->saldo ?? 0, 2, ',', '.') }}</p>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <div class="gap-3">
                                    <i class="fas fa-wallet fa-lg text-warning"></i>
                                    <span>Saldo bloqueado</span>
                                </div>
                                <p class="mb-0">R$ {{ number_format($client->saldo_bloqueado ?? 0, 2, ',', '.') }}</p>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <div class="gap-3">
                                    <i class="fas fa-download fa-lg text-success"></i>
                                    <span>Vendas realizadas</span>
                                </div>
                                <p class="mb-0">R$
                                    {{ number_format($client->transactions_in()->where('status', 'pago')->sum('amount') ?? 0, 2, ',', '.') }}
                                </p>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <div class="gap-3">
                                    <i class="fas fa-upload fa-lg text-warning"></i>
                                    <span>Retiradas realizadas</span>
                                </div>
                                <p class="mb-0">R$
                                    {{ number_format($client->transactions_out()->where('status', 'pago')->sum('amount') ?? 0, 2, ',', '.') }}
                                </p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card my-4 mb-lg-0">
                    <div class="card-body p-0 pb-2 mb-2" style="padding-bottom: 12px !important;">
                        <ul class="list-group list-group-flush rounded-3">
                            <li class="list-group-item  p-3">
                                <div class="gap-3 mb-2">
                                    <span class="d-flex align-items-center justify-content-between">
                                        <span>
                                            <i class="fas fa-key fa-lg text-warning"></i>
                                            Chave Token:
                                        </span>
                                        <i class="fas fa-sync text-info cursor-pointer" id="icon-rotate-clientId"
                                            onclick="gerarChave('clientId')"></i>
                                    </span>
                                </div>
                                <p id="clientId" class="mb-0">{{ $client->clientId }}</p>
                            </li>

                            <li class="list-group-item p-3">
                                <div class="gap-3 mb-2">
                                    <span class="d-flex align-items-center justify-content-between">
                                        <span>
                                            <i class="fas fa-key fa-lg text-warning"></i>
                                            Chave Secret:
                                        </span>
                                        <i class="fas fa-sync text-info cursor-pointer" id="icon-rotate-secret"
                                            onclick="gerarChave('secret')"></i>
                                    </span>
                                </div>
                                <p id="secret" class="mb-0">{{ $client->secret }}</p>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Nome Completo</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="name" value="{{ $client->name }}">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">CPF/CNPJ</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="cpf_cnpj"
                                            value="{{ $client->cpf_cnpj }}">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Email</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="email" value="{{ $client->email }}">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Telefone</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="telefone"
                                            value="{{ $client->telefone }}">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">CPF/CNPJ</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="cpf_cnpj"
                                            value="{{ $client->cpf_cnpj }}">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Senha</p>
                                    </div>
                                    <div class="col-sm-9 mb-3">
                                        <input type="text" class="form-control" name="new_password">
                                        <small class="text-danger">Digite somente se
                                            necessário alterá-la</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card mb-4 mb-md-0">
                            <div class="card-body">
                                <p class="mb-4"><span class="text-primary font-italic me-1">Endereço</span>
                                </p>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="mb-0">CEP</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="cep" value="{{ $client->cep }}">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="mb-0">Logradrouro</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="rua" value="{{ $client->rua }}">
                                    </div>
                                </div>
                                <hr>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="mb-0">Nº</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="numero_residencia"
                                            value="{{ $client->numero_residencia }}">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="mb-0">Bairro</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="bairro" value="{{ $client->bairro }}">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="mb-0">Cidade</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="cidade" value="{{ $client->cidade }}">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="mb-0">UF</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="estado" value="{{ $client->estado }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4 mb-md-0">
                            <div class="card-body">
                                <p class="mb-4"><span class="text-primary font-italic me-1">Adquirência / Taxas</span>
                                </p>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="mb-0">Adquirente</p>
                                    </div>
                                    <div class="col-sm-6 ">
                                        @php
                                            $adquirentes = [ 'cashtime', 'transfeera', 'efi', 'pagarme', 'xgate', 'getpay', 'getpay2', 'rapdyn'];
                                        @endphp
                                        <select class="form-control" name="adquirente_default">
                                            <option value="padrao" {{ $client->adquirente_default ?? null ? '' : 'selected' }}>
                                                Padrão</option>
                                            @foreach ($adquirentes as $adquirente)
                                                <option value="{{ $adquirente }}" {{ $client->adquirente_default == $adquirente ? 'selected' : '' }}>
                                                    {{ strtoupper($adquirente) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="mb-0">Baseline (R$)</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="baseline"
                                            value="{{ $client->baseline }}">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="mb-0">Taxa cash-in (%)</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="taxa_cash_in"
                                            value="{{ $client->taxa_cash_in }}">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="mb-0">Taxa cash-in (R$)</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="taxa_cash_in_fixa"
                                            value="{{ $client->taxa_cash_in_fixa }}">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="mb-0">Taxa cash-out (%)</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="taxa_cash_out"
                                            value="{{ $client->taxa_cash_out }}">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="mb-0">Taxa cash-out (R$)</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="taxa_cash_out_fixa"
                                            value="{{ $client->taxa_cash_out_fixa }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4 mb-md-0">
                            <div class="card-body">
                                <p class="mb-4"><span class="text-primary font-italic me-1">Indique e ganhe</span>
                                </p>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="mb-0">Ativar</p>
                                    </div>
                                    <div class="col-sm-6 ">
                                       
                                        <select class="form-control" name="ativar_split">
                                            <option value="1" {{ $client->ativar_split == 1 ? 'selected' : '' }}>Sim</option>
                                            <option value="0" {{ $client->ativar_split == 0 ? 'selected' : '' }}>Não</option>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="mb-0">Valor fixo (R$)</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="split_fixed"
                                            value="{{ $client->split_fixed }}">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="mb-0">Valor (%)</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="split_percent"
                                            value="{{ $client->split_percent }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card my-4 mb-lg-0">
                    <div class="card-body p-4 row">
                        <p class="mb-4"><span class="text-primary font-italic me-1">Documentação</span>
                        </p>
                        <div class="col-md-4 text-center">
                            <p class="my-2">Frente RG</p>
                            <img width="350px" height="auto"
                                src="{{ url('/private/' . base64_encode($client->foto_rg_frente)) }}" id="v-foto_rg_frente"
                                class="img-thumbnail" data-bs-toggle="popover" data-bs-trigger="hover focus"
                                data-bs-html="true" title="Visualização ampliada" style="cursor:pointer;"
                                onclick="verImagem(this)">
                        </div>
                        <div class="col-md-4 text-center">
                            <p class="my-2">Verso RG</p>
                            <img width="350px" height="auto"
                                src="{{ url('/private/' . base64_encode($client->foto_rg_verso)) }}" id="v-foto_rg_verso"
                                class="img-thumbnail" data-bs-toggle="popover" data-bs-trigger="hover focus"
                                data-bs-html="true" title="Visualização ampliada" style="cursor:pointer;"
                                onclick="verImagem(this)">
                        </div>
                        <div class="col-md-4 text-center">
                            <p class="my-2">Selfie</p>
                            <img width="350px" height="auto"
                                src="{{ url('/private/' . base64_encode($client->selfie_rg)) }}" id="v-selfie_rg"
                                class="img-thumbnail" data-bs-toggle="popover" data-bs-trigger="hover focus"
                                data-bs-html="true" title="Visualização ampliada" style="cursor:pointer;"
                                onclick="verImagem(this)">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <script>
        const baseUrl = `{{ url('/private') }}`;
        let name = '';
        document.addEventListener("DOMContentLoaded", function () {
            // Visualizar
            document.addEventListener("click", function (event) {
                if (event.target.classList.contains("btn-visualizar") || event.target.closest(
                    ".btn-visualizar")) {
                    const btn = event.target.closest(".btn-visualizar");

                    document.getElementById('v-id').innerText = btn.dataset.id;
                    document.getElementById('v-nome').innerText = btn.dataset.nome;
                    document.getElementById('v-cpf_cnpj').innerText = btn.dataset.cpf_cnpj;
                    document.getElementById('v-email').innerText = btn.dataset.email;
                    document.getElementById('v-telefone').innerText = btn.dataset.telefone;
                    document.getElementById('v-saldo').innerText = 'R$ ' + btn.dataset.saldo;
                    document.getElementById('v-taxa_cash_in').innerText = btn.dataset.taxa_cash_in + "%";
                    document.getElementById('v-taxa_cash_out').innerText = btn.dataset.taxa_cash_out + "%";
                    document.getElementById('v-split_fixed').innerText = "R$ " + btn.dataset.split_fixed;
                    document.getElementById('v-split_percent').innerText = btn.dataset.split_percent + "%";
                    document.getElementById('v-client_id').innerText = btn.dataset.client_id;
                    document.getElementById('v-client_secret').innerText = btn.dataset.client_secret;
                    document.getElementById('v-codigo_referencia').innerText = btn.dataset
                        .codigo_referencia;

                    const frente = document.getElementById('v-foto_rg_frente');
                    const verso = document.getElementById('v-foto_rg_verso');
                    const selfie = document.getElementById('v-selfie_rg');

                    frente.src = `${baseUrl}/${btoa(btn.dataset.foto_rg_frente)}`;
                    verso.src = `${baseUrl}/${btoa(btn.dataset.foto_rg_verso)}`;
                    selfie.src = `${baseUrl}/${btoa(btn.dataset.selfie_rg)}`;

                    frente.setAttribute('data-bs-content',
                        `<img src='${baseUrl}/${btoa(btn.dataset.foto_rg_frente)}' width='200'>`);
                    verso.setAttribute('data-bs-content',
                        `<img src='${baseUrl}/${btoa(btn.dataset.foto_rg_verso)}' width='200'>`);
                    selfie.setAttribute('data-bs-content',
                        `<img src='${baseUrl}/${btoa(btn.dataset.selfie_rg)}' width='200'>`);
                }
            });
        });

        function verImagem(data) {
            Swal.fire({
                title: 'Visualizar Imagem',
                html: `
                                    <div style="position: relative; text-align: center; overflow: hidden;">
                                        <div id="img-wrapper" style="display: inline-block; cursor: grab;">
                                            <img id="imagem-transformavel" 
                                                src="${data.src}" 
                                                alt="Imagem" 
                                                width="auto" 
                                                height="640px" 
                                                style="transition: transform 0.2s ease; max-width: 100%;">
                                        </div>
                                        <div style="position: absolute; bottom: 10px; left: 0; right: 0; z-index: 10; display: flex; justify-content: center; gap: 10px;">
                                            <button class="btn btn-info" id="rotate-btn">🔄 Rotacionar</button>
                                            <button class="btn btn-info" id="zoom-in-btn">🔍 Zoom +</button>
                                            <button class="btn btn-info" id="zoom-out-btn">🔎 Zoom -</button>
                                            <button class="btn btn-info" id="reset-btn">♻️ Resetar</button>
                                        </div>
                                    </div>
                                `,
                showConfirmButton: false,
                showCloseButton: true,
                width: '70vw',
                height: '70vh',
                didClose: () => {
                    // Limpar transformações e resetar imagem
                    const img = document.getElementById('imagem-transformavel');
                    if (img) {
                        img.src = '';
                        img.style.transform = '';
                    }
                },
                didOpen: () => {
                    const img = document.getElementById('imagem-transformavel');
                    const wrapper = document.getElementById('img-wrapper');
                    let angle = 0;
                    let scale = 1;
                    let posX = 0;
                    let posY = 0;

                    function updateTransform() {
                        img.style.transform =
                            `translate(${posX}px, ${posY}px) rotate(${angle}deg) scale(${scale})`;
                    }

                    // Zoom / Rotação
                    document.getElementById('rotate-btn').addEventListener('click', () => {
                        angle = (angle + 90) % 360;
                        updateTransform();
                    });

                    document.getElementById('zoom-in-btn').addEventListener('click', () => {
                        scale = Math.min(scale + 0.1, 3);
                        updateTransform();
                    });

                    document.getElementById('zoom-out-btn').addEventListener('click', () => {
                        scale = Math.max(scale - 0.1, 0.3);
                        updateTransform();
                    });

                    document.getElementById('reset-btn').addEventListener('click', () => {
                        angle = 0;
                        scale = 1;
                        posX = 0;
                        posY = 0;
                        updateTransform();
                    });

                    // Pan (arrastar)
                    let isDragging = false;
                    let startX, startY;

                    const startDrag = (e) => {
                        isDragging = true;
                        wrapper.style.cursor = 'grabbing';
                        startX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
                        startY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;
                    };

                    const doDrag = (e) => {
                        if (!isDragging) return;
                        const currentX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
                        const currentY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;
                        posX += currentX - startX;
                        posY += currentY - startY;
                        startX = currentX;
                        startY = currentY;
                        updateTransform();
                    };

                    const stopDrag = () => {
                        isDragging = false;
                        wrapper.style.cursor = 'grab';
                    };

                    // Eventos mouse
                    wrapper.addEventListener('mousedown', startDrag);
                    wrapper.addEventListener('mousemove', doDrag);
                    wrapper.addEventListener('mouseup', stopDrag);
                    wrapper.addEventListener('mouseleave', stopDrag);

                    // Eventos touch
                    wrapper.addEventListener('touchstart', startDrag);
                    wrapper.addEventListener('touchmove', doDrag);
                    wrapper.addEventListener('touchend', stopDrag);
                }
            });
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            var cpfCnpjMask = function (val) {
                return val.replace(/\D/g, '').length <= 11 ?
                    '000.000.000-009' :
                    '00.000.000/0000-00';
            };

            $('#e-cpf_cnpj').mask(cpfCnpjMask, {
                onKeyPress: function (val, e, field, options) {
                    field.mask(cpfCnpjMask.apply({}, arguments), options);
                }
            });

            // Máscara dinâmica Telefone
            var phoneMask = function (val) {
                return val.replace(/\D/g, '').length === 11 ?
                    '(00) 00000-0000' :
                    '(00) 0000-00009';
            };

            $('#e-telefone').mask(phoneMask, {
                onKeyPress: function (val, e, field, options) {
                    field.mask(phoneMask.apply({}, arguments), options);
                }
            });

            $('#e-taxa_cash_in').mask('000.00', {
                reverse: true
            });

            $('#e-taxa_cash_in').on('blur', function () {
                let valor = parseFloat($(this).val().replace(',', '.'));

                if (valor > 100) {
                    $(this).val('100.00');
                } else if (isNaN(valor)) {
                    $(this).val('0.00');
                } else {
                    // Garante formatação com 2 casas decimais
                    $(this).val(valor.toFixed(2));
                }
            });

            $('#e-taxa_cash_out').mask('000.00', {
                reverse: true
            });

            $('#e-taxa_cash_out').on('blur', function () {
                let valor = parseFloat($(this).val().replace(',', '.'));

                if (valor > 100) {
                    $(this).val('100.00');
                } else if (isNaN(valor)) {
                    $(this).val('0.00');
                } else {
                    // Garante formatação com 2 casas decimais
                    $(this).val(valor.toFixed(2));
                }
            });
        });

        function gerarChave(campo) {
            let icon = document.getElementById(`icon-rotate-${campo}`);

            // força reset da animação (caso clique várias vezes rápido)
            icon.classList.remove('rotate-icon');
            void icon.offsetWidth; // reflow hack
            icon.classList.add('rotate-icon');

            let firstName = "{{ explode(' ', $client->name)[0] }}".toLowerCase();
            let prefix = campo == 'clientId' ? `ct_${firstName}_` : `cs_${firstName}_`;
            let chave = generateUUIDv4();
            chave = `${prefix}${chave.replace(/-/g, '')}`;

            document.getElementById(campo).innerText = chave;
            document.querySelector(`[name="${campo}"]`).value = chave;

            setTimeout(() => {
                icon.classList.remove('rotate-icon');
            }, 600); // ligeiramente maior que o tempo da animação
        }


        function generateUUIDv4() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                const r = Math.random() * 16 | 0;
                const v = c === 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        }
    </script>
@endsection