@php
    $setting = \App\Helpers\Helper::settings();
@endphp

@extends('layouts.aluno')

@section('title', 'Meu perfil')

@section('content')
    <div class="header mb-3">
        <h1 class="header-title">
            Meu perfil
        </h1>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card flex-fill w-100" style="min-height:296px;">
                <div class="card-header" style="border-bottom:1px solid rgba(0, 0, 0, 0.07);">
                    <h5 class="card-title mb-0 ">Pessoais</h5>
                </div>
                <div class="card-body p-3 d-flex align-items-center justify-content-start">
                    <div class="row w-100">
                        <div class="col-4 col-md-3 text-center">

                            <form id="form-avatar" action="{{ route('aluno.update.avatar') }}" class="w-100 mb-3"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('POST')

                                <div class="d-flex justify-content-center">
                                    <div class="avatar-container position-relative" style="width: 80px; height: 80px;">
                                        <input type="file" id="avatarInput" name="avatar" accept="image/*"
                                            class="d-none" onchange="this.form.submit()">
                                        <img src="{{ asset(auth('aluno')->user()->avatar) }}"
                                            class="rounded-circle w-100 h-100" alt="Avatar"
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
                            <p class="">Nome: <span>{{ auth('aluno')->user()->name }}</span></p>
                            <p class="">Email: <span>{{ auth('aluno')->user()->email }}</span></p>
                            <p class="">Telefone: <span>{{ auth('aluno')->user()->celular }}</span></p>
                            <p class="">CPF: <span>{{ auth('aluno')->user()->cpf }}</span></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card flex-fill w-100" style="min-height:296px;">
                <div class="card-header d-flex align-items-center justify-content-between"
                    style="border-bottom:1px solid rgba(0, 0, 0, 0.07);">
                    <h5 class="card-title mb-0">Endereço</h5>
                    <i data-lucide="square-pen" style="cursor: pointer;" class="me-2"
                    data-bs-toggle="modal"
                                data-bs-target="#alterarEnderecoModal"></i>
                </div>
                <div class="card-body px-3 py-3">
                    <div class="col-12 row">
                        <div class="col-12">
                            <p class="">CEP: <span>{{ auth('aluno')->user()->cep ?? 'Não informado' }}</span>
                            </p>
                        </div>
                        <div class="col-12">
                            <p class="">Logradouro: <span>{{ auth('aluno')->user()->street ?? 'Não informado' }}</span>
                            </p>
                        </div>
                        <div class="col-12">
                            <p class="">Bairro:
                                <span>{{ auth('aluno')->user()->address ?? 'Não informado' }}</span>
                            </p>
                        </div>
                       
                        <div class="col-12">
                            <p class="">Cidade: <span>{{ auth('aluno')->user()->city ?? 'Não informado' }}</span>
                            </p>
                        </div>
                        <div class="col-12">
                            <p class="">Estado: <span>{{ auth('aluno')->user()->uf ?? 'Não informado' }}</span>
                            </p>
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
                <form action="{{ route('aluno.senha.alterar') }}" method="POST">
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


    <!-- Modal Alterar senha -->
    <div class="modal fade" id="alterarEnderecoModal" tabindex="-1" aria-labelledby="alterarEnderecoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alterarEnderecoModalLabel">Alterar endereço</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('aluno.endereco.alterar') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        {{-- CEP --}}
                        <div class="mb-3 position-relative">
                            <label class="form-label">CEP</label>
                            <input type="text" class="form-control" name="cep" id="cep"
                                value="{{ auth('aluno')->user()->cep }}" required>
                            @error('cep')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Rua --}}
                        <div class="mb-3 position-relative">
                            <label class="form-label">Rua</label>
                            <input type="text" class="form-control" name="street" id="street"
                                value="{{ auth('aluno')->user()->street }}" required>
                            @error('street')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Bairro --}}
                        <div class="mb-3 position-relative">
                            <label class="form-label">Bairro</label>
                            <input type="text" class="form-control" name="address" id="address"
                                value="{{ auth('aluno')->user()->address }}" required>
                            @error('address')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Cidade --}}
                        <div class="mb-3 position-relative">
                            <label class="form-label">Cidade</label>
                            <input type="text" class="form-control" name="city" id="city"
                                value="{{ auth('aluno')->user()->city }}" required>
                            @error('city')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- UF --}}
                        <div class="mb-3 position-relative">
                            <label class="form-label">UF</label>
                            <input type="text" class="form-control" name="uf" id="uf"
                                value="{{ auth('aluno')->user()->uf }}" required maxlength="2">
                            @error('uf')
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

    {{-- Script ViaCEP --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cepInput = document.getElementById('cep');

            cepInput.addEventListener('blur', function() {
                let cep = this.value.replace(/\D/g, '');

                if (cep.length === 8) {
                    fetch(`https://viacep.com.br/ws/${cep}/json/`)
                        .then(response => response.json())
                        .then(data => {
                            if (!("erro" in data)) {
                                document.getElementById('street').value = data.logradouro || '';
                                document.getElementById('address').value = data.bairro || '';
                                document.getElementById('city').value = data.localidade || '';
                                document.getElementById('uf').value = data.uf || '';
                            } else {
                                alert("CEP não encontrado.");
                            }
                        })
                        .catch(() => {
                            alert("Erro ao buscar o CEP. Tente novamente.");
                        });
                }
            });
        });
    </script>

    @if ($errors->any())
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                const modal = new bootstrap.Modal(document.getElementById('alterarSenhaModal'));
                modal.show();
            });
        </script>
    @endif
@endsection
