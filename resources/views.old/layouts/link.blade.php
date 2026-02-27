@php
    use App\Models\Setting;

    $setting = Setting::first();
    $color = $link->user->software_color ?? $setting->software_color;
    // Função para converter HEX para RGBA
    function hexToRgba($hex, $opacity = 0.5)
    {
        $hex = str_replace('#', '', $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
            $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
            $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }

        return "rgba($r, $g, $b, $opacity)";
    }

    $opacityColor = Str::contains($color, 'rgba')
        ? preg_replace('/rgba\((\d+),\s*(\d+),\s*(\d+),\s*[\d.]+\)/', 'rgba($1, $2, $3, 0.8)', $color)
        : hexToRgba($color, 0.8);

    $opacityColor2 = Str::contains($color, 'rgba')
        ? preg_replace('/rgba\((\d+),\s*(\d+),\s*(\d+),\s*[\d.]+\)/', 'rgba($1, $2, $3, 0.1)', $color)
        : hexToRgba($color, 0.1);

    $isUser = $link->user->permission == 'user';
@endphp

@php

@endphp

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="Link de pagamento para recebido. Você pode pagar utilizando os meios {{ implode(',', $link->meios) }}">
    <meta name="author" content="https://t.me/thigasdev">
    <title>{{ $setting->software_name }} - @yield('title', 'Minha Aplicação')</title>
    <link rel="icon" href="/storage/{{ $link->user->favicon_light }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --gateway-primary-color:
                {{ $isUser ? $link->user->software_color : $setting->software_color ?? '#008f39' }}
            ;
            --gateway-background-color:
                {{ $isUser ? $link->user->software_color_background : $setting->software_color_background }}
            ;
            --gateway-sidebar-color:
                {{ $isUser ? $link->user->software_color_sidebar : $setting->software_color_sidebar }}
            ;
            --gateway-text-color:
                {{ $isUser ? $link->user->software_color_text : $setting->software_color_text }}
            ;
            --gateway-primary-opacity:
                {{$opacityColor}}
            ;
            --gateway-primary-opacity2:
                {{$opacityColor2}}
            ;
            --gateway-logo:
                {{ $isUser ? $link->user->software_logo : $setting->software_logo }}
            ;
            --bs-btn-close-color:
                {{ $isUser ? $link->user->software_color_text : $setting->software_color_text }}
            ;
        }

        /* Cor de fundo principal da página (clara, quase branca) */
        body {
            background-color: var(--gateway-background-color);
        }

        /* Cor amarela do Header do Mercado Pago */
        .thigasdevheader {
            background-color: var(--gateway-sidebar-color);
            /* Amarelo característico */
            height: 56px;
            /* Altura padrão para headers */
            display: flex;
            align-items: center;
        }

        /* Container principal para centralizar o card e dar o espaçamento */
        .thigasdevmain-content {
            padding-top: 50px;
            padding-bottom: 50px;
        }

        /* Card de Pagamento Central */
        .thigasdevpayment-card {
            border: none;
            /* Remove a borda padrão do card */
            box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.1);
            /* Sombra suave */
            max-width: 480px;
            /* Largura máxima para o card do formulário */
        }

        /* Estilo para o valor R$ 0,00 */
        .thigasdevvalue {
            font-size: 3rem;
            font-weight: 300;
            /* Light weight para a fonte */
            color: #333;
        }


        .btn-primary {
            background: var(--gateway-primary-color) !important;
            border-color: var(--gateway-primary-color) !important;
            color: white !important;
        }

        .btn-primary:hover {
            background: var(--gateway-primary-color) !important;
            border-color: var(--gateway-primary-color) !important;
        }

        #basic-addon1 {
            background-color: transparent !important;
            font-weight: bold !important;
            font-size: 48px !important;
            border-color: transparent !important;
            color: #636363ff !important;
        }

        .money-input {
            border-color: transparent !important;
            font-weight: bold !important;
            font-size: 48px !important;
        }

        .money-input:focus {
            box-shadow: none !important;
            outline: none !important;
        }

        .list-group-item:hover {
            background-color: var(--gateway-primary-opacity2) !important;
            color: var(--gateway-text-color) !important;
        }

        .personal-address .form-label {
            font-size: 0.9rem;
            font-weight: 500;
        }

        .personal-address input.form-control {
            border-radius: 10px;
            box-shadow: none !important;
        }

        @media (max-width: 576px) {
            .personal-address .row>div {
                margin-bottom: 0.5rem;
            }
        }

        .payment-card {
            background-color: #fff;
        }

        .payment-card:hover {
            border-color: var(--gateway-primary-opacity2);
            box-shadow: 0 0 0 0.25rem var(--gateway-primary-opacity);
        }

        .payment-card.active {
            background-color: var(--gateway-primary-opacity);
            color: #fff;
            border-color: var(--gateway-primary-color);
        }

        .payment-card.active i {
            color: #fff;
        }

        .btn-voltar {
            border-color: transparent !important;
        }

        .btn-voltar:hover {
            border-color: transparent !important;
            background: transparent !important;
            color: #333 !important;
        }
    </style>
</head>

<body>

    <header class="thigasdevheader shadow-sm sticky-top">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <span class="fs-4 fw-bold text-dark">
                    <img src="{{ asset('storage/' . $link->user->logo_light) }}" alt="{{ $link->user->software_name }}"
                        height="24">
                </span>
            </div>

            <nav class="d-flex align-items-center">
                <a href="{{ env('APP_URL') }}" target="_blank" class="btn btn-primary fw-bold me-3"
                    style="background-color: white; border-color: white;">Abrir conta grátis</a>
            </nav>
        </div>
    </header>

    <main class="thigasdevmain-content">
        <div class="container d-flex justify-content-center">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script>
        $('.money-input').inputmask('currency', {
            radixPoint: ",", // Ponto decimal brasileiro
            groupSeparator: ".", // Separador de milhar brasileiro
            allowMinus: false,
            prefix: 'R$ ',
            digits: 2,
            autoGroup: true,
            rightAlign: false
        });

        $('.cpf-input').inputmask('999.999.999-99', {
            placeholder: '_',
            clearIncomplete: true,
            showMaskOnHover: false
        });

        $('.input-estado').inputmask('AA', {
            placeholder: '_',
            clearIncomplete: true,
            showMaskOnHover: false,
            definitions: {
                'A': { validator: "[A-Za-z]", casing: "upper" } // aceita só letras e converte para maiúsculo
            }
        });

        $('.input-telefone').inputmask({
            mask: ['(99) 9999-9999', '(99) 99999-9999'], // aceita 8 ou 9 dígitos
            keepStatic: true,
            placeholder: '_',
            clearIncomplete: true,
            showMaskOnHover: false
        });
    </script>
    <script>
        lucide.createIcons();
    </script>
    <script>
        // Define a função no escopo global
        function showToast(type, message) {
            Swal.mixin({
                toast: true,
                icon: type,
                title: message,
                animation: false,
                position: 'top',
                showConfirmButton: false,
                showCloseButton: true,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            }).fire(); // 🔥 Adiciona o .fire() para exibir o toast!
        }

        // Espera o DOM carregar antes de mostrar o toast
        document.addEventListener("DOMContentLoaded", function () {
            @if (session('success'))
                showToast('success', "{{ session('success') }}");
            @endif

            @if (session('error'))
                showToast('error', "{{ session('error') }}");
            @endif

            @if (session('info'))
                showToast('info', "{{ session('info') }}");
            @endif

            @if (session('warning'))
                showToast('warning', "{{ session('warning') }}");
            @endif
        });
    </script>
</body>

</html>