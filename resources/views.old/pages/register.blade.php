@php
    use App\Models\Setting;
    $setting = Setting::first();
@endphp

@extends('layouts.auth')

@section('title', 'Registrar-me')

@section('content')
    <div class="row h-100 my-3">
        <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">

            <div class="d-table-cell align-middle">

                <div class="card">
                    <div class="card-body">
                        <div class="m-sm-4">
                            <div class="text-center mb-4">
                                <img src="/storage/{{ $setting->logo_light . '?ver=' . uniqid() }}" alt="SoftBank"
                                    width="auto" height="40px" />
                            </div>
                            <div class="progress px-1" style="height: 3px;">
                                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="step-container d-flex justify-content-between">
                                <div class="step-circle" onclick="displayStep(1)">Tipo</div>
                                <div class="step-circle" onclick="displayStep(2)">Pessoal</div>
                                <div class="step-circle" onclick="displayStep(3)">Confirmar</div>
                            </div>
                            <!-- ... resto do HTML acima permanece ... -->

                            <form id="multi-step-form" action="{{ route('auth.register') }}" method="POST">
                                @csrf
                                @if (request()->has('ref'))
                                    <input type="hidden" name="client_indication" value="{{ request()->get('ref') }}">
                                @endif

                                <!-- Step 1 -->
                                <div class="step step-1">
                                    <div class="mb-3">
                                        <label for="tipo" class="form-label">Tipo de conta</label>
                                        <select class="form-control" id="tipo" name="tipo" required
                                            value="{{ old('tipo') }}">
                                            <option value="">Selecione</option>
                                            <option value="pessoa_fisica">Pessoa Física</option>
                                            <option value="pessoa_juridica">Pessoa Jurídica</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-primary next-step">Avançar</button>
                                </div>

                                <!-- Step 2 -->
                                <div class="step step-2">
                                    <div class="step-content"></div> <!-- Aqui vai o conteúdo dinâmico dos templates -->

                                    <!-- Os campos de email e celular que são comuns aos dois -->
                                    <div class="mb-3">
                                        <div class="input-group">
                                            <span class="btn btn-primary d-flex align-items-center justify-content-center">
                                                <i class="fa-solid fa-envelope"></i>
                                            </span>
                                            <input type="email" name="email" class="form-control form-control-lg"
                                                placeholder="Digite seu email" required value="{{ old('email') }}">
                                        </div>
                                        @error('email')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <div class="input-group">
                                            <span class="btn btn-primary d-flex align-items-center justify-content-center">
                                                <i class="fa-solid fa-mobile-screen"></i>
                                            </span>
                                            <input type="text" name="phone" class="form-control form-control-lg"
                                                placeholder="Digite seu celular" required value="{{ old('phone') }}">
                                        </div>
                                        @error('phone')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="button" class="btn btn-primary prev-step">Voltar</button>
                                    <button type="button" class="btn btn-primary next-step">Avançar</button>
                                </div>



                                <!-- Step 3 -->
                                
                                <div class="step step-3">
                                    <div class="mb-3 mt-3">
                                        <div class="input-group">
                                            <div class="btn btn-primary d-flex align-items-center justify-content-center"
                                                type="button"><i class="fa-solid fa-dollar"></i></div>
                                            <select type="text" name="media_faturamento"
                                                value="{{ old('media_faturamento') }}" class="form-control form-control-lg"
                                                placeholder="Faturamento">
                                                <option value="0">Qual seu faturamento mensal?</option>
                                                <option value="0">Sem faturamento</option>
                                                <option value="100000">Abaixo de 100 mil</option>
                                                <option value="500000">Entre 100 e 500 mil</option>
                                                <option value="1000000">Entre 500 mil e 1 milhão</option>
                                                <option value="10000000">Mais de 1 milhão</option>
                                            </select>
                                        </div>
                                        @error('media_faturamento')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 mt-3 col-md-6">
                                            <div class="input-group">
                                                <div class="btn btn-primary d-flex align-items-center justify-content-center"
                                                    type="button"><i class="fa-solid fa-user-lock"></i></div>
                                                <input type="password" name="password" value="{{ old('password') }}"
                                                    class="form-control form-control-lg" placeholder="Senha">
                                            </div>
                                            @error('password')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 mt-3 col-md-6">
                                            <div class="input-group">
                                                <div class="btn btn-primary d-flex align-items-center justify-content-center"
                                                    type="button"><i class="fa-solid fa-user-lock"></i></div>
                                                <input type="password" name="password_confirm"
                                                    value="{{ old('password_confirm') }}"
                                                    class="form-control form-control-lg" placeholder="Confirmar senha">
                                            </div>
                                            @error('password_confirm')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <!-- NOVO: Bloco de validação visual da senha -->
                                    <div id="password-validation-rules" class="mb-3" style="font-size: 0.85em;">
                                        <p class="mb-1 rule" id="pass-length">Pelo menos 8 caracteres</p>
                                        <p class="mb-1 rule" id="pass-upper">Uma letra maiúscula</p>
                                        <p class="mb-1 rule" id="pass-lower">Uma letra minúscula</p>
                                        <p class="mb-1 rule" id="pass-special">Um caractere especial (!@#$...)</p>
                                        <p class="mb-1 rule" id="pass-match">As senhas devem ser idênticas</p>
                                    </div>

                                    <button type="button" class="btn btn-primary prev-step">Voltar</button>
                                    <!-- MODIFICADO: Botão de envio desabilitado por padrão e com ID -->
                                    <button type="submit" id="submit-btn" class="btn btn-success" disabled>Enviar</button>
                                </div>
                                <div class="text-center mt-5" style="line-height: 10px">
                                    <a class='btn btn-lg btn-link' href='/login'>Já possui cadastro? Fazer Login</a>
                                    <!-- <button type="submit" class="btn btn-lg btn-primary">Sign in</button> -->
                                </div>
                            </form>

                            <!-- Templates fora do form -->
                            <div id="template-fisica" class="d-none">
                                <div class="mb-3 mt-3">
                                    <div class="input-group">
                                        <span class="btn btn-primary d-flex align-items-center justify-content-center">
                                            <i class="fa-solid fa-user"></i>
                                        </span>
                                        <input type="text" name="name" class="form-control form-control-lg"
                                            placeholder="Nome completo" required value="{{ old('name') }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="btn btn-primary d-flex align-items-center justify-content-center">
                                            <i class="fa-solid fa-id-card"></i>
                                        </span>
                                        <input type="text" name="cpf" class="form-control form-control-lg"
                                            placeholder="Digite seu CPF" required value="{{ old('cpf') }}">
                                    </div>
                                </div>
                            </div>

                            <div id="template-juridica" class="d-none">
                                <div class="mb-3 mt-3">
                                    <div class="input-group">
                                        <span class="btn btn-primary d-flex align-items-center justify-content-center">
                                            <i class="fa-solid fa-building"></i>
                                        </span>
                                        <input type="text" name="razao_social" class="form-control form-control-lg"
                                            placeholder="Razão Social" required value="{{ old('razao_social') }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="btn btn-primary d-flex align-items-center justify-content-center">
                                            <i class="fa-solid fa-id-card"></i>
                                        </span>
                                        <input type="text" name="cnpj" class="form-control form-control-lg"
                                            placeholder="CNPJ" required value="{{ old('cnpj') }}">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <style>
        /* Estilos para a validação visual da senha */
        .rule {
            color: #dc3545; /* Cor de erro do Bootstrap (vermelho) */
            transition: color 0.3s ease-in-out;
        }
        .rule.valid {
            color: #198754; /* Cor de sucesso do Bootstrap (verde) */
        }
        .rule::before {
            content: '❌ ';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
        }
        .rule.valid::before {
            content: '✅ ';
        }
    </style>
    <script>
        let currentStep = 1;
        let tipoSelecionado = '';

        function insertStep2Fields(tipo) {
            const content = $('.step-content');
            content.empty(); // limpa
            if (tipo === 'pessoa_fisica') {
                content.html($('#template-fisica').html());
            } else if (tipo === 'pessoa_juridica') {
                content.html($('#template-juridica').html());
            }
        }

        function showStep(step) {
            $('.step').hide();
            $(`.step-${step}`).show();
            $('.step-circle').removeClass('active');
            $(`.step-circle:nth-child(${step})`).addClass('active');
            updateProgressBar(step);
        }

        function updateProgressBar(step) {
            const percent = ((step - 1) / 2) * 100;
            $(".progress-bar").css("width", percent + "%");
        }

        function validateStep(step) {
            let valid = true;
            $(`.step-${step}:visible input, .step-${step}:visible select`).each(function() {
                if ($(this).prop('required') && $(this).val().trim() === '') {
                    // Adiciona uma classe de erro ou mostra uma mensagem
                    $(this).addClass('is-invalid');
                    valid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            return valid;
        }

        $(document).ready(function() {
            $('.step').hide();
            $('.step-1').show();
            updateProgressBar(currentStep);

            // tipo change
            $('#tipo').on('change', function() {
                tipoSelecionado = $(this).val();
            });

            $('.next-step').click(function() {
                if (!validateStep(currentStep)) return;

                if (currentStep === 1) {
                    tipoSelecionado = $('#tipo').val();
                    if (!tipoSelecionado) {
                        alert("Selecione o tipo de conta");
                        return;
                    }
                    insertStep2Fields(tipoSelecionado);
                }

                if (currentStep < 3) {
                    currentStep++;
                    showStep(currentStep);
                }
            });

            $('.prev-step').click(function() {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });

            // Validação de inputs e máscaras
            $(document).on('input', 'input[name="name"]', function() {
                const onlyLetters = $(this).val().replace(/[^A-Za-zÀ-ÿ ]+/g, '');
                $(this).val(onlyLetters);
            });

            $(document).on('input', 'input[name="razao_social"]', function() {
                const onlyLetters = $(this).val().replace(/[^A-Za-zÀ-ÿ0-9 ]+/g, '');
                $(this).val(onlyLetters);
            });

            $(document).on('focus', 'input[name="cpf"]', function() {
                $(this).mask('000.000.000-00', {
                    reverse: true
                });
            });

            $(document).on('focus', 'input[name="cnpj"]', function() {
                $(this).mask('00.000.000/0000-00', {
                    reverse: true
                });
            });

            $('input[name="phone"]').mask('(00) 00000-0000');

            // --- INÍCIO DA NOVA LÓGICA DE VALIDAÇÃO DE SENHA ---
            
            function checkPasswordValidation() {
                const password = $('input[name="password"]').val();
                const confirmPassword = $('input[name="password_confirm"]').val();
                
                let allValid = true;

                // 1. Validação de comprimento
                if (password.length >= 8) {
                    $('#pass-length').addClass('valid');
                } else {
                    $('#pass-length').removeClass('valid');
                    allValid = false;
                }

                // 2. Validação de letra maiúscula
                if (/[A-Z]/.test(password)) {
                    $('#pass-upper').addClass('valid');
                } else {
                    $('#pass-upper').removeClass('valid');
                    allValid = false;
                }

                // 3. Validação de letra minúscula
                if (/[a-z]/.test(password)) {
                    $('#pass-lower').addClass('valid');
                } else {
                    $('#pass-lower').removeClass('valid');
                    allValid = false;
                }

                // 4. Validação de caractere especial
                if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                    $('#pass-special').addClass('valid');
                } else {
                    $('#pass-special').removeClass('valid');
                    allValid = false;
                }
                
                // 5. Validação de senhas idênticas
                if (password && password === confirmPassword) {
                    $('#pass-match').addClass('valid');
                } else {
                    $('#pass-match').removeClass('valid');
                    allValid = false;
                }

                // Habilita ou desabilita o botão de envio
                $('#submit-btn').prop('disabled', !allValid);
            }

            // Adiciona o listener aos campos de senha usando delegação de eventos
            // Isso garante que funcione mesmo que os campos sejam adicionados dinamicamente
            $(document).on('keyup', 'input[name="password"], input[name="password_confirm"]', function() {
                checkPasswordValidation();
            });

             // --- FIM DA NOVA LÓGICA DE VALIDAÇÃO DE SENHA ---
        });
    </script> -->
<!-- <script disable-devtool-auto src="https://cdn.jsdelivr.net/npm/disable-devtool@latest"></script> -->
@endsection
