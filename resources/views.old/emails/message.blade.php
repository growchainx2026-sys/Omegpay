@php
    $setting = \App\Helpers\Helper::settings();
@endphp

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>{{ $setting->software_name }} - Mensagem</title>
</head>

<body style="background-color:#eff0e8;margin:0;padding:0;font-family:Montserrat,Helvetica,Arial,sans-serif;">

    <table style="max-width:560px;background-color:white;margin:0 auto;padding:0;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
        <tbody>
            <tr>
                <td align="left" style="padding:32px;">
                    <h1 style="font-size:32px;font-weight:bold;margin-bottom:20px;">Olá, {{ $name }}!</h1>
                    <p style="font-size:16px;line-height:1.5;color:#000000;margin-bottom:20px;">
                        {!! nl2br(e($mensagem)) !!}
                    </p>
                    <p style="font-size:16px;margin-top:30px;">
                        Acesse: 
                        <a href="{{ env('APP_URL') }}/login" style="font-weight:bold;font-size:18px;">
                            {{ $setting->software_name }}
                        </a>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>

</body>
</html>
