@php
use App\Helpers\Helper;
    $setting = Helper::settings();
@endphp

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $setting->software_description }}">
	<meta name="author" content="@thigasdev">
    <title>{{ $setting->software_name }} Docs - @yield('title', 'Documentação')</title>
    <link href="{{ asset('assets/css/modern.css') }}" rel="stylesheet" >
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
    <link rel="icon" href="/storage/{{ $setting->favicon_light }}"/>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js" integrity="sha512-F5Ul1uuyFlGnIT1dk2c4kB4DBdi5wnBJjVhL7gQlGh46Xn0VhvD8kgxLtjdZ5YN83gybk/aASUAlpdoWUjRR3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Head: Adiciona o CSS de highlight.js -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/default.min.css">

<!-- Antes do </body>: scripts -->
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/json.min.js"></script>
<script>hljs.highlightAll();</script>
   <style>
		:root {
            --gateway-primary-color: {{ $setting->software_color ?? '#008f39' }};
            --gateway-background-color: {{ $setting->software_color_background }};
            --gateway-sidebar-color: {{ $setting->software_color_sidebar }};
            --gateway-text-color: {{ $setting->software_color_text }};
            --gateway-primary-opacity:rgb(0, 104, 42);
            --bs-btn-close-color: {{ $setting->software_color_text }};
        }

        /* * {
            outline: 1px solid rgb(255, 0, 0);
            } */

        html, body {
        overflow-y: hidden; /* ou overflow-y: hidden */
        height: 100%;
        }

        body {
         margin: 0 !important;
         padding: 0 !important;
         overflow: hidden !important
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

        .method-method {
            background: green;
            padding: 5px;
            padding-left: 10px;
            padding-right: 10px;
            border-radius: 20px;
            margin-right: 10px;
            font-weight: bold;
        }
        .accordion {
            --bs-accordion-active-color: var(--gateway-text-color) !important;
            --bs-accordion-active-bg: var(--gateway-sidebar-color) !important;
            --bs-accordion-border-color: var(--gateway-primary-color) !important;
            --bs-accordion-border-radius: 10px !important;
        }

        .btn-dark {
            border-color: var(--gateway-primary-color) !important;
            background: var(--gateway-primary-color) !important;
            color: white !important;
        }

        .btn-dark:hover {
            background: var(--gateway-primary-color) !important;
            color: white !important;
        }

        .accordion-button:not(.collapsed),
        .accordion-header {
            border-top-left-radius: 10px !important;
            border-top-right-radius: 10px !important;
        }

        .accordion-item:first-of-type>.accordion-header,
        .accordion-item:first-of-type>.accordion-header .accordion-button {
            border-top-left-radius: 10px !important;
            border-top-right-radius: 10px !important;
        }
        .accordion-item:last-of-type>.accordion-header .accordion-button.collapsed {
            border-bottom-left-radius: 10px !important;
            border-bottom-right-radius: 10px !important;
        }
        
        .accordion-button {
            background: var(--gateway-sidebar-color) !important;
            color: var var(--gateway-text-color) !important;
        }

        table.dataTable tbody tr {
            border-bottom: none !important;
        }

        table.dataTable {
            border-collapse: collapse !important;
        }


     .header {
     	background: transparent !important;
     }
     
     .wrapper,
     pre code {
     	background-color: var(--gateway-background-color) !important;
     }
     
     .header-title,
     .header-subtitle,
     .dropdown-toggle i {
     	color: gray !important;
     }
     
     
     .sidebar-brand, 
     .sidebar-brand:hover,
     .sidebar-footer,
     .dropdown-menu,
     .accordion-header,
     .accordion-item:last-of-type {
            background: var(--gateway-sidebar-color) !important;
            color: var var(--gateway-text-color) !important;
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
        .form-floating > label,
        .form-control,
        .form-control > label,
        .text-gateway,
        .btn-outline-success,
        .btn-outline-success span,
        .accordion-button::after {
            color: var(--gateway-text-color) !important;
        }

        select:valid,
        .form-select:valid,
        .form-select:selected,
        .accordion-button:not(.collapsed)  {
            color: var(--gateway-text-color) !important;
        }

        .borda {
            padding: 15px;
            border-radius: 10px;
            border: 1px solid var(--gateway-primary-color) !important;
        }
        .nav-link.active {
            background: var(--gateway-opacity) !important;
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
     
     .card {
      	border-radius: 15px !important; 
     }
     
        .wrapper:before {
            background: transparent !important;
        }
        .card,
        .navbar .sidebar-brand, 
        .sidebar-brand:hover {
            background-color: var(--gateway-sidebar-color) !important;
            color: var(--gateway-text-color) !important;
        }
        .file-info,
        .bg-primary-dark,
        .badge.text-bg-dark {
            background: var(--gateway-primary-color) !important;
        }
        .btn-primary {
            background: var(--gateway-primary-color) !important;
            border-color: var(--gateway-primary-color) !important;
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

    .sidebar-item > .sidebar-link {
        border-left: 2px solid transparent;
    }
    .sidebar-item.active > .sidebar-link,
    .sidebar-item:hover > .sidebar-link {
        border-left: 2px solid var(--gateway-primary-color);
    }
     
    .pendente{
        font-weight: 700 !important;
        min-width: 24.8px !important;
        width: 100% !important;
        color:rgb(0, 0, 0) !important;
        background:rgb(255, 102, 0) !important;
        border-radius: 20px !important;
        border-color: transparent !important;
    }
    .pago{
        font-weight: 700 !important;
        min-width: 24.8px !important;
        width: 100% !important;
        color:rgb(0, 0, 0) !important;
        background:rgb(0, 175, 67) !important;
        border-radius: 20px !important;
        border-color: transparent !important;
    }
    .cancelado{
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
        border-radius: 5px !important;
        color: var(--gateway-primary-color) !important;
        border-color: var(--gateway-primary-color) !important;
        padding: 5px !important;
        padding-left: 10px !important;
        padding-right: 10px !important;
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
    
    .btn-close {
        fill: var(--gateway-text-color) !important;
    }
        .btn-close,
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
    .circle-green {
        width: 15px;
        height:15px;
        border-radius: 36px;
        margin-right: 10px;
        background: linear-gradient(135deg,rgba(0, 255, 81, 1) 0%, rgb(0, 167, 8) 49%);;
    }

    .circle-red {
        width: 15px;
        height:15px;
        border-radius: 36px;
        margin-right: 10px;
        background: linear-gradient(135deg,rgb(255, 0, 0) 0%, rgb(167, 0, 0) 49%);;
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

        span.text-primary {
            color: var(--gateway-primary-color) !important;
        }

        #responseTabs > li > a {
            color: var(--gateway-text-color) !important;
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
	<div class="wrapper page-content-fade" style="height:100vh;">
        @include('partials.sidebar-documentation')

        <div class="main">
            @include('partials.navbar-documentation')
            <main class="content" style="height:100vh;overflow-x:hidden;overflow-y:auto;">
                <div class="container-fluid">
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
        // Define a função no escopo global
        function showToast(type, message) {
            Swal.mixin({
                toast: true,
                icon: type,
                title: message,
                animation: false,
                position: 'top-right',
                showConfirmButton: false,
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
  function hexToRgba(hex, alpha = 0.5) {
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
  const rgbaColor = hexToRgba(primaryColor, 0.3);
  document.documentElement.style.setProperty('--gateway-opacity', rgbaColor);
});
</script>
@include('partials.modal-z-index-fix')

</body>
</html>
