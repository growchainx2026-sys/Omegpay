@php
    $checkout = \App\Models\Checkout::where('uuid', request()->segment(2))
        ->with('produto')
        ->first();
        
    if ($checkout) {
        $title = $checkout->produto->produto_name;
        $meta_description = $checkout->produto->produto_description;
        $meta_favicon = url('/storage/' . $checkout->produto->image);
       
    } else {
        $title = 'Nenhum produto selecionado';
    }

@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Vite dev scripts via ngrok -->
    {{-- Dark mode handling --}}
    <script>
        (function() {
            const appearance = '{{ $appearance ?? 'system' }}';
            if (appearance === 'system') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (prefersDark) {
                    document.documentElement.classList.add('dark');
                }
            }
        })();
    </script>

    <style>
        html {
            background-color: oklch(1 0 0);
        }

        html.dark {
            background-color: oklch(0.145 0 0);
        }
    </style>

    <title inertia>{{ $title }}</title>
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="{{ $title }}" />
    <meta property="og:description" content="{{ $meta_description }}" />
    <meta property="og:image" content="{{ $meta_favicon }}" />

    <!-- X (Twitter) -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="{{ url()->current() }}" />
    <meta property="twitter:title" content="{{ $title }}" />
    <meta property="twitter:description" content="{{ $meta_description }}" />
    <meta property="twitter:image" content="{{ $meta_favicon }}" />

    <link rel="icon" href="{{ $meta_favicon }}" sizes="any">
    <link rel="icon" href="{{ $meta_favicon }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ $meta_favicon }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">



    <!-- Meta Tags Generated with https://metatags.io -->
    <!-- @routes -->
    @viteReactRefresh
    @vite(['resources/js/app.tsx'])
    @inertiaHead

</head>

<body class="bg-light dark:bg-dark">
    @inertia
 <!-- <script disable-devtool-auto src="https://cdn.jsdelivr.net/npm/disable-devtool@latest"></script> -->
</body>
