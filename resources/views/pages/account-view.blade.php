@php
    $setting = \App\Helpers\Helper::settings();
@endphp

@extends('layouts.app')

@section('title', 'Meu perfil')

@section('content')
    <div class="header mb-3">
        <h1 class="header-title">
            Meu perfil
        </h1>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <div class="card flex-fill w-100">
                <div class="card-header" style="border-bottom:1px solid rgba(0, 0, 0, 0.07);">
                    <h5 class="card-title mb-0 ">Nivel</h5>
                </div>
                <div class="card-body p-0 w-100 ">
                    @php
                        $nivel_atual = auth()->user()->nivelAtual;
                        // Se não tem nível atribuído (ex.: admin ou sem depósitos), usa o primeiro nível com 0% de progresso
                        if (!$nivel_atual) {
                            $nivel_atual = \App\Models\Gamefication::orderBy('min')->first();
                        }
                        $min = $nivel_atual ? ($nivel_atual->min ?? 0) : 0;
                        $max = $nivel_atual ? ($nivel_atual->max ?? 1) : 1; // evita divisão por zero
                        $atual = auth()->user()->transactions_in->where('status', 'pago')->sum('amount');

                        // Cálculo da porcentagem entre min e max
                        $progresso = 0;
                        if ($nivel_atual && ($max - $min) > 0) {
                            $progresso = max(0, min(100, (($atual - $min) / ($max - $min)) * 100));
                        }
                        
                        // Verifica se a imagem existe no servidor
                        $imagemExiste = false;
                        if ($nivel_atual && isset($nivel_atual->image) && !empty($nivel_atual->image)) {
                            $caminhoImagem = public_path(str_replace('/storage/', 'storage/', $nivel_atual->image));
                            $imagemExiste = file_exists($caminhoImagem);
                        }
                    @endphp

                    <div class="mb-3 mt-3 px-3 d-flex justify-content-center">

                        <div class="d-flex flex-row align-items-center w-100 p-3 py-2 rounded"
                            style="min-height: 50px; border: 1px dashed gray;">
                            @if ($imagemExiste)
                                <div class="me-3 d-flex align-items-center">
                                    <img src="{{ $nivel_atual->image }}" alt="Nível" style="height: 45px; width: auto;">
                                </div>
                            @endif

                            <div class="d-flex flex-column w-100">
                                <small class=" fw-bold mb-1">
                                    @if ($nivel_atual)
                                        Você é nível {{ $nivel_atual->name }} ({{ number_format($progresso, 0) }}%)
                                    @else
                                        Nenhum nível configurado
                                    @endif
                                </small>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar"
                                        style="width: {{ $progresso }}%;" aria-valuenow="{{ $progresso }}"
                                        aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-3">
            <div class="card flex-fill w-100" style="min-height:296px;">
                <div class="card-header" style="border-bottom:1px solid rgba(0, 0, 0, 0.07);">
                    <h5 class="card-title mb-0 ">Pessoais</h5>
                </div>
                <div class="card-body p-3 d-flex align-items-center justify-content-start">
                    <div class="row w-100">
                        <div class="col-4 col-md-3 text-center">

                            <form id="form-avatar" action="{{ route('user.update.avatar') }}" class="w-100 mb-3"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('POST')

                                <div class="d-flex justify-content-center">
                                    <div class="avatar-container position-relative" style="width: 80px; height: 80px;">
                                        <input type="file" id="avatarInput" name="avatar" accept="image/*"
                                            class="d-none">
                                        <img src="{{ asset(auth()->user()->avatar) }}" class="rounded-circle w-100 h-100"
                                            alt="Avatar"
                                            style="object-fit: cover;border: 1px solid {{ $setting->software_color }}">

                                        <div class="edit-overlay position-absolute start-0 bottom-0 w-100 text-center"
                                            style="height: 25%; background: rgba(0, 0, 0, 0.5); border-bottom-left-radius: 50%; border-bottom-right-radius: 50%; cursor: pointer;">
                                            <span class="text-white small">Editar</span>
                                        </div>

                                        <div class="position-absolute top-0 start-0 w-100 h-100" style="cursor: pointer;"
                                            onclick="document.getElementById('avatarInput').click()"></div>
                                    </div>
                                </div>
                            </form>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#alterarSenhaModal">Alterar senha</button>
                        </div>
                        <div class="col-8 col-md-9">
                            <p class="">Nome: <span>{{ auth()->user()->name }}</span></p>
                            <p class="">Email: <span>{{ auth()->user()->email }}</span></p>
                            <p class="">Telefone: <span>{{ auth()->user()->telefone }}</span></p>
                            <p class="">CPF/CNPJ: <span>{{ auth()->user()->cpf_cnpj }}</span></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-3">
            <div class="card flex-fill w-100" style="min-height:296px;">
                <div class="card-header" style="border-bottom:1px solid rgba(0, 0, 0, 0.07);">
                    <h5 class="card-title mb-0">Endereço</h5>
                </div>
                <div class="card-body px-3 py-3">
                    <div class="col-12 row">
                        <div class="col-12 col-md-7">
                            <p class="">Logradouro: <span>{{ auth()->user()->rua ?? 'Não informado' }}</span></p>
                        </div>
                        <div class="col-12 col-md-5 md:text-end">
                            <p class="">Número: <span>{{ auth()->user()->numero ?? 'Não informado' }}</span></p>
                        </div>
                        <div class="col-12">
                            <p class="">Complemento:
                                <span>{{ auth()->user()->complemento ?? 'Não informado' }}</span></p>
                        </div>
                        <div class="col-12">
                            <p class="">Bairro: <span>{{ auth()->user()->bairro ?? 'Não informado' }}</span></p>
                        </div>
                        <div class="col-12">
                            <p class="">Cidade: <span>{{ auth()->user()->cidade ?? 'Não informado' }}</span></p>
                        </div>
                        <div class="col-12">
                            <p class="">Estado: <span>{{ auth()->user()->estado ?? 'Não informado' }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 h-100 mb-3">
            <div class="card flex-fill w-100 h-100" style="min-height:294.5px;">
                <div class="card-header" style="border-bottom:1px solid rgba(0, 0, 0, 0.07);">
                    <h5 class="card-title mb-0">Taxas</h5>
                </div>
                <div class="card-body h-100 px-3 md:px-5 py-3">
                    <div class="row py-3">
                        <div class="col-md-6 ">
                            <h5>Depósito</h5>
                            @php
                                $taxaPercentual = auth()->user()->taxa_cash_in;
                                $taxaFixa =
                                    $setting->taxa_fixa > 0
                                        ? '+ R$ ' . number_format($setting->taxa_fixa, 2, ',', '.')
                                        : '';
                            @endphp

                            <h6><strong>Aplicativo:</strong> {{ $taxaPercentual }}% {{ $taxaFixa }}</h6>
                            <h6><strong>API:</strong> {{ $taxaPercentual }}% {{ $taxaFixa }}</h6>
                        </div>
                        <div class="col-md-6">
                            <h5>Saque</h5>
                            <h6><strong>Aplicativo: </strong>{{ auth()->user()->taxa_cash_out }}%</h6>
                            <h6><strong>API: </strong> {{ auth()->user()->taxa_cash_out }}% </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-3">
            <div class="card flex-fill w-100" style="min-height:244.5px;">
                <div class="card-header" style="border-bottom:1px solid rgba(0, 0, 0, 0.07);">
                    <h5 class="card-title mb-0">Chaves API &nbsp;<span class="text-sm text-muted">Clique na chave para copiar</span></h5>
                    
                </div>
                <div class="card-body px-3 py-3">
                    <div class="alert p-3 cursor-pointer" role="alert"
                        style="background: var(--gateway-primary-opacity)!important;font-weight:bold;"
                        onclick="copiarToken()">
                        <i class="fa-solid fa-copy text-white cursor-pointer" style="font-size: 24px"></i>&nbsp;
                        <span class="d-inline-block text-truncate w-100 w-sm-50"
                            id="tokenText">Token:&nbsp;{{ auth()->user()->clientId }}</span>
                    </div>

                    <div class="alert p-3 cursor-pointer" role="alert"
                        style="background: var(--gateway-primary-opacity)!important;font-weight:bold;"
                        onclick="copiarSecret()">
                        <i class="fa-solid fa-copy text-white " style="font-size: 24px"></i>&nbsp;
                        <span class="d-inline-block text-truncate w-100 w-sm-50"
                            id="secretText">Secret:&nbsp;{{ auth()->user()->secret }}</span>
                    </div>
                    <div class="my-3 w-100">
                        <a href="{{ route('docs.index') }}" target="_blank">
                            <button type="button" class="btn btn-primary text-white w-100">Documentação</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Modal Alterar senha -->
    <div class="modal fade" id="alterarSenhaModal" tabindex="-1" aria-labelledby="alterarSenhaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alterarSenhaModalLabel">Alterar minha senha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('senha.alterar') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3 position-relative">
                            <label class="form-label">Senha atual</label>
                            <input type="password" class="form-control" name="senha_atual" id="senha_atual"
                                value="{{ old('senha_atual') }}" required>
                            <i class="fa-solid fa-eye toggle-password" data-target="senha_atual"
                                style="position: absolute; top: 75%; right: 15px; transform: translateY(-50%); cursor: pointer;"></i>
                            @error('senha_atual')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 position-relative">
                            <label class="form-label">Nova senha</label>
                            <input type="password" class="form-control" name="nova_senha" id="nova_senha"
                                value="{{ old('nova_senha') }}" required>
                            <i class="fa-solid fa-eye toggle-password" data-target="nova_senha"
                                style="position: absolute; top: 75%; right: 15px; transform: translateY(-50%); cursor: pointer;"></i>
                            @error('nova_senha')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 position-relative">
                            <label class="form-label">Repetir nova senha</label>
                            <input type="password" class="form-control" name="nova_senha_confirmation"
                                id="nova_senha_confirmation" value="{{ old('nova_senha_confirmation') }}" required>
                            <i class="fa-solid fa-eye toggle-password" data-target="nova_senha_confirmation"
                                style="position: absolute; top: 75%; right: 15px; transform: translateY(-50%); cursor: pointer;"></i>
                            @error('nova_senha_confirmation')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Alterar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                const modal = new bootstrap.Modal(document.getElementById('alterarSenhaModal'));
                modal.show();
            });
        </script>
    @endif

    <script>
        document.querySelectorAll('.toggle-password').forEach(function(icon) {
            icon.addEventListener('click', function() {
                const inputId = this.getAttribute('data-target');
                const input = document.getElementById(inputId);

                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                }
            });
        });
    </script>


    <script>
        function copiarTextoDoElemento(elementId, label) {
            const element = document.getElementById(elementId);
            if (!element) {
                console.warn('Elemento não encontrado:', elementId);
                return;
            }

            let text = element.innerText.trim();

            // Remove o label do início, se presente
            if (text.startsWith(label)) {
                text = text.slice(label.length + 2).trim();
            }
            navigator.clipboard.writeText(text)
                .then(() => {
                    showToast('success', `${label} copiado com sucesso!`);
                })
                .catch(err => {
                    console.error('Erro ao copiar:', err);
                    showToast('success', 'Erro ao copiar o texto.');
                });
        }

        function copiarToken() {
            copiarTextoDoElemento('tokenText', 'Token');
        }

        function copiarSecret() {
            copiarTextoDoElemento('secretText', 'Secret');
        }
    </script>

    @include('partials.avatar-crop-modal')

@endsection
