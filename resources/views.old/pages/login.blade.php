@php
    use App\Helpers\Helper;
    $setting = Helper::settings();
    $active = session('tipo_auth', null);
    $form = request()->query('form') ?? null;
    $loginBgUrl = \App\Helpers\Helper::loginBackgroundUrl($setting->login_background ?? null);
@endphp

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ $setting->software_description }}">
    <meta name="author" content="@thigasdev">
    <link rel="icon" href="/storage/{{ $setting->favicon_light }}" />
    <title>{{ $setting->software_name }} - Login</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLMDJc1b/T0EIVtA7bB0w0B0w0w0w0w0w0w0w0w0w0w0w0w0w0w0w0w0w0w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">

    <style>
        :root {
            --gateway-primary: {{ $setting->software_color ?? '#2563eb' }};
            --gateway-primary-hover: {{ $setting->software_color ?? '#1d4ed8' }};
            --gateway-sidebar: {{ $setting->software_color_sidebar ?? '#0f172a' }};
            --gateway-text: {{ $setting->software_color_text ?? '#f8fafc' }};
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #0f172a;
            color: #1e293b;
            overflow-x: hidden;
        }

        body.loaded {
            opacity: 1;
        }

        body {
            transition: opacity 0.35s ease;
        }

        body.page-transition-out {
            opacity: 0;
        }

        .login-root {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        .login-panel {
            flex: 0 0 50%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            transition: transform 0.65s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: transform;
        }

        .login-panel-form {
            background: #ffffff;
            z-index: 2;
            position: relative;
            transition: transform 0.65s cubic-bezier(0.4, 0, 0.2, 1), background 0.4s ease, box-shadow 0.4s ease, border-color 0.4s ease;
            border: 1px solid transparent;
        }

        .login-panel-form:hover {
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08), inset 0 1px 0 rgba(255, 255, 255, 0.9);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .login-panel-visual {
            background: url('{{ $loginBgUrl }}') center / cover no-repeat;
            position: relative;
            overflow: hidden;
            z-index: 1;
            align-items: flex-end;
            padding-bottom: 2rem;
        }

        .login-panel-visual::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 45%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.5) 0%, transparent 100%);
            pointer-events: none;
            z-index: 0;
        }

        .login-panel-visual .visual-content {
            z-index: 1;
        }

        .login-layout--aluno .login-panel-form {
            transform: translateX(100%);
        }

        .login-layout--aluno .login-panel-visual {
            transform: translateX(-100%);
        }

        .login-form-inner {
            width: 100%;
            max-width: 380px;
        }

        .login-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
        }

        .login-brand img {
            height: 40px;
            width: auto;
        }

        .login-tabs {
            display: flex;
            gap: 2px;
            margin-bottom: 1.5rem;
            background: #f1f5f9;
            padding: 3px;
            border-radius: 10px;
        }

        .login-tab {
            flex: 1;
            padding: 0.5rem 0.75rem;
            border: none;
            background: transparent;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.8125rem;
            font-weight: 500;
            color: #64748b;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .login-tab:hover {
            color: #334155;
        }

        .login-tab.active {
            background: #fff;
            color: var(--gateway-primary);
            box-shadow: 0 1px 2px rgba(0,0,0,0.06);
        }

        .login-form-block {
            display: none;
        }

        .login-form-block.active {
            display: block;
            animation: loginFadeIn 0.4s ease;
        }

        @keyframes loginFadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-form-block h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 0.25rem 0;
        }

        .login-form-block .login-subtitle {
            color: #64748b;
            font-size: 0.9375rem;
            margin-bottom: 1.5rem;
        }

        .login-form-block .login-subtitle a {
            color: var(--gateway-primary);
            text-decoration: none;
            font-weight: 500;
        }

        .login-form-block .login-subtitle a:hover {
            text-decoration: underline;
        }

        .form-group-login {
            margin-bottom: 1.25rem;
        }

        .form-group-login label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 500;
            color: #334155;
            margin-bottom: 0.375rem;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap input {
            width: 100%;
            padding: 0.875rem 1rem;
            padding-right: 3rem;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.9375rem;
            font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .input-wrap input:focus {
            outline: none;
            border-color: var(--gateway-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .input-wrap input::placeholder {
            color: #94a3b8;
        }

        .input-wrap .toggle-pwd {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 4px;
        }

        .input-wrap .toggle-pwd:hover {
            color: #334155;
        }

        .btn-login-primary {
            width: 100%;
            padding: 0.875rem 1.5rem;
            background: var(--gateway-primary);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            margin-top: 0.5rem;
        }

        .btn-login-primary:hover {
            background: var(--gateway-primary-hover);
        }

        .btn-login-primary:active {
            transform: scale(0.99);
        }

        .login-options {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .login-check {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            font-size: 0.8125rem;
            color: #475569;
            cursor: pointer;
            user-select: none;
        }

        .login-check input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-track {
            flex-shrink: 0;
            width: 28px;
            height: 16px;
            background: #e2e8f0;
            border-radius: 999px;
            position: relative;
            transition: background 0.25s ease;
        }

        .toggle-knob {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 12px;
            height: 12px;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 1px 2px rgba(0,0,0,0.12);
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .login-check input:checked + .toggle-track {
            background: var(--gateway-primary);
        }

        .login-check input:checked + .toggle-track .toggle-knob {
            transform: translateX(14px);
        }

        .login-back {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            color: #64748b;
            font-size: 0.875rem;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            margin-bottom: 1rem;
            font-family: inherit;
        }

        .login-form-block[data-block="produtor"] .login-back {
            display: none;
        }

        .login-back:hover {
            color: var(--gateway-primary);
        }

        .visual-content {
            position: relative;
            z-index: 1;
            text-align: center;
            color: rgba(255,255,255,0.95);
            width: 100%;
        }

        .visual-content p {
            font-size: 0.875rem;
            opacity: 0.9;
            margin: 0;
        }

        .login-alert {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }

        .login-alert-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .swal2-popup {
            background-color: var(--gateway-sidebar) !important;
            color: var(--gateway-text) !important;
        }

        @media (max-width: 900px) {
            .login-root {
                flex-direction: column;
                min-height: 100vh;
            }

            .login-panel {
                flex: none;
                min-height: auto;
                padding: 1.5rem 1.25rem;
                transition: none;
            }

            .login-panel-form {
                flex: 1;
                width: 100%;
                min-height: 100vh;
                padding: 2rem 1.25rem;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #ffffff;
            }

            .login-panel-form:hover {
                background: #ffffff;
                backdrop-filter: none;
                -webkit-backdrop-filter: none;
                box-shadow: none;
                border-color: transparent;
            }

            .login-panel-visual {
                display: none;
            }

            .login-form-inner {
                max-width: 100%;
                width: 100%;
            }

            .login-brand {
                margin-bottom: 1.5rem;
                justify-content: center;
            }

            .login-brand img {
                height: 36px;
            }

            .login-tabs {
                margin-bottom: 1.5rem;
            }

            .login-form-block h1 {
                font-size: 1.5rem;
            }

            .login-layout--aluno .login-panel-form,
            .login-layout--aluno .login-panel-visual {
                transform: none;
            }
        }

        @media (max-width: 480px) {
            .login-panel {
                padding: 1rem;
            }

            .login-tab {
                padding: 0.625rem 0.75rem;
                font-size: 0.875rem;
            }

            .login-options .login-check {
                padding: 0.5rem 0.75rem;
                font-size: 0.8125rem;
            }

            .btn-login-primary {
                padding: 0.75rem 1.25rem;
            }

            .input-wrap input {
                padding: 0.75rem 1rem;
                padding-right: 2.75rem;
            }
        }
    </style>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
</head>

<body>
    <div class="splash active"><div class="splash-icon"></div></div>

    <div class="login-root {{ ($form && $form === 'aluno') ? 'login-layout--aluno' : '' }}" id="loginRoot">
        <div class="login-panel login-panel-form">
            <div class="login-form-inner">
                <div class="login-brand">
                    <img src="{{ \App\Helpers\Helper::logoUrl($setting->logo_light ?? null) }}?ver={{ uniqid() }}" alt="{{ $setting->software_name }}" />
                </div>

                <div class="login-tabs">
                    <button type="button" class="login-tab active" data-tab="produtor" id="tabProdutor">Sou Produtor</button>
                    <button type="button" class="login-tab" data-tab="aluno" id="tabAluno">Sou Aluno</button>
                </div>

                @error('banido')
                    <div class="login-alert login-alert-warning">{{ $message }}</div>
                @enderror

                <!-- Form Produtor -->
                <div class="login-form-block active" id="formBlockProdutor" data-block="produtor">
                    <button type="button" class="login-back" onclick="backToHome()">
                        <i data-lucide="arrow-left" width="18" height="18"></i> Voltar
                    </button>
                    <h1>Entrar</h1>
                    <p class="login-subtitle">Ainda não tem conta? <a href="/auth/jwt/register" id="linkCriarConta">Crie uma nova</a></p>

                    <form method="POST" action="{{ route('auth.login') }}">
                        @csrf
                        <div class="form-group-login">
                            <label for="email-produtor">Email</label>
                            <div class="input-wrap">
                                <input type="email" name="email" id="email-produtor" placeholder="Seu email" value="{{ old('email', '') }}" required>
                            </div>
                            @error('email')<div class="text-danger" style="font-size:0.8125rem;margin-top:4px">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group-login">
                            <label for="pwd-produtor">Senha</label>
                            <div class="input-wrap js-toggle-password">
                                <input type="password" name="password" id="pwd-produtor" placeholder="Sua senha" aria-label="Senha" required>
                                <button type="button" class="toggle-pwd js-toggle-btn" title="Mostrar senha"><i class="fas fa-eye js-toggle-icon"></i></button>
                            </div>
                            @error('password')<div class="text-danger" style="font-size:0.8125rem;margin-top:4px">{{ $message }}</div>@enderror
                        </div>
                        <div class="login-options">
                            <label class="login-check">
                                <input type="checkbox" name="remember_email" value="1">
                                <span class="toggle-track"><span class="toggle-knob"></span></span>
                                <span>Lembrar email</span>
                            </label>
                            <label class="login-check">
                                <input type="checkbox" name="auto_login" value="1">
                                <span class="toggle-track"><span class="toggle-knob"></span></span>
                                <span>Entrar automaticamente</span>
                            </label>
                        </div>
                        <p class="login-subtitle" style="margin-bottom:1rem">Esqueci a senha <a href="#" id="btnRecPassProd">Recuperar?</a></p>
                        <button type="submit" class="btn-login-primary">Entrar</button>
                    </form>
                </div>

                <!-- Form Aluno -->
                <div class="login-form-block" id="formBlockAluno" data-block="aluno">
                    <button type="button" class="login-back" onclick="backToHome()">
                        <i data-lucide="arrow-left" width="18" height="18"></i> Voltar
                    </button>
                    <h1>Entrar</h1>
                    <p class="login-subtitle">Precisa de uma <a href="#" id="btnRecPassAluno">Nova senha?</a></p>

                    <form action="{{ route('aluno.auth') }}" method="POST">
                        @csrf
                        <div class="form-group-login">
                            <label for="email-aluno">Email</label>
                            <div class="input-wrap">
                                <input type="email" name="email" id="email-aluno" placeholder="Seu email" required>
                            </div>
                            @error('email')<div class="text-danger" style="font-size:0.8125rem;margin-top:4px">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group-login">
                            <label for="pwd-aluno">Senha</label>
                            <div class="input-wrap js-toggle-password">
                                <input type="password" name="password" id="pwd-aluno" placeholder="Sua senha" aria-label="Senha" required>
                                <button type="button" class="toggle-pwd js-toggle-btn" title="Mostrar senha"><i class="fas fa-eye js-toggle-icon"></i></button>
                            </div>
                            @error('password')<div class="text-danger" style="font-size:0.8125rem;margin-top:4px">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn-login-primary">Entrar</button>
                    </form>
                </div>

                <!-- Recuperar senha Produtor -->
                <div class="login-form-block" id="formBlockRecProd" data-block="recprod">
                    <button type="button" class="login-back" onclick="showBlock('produtor')">
                        <i data-lucide="arrow-left" width="18" height="18"></i> Voltar
                    </button>
                    <h1>Recuperar senha</h1>
                    <p class="login-subtitle">Lembrei-me da senha! <a href="#" onclick="showBlock('produtor'); return false">Acessar</a></p>
                    <form action="{{ route('auth.recpass') }}" method="POST">
                        @csrf
                        <div class="form-group-login">
                            <label>Email</label>
                            <div class="input-wrap">
                                <input type="email" name="email" placeholder="Seu email" required>
                            </div>
                        </div>
                        <button type="submit" class="btn-login-primary">Recuperar</button>
                    </form>
                </div>

                <!-- Recuperar senha Aluno -->
                <div class="login-form-block" id="formBlockRecAluno" data-block="recaluno">
                    <button type="button" class="login-back" onclick="showBlock('aluno')">
                        <i data-lucide="arrow-left" width="18" height="18"></i> Voltar
                    </button>
                    <h1>Nova senha</h1>
                    <p class="login-subtitle">Lembrei-me da senha! <a href="#" onclick="showBlock('aluno'); return false">Acessar</a></p>
                    <form action="{{ route('aluno.newpass') }}" method="POST">
                        @csrf
                        <div class="form-group-login">
                            <label>Email</label>
                            <div class="input-wrap">
                                <input type="email" name="email" placeholder="Seu email" required>
                            </div>
                        </div>
                        <button type="submit" class="btn-login-primary">Recuperar</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="login-panel login-panel-visual">
            <div class="visual-content">
                <p>© {{ date('Y') }} {{ $setting->software_name }}. Todos os direitos reservados.</p>
            </div>
        </div>
    </div>

    <script>
        document.body.classList.add('loaded');

        const loginRoot = document.getElementById('loginRoot');
        const tabProdutor = document.getElementById('tabProdutor');
        const tabAluno = document.getElementById('tabAluno');
        const formBlockProdutor = document.getElementById('formBlockProdutor');
        const formBlockAluno = document.getElementById('formBlockAluno');

        function setLayout(isAluno) {
            if (isAluno) {
                loginRoot.classList.add('login-layout--aluno');
            } else {
                loginRoot.classList.remove('login-layout--aluno');
            }
        }

        function showBlock(block) {
            document.querySelectorAll('.login-form-block').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.login-tab').forEach(el => el.classList.remove('active'));

            if (block === 'produtor') {
                formBlockProdutor.classList.add('active');
                tabProdutor.classList.add('active');
                setLayout(false);
            } else if (block === 'aluno') {
                formBlockAluno.classList.add('active');
                tabAluno.classList.add('active');
                setLayout(true);
            } else if (block === 'recprod') {
                document.getElementById('formBlockRecProd').classList.add('active');
                tabProdutor.classList.add('active');
                setLayout(false);
            } else if (block === 'recaluno') {
                document.getElementById('formBlockRecAluno').classList.add('active');
                tabAluno.classList.add('active');
                setLayout(true);
            }
        }

        function backToHome() {
            showBlock('produtor');
        }

        tabProdutor.addEventListener('click', function() {
            if (this.classList.contains('active')) return;
            showBlock('produtor');
        });

        tabAluno.addEventListener('click', function() {
            if (this.classList.contains('active')) return;
            showBlock('aluno');
        });

        document.getElementById('btnRecPassProd').addEventListener('click', function(e) {
            e.preventDefault();
            showBlock('recprod');
        });

        document.getElementById('btnRecPassAluno').addEventListener('click', function(e) {
            e.preventDefault();
            showBlock('recaluno');
        });

        document.getElementById('linkCriarConta').addEventListener('click', function(e) {
            e.preventDefault();
            var url = this.getAttribute('href');
            document.body.classList.add('page-transition-out');
            setTimeout(function() {
                window.location.href = url;
            }, 350);
        });

        document.querySelectorAll('.login-tab[data-tab]').forEach(btn => {
            btn.addEventListener('click', function() {
                const tab = this.getAttribute('data-tab');
                showBlock(tab);
            });
        });

        (function() {
            const form = "{{ $form ?? '' }}";
            const tipo = "{{ $active ?? '' }}";
            if (form === 'aluno' || tipo === 'aluno') {
                showBlock('aluno');
            } else if (form === 'new-password' || tipo === 'new-password') {
                showBlock('recaluno');
            } else {
                showBlock('produtor');
            }
        })();

        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return null;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const rememberedEmail = getCookie('remembered_email');
            if (rememberedEmail && document.getElementById('email-produtor')) {
                document.getElementById('email-produtor').value = rememberedEmail;
                const cb = document.querySelector('input[name="remember_email"]');
                if (cb) cb.checked = true;
            }
        });
    </script>

    <script>
        function showToast(type, message) {
            Swal.mixin({
                toast: true,
                icon: type,
                title: message,
                animation: false,
                position: 'top-right',
                showConfirmButton: false,
                showCloseButton: true,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            }).fire();
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success')) showToast('success', "{{ session('success') }}"); @endif
            @if (session('error')) showToast('error', "{{ session('error') }}"); @endif
            @if (session('info')) showToast('info', "{{ session('info') }}"); @endif
            @if (session('warning')) showToast('warning', "{{ session('warning') }}"); @endif
        });
    </script>

    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script>lucide.createIcons();</script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleContainers = document.querySelectorAll('.js-toggle-password');
            toggleContainers.forEach(container => {
                const passwordInput = container.querySelector('input[type="password"], input[type="text"]');
                const toggleButton = container.querySelector('.js-toggle-btn');
                const toggleIcon = container.querySelector('.js-toggle-icon');
                if (passwordInput && toggleButton && toggleIcon) {
                    toggleButton.addEventListener('click', function() {
                        const currentType = passwordInput.getAttribute('type');
                        const newType = currentType === 'password' ? 'text' : 'password';
                        passwordInput.setAttribute('type', newType);
                        toggleIcon.classList.toggle('fa-eye', newType === 'password');
                        toggleIcon.classList.toggle('fa-eye-slash', newType === 'text');
                        toggleButton.setAttribute('title', newType === 'text' ? 'Esconder senha' : 'Mostrar senha');
                        passwordInput.focus();
                    });
                }
            });
        });
    </script>
</body>

</html>
