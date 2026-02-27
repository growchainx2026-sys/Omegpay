@php
    use App\Models\Setting;
    $setting = Setting::first();
@endphp

@props(['produto' => null, 
        'colors' => [
        'area_member_color_primary' => $setting->software_color,
        'area_member_color_background' => $setting->software_color_background,
        'area_member_color_sidebar' => $setting->software_color_sidebar,
        'area_member_color_text' => $setting->software_color_text,
        'area_member_background_image' => null
    ]
])

@php

    $color = $colors['area_member_color_primary'];
    // Função para converter HEX para RGBA
    function hexToRgba($hex, $opacity = 0.5) {
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
@endphp

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <meta name="description" content="{{ $setting->software_description }}">
	<meta name="author" content="@thigasdev">
    <title>{{ $setting->software_name }} - @yield('title', 'Minha Aplicação')</title>
    <link href="{{ asset('assets/css/modern.css') }}" rel="stylesheet" >
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
    <link rel="icon" href="/storage/{{ $setting->favicon_light }}"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />
 
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js" integrity="sha512-F5Ul1uuyFlGnIT1dk2c4kB4DBdi5wnBJjVhL7gQlGh46Xn0VhvD8kgxLtjdZ5YN83gybk/aASUAlpdoWUjRR3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/brands.min.css" integrity="sha512-bSncow0ApIhONbz+pNI52n0trz5fMWbgteHsonaPk42JbunIeM9ee+zTYAUP1eLPky5wP0XZ7MSLAPxKkwnlzw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=dashboard" />
   <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
  <style> 
		:root {
            --gateway-primary-color: {{ $colors['area_member_color_primary'] }};
            --gateway-background-color: {{ $colors['area_member_color_background'] }};
            --gateway-sidebar-color: {{ $colors['area_member_color_sidebar'] }};
            --gateway-text-color: {{ $colors['area_member_color_text'] }};
            --gateway-primary-opacity:{{$opacityColor}};
            --gateway-primary-opacity2:{{$opacityColor2}};
            --gateway-logo: {{ $setting->software_logo }};
            --bs-btn-close-color: {{ $colors['area_member_color_text'] }};
            --bs-secondary-color: {{ $colors['area_member_color_text'] }};
            --bs-btn-close-color: {{ $colors['area_member_color_text'] }} !important;
        }

        html, body {
            font-family: 'Inter', 'Open Sans';
        overflow-y: hidden; /* ou overflow-y: hidden */
        height: 100%;
        }

        body {
         margin: 0 !important;
         padding: 0 !important;
         overflow: hidden !important;
         
        }

        /* WebKit: Chrome, Edge, Safari, Opera */
        ::-webkit-scrollbar {
        width: 6px;         /* largura vertical */
        height: 6px;        /* altura horizontal */
        }

        ::-webkit-scrollbar-track {
        background: transparent;
        }

        ::-webkit-scrollbar-thumb {
        background-color: var(--gateway-primary-color);   /* cor da barra */
        border-radius: 10px;
        border: 1px solid transparent;
        }

        /* Firefox */
        * {
        scrollbar-width: auto;          /* "auto" ou "thin" */
        scrollbar-color: var(--gateway-primary-color) transparent; /* cor da barra e do fundo */
        }

        /* modais usam controle global de z-index no CSS principal */

        
        table.dataTable tbody tr {
            border-bottom: none !important;
        }

        table.dataTable {
            border-collapse: collapse !important;
        }

        .sidebar-link span,
        .sidebar-link i {
            font-size: 18px !important;
            color: var(--gateway-text-color) !important;
        }
     .header {
     	background: transparent !important;
     }
     
     .wrapper {
     	background-color: var(--gateway-background-color) !important;
        @if(!empty($colors['area_member_background_image']))
            background-image: url("{{ asset('storage/'.$colors['area_member_background_image']) }}");
            background-repeat: no-repeat !important;
            background-size: cover !important;
            background-position: center center !important;
        @endif
     }
     
     
     
     .card {
      	border-radius: 0px !important; 
     }
     
        .wrapper:before {
            background: transparent !important;
        }
        .card,
        .navbar .sidebar-brand, 
        .sidebar-brand:hover,
        .offcanvas {
            background-color: var(--gateway-sidebar-color) !important;
            color: var(--gateway-text-color) !important;
        }


        .sidebar-brand.text-center {
            text-align: start !important;
        }

        .file-info,
        .bg-primary-dark,
        .badge.text-bg-dark {
            background: var(--gateway-primary-color) !important;
            color: white !important;
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
        .fas,
        .fa,
        .fa-solid,
        .text-primary,
        .text-primary:after,
         .card-title,
         .sidebar-user small {
            color: var(--gateway-primary-color) !important;
        }

        button fa,
        button .fas,
        button .fa-solid {
            color: white !important;
        }

        .nav-link fa,
        .nav-link .fas,
        .nav-link .fa-solid {
            color: white !important;
        }

        .dropdown-item fa,
        .dropdown-item .fas,
        .dropdown-item .fa-solid,
        .sidebar-user .text-primary {
            color: var(--gateway-primary-color) !important;
        }
        .sidebar-user small {
            margin-top: -10px !important;
        }

        body {
			opacity: 0;
		}
        .badge-icon-info {
            border-radius: 8px;
            color:rgb(0, 80, 126);
            padding: 2px;
            font-size: 28px;
        }
        .badge-icon-success {
            border-radius: 8px;
            color:rgb(4, 126, 0);
            padding: 2px;
            font-size: 28px;
        }
        .badge-icon-danger {
            border-radius: 8px;
            color:rgb(126, 19, 0);
            padding: 2px;
            font-size: 28px;
        }

        .badge-icon-warning {
            border-radius: 8px;
            color:rgb(126, 124, 0);
            padding: 2px;
            font-size: 28px;
        }

        .item-trasanctions {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            position: relative;
            max-width: 100%;
            height: 100%;
        }
        .item-trasanctions i {
            position: absolute;
            top: 33%;
            left: 0;
            margin-right: 10px;
            font-size: 28px;
        }
        .item-trasanctions span {
            width: 100px;
            text-align: start;
        }
        .time {
            padding: 4px 12px;
            color: #f7f7f7;
            font-size: 1.6em;
            font-weight: 300;
            border-radius: 4px;
        }
    .header-title,
    .header-subtitle,
    .nav-item .nav-link i {
     	color: gray !important;
    }
    
    .pendente {
        font-weight: 700 !important;
        min-width: 24.8px !important;
        width: 100% !important;
        color:rgb(0, 0, 0) !important;
        background:rgb(255, 102, 0) !important;
        border-radius: 20px !important;
        border-color: transparent !important;
    }
    .btn-outline-success,
    .pago {
        font-weight: 700 !important;
        min-width: 24.8px !important;
        width: 100% !important;
        color: rgb(1, 37, 15) !important;
        background:rgb(0, 175, 67) !important;
        border-radius: 20px !important;
        border-color: transparent !important;
    }
    .cancelado {
        font-weight: 700 !important;
        min-width: 24.8px !important;
        width: 100% !important;
        color:rgb(0, 0, 0) !important;
        background:rgb(255, 0, 0) !important;
        border-radius: 20px !important;
        border-color: transparent !important;
    }
    .padrao{
        font-weight: 700 !important;
        min-width: 24.8px !important;
        width: 100% !important;
       color:rgb(0, 0, 0) !important;
        background:rgb(197, 197, 197) !important;
        border-radius: 20px !important;
        border-color: transparent !important;
    }

    .swiper-pagination-bullet {
        background-color: var(--gateway-primary-color) !important;
    }

    .button-more {
        border-radius: 1px !important;
        color: var(--gateway-primary-color) !important;
        border-color: var(--gateway-primary-color) !important;
        padding-left: 12px !important;
        padding-top: 6px !important;
        padding-bottom: 6px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 20px !important;
    }

    [name="periodo"] {
        border-color: var(--gateway-primary-color) !important;
    }
    .button-more i {
        color: var(--gateway-primary-color) !important;
    }
    .button-more:hover,
    .button-more:active,
    .button-more:focus {
        background: transparent !important;
    }
     
     .main .navbar .menu-hamburguer,
     .main .navbar .menu-hamburguer:after,
     .main .navbar .menu-hamburguer:before {
     	display: none !important;
     }
     
     @media screen and (max-width: 991px) {
       .main .navbar .menu-hamburguer,
       .main .navbar .menu-hamburguer:after,
       .main .navbar .menu-hamburguer:before {
          display: block !important;
       }
       
     }
     
     /* .sidebar {
     	border-right: 1px dashed var(--gateway-primary-color) !important;
     } */
     
     .content {
      	margin-top: 20px !important; 
     }

     .icone-card {
        display:flex;
        align-items:center;
        justify-content:center;
        background: var(--gateway-opacity) !important;
        border-radius: 10px !important;
        min-width: 40px !important;
        min-height: 40px !important;
        width: 40px !important;
        height: 40px !important;
     }

     .icone-card i {
        font-size: 20px !important;
     }

    #graficoDepositosSaques {
        width: 100% !important;
        height: 100% !important;
        max-width: 100%;
        max-height: 250px;
        box-sizing: border-box;
    }
    
    .velocimetro {
        position:relative;
        min-height:221px
    }
    
    .grafico-depositos {
        max-width: 98%;
        position:relative;
    }

    @media screen and (max-width: 991px) {
        .chart-sm {
            height: 250px; /* ou ajuste conforme seu layout */
        }

        #graficoDepositosSaques {
            min-height: 250px !important;
            max-height: 100% !important;
        }

        .velocimetro {
            max-width: 98%;
            position:relative;
            min-height:320px
        }

        .grafico-depositos {
            max-width: 98%;
            min-height: 300px;
            position:relative;
        }
    }
    
    @media screen and (max-width: 540px) {
        .chart-sm {
            height: 250px; /* ou ajuste conforme seu layout */
        }

        #graficoDepositosSaques {
            max-height: 100% !important;
        }

        .velocimetro {
            position:relative;
            min-height:280px
        }

        .grafico-depositos {
            max-width: 98%;
            min-height: 300px;
            position:relative;
        }

        
    }

    .apexcharts-svg,
    .apexcharts-radialbar-area {
        width: 100% !important;
    }

   .sidebar {
        max-width: 18rem !important;
        min-width: 15rem !important;
        width: 100% !important;
        height: 100vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        background-color: var(--gateway-sidebar-color) !important;
        
    }
    .sidebar-nav,
    .sidebar:hover {
        background-color: var(--gateway-sidebar-color) !important;
        
    }

    .sidebar-content {
        flex-grow: 1;
        overflow-y: auto;
        padding-bottom: 50px; /* espaço extra para evitar sobreposição com footer */
        background-color: var(--gateway-sidebar-color) !important;
        
    }

    .sidebar-footer {
        background-color: var(--gateway-sidebar-color) !important;
        position: sticky;
        bottom: 40px;
        z-index: 10;
    }
    .avatar-container {
        position: relative;
        width: 80px;
        height: 80px;
    }

    .avatar-container:hover .edit-overlay {
        opacity: 1;
        pointer-events: auto;
    }

    .edit-overlay {
        position: absolute;
        bottom: 0;
        height: 25%;
        width: 100%;
        background: rgba(0, 0, 0, 0.6);
        clip-path: ellipse(100% 80% at 50% 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        justify-content: center;
        align-items: center;
        pointer-events: none;
    }

    main.content {
        padding-left: 7rem !important;
        padding-right: 7rem !important;
    }

    @media screen and (max-width: 1291px) {
        main.content {
        padding-left: 5rem !important;
        padding-right: 5rem !important;
    }
    }

    @media screen and (max-width: 1190px) {
        main.content {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
    }

    .text-wrap-ellipsis {
    display: -webkit-box;
    -webkit-line-clamp: 1;       /* Número de linhas desejadas */
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 14px;
}

    .text-wrap-ellipsis2 {
        display: -webkit-box;
        -webkit-line-clamp: 1;       /* Número de linhas desejadas */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
        .close {
            color: var(--gateway-text-color) !important;
            stroke: var(--gateway-text-color) !important;
        }
    
        select,
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

        .btn-secondary {
            border-color:rgb(71, 71, 71) !important;
            background:transparent !important;
            color:white !important;
        }

        .btn-secondary:hover {
            border-color:rgb(71, 71, 71) !important;
            background:rgb(71, 71, 71) !important;
            color:white !important;
        }
        .btn-outline-secondary {
            border-color:rgb(71, 71, 71) !important;
            background-color: transparent !important;
            color:white !important;
        }

        .btn-outline-secondary:hover {
            border-color:rgb(71, 71, 71) !important;
            background:rgb(71, 71, 71) !important;
            color:white !important;
        }
        .btn-close {
            --bs-btn-close-color: var(--gateway-text-color) !important;
            --bs-btn-close-bg: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='var(--gateway-text-color)' d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414'/%3E%3C/svg%3E") !important;
        }

        
        .btn {
            border-radius: 10px !important;
        }

        .btn-group > .btn {
            border-radius: 0 !important;
        }
        
        .btn-group > .btn:first-of-type {
            border-top-left-radius: 10px !important;
            border-bottom-left-radius: 10px !important;
        }

        .btn-group > .btn:last-of-type {
            border-top-right-radius: 10px !important;
            border-bottom-right-radius: 10px !important;
        }

        .header-title,
     .header-subtitle,
     .dropdown-toggle i {
     	color: gray !important;
     }
     
     
     .sidebar-brand, 
     .sidebar-brand:hover,
     .dropdown-menu {
            background: var(--gateway-sidebar-color) !important;
            color: var var(--gateway-text-color) !important;
        }

        

        select:valid,
        .form-select:valid,
        .form-select:selected,
        select:selected {
            color: var var(--gateway-text-color) !important;
        }


        td,
        th,
        input,
        select,
        textarea,
        label,
        .form-floating > label,
        .form-floating > label:after,
        .form-control {
            background: transparent !important;
            background-color: transparent !important;
        }

        .header > .header-title,
        .sidebar-link,
        .dropdown-item,
        .dropdown-menu > .dropdown-item {
            color: var(--gateway-text-color) !important;
        }

        .sidebar-link:hover,
        .sidebar-item.active > .sidebar-link,
        .select option {
            background: var(--gateway-background-color) !important;
            color: var(--gateway-text-color) !important;
        } 

        .swal2-popup,
        .sidebar-dropdown > .sidebar-item > .sidebar-link,
        .modal-content,
        [data-bs-dismiss="modal"] {
            background-color: var(--gateway-sidebar-color) !important;
            color: var(--gateway-text-color) !important;
        } 

        .sidebar-dropdown > .sidebar-item > .sidebar-link:hover,
        .dropdown-item:hover {
            background-color: var(--gateway-background-color) !important;
            color: var(--gateway-text-color) !important;
        }

        
        span#tokenText,
        span#secretText {
            color: white !important;
        }

        .btn-success {
            color: white !important;
        }
        
        .paginate_button {
            border-radius: 100px !important;
            border-color: var(--gateway-primary-color) !important;
        }
        
        .paginate_button:hover {
            background: var(--gateway-primary-color) !important;
            color: white !important;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            color: white !important;
        }

        .paginate_button.next.disabled:hover,
        .paginate_button.previous.disabled:hover {
            background: transparent !important;
        } 

        .item-conversao {
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gateway-opacity);
            padding: 10px;
            border-radius: 100px;
        }
        .item-conversao i {
            color: var(--gateway-primary-color);
        }
        .progress {
            height: 10px !important;
            background: var(--gateway-opacity2) !important;
        }
        .progress-bar {
            background: var(--gateway-primary-color) !important;
        }

        .icon-circle {
            width: 40px !important;
            height: 40px !important;
            border-radius: 20% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center  !important;
            color: #fff !important;
        }
        .text-success-light {
            color: #45c15f !important;
        }
        .text-danger-light {
            color: #e15757 !important;
        }

       .balance-card {
    position: relative;
    background-color: var(--gateway-primary-color) !important;
    background: linear-gradient(145deg,rgba(255, 255, 255, 0) 0%, rgba(0, 0, 0, 0.2) 52%);
    color: white;
    border-radius: 1rem;
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    min-height: 100px;
    overflow: hidden;
}

/* Imagem de fundo via ::after */
.balance-card::after {
    content: "";
    position: absolute;
    bottom: 0;
    right: 0;
    width: 140px;
    height: 140px;
    background-image: url(@json('/storage/' . $setting->favicon_light));
    background-size: contain;
    background-repeat: no-repeat;
    background-position: left bottom;
    opacity: 0.05; /* transparência */
    filter: brightness(0) invert(1);
    pointer-events: none; /* permite clicar nos conteúdos acima */
    z-index: 0;
}

/* Garante que o conteúdo fique acima da imagem */
.balance-card > * {
    position: relative;
    z-index: 1;
}
        .balance-value {
        font-size: 2rem;
        font-weight: bold;
        }

        .balance-change {
        font-size: 0.9rem;
        color: #52d56b; /* verde claro */
        }

        .btn-green {
            cursor: pointer !important;
            background-color: #00cc66 !important;
            color: white !important;
            border: none !important;
        }

        .btn-green:hover {
            cursor: pointer !important;
            background-color: #00b358 !important;
        }

        .btn-dark-blue {
            cursor: pointer !important;
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: white !important;
            border: none !important;
        }

        .btn-dark-blue:hover {
            background-color: rgba(255, 255, 255, 0.2) !important;
        }

        .btn-outline-success i {
            color: var(--gateway-primary-color) !important;
        }
        
        .btn-outline-success:hover i,
        .btn-outline-success:hover {
            background-color: var(--gateway-primary-color) !important;
            color: white !important;
        }

        .btn-secondary {
            border-color: rgb(59, 59, 59) !important;
            color: rgb(59, 59, 59) !important;
        }

        .btn-secondary:hover {
            border-color: rgb(59, 59, 59) !important;
            background: rgb(59, 59, 59) !important;
            color: white !important;
        }

        .btn-check:checked + .btn-outline-success {
            background-color: var(--gateway-primary-color) !important;
            color: white !important;
            border-color: var(--gateway-primary-color) !important;
        }

        .card-metric {
      border-radius: 1rem;
      padding: 1.5rem;
      height: 100%;
    }
    .card-value {
      font-size: 1.5rem;
      font-weight: 600;
    }
    .card-change {
      font-size: 0.875rem;
    }
    .card-icon {
      font-size: 1.2rem;
      margin-right: 0.5rem;
    }
    .card-success {
      color: #28a745;
    }
    .card-danger {
      color: #dc3545;
    }
    .recent-activity .amount {
      font-weight: 600;
    }
    .card-visa {
      background: linear-gradient(to right, #003366, #004080);
      border-radius: 1rem;
      padding: 1.5rem;
      color: white;
      font-size: 1.2rem;
      height: 80%;
    }

    .activity-icon {
      width: 36px;
      height: 36px;
      min-width: 36px;
      min-height: 36px;
      max-width: 36px;
      max-height: 36px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      margin-right: 10px;
    }

    .activity-add {
      background-color: #dcfce7;
      color: #22c55e;
    }

    .activity-send {
      background-color: #e0f2fe;
      color: #0ea5e9;
    }

    .status-success {
      background-color: #dcfce7;
      color: #15803d;
      font-size: 0.875rem;
      padding: 2px 8px;
      border-radius: 0.375rem;
    }

    .status-pending {
      background-color: #f1f5f9;
      color: #64748b;
      font-size: 0.875rem;
      padding: 2px 8px;
      border-radius: 0.375rem;
    }

    .table-borderless tbody tr {
      border-bottom: 1px solid #e9ecef;
    }

    .btn-sm i {
      margin-right: 4px;
    }
    
    .navbar {
        max-height: 60px !important;
        height:60px !important;
    }

    .nav-logo-mobile {
        display: none !important;
    }
    .navbar {
        display: flex !important;
    }

    main.content {
        margin-top: 0px !important;
        padding-top: 10px !important;
    } 

    .arrow-1 {
        position: absolute;
        top:20px;
        right:10px;
    }

    .arrow-2 {
        position: absolute;
        top:28px;
        right:10px;
    }

    .tag-aluno {
        display: none !important;
    }

    @media screen and (max-width: 991px) {
        .sidebar:not(.toggled) {
            margin-left: -18rem !important;
        }
        
        .sidebar.toggled {
            margin-left: 0 !important;
            max-width: 12rem !important;
        }

        .tag-aluno {
            display: block !important;
        }
        .sidebar-brand {
            display: none !important;
        }

        .nav-logo-mobile {
            display: block !important;
        }
    }

    @media screen and (max-width: 540px) {
        

        
        .arrow-1,
        .arrow-2 {
            right: 25px;
        }
        .arrow-1 {
            top: 38px
        }
        .arrow-2 {
            top: 46px
        }
        .navbar {
            display: flex !important;
        }
       
        .navbar {
            max-height: 50px !important;
            height: 50px !important;
            margin: 0 !important;
        } 
        
        
        
    }
    @media (max-width: 576px) {
    .table-dash-transactions td {
        white-space: nowrap;
    }
    .table-dash-transactions td .fw-bold {
        font-size: 14px;
    }
    .table-dash-transactions td small {
        font-size: 12px;
    }

    }
    .dropup .dropdown-toggle::after {
        display: none !important;
    }

    .nav-config-taxas {
        --bs-nav-pills-link-active-bg: var(--gateway-primary-color) !important;
        --bs-nav-pills-link-active-color: white !important;
        --bs-nav-link-color: white !important;
        --bs-nav-link-hover-color: white !important;
    }
    .nav-config-taxas .nav-item button {
        border-radius: 15px !important;
    }
    .nav-config-taxas .nav-item button.active {
        color: white !important;
    }
    .nav-config-taxas .nav-item button:hover {
        background: var(--gateway-opacity) !important;
    }

     .table-config-taxas,
    .table-config-taxas th,
    .table-config-taxas td {
        border: none !important;
    }

   .sidebar-link {
    display: flex !important;
    justify-content: flex-start !important;
    gap: 8px !important;
   }

   .sidebar-link span {
    text-align: start !important;
   }
   .sidebar-link i {
    text-align: start !important;
    height: auto !important;
    width: 10px !important;
   }
   .card-dash {
      border-radius: 0.75rem !important;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
      border: 1px solid rgba(0, 0, 0, 0.21) !important;
    }
    .icon-eye {
       width: 20px !important;
      position: absolute;
      top: 1rem;
      right: 1rem;
      cursor: pointer;
      opacity: 0.5;
      color: rgba(39, 39, 39, 1) !important;
    }
    .icon-eye:hover {
      opacity: 1;
    }
    .wrapper::before{
        display: none !important;
    }
    .card-title,
    .valor-visivel {
        color: #111827 !important;
    }
    
    .header-title,
    .sidebar-dropdown .sidebar-item a.sidebar-link,
    .sidebar-link span {
        color: var(--gateway-text-color) !important;
    }
    .sidebar-link svg {
        stroke: rgb(156 163 175 / var(--tw-text-opacity, 1)) !important;
    }
    .sidebar-link .sidebar-item a.sidebar-link {
        font-size: .875rem !important;
        color: var(--gateway-text-color) !important;
    }
    /*nav#sidebar {
        border-right: 1px solid #9d9fa356 !important;
    } */
    
    .lucide.eye-toggle {
       width: 20px !important;
         stroke: #a5aab1 !important;
    }
    .table tbody, .table td, .table tfoot,  .table thead, .table tr {
        border-color: transparent !important;
    }
    .lucide {
        stroke: #535d6a !important;
    }
    small.text-muted {
        font-size: .875rem !important;
        color: #78808a !important;
    }
    [name="periodo"] {
        border-radius: 5px !important;
        border-color: #a5aab1 !important;
    }

    [name="periodo"]:active {
        border-color: #a5aab1 !important;
        box-shadow: none !important;
    }

    .sidebar .sidebar-nav .sidebar-item .sidebar-link span {
    color: var(--gateway-text-color) !important;
    }

    .sidebar-item > .sidebar-link {
        border-radius: 10px !important;
    }
    .sidebar-item.active > .sidebar-link,
    .sidebar-item:hover > .sidebar-link {
        background:  var(--gateway-opacity2) !important;
    }
    .sidebar-item.active > .sidebar-link span.align-middle {
        color: var(--gateway-primary-color) !important;
    }
    .sidebar-item.active > .sidebar-link svg {
        stroke: var(--gateway-primary-color) !important;
    }

    .sidebar-item:hover > .sidebar-link svg {
        stroke: var(--gateway-primary-color) !important;
    }

    .sidebar-item:hover > .sidebar-link span.align-middle {
        color: var(--gateway-primary-color) !important;
    }
     .card {
        border: 1px solid #a5aab1 !important;
     	box-shadow: none !important;
     }
     .nav-tabs {
        --bs-nav-tabs-border-width: 0 !important;
        --bs-nav-tabs-link-active-bg: var(--gateway-primary-color) !important;
        --bs-nav-tabs-link-active-color: white !important;
        padding: 0 !important;
        margin: 0 !important;
        margin-bottom: -10px !important;
     }
     .nav {
        --bs-nav-link-color: var(--gateway-primary-color) !important;
        --bs-nav-link-hover-color: var(--gateway-primary-color) !important;
     }
     .nav-tabs .nav-link {
        border-radius: 10px !important;
        padding: 10px 20px !important;
     }
     .fa-brands {
        color: var(--gateway-primary-color) !important;
     }
     .badge.bg-primary {
        background-color: var(--gateway-primary-color) !important;
     }

     .form-switch .form-check-input {
            bs-form-switch-bg: var(--gateway-primary-color) !important;
            background-image: var(--bs-form-switch-bg) !important;
        }

        a {
            text-decoration: none !important;
        }
        .dropdown-item:hover {
            background: var(--gateway-primary-opacity2) !important;
        }
        .table-responsive {
            overflow-x: hidden !important;
        }
        .navbar {
            z-index: 2 !important;
            /* box-shadow: 4px 1px 10px rgba(0, 0, 0, 0.1) !important; */
        }
        .aumentar {
            transition: all ease 0.5s !important;
        }
        .aumentar:hover {
            transform: scale(1.05) !important;
            box-shadow: 0 0 5px var(--gateway-primary-opacity) !important;
        }
        .girar {
            transition: all ease 0.5s !important;
            cursor: pointer !important;
        }

        .aumentar:hover .girar,
        .girar:hover {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        .produto {
            border-radius: 0px !important;
            max-height: 450px !important;
            min-height: 450px !important;
        }

        .produto .card-body .card-link {
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            position: absolute;
            bottom: 8px;
            margin-left: -14px !important;
            width:96%;
        }

        .produto .card-body .card-link .btn {
            border-radius: 0 !important;
        }

        .produto img {
            max-height: 300px !important;
            min-height: 300px !important;
            object-fit: cover !important;
        }

        .descricao {
            display: -webkit-box;
            -webkit-line-clamp: 4; /* número máximo de linhas */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nav-category {
            width: 100%;
        }

    p,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        span,
        td,
        th,
        li,
        button,
        input,
        select,
        textarea,
        label,
        small,
        .dropdown-menu, 
        .dropdown-item,
        .text-muted,
        .card-title,
        .text-gateway,
        .form-floating > label,
        .form-control,
        .form-control > label,
        .text-gateway,
        .menu-hamburger > i,
        .menu-hamburger > i:after,
        .menu-hamburger > i:before,
        .badge,
        .card .card-body .text-gateway {
            color: var(--gateway-text-color) !important;
        }

        .color-box {
            color: var(--gateway-primary-color) !important;
        }

        .page-content-fade main.content {
            animation: pageFadeIn 0.35s ease forwards;
        }
        @keyframes pageFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
	</style>
	<script src="{{ asset('assets/js/settings.js') }}"></script>

</head>
<body >
	<div class="wrapper page-content-fade" style="height:100vh;position:relative;">
        @include('partials.sidebar-aluno', ['produto' => $produto])

        <div class="main">
            @include('partials.navbar-aluno', ['produto' => $produto])
            <main class="content" style="height:100vh;overflow-x:hidden;overflow-y:auto;">
                <div class="container-fluid pt-3 relative">
                    @yield('content')
                </div>
            </main>
        </div>
   {{--      @include('partials.footer') --}}
    </div>
    <svg width="0" height="0" style="position:absolute">
		<defs>
			<symbol viewBox="0 0 512 512" id="ion-ios-pulse-strong">
				<path
					d="M448 273.001c-21.27 0-39.296 13.999-45.596 32.999h-38.857l-28.361-85.417a15.999 15.999 0 0 0-15.183-10.956c-.112 0-.224 0-.335.004a15.997 15.997 0 0 0-15.049 11.588l-44.484 155.262-52.353-314.108C206.535 54.893 200.333 48 192 48s-13.693 5.776-15.525 13.135L115.496 306H16v31.999h112c7.348 0 13.75-5.003 15.525-12.134l45.368-182.177 51.324 307.94c1.229 7.377 7.397 11.92 14.864 12.344.308.018.614.028.919.028 7.097 0 13.406-3.701 15.381-10.594l49.744-173.617 15.689 47.252A16.001 16.001 0 0 0 352 337.999h51.108C409.973 355.999 427.477 369 448 369c26.511 0 48-22.492 48-49 0-26.509-21.489-46.999-48-46.999z">
				</path>
			</symbol>
		</defs>
	</svg>
    <script src="{{ asset('assets/js/app.js') }}"></script>
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
                position: 'top-right',
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
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Captura a variável CSS --gateway-primary-color
  const rootStyles = getComputedStyle(document.documentElement);
  const primaryColor = rootStyles.getPropertyValue('--gateway-primary-color').trim();

  // Função para converter HEX para RGBA
  function hexToRgba2(hex, alpha = 0.5) {
    // Remove "#" se existir
    hex = hex.replace('#', '');

    // Expande hex curto (ex: #08f)
    if (hex.length === 3) {
      hex = hex.split('').map(c => c + c).join('');
    }

    const r = parseInt(hex.substring(0, 2), 16);
    const g = parseInt(hex.substring(2, 4), 16);
    const b = parseInt(hex.substring(4, 6), 16);

    return `rgba(${r}, ${g}, ${b}, ${alpha})`;
  }

  // Converte e adiciona nova variável CSS
  const rgbaColor = hexToRgba2(primaryColor, 0.3);
  document.documentElement.style.setProperty('--gateway-opacity', rgbaColor);

  const rgbaColor2 = hexToRgba2(primaryColor, 0.1);
  document.documentElement.style.setProperty('--gateway-opacity2', rgbaColor2);
});
</script>

</body>
</html>
