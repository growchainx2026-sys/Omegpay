@php
    $user = auth('aluno')->user();
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu perfil - Área de Membros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #0a0a0a;
            color: #ffffff;
            min-height: 100vh;
        }
        .header {
            background: linear-gradient(180deg, rgba(0,0,0,0.9) 0%, transparent 100%);
            padding: 20px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .logo { font-size: 24px; font-weight: bold; color: #0b6856; text-decoration: none; }
        .header-right { display: flex; align-items: center; gap: 16px; }
        .header-avatar-link { display: block; flex-shrink: 0; transition: opacity 0.2s; }
        .header-avatar-link:hover { opacity: 0.9; }
        .header-avatar {
            width: 42px; height: 42px; border-radius: 50%; object-fit: cover;
            border: 2px solid #0b6856; background: #1a1a1a; display: block;
        }
        .header-greeting-wrap { display: flex; flex-direction: column; align-items: flex-end; line-height: 1.2; }
        .header-greeting-label { font-size: 11px; opacity: 0.8; color: #fff; }
        .header-greeting-name { font-size: 14px; font-weight: 600; color: #fff; }
        .logout-btn {
            background: transparent; border: 1px solid #ffffff; color: #ffffff;
            padding: 8px 16px; border-radius: 4px; text-decoration: none; transition: all 0.3s;
        }
        .logout-btn:hover { background: #ffffff; color: #0a0a0a; }
        .container-main { padding: 60px; max-width: 1000px; margin: 0 auto; }
        .page-title { font-size: 28px; font-weight: 700; margin-bottom: 8px; }
        .page-subtitle { font-size: 15px; opacity: 0.7; margin-bottom: 32px; }
        .profile-card {
            background: #1a1a1a;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            border: 1px solid rgba(255,255,255,0.06);
        }
        .profile-card-title {
            font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;
            color: rgba(255,255,255,0.6); margin-bottom: 20px; padding-bottom: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .profile-row { display: flex; align-items: flex-start; gap: 24px; flex-wrap: wrap; }
        .profile-avatar-wrap {
            flex-shrink: 0;
            position: relative;
        }
        .profile-avatar {
            width: 88px; height: 88px; border-radius: 50%; object-fit: cover;
            border: 2px solid #0b6856; background: #0d0d0d; display: block;
        }
        .profile-avatar-edit {
            position: absolute; inset: 0; border-radius: 50%; background: rgba(0,0,0,0.5);
            display: flex; align-items: center; justify-content: center; cursor: pointer;
            opacity: 0; transition: opacity 0.2s; font-size: 12px; color: #fff;
        }
        .profile-avatar-wrap:hover .profile-avatar-edit { opacity: 1; }
        .profile-info { flex: 1; min-width: 0; }
        .profile-info p {
            font-size: 14px; margin-bottom: 8px; color: rgba(255,255,255,0.9);
            display: flex; align-items: center; gap: 8px;
        }
        .profile-info p span { color: rgba(255,255,255,0.6); min-width: 90px; }
        .profile-btn-outline {
            background: transparent !important;
            border: 1px solid rgba(255,255,255,0.35);
            color: #ffffff !important;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            text-decoration: none;
            display: inline-block;
            margin-top: 12px;
            transition: all 0.2s;
            cursor: pointer;
        }
        .profile-btn-outline:hover {
            background: rgba(255,255,255,0.1) !important;
            color: #ffffff !important;
            border-color: rgba(255,255,255,0.5);
        }
        .course-card {
            display: flex; align-items: center; gap: 16px; padding: 16px;
            background: rgba(255,255,255,0.03); border-radius: 10px; margin-bottom: 12px;
            text-decoration: none; color: #fff; border: 1px solid transparent; transition: all 0.2s;
        }
        .course-card:hover { background: rgba(255,255,255,0.06); border-color: rgba(11,104,86,0.3); color: #fff; }
        .course-card-thumb {
            width: 64px; height: 64px; border-radius: 8px; overflow: hidden; flex-shrink: 0;
            background: linear-gradient(135deg, #0b6856 0%, #0a4d3f 100%);
        }
        .course-card-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .course-card-body { flex: 1; min-width: 0; }
        .course-card-title { font-size: 15px; font-weight: 600; margin-bottom: 6px; }
        .course-card-meta { font-size: 12px; color: rgba(255,255,255,0.5); margin-bottom: 8px; }
        .course-card-progress {
            height: 4px; background: rgba(255,255,255,0.1); border-radius: 2px; overflow: hidden;
        }
        .course-card-progress-fill { height: 100%; background: #0b6856; border-radius: 2px; transition: width 0.3s; }
        .course-card-arrow { color: rgba(255,255,255,0.3); font-size: 12px; flex-shrink: 0; }
        .empty-courses { color: rgba(255,255,255,0.5); font-size: 14px; padding: 24px 0; }
        /* Modais tema escuro + garantir que fiquem acima de qualquer overlay */
        .modal-backdrop {
            z-index: 900000000000 !important;
            background-color: rgba(0, 0, 0, 0.5) !important;
            opacity: 1 !important;
        }
        .modal,
        .modal.show {
            z-index: 900000000001 !important;
        }
        .modal .modal-dialog {
            z-index: 900000000002 !important;
        }
        .modal .modal-content {
            position: relative !important;
            z-index: 900000000003 !important;
        }
        /* Modais tema escuro */
        .modal-dark .modal-content { background: #1a1a1a; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; color: #fff; }
        .modal-dark .modal-header { border-bottom-color: rgba(255,255,255,0.08); }
        .modal-dark .modal-footer { border-top-color: rgba(255,255,255,0.08); }
        .modal-dark .form-control { background: #0d0d0d; border-color: rgba(255,255,255,0.1); color: #fff; }
        .modal-dark .form-control:focus { background: #0d0d0d; border-color: #0b6856; color: #fff; box-shadow: 0 0 0 0.2rem rgba(11,104,86,0.25); }
        .modal-dark .form-label { color: rgba(255,255,255,0.8); }
        .modal-dark .btn-close { filter: invert(1); opacity: 0.7; }
        .modal-dark .btn-secondary { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2); color: #fff; }
        .modal-dark .btn-secondary:hover { background: rgba(255,255,255,0.15); color: #fff; }
        .modal-dark .btn-primary { background: #0b6856; border-color: #0b6856; }
        .modal-dark .btn-primary:hover { background: #0a4d3f; border-color: #0a4d3f; }
        .modal-dark .text-danger { color: #e57373 !important; }
        /* Garante que os modais do aluno também sejam movidos para o body e fiquem acima de qualquer overlay */
        .modal-backdrop {
            z-index: 900000000000 !important;
            background-color: rgba(0, 0, 0, 0.5) !important;
            opacity: 1 !important;
        }
        .modal,
        .modal.show {
            z-index: 900000000001 !important;
        }
        .modal .modal-dialog {
            z-index: 900000000002 !important;
        }
        .modal .modal-content {
            position: relative !important;
            z-index: 900000000003 !important;
        }
        @media (max-width: 768px) {
            .header { padding: 15px 20px; }
            .container-main { padding: 30px 20px; }
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="{{ route('aluno.produtos.adquiridos') }}" class="logo">Área de Membros</a>
        <div class="header-right">
            <div class="header-greeting-wrap">
                <span class="header-greeting-label">Olá,</span>
                <span class="header-greeting-name">{{ $user->name }}</span>
            </div>
            <a href="{{ route('aluno.profile') }}" class="header-avatar-link" title="Meu perfil">
                <img src="{{ $user->avatar ? asset($user->avatar) : asset('default-avatar.png') }}" alt="{{ $user->name }}" class="header-avatar" onerror="this.src='{{ asset('default-avatar.png') }}'">
            </a>
            <form method="POST" action="{{ route('aluno.logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="logout-btn">Sair →</button>
        </form>
        </div>
    </header>

    <div class="container-main">
        <h1 class="page-title">Meu perfil</h1>
        <p class="page-subtitle">Suas informações e cursos</p>

        {{-- Dados pessoais + Foto --}}
        <div class="profile-card">
            <div class="profile-card-title">Dados pessoais</div>
            <div class="profile-row">
                <div class="profile-avatar-wrap">
                    <form id="form-avatar" action="{{ route('aluno.update.avatar') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" id="avatarInput" name="avatar" accept="image/*" class="d-none">
                        <label for="avatarInput" class="d-block mb-0" style="cursor:pointer;">
                            <img src="{{ $user->avatar ? asset($user->avatar) : asset('default-avatar.png') }}" alt="Avatar" class="profile-avatar" id="profileAvatarImg" onerror="this.src='{{ asset('default-avatar.png') }}'">
                            <span class="profile-avatar-edit"><i class="fas fa-camera me-1"></i> Trocar foto</span>
                        </label>
                    </form>
                </div>
                <div class="profile-info">
                    <p><span>Nome</span> {{ $user->name }}</p>
                    <p><span>Email</span> {{ $user->email }}</p>
                    <p><span>Telefone</span> {{ $user->celular ?? '—' }}</p>
                    <p><span>CPF</span> {{ $user->cpf ?? '—' }}</p>
                    <button type="button" class="profile-btn-outline" data-bs-toggle="modal" data-bs-target="#alterarSenhaModal">
                        <i class="fas fa-key me-1"></i> Alterar senha
                    </button>
                </div>
            </div>
        </div>

        {{-- Endereço --}}
        <div class="profile-card">
            <div class="profile-card-title d-flex align-items-center justify-content-between">
                <span>Endereço</span>
                <button type="button" class="profile-btn-outline py-1 px-2" style="margin-top:0;font-size:12px;" data-bs-toggle="modal" data-bs-target="#alterarEnderecoModal">
                    <i class="fas fa-pen me-1"></i> Editar
                </button>
            </div>
            <div class="profile-info">
                <p><span>CEP</span> {{ $user->cep ?? 'Não informado' }}</p>
                <p><span>Logradouro</span> {{ $user->street ?? '—' }}</p>
                <p><span>Bairro</span> {{ $user->address ?? '—' }}</p>
                <p><span>Cidade</span> {{ $user->city ?? '—' }}</p>
                <p><span>Estado</span> {{ $user->uf ?? '—' }}</p>
            </div>
        </div>

        {{-- Meus Cursos --}}
        <div class="profile-card">
            <div class="profile-card-title"><i class="fas fa-graduation-cap me-2"></i>Meus cursos</div>
            @if(isset($cursosComDados) && count($cursosComDados) > 0)
                @foreach($cursosComDados as $item)
                    @php
                        $produto = $item['produto'];
                        $pedido = $item['pedido'];
                        $progresso = $item['progresso'];
                        $valorFormatado = $pedido->valor ? 'R$ ' . number_format((float) $pedido->valor, 2, ',', '.') : '—';
                    @endphp
                    <a href="{{ route('aluno.produto.id', ['id' => $produto->id]) }}" class="course-card">
                        <div class="course-card-thumb">
                            @if($produto->image && $produto->image != 'produtos/box_default.svg')
                                <img src="/storage/{{ $produto->image }}" alt="{{ $produto->name }}">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center"><i class="fas fa-book" style="color:rgba(255,255,255,0.6);"></i></div>
                            @endif
                        </div>
                        <div class="course-card-body">
                            <div class="course-card-title">{{ $produto->name }}</div>
                            <div class="course-card-meta">
                                Comprado em {{ $pedido->created_at->format('d/m/Y') }} · {{ $valorFormatado }}
                            </div>
                            <div class="course-card-progress">
                                <div class="course-card-progress-fill" style="width: {{ $progresso }}%;"></div>
                            </div>
                            <div class="course-card-meta mt-1">{{ $progresso }}% concluído</div>
                        </div>
                        <span class="course-card-arrow"><i class="fas fa-chevron-right"></i></span>
                    </a>
                @endforeach
            @else
                <p class="empty-courses">Você ainda não possui cursos adquiridos.</p>
            @endif
        </div>
    </div>

    {{-- Modal Alterar senha --}}
    <div class="modal fade modal-dark" id="alterarSenhaModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alterar senha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('aluno.senha.alterar') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Senha atual</label>
                            <input type="password" class="form-control" name="senha_atual" value="{{ old('senha_atual') }}" required>
                            @error('senha_atual')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nova senha</label>
                            <input type="password" class="form-control" name="nova_senha" value="{{ old('nova_senha') }}" required>
                            @error('nova_senha')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Repetir nova senha</label>
                            <input type="password" class="form-control" name="nova_senha_confirmation" required>
                            @error('nova_senha_confirmation')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
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

    {{-- Modal Alterar endereço --}}
    <div class="modal fade modal-dark" id="alterarEnderecoModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alterar endereço</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('aluno.endereco.alterar') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label">CEP</label><input type="text" class="form-control" name="cep" id="cep" value="{{ $user->cep }}"></div>
                        <div class="mb-3"><label class="form-label">Rua</label><input type="text" class="form-control" name="street" id="street" value="{{ $user->street }}"></div>
                        <div class="mb-3"><label class="form-label">Bairro</label><input type="text" class="form-control" name="address" id="address" value="{{ $user->address }}"></div>
                        <div class="mb-3"><label class="form-label">Cidade</label><input type="text" class="form-control" name="city" id="city" value="{{ $user->city }}"></div>
                        <div class="mb-3"><label class="form-label">UF</label><input type="text" class="form-control" name="uf" id="uf" value="{{ $user->uf }}" maxlength="2"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('cep').addEventListener('blur', function() {
                var cep = this.value.replace(/\D/g, '');
                if (cep.length === 8) {
                    fetch('https://viacep.com.br/ws/' + cep + '/json/').then(function(r) { return r.json(); }).then(function(data) {
                        if (!data.erro) {
                            document.getElementById('street').value = data.logradouro || '';
                            document.getElementById('address').value = data.bairro || '';
                            document.getElementById('city').value = data.localidade || '';
                            document.getElementById('uf').value = data.uf || '';
                        }
                    });
                }
            });
        });
    </script>
    @if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('alterarSenhaModal'));
            modal.show();
        });
    </script>
    @endif

    @include('partials.avatar-crop-modal')
</body>
</html>
