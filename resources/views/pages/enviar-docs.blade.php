@extends('layouts.app')

@section('title', 'Completar cadastro')

@section('content')
    <div class="header">
        <h1 class="header-title">
            Olá, Sr(a) {{ explode(' ', auth()->user()->name)[0] }}!
        </h1>
        @if (auth()->user()->status === 'aguardando')
            <p class="header-subtitle">Para finalizar o seu cadastro é necessário preencher os dados abaixo.</p>
        @else
            <p class="header-subtitle">Documentos em análise.</p>
        @endif
    </div>
    @if (auth()->user()->status === 'aguardando')
        <form id="form-verify-docs" action="{{ route('auth.verifydocs') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div id="smartwizard-arrows-success" class="wizard wizard-success mb-4 sw sw-theme-arrows sw-justified">
                    <ul class="nav">
                        <li class="nav-item"><a class="nav-link inactive active"
                                href="#arrows-success-step-1">Pessoais<br><small>Informações pessoais</small></a></li>
                        <li class="nav-item"><a class="nav-link inactive done"
                                href="#arrows-success-step-2">Endereço<br><small>Informações de endereço</small></a></li>
                        <li class="nav-item"><a class="nav-link inactive done"
                                href="#arrows-success-step-3">Validação<br><small>Informações de verificação</small></a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="arrows-success-step-1" class="tab-pane" role="tabpanel">
                            <div class="row">
                                <div class="mb-3 mt-3 col-md-6">
                                    <div class="">
                                        <label for="name">Nome Completo</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Nome completo" value="{{ auth()->user()->name }}" required>
                                    </div>
                                    @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3 mt-3 col-md-3">
                                    <div class="">
                                        <label for="cpf_cnpj">CPF / CNPJ</label>
                                        <input type="text" class="form-control" id="cpf_cnpj" name="cpf_cnpj"
                                            placeholder="Digite o CPF ou CNPJ" value="{{ auth()->user()->cpf_cnpj }}" required>
                                    </div>
                                    @error('cpf_cnpj')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3 mt-3 col-md-3">
                                    <div class="">
                                        <label for="data_nascimento">Data de Nascimento</label>
                                        <input type="text" class="form-control" id="data_nascimento" name="data_nascimento"
                                            placeholder="Digite sua data de nascimento" value="{{ old('data_nascimento') }}"
                                            required>
                                    </div>
                                    @error('data_nascimento')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3 mt-3 col-md-6">
                                    <div class="">
                                        <label for="nome_mae">Nome da mãe</label>
                                        <input type="text" class="form-control" id="nome_mae" name="nome_mae"
                                            placeholder="Digite o nome completo da sua mãe"
                                            value="{{ auth()->user()->nome_mae }}" required>
                                    </div>
                                    @error('nome_mae')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3 mt-3 col-md-6">
                                    <div class="">
                                        <label for="nome_pai">Nome do pai</label>
                                        <input type="text" class="form-control" id="nome_pai" name="nome_pai"
                                            placeholder="Digite o nome completo do seu pai"
                                            value="{{ auth()->user()->nome_pai }}" required>
                                    </div>
                                    @error('nome_pai')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        <div id="arrows-success-step-2" class="tab-pane" role="tabpanel">
                            <div class="row">
                                <div class="mb-3 mt-3 col-md-3">
                                    <div class="">
                                        <label for="cep">CEP</label>
                                        <input type="text" class="form-control" id="cep" name="cep" placeholder="Digite seu CEP"
                                            value="{{ auth()->user()->cep }}" required>
                                    </div>
                                    @error('cep')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3 mt-3 col-md-7">
                                    <div class="">
                                        <label for="rua">Logradouro</label>
                                        <input type="text" class="form-control" id="rua" name="rua"
                                            placeholder="Digite o logradouro" value="{{ auth()->user()->rua }}" required>
                                    </div>
                                    @error('rua')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3 mt-3 col-md-2">
                                    <div class="">
                                        <label for="numero_residencia">Número</label>
                                        <input type="text" class="form-control" id="numero_residencia" name="numero_residencia"
                                            placeholder="Digite o número" value="{{ auth()->user()->numero_residencia }}"
                                            required>
                                    </div>
                                    @error('numero_residencia')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3 mt-3 col-md-4">
                                    <div class="">
                                        <label for="complemento">Complemento</label>
                                        <input type="text" class="form-control" id="complemento" name="complemento"
                                            placeholder="Complemento" value="{{ auth()->user()->complemento }}">
                                    </div>
                                </div>
                                <div class="mb-3 mt-3 col-md-3">
                                    <div class="">
                                        <label for="bairro">Bairro</label>
                                        <input type="text" class="form-control" id="bairro" name="bairro" placeholder="Bairro"
                                            value="{{ auth()->user()->bairro }}" required>
                                    </div>
                                </div>
                                <div class="mb-3 mt-3 col-md-3">
                                    <div class="">
                                        <label for="cidade">Cidade</label>
                                        <input type="text" class="form-control" id="cidade" name="cidade" placeholder="Cidade"
                                            value="{{ auth()->user()->cidade }}" required>
                                    </div>
                                </div>
                                <div class="mb-3 mt-3 col-md-2">
                                    <div class="">
                                        <label for="estado">UF</label>
                                        <input type="text" class="form-control" id="estado" name="estado" placeholder="UF"
                                            value="{{ auth()->user()->estado }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="arrows-success-step-3" class="tab-pane" role="tabpanel">
                            <div class="row">
                                <div class="mb-3 mt-3 col-md-4">
                                    <x-image-upload :id="'foto_rg_frente'" name="foto_rg_frente" label="Foto RG/CNH frente"
                                        :value="auth()->user()->foto_rg_frente" />
                                    @error('foto_rg_frente')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3 mt-3 col-md-4">
                                    <x-image-upload :id="'foto_rg_verso'" name="foto_rg_verso" label="Foto RG/CNH Verso"
                                        :value="auth()->user()->foto_rg_verso" />
                                    @error('foto_rg_verso')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3 mt-3 col-md-4">
                                    <x-image-upload :id="'selfie_rg'" name="selfie_rg" label="Selfie segurando RG/CNH"
                                        :value="auth()->user()->selfie_rg" />
                                    @error('selfie_rg')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="toolbar toolbar-bottom text-end">
                        <button class="btn sw-btn-prev disabled" type="button">Anterior</button>
                        <button class="btn sw-btn-next" type="button">Próximo</button>
                        <button id="btn-submit-form" class="btn btn-submit btn-primary" type="button">Enviar</button>
                    </div>
                </div>
            </div>
        </form>
    @else
        <div class="card row">
            <div class="card-body text-center">
                <h3>Já recebemos seu dados.</h3>
                <h3>Estamos fazendo uma análise dos dados para sua segurança.</h3>
                <h4>Em breve seu acesso estará liberado.</h4>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            if (window.location.hash !== "#arrows-success-step-1") {
                history.replaceState(null, "", "#arrows-success-step-1");
            }

            document.getElementById('btn-submit-form').addEventListener('click', function (e) {
                e.preventDefault();
                let imgF = document.getElementById('file-foto_rg_frente');
                let imgV = document.getElementById('file-foto_rg_verso');
                let imgS = document.getElementById('file-selfie_rg');

                let btnSub = document.getElementById("btn-submit-form");
                let form = document.getElementById('form-verify-docs');

                if (imgF.value.length == 0) {
                    showToast('error', `Foto RG/CHN frente é um campo obrigatório`);
                    return;
                }

                if (imgV.value.length == 0) {
                    showToast('error', `Foto RG/CHN verso é um campo obrigatório`);
                    return;
                }

                if (imgS.value.length == 0) {
                    showToast('error', `Selfie segurando RG/CNH é um campo obrigatório`);
                    return;
                }

                form.submit();
            })

            $('#smartwizard-arrows-success').smartWizard({
                selected: 0,
                theme: 'default',
                justified: true,
                autoAdjustHeight: true,
                backButtonSupport: true,
                enableUrlHash: true
            });

            const $wizard = $('#smartwizard-arrows-success');
            const btnNext = document.querySelector('.sw-btn-next');
            const btnSubmit = document.querySelector('.btn-submit');
            btnSubmit.style.display = 'none';

            $wizard.on("showStep", function (e, anchorObject, stepIndex, stepDirection, stepPosition) {
                btnSubmit.style.display = stepPosition === 'last' ? 'inline-block' : 'none';
                btnNext.style.display = stepPosition === 'last' ? 'none' : 'inline-block';
            });

            $wizard.on("leaveStep", function (e, anchorObject, currentStepIdx, nextStepIdx, stepDirection) {

                if (stepDirection === 'forward') {
                    const currentStepPane = document.querySelector(`#arrows-success-step-${currentStepIdx + 1}`);
                    const inputs = currentStepPane.querySelectorAll('input[required], select[required], textarea[required]');

                    let valid = true;
                    inputs.forEach(input => {
                        if (!input.value.trim()) {
                            input.classList.add('is-invalid');
                            valid = false;
                        } else {
                            input.classList.remove('is-invalid');
                        }
                    });
                    return valid;
                }
                return true;
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    const tag = document.activeElement.tagName;
                    if (['INPUT', 'TEXTAREA', 'SELECT'].includes(tag)) {
                        e.preventDefault();
                    }
                }
            });

            $('#cpf_cnpj').mask(function (val) {
                return val.replace(/\D/g, '').length <= 11 ? '000.000.000-00' : '00.000.000/0000-00';
            }, {
                onKeyPress: function (val, e, field, options) {
                    field.mask(options.translation[0].pattern, options);
                }
            });

            $('#data_nascimento').mask('00/00/0000');
            $('#cep').mask('00000-000');

            $('#cep').on('blur', function () {
                const cep = $(this).val().replace(/\D/g, '');
                if (cep.length === 8) {
                    $.ajax({
                        url: `https://viacep.com.br/ws/${cep}/json/`,
                        dataType: 'json',
                        success: function (data) {
                            if (!data.erro) {
                                $('#rua').val(data.logradouro);
                                $('#bairro').val(data.bairro);
                                $('#cidade').val(data.localidade);
                                $('#estado').val(data.uf);
                                $('#complemento').val(data.complemento);
                            } else {
                                alert('CEP não encontrado.');
                            }
                        },
                        error: function () {
                            alert('Erro ao buscar o CEP.');
                        }
                    });
                }
            });
        });
    </script>
@endsection