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
    <meta name="robots" content="noindex">
    <link rel="icon" href="{{ \App\Helpers\Helper::faviconUrl($setting->favicon_light ?? null) }}" />
    <title>{{ $setting->software_name }} - Em manutenção</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #ffffff;
            color: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .maint-text {
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
        }
    </style>
</head>

<body>
    <p class="maint-text">Em manutenção</p>
</body>

</html>
