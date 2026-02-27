
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
	<meta name="author" content="@thigasdev">
    <link rel="icon" href="/storage/{{ $setting->favicon_light }}"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
	<title>{{ $setting->software_name }} - Registrar-me</title>

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
            --gateway-primary-opacity:rgb(0, 104, 42);
        }
		body {
			opacity: 0;
            background: var(--gateway-primary-color);
            background: linear-gradient(135deg,	var(--gateway-primary-color) 0%, rgb(0, 110, 59) 100%);
		}
		.card,
		input,
        select,
		placeholder {
			background: var(--gateway-sidebar-color) !important;
			color: var(--gateway-text-color) !important;
		}

        input::placeholder {
			color: var(--gateway-text-color) !important;
        }

        input:-ms-input-placeholder { /* Internet Explorer 10–11 */
			color: var(--gateway-text-color) !important;
        }

        input::-ms-input-placeholder { /* Microsoft Edge (antigo) */
			color: var(--gateway-text-color) !important;
        }

        input::-webkit-input-placeholder { /* Chrome/Safari/Opera */
			color: var(--gateway-text-color) !important;
        }

        input::-moz-placeholder { /* Firefox 19+ */
			color: var(--gateway-text-color) !important;
        }

        input:-moz-placeholder { /* Firefox 4–18 */
			color: var(--gateway-text-color) !important;
        }


		.form-control label {
			color: var(--gateway-text-color) !important;
		}

		.splash,
        .splash.active,
        .progress-bar {
			background: var(--gateway-primary-color) !important;
			background-color: var(--gateway-primary-color) !important;
		}
		
		.btn-primary,
		.form-check-input:checked {
            background: var(--gateway-primary-color) !important;
            border-color: var(--gateway-primary-color) !important;
        }
		a,
		.btn-link {
			color: var(--gateway-primary-color) !important;
		}
		.form-check-input:checked {
			color: white !important;
		}
        .btn-primary:hover {
            background: var(--gateway-primary-color) !important;
            border-color: var(--gateway-primary-color) !important;
        }

		text-primary {
			color: var(--gateway-primary-color) !important;
		}
        .input-group .btn {
            min-width: 40px;
            max-width: 40px;
            width:100%;
        }
        .icon-form {
            background:var(--gateway-primary-color) !important;
            border-color:var(--gateway-primary-color) !important;
            border-radius: 8px !important;
            box-shadow: 0px 0px 4px 2px var(--gateway-primary-color);
            z-index: 100 !important;
        }
        .input-group .btn {
            min-width: 40px;
            max-width: 40px;
            width:100%;
        }
        .card {
			border-radius: 15px !important;
		}


.step-container {
  position: relative;
  text-align: center;
  transform: translateY(-43%);
}

.step-circle {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background-color: #fff;
  border: 2px solid var(--gateway-primary-color);
  line-height: 30px;
  font-weight: bold;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 10px;
  cursor: pointer; /* Added cursor pointer */
}

.step-line {
  position: absolute;
  top: 16px;
  left: 50px;
  width: calc(100% - 100px);
  height: 2px;
  background-color: var(--gateway-primary-color);
  z-index: -1;
}

#multi-step-form{
  overflow-x: hidden;
}
       .is-invalid {
    border-color: #dc3545 !important;
}
.step-circle.active {
    font-weight: bold;
    color: #0d6efd;
} 
	</style>
	<script src="{{ asset('assets/js/settings.js') }}"></script>
	<!-- END SETTINGS -->
</head>
<!-- SET YOUR THEME -->

<body class="theme-blue login-background" >
	<div class="splash active">
		<div class="splash-icon"></div>
	</div>

	<main class="main h-100 w-100">
		<div class="container h-100">
			<div class="row h-100">
				<div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
                    
					<div class="d-table-cell align-middle">
						
                        <div class="card">
                            <div class="card-body">
                                <div class="m-sm-4">
                                    <div class="text-center mb-4">
                                        <img src="/storage/{{ $setting->logo_light.'?ver='.uniqid() }}" alt="SoftBank" width="auto" height="40px" />
                                    </div>
                                    <div class="progress px-1" style="height: 3px;">
                                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="step-container d-flex justify-content-between">
                                        <div class="step-circle" onclick="displayStep(1)">1</div>
                                        <div class="step-circle" onclick="displayStep(2)">2</div>
                                        <div class="step-circle" onclick="displayStep(3)">3</div>
                                    </div>
                                    <!-- ... resto do HTML acima permanece ... -->

                                    <form id="multi-step-form" action="{{ route('auth.register') }}" method="POST">
                                        @csrf
                                        @if(request()->has('ref'))
                                            <input type="hidden" name="client_indication" value="{{ request()->get('ref') }}">
                                        @endif

                                        <!-- Step 1 -->
                                        <div class="step step-1">
                                            <div class="mb-3">
                                                <label for="tipo" class="form-label">Tipo de conta</label>
                                                <select class="form-control" id="tipo" name="tipo" required value="{{ old('tipo') }}">
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
                                                    <input type="email" name="email" class="form-control form-control-lg" placeholder="Digite seu email" required value="{{ old('email') }}">
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
                                                    <input type="text" name="phone" class="form-control form-control-lg" placeholder="Digite seu celular" required value="{{ old('phone') }}">
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
                                                <div class="btn btn-primary d-flex align-items-center justify-content-center" type="button"><i class="fa-solid fa-dollar"></i></div>
                                                <select type="text"  name="media_faturamento" value="{{ old('media_faturamento') }}" class="form-control form-control-lg" placeholder="Faturamento">
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
                                                    <div class="btn btn-primary d-flex align-items-center justify-content-center" type="button"><i class="fa-solid fa-user-lock"></i></div>
                                                    <input type="password"  name="password" value="{{ old('password') }}" class="form-control form-control-lg" placeholder="Senha">
                                                </div>
                                                @error('password')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3 mt-3 col-md-6">
                                                <div class="input-group">
                                                    <div class="btn btn-primary d-flex align-items-center justify-content-center" type="button"><i class="fa-solid fa-user-lock"></i></div>
                                                    <input type="password" name="password_confirm" value="{{ old('password_confirm') }}" class="form-control form-control-lg" placeholder="Confirmar senha">
                                                </div>
                                                @error('password_confirm')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                            <button type="button" class="btn btn-primary prev-step">Voltar</button>
                                            <button type="submit" class="btn btn-success">Enviar</button>
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
                                                    <input type="text" name="name" class="form-control form-control-lg" placeholder="Nome completo" required value="{{ old('name') }}">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="input-group">
                                                    <span class="btn btn-primary d-flex align-items-center justify-content-center">
                                                        <i class="fa-solid fa-id-card"></i>
                                                    </span>
                                                    <input type="text" name="cpf" class="form-control form-control-lg" placeholder="Digite seu CPF" required value="{{ old('cpf') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div id="template-juridica" class="d-none">
                                            <div class="mb-3 mt-3">
                                                <div class="input-group">
                                                    <span class="btn btn-primary d-flex align-items-center justify-content-center">
                                                        <i class="fa-solid fa-building"></i>
                                                    </span>
                                                    <input type="text" name="razao_social" class="form-control form-control-lg" placeholder="Razão Social" required value="{{ old('razao_social') }}">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="input-group">
                                                    <span class="btn btn-primary d-flex align-items-center justify-content-center">
                                                        <i class="fa-solid fa-id-card"></i>
                                                    </span>
                                                    <input type="text" name="cnpj" class="form-control form-control-lg" placeholder="CNPJ" required value="{{ old('cnpj') }}">
                                                </div>
                                            </div>
                                        </div>

                                </div>
                            </div>
                        </div>

					</div>
				</div>
			</div>
		</div>
	</main>

	<svg width="0" height="0" style="position:absolute">
		<defs>
			<symbol viewBox="0 0 512 512" id="ion-ios-pulse-strong">
				<path
					d="M448 273.001c-21.27 0-39.296 13.999-45.596 32.999h-38.857l-28.361-85.417a15.999 15.999 0 0 0-15.183-10.956c-.112 0-.224 0-.335.004a15.997 15.997 0 0 0-15.049 11.588l-44.484 155.262-52.353-314.108C206.535 54.893 200.333 48 192 48s-13.693 5.776-15.525 13.135L115.496 306H16v31.999h112c7.348 0 13.75-5.003 15.525-12.134l45.368-182.177 51.324 307.94c1.229 7.377 7.397 11.92 14.864 12.344.308.018.614.028.919.028 7.097 0 13.406-3.701 15.381-10.594l49.744-173.617 15.689 47.252A16.001 16.001 0 0 0 352 337.999h51.108C409.973 355.999 427.477 369 448 369c26.511 0 48-22.492 48-49 0-26.509-21.489-46.999-48-46.999z">
				</path>
			</symbol>
		</defs>
	</svg>
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
            $(`.step-${step}:visible input, .step-${step}:visible select`).each(function () {
                if ($(this).prop('required') && $(this).val().trim() === '') {
                    $(this).addClass('is-invalid');
                    valid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            return valid;
        }

        $(document).ready(function () {
            $('.step').hide();
            $('.step-1').show();
            updateProgressBar(currentStep);

            // tipo change
            $('#tipo').on('change', function () {
                tipoSelecionado = $(this).val();
            });

            $('.next-step').click(function () {
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

            $('.prev-step').click(function () {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });
        });

        $(document).ready(function () {
          
            $(document).on('input', 'input[name="name"]', function () {
                const onlyLetters = $(this).val().replace(/[^A-Za-zÀ-ÿ ]+/g, '');
                $(this).val(onlyLetters);
            });

            $(document).on('input', 'input[name="razao_social"]', function () {
                const onlyLetters = $(this).val().replace(/[^A-Za-zÀ-ÿ0-9 ]+/g, '');
                $(this).val(onlyLetters);
            });

            $(document).on('focus', 'input[name="cpf"]', function () {
                $(this).mask('000.000.000-00', { reverse: true });
            });

            $(document).on('focus', 'input[name="cnpj"]', function () {
                $(this).mask('00.000.000/0000-00', { reverse: true });
            });

            // Máscara para telefone
            $('input[name="phone"]').mask('(00) 00000-0000');
        });
      </script>
      
	<script src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>
