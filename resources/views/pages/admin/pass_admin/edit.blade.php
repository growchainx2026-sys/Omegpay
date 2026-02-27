@extends('layouts.app')

@section('title', 'Alterar Senha Master Admin')

@section('content')
    <div class="header mb-3">
        <h3 class="header-title">
            Alterar Senha Master Admin
        </h3>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col mt-0">
                            <h5 class="card-title text-start">Configuração de Segurança</h5>
                            <p class="text-muted">Defina uma nova senha master para o administrador</p>
                        </div>
                        <div class="col-auto">
                            <span class="text-primary" style="font-size:32px">
                                <i class="fa-solid fa-key"></i>
                            </span>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.pass_admin.update', $userId) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="current_password" class="form-label">
                                <i class="fa-solid fa-lock me-1"></i> Senha Atual
                            </label>
                            <input type="password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" 
                                   name="current_password" 
                                   placeholder="Digite a senha atual"
                                   required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="new_password" class="form-label">
                                <i class="fa-solid fa-key me-1"></i> Nova Senha Master
                            </label>
                            <input type="password" 
                                   class="form-control @error('new_password') is-invalid @enderror" 
                                   id="new_password" 
                                   name="new_password" 
                                   placeholder="Digite a nova senha master"
                                   required
                                   minlength="8">
                            <small class="text-muted">Mínimo de 8 caracteres</small>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="new_password_confirmation" class="form-label">
                                <i class="fa-solid fa-check-double me-1"></i> Confirmar Nova Senha
                            </label>
                            <input type="password" 
                                   class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                                   id="new_password_confirmation" 
                                   name="new_password_confirmation" 
                                   placeholder="Confirme a nova senha"
                                   required
                                   minlength="8">
                            @error('new_password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning" role="alert">
                            <i class="fa-solid fa-exclamation-triangle me-2"></i>
                            <strong>Atenção:</strong> Esta senha master concede acesso administrativo total ao sistema.
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-save me-2"></i>
                                Atualizar Senha Master
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left me-2"></i>
                                Voltar ao Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-3" style="border-left: 5px solid #17a2b8 !important;">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fa-solid fa-info-circle me-2"></i>
                        Informações
                    </h6>
                    <ul class="mb-0 text-muted small">
                        <li>A senha deve ter no mínimo 8 caracteres</li>
                        <li>Use uma combinação de letras, números e caracteres especiais</li>
                        <li>Não compartilhe esta senha com terceiros</li>
                        <li>Altere a senha periodicamente para maior segurança</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection