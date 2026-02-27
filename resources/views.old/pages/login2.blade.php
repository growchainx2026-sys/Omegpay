@php
    use App\Helpers\Helper;
    $setting = Helper::settings();
@endphp

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Gateway de pagamento com checkout transparente, seguro e fácil de integrar. Ofereça múltiplos meios de pagamento, aumente suas vendas e melhore a experiência dos seus clientes.">
    <meta name="author" content="ThigasDEV">
    <link rel="icon" href="/storage/{{ $setting->favicon_light }}" />
    <title>{{ $setting->software_name }} - Login</title>
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- PICK ONE OF THE STYLES BELOW -->
    <!-- <link href="css/modern.css" rel="stylesheet"> -->
    <!-- <link href="css/classic.css" rel="stylesheet"> -->
    <!-- <link href="css/dark.css" rel="stylesheet"> -->
    <!-- <link href="css/light.css" rel="stylesheet"> -->

    <!-- BEGIN SETTINGS -->
    <!-- You can remove this after picking a style -->
    <style>
        :root {
            --gateway-primary-color: {{ $setting->software_color ?? '#008f39' }};
            --gateway-background-color: {{ $setting->software_color_background }};
            --gateway-sidebar-color: {{ $setting->software_color_sidebar }};
            --gateway-text-color: {{ $setting->software_color_text }};
            --gateway-primary-opacity: rgb(0, 104, 42);
        }

        body {
            opacity: 0;
            background: #f5f5f5 !important;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
            color: gray;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid rgba(65, 65, 65, 0.575);
        }

        .divider:not(:empty)::before {
            margin-right: .75em;
        }

        .divider:not(:empty)::after {
            margin-left: .75em;
        }

        .btn-primary,
        .form-check-input:checked {
            background: var(--gateway-primary-color) !important;
            border-color: var(--gateway-primary-color) !important;
        }

        .nav {
            --bs-nav-link-padding-x: 1rem;
            --bs-nav-link-padding-x: 1rem;
            --bs-nav-link-padding-y: 0.5rem;
            --bs-nav-link-font-weight: ;
            --bs-nav-link-color: var(--gateway-primary-color);
            --bs-nav-link-hover-color: var(--gateway-primary-opacity);
            --bs-nav-link-disabled-color: var(--bs-primary-opacity);
        }

        a {
            color: var(--gateway-primary-color) !important;
        }

        .bg-opacity {
            position: relative;
            margin-left: -60px !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-opacity::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-image: url('/storage/images/image_auth.png');
            background-size: cover;
            opacity: 0.3;
            z-index: 0;
        }

        .bg-opacity img {
            position: relative;
            z-index: 1;
        }
    </style>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <!-- END SETTINGS -->
</head>
<!-- SET YOUR THEME -->

<body class="login-background">
    <div class="splash active">
        <div class="splash-icon"></div>
    </div>

    <main class="main h-100 w-100 ml-0 pl-0">
        <div class="container-fluid ml-0 pl-0 h-100">
            <div class="row h-100 m-0 p-0">
                <div class="bg-opacity col-sm-12 col-md-8 col-xl-9 d-none d-md-flex align-items-center justify-content-center"
                    style="
                    opacity: 0.3;
                    position: relative;
                    margin-left: -60px !important;
                    background-image: url('/storage/images/image_auth.png'); 
                    background-size: cover;
                    ">
                    <img src="/storage/{{ $setting->logo_light . '?ver=' . uniqid() }}" alt="SoftBank" width="auto"
                        height="80px" style="position: absolute; top: 48%;bottom:48%;" />
                </div>
                <div class="col-sm-12 col-md-4 col-xl-3 mx-auto d-flex align-items-center justify-content-end">
                    <div class="row w-100">
                        <div id="menuEscolha">
                            <div class="mb-3 col-12 w-100">
                                <div class="text-center mb-4">
                                    <img src="/storage/{{ $setting->logo_light . '?ver=' . uniqid() }}" alt="SoftBank"
                                        width="auto" height="40px" />
                                </div>
                            </div>
                            <div class="mb-3 col-12 w-100">
                                <button id="btnSouAluno" class="btn btn-primary btn-lg w-100">Sou Aluno</button>
                            </div>
                            <div class="mb-3 divider">ou</div>
                            <div class="mb-3 col-12 w-100">
                                <button id="btnSouProdutor" class="btn btn-primary btn-lg w-100">Sou Produtor</button>
                            </div>
                        </div>

                        <!-- Bloco de login aluno -->
                        <div id="formAluno" class="d-none w-100 px-2">
                            <form action="{{ route('aluno.auth') }}" method="POST">
                                @csrf
                                <div class="mb-3 w-100" style="position: relative;">
                                    <div class="mb-3" style="cursor: pointer" onclick="backToHome()">
                                        <x-lucide-icon :icon="'arrow-left'" size="24" color="gray" />
                                    </div>

                                    <h1 class="fw-bold">Entrar</h1>
                                    <p>Precisa de uma <a href="#">Nova senha?</a></p>
                                    <div style="position: absolute;bottom:0px;right:0px">
                                        <img src="/storage/{{ $setting->favicon_light . '?ver=' . uniqid() }}"
                                            alt="SoftBank" width="60px" height="60px" />
                                    </div>
                                </div>

                                <div class="mb-3 w-100">
                                    <input type="email" class="form-control form-control-lg" name="email"
                                        placeholder="Email">
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 w-100">
                                    <input type="password" class="form-control form-control-lg" name="password"
                                        placeholder="Senha">
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100">Entrar</button>
                            </form>
                        </div>

                        <!-- Bloco de login produtor -->
                        <div id="formProdutor" class="d-none w-100 px-2">
                            <div class="mb-3 w-100" style="position: relative;">
                                <div class="mb-3" style="cursor: pointer" onclick="backToHome()">
                                    <x-lucide-icon :icon="'arrow-left'" size="24" color="gray" />
                                </div>
                                <h1 class="fw-bold">Entrar</h1>
                                <p>Ainda não tem conta? <a href="#">Crie uma nova</a></p>
                                <div style="position: absolute;bottom:0px;right:0px">
                                    <img src="/storage/{{ $setting->favicon_light . '?ver=' . uniqid() }}"
                                        alt="SoftBank" width="60px" height="60px" />
                                </div>
                            </div>

                            <div class="mb-3 w-100">
                                <input type="email" class="form-control form-control-lg" placeholder="Email">
                            </div>
                            <div class="mb-3 w-100">
                                <input type="password" class="form-control form-control-lg" placeholder="Senha">
                            </div>
                            <button class="btn btn-primary btn-lg w-100">Entrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('btnSouAluno').addEventListener('click', function() {
            document.getElementById('menuEscolha').classList.add('d-none');
            document.getElementById('formAluno').classList.remove('d-none');
        });

        document.getElementById('btnSouProdutor').addEventListener('click', function() {
            document.getElementById('menuEscolha').classList.add('d-none');
            document.getElementById('formAluno').classList.add('d-none');
            document.getElementById('formProdutor').classList.remove('d-none');
        });

        function backToHome() {
            document.getElementById('menuEscolha').classList.remove('d-none');
            document.getElementById('formAluno').classList.add('d-none');
            document.getElementById('formProdutor').classList.add('d-none');
        }
    </script>

    {{-- <form method="POST" action="{{ route('auth.login') }}">
        @csrf
        <div class="mb-3 mt-3">
            <div class="input-group">
                <div class="btn btn-primary " type="button"><i class="fa-solid fa-user"></i></div>
                <input autofocus type="text" name="email" value="{{ old('email') }}" style="padding-left:5px"
                    class="form-control form-control-lg" placeholder="Digite seu email">
            </div>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <div class="input-group">
                <div class="btn btn-primary d-flex align-items-center justify-content-center" type="button"><i
                        class="fa-solid fa-lock"></i></div>
                <input type="password" name="password" value="{{ old('password') }}" style="padding-left:5px"
                    class="form-control form-control-lg" placeholder="Digite sua senha">
            </div>
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            <small>
                <a href='/reset-password'>Esqueceu a senha?</a>
            </small>
        </div>
        <div>
            <div class="form-check align-items-center">
                <input id="customControlInline" type="checkbox" class="form-check-input" value="remember-me"
                    name="remember-me" checked>
                <label class="form-check-label text-small" for="customControlInline">Lembrar-me</label>
            </div>
        </div>
        <div class="text-center mt-3">
            <button type="submit" class='btn btn-lg btn-primary w-100' href='/dashboard'><i
                    class="fa-solid fa-right-to-bracket"></i>&nbsp;Acessar</button>
            <!-- <button type="submit" class="btn btn-lg btn-primary">Sign in</button> -->
        </div>

        <div class="text-center mt-3" style="line-height: 10px">
            <p style="padding-top:15px;border-top: 1px solid gray">Ainda não tem uma conta?</p>
            <a class='btn btn-lg btn-link' href='/register'>Cadastrar-me</a>
            <!-- <button type="submit" class="btn btn-lg btn-primary">Sign in</button> -->
        </div>
    </form> --}}
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>
