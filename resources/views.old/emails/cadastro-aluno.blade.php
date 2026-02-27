@php
    $setting = \App\Helpers\Helper::settings();
@endphp

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ $setting->software_name }} - Bem vindo a area de membros</title>
</head>

<body>
    <div style="background-color:#eff0e8;margin:0!important;padding:0!important"><img alt=""
            src="https://ci3.googleusercontent.com/meips/ADKq_NY-WY85xiy7SZMiNvV1sR77u7jx8HmrMqycXzuCG__eJxPbsupNPord1B4GnIYip4jzkJswUmlvkd_09uhn8GSIJEBtfx_m7JY_vlucRo33-ExCJ566wz7i3gMhArrkIAbJ0szbv8Ma4r3dJRzx0OQBgXqvcwLdR_EjotbjK18yPelii0Fr1VQqs2mdZhbdU8-2T1CnWmtoQA_-AyOO19CYNp_l3hx9rRIE8NG9_5yDwk-duvsoH0YJm8H7rIn4-QkXiwrb-Sk4CliNCgKLOj9g0lYQSOko5-AXUfVW5VZ91Q-WAja-X8VQ6Vy5JBtoamwscd4ud5AQAEttLjugas4Sy9Wdum0vc56QW7uASFpqva0-ozvI0uJLs0jzSt0IDliniO3d2DvkhOsVzu6MN2rTdWT8eJdTTO2_dm6VmPGZOfdfD3ZdMvugRH9BT0uY0RgxB_CYmbco-1W_iG4x_e-JzMn-gQXJHmljc5VTwBir6lKtRTihwnlkT7Y0CKWz_Pd_u19zmAh3hjjHLctymIPKmIPO9-dBQMc8yDScJT1Wf6bA3eUtJ2JEd9SDL3lu_t7fkwZWGPZBxNC6kBpqdBbt-QtTpjTnuMQUiEWRAXCxF5AMDn9RcniycFkKkuaht-3-ySLXHRFCyQshQ9fX6yS_0CfNTzH5Q_03O011tVbQuVKSDklCXhKy7sYxw__qAgNMxjYQHTs6YFbi0_rGxlVMpqOrMPXkBJQwCdm5B8tlx5SF64IHMCizwvns1q9qLnpGBDkredo-kfozdLXmzU3SOLqbH9AWc_NKcPGgBMLmM4n5Re3DxiVllNyuiic65Xj0M95hhTNJuE2ZCL0JC-v5O2n-o7d6bh3sSpn1r9vf=s0-d-e1-ft#https://tracking.transacional.picpay.com/tracking/1/open/JUKxwwB6O0Rt6f9QLoOPwS2jEp3DWqnxzigw8QefHfMnBjT730HRTKN_iFcn9Gw1poY0HtpbABs0coOkVcq_I2dUCTCCaJL3fKbcKuOomk9bKpHZP5CYNILVkaVttDiWDcUFrzS-U8H7eNpcUGvJkJCV1sK9l0zIOh5upRgPs3i44HEQzS97Z0QkUI7OQb-JKeZqTzcnzZQwIjg46ajDPW74sXgoqfPQvHhyvAmu8SzJmd5o3BPm5keno6IkdOXdiqEwsKKQylREWfmwxdfIaVxbO-n-ib3ePzU2okagHTj_42pArWfmdOR76t2UcFVOaUKdnBwGVamX2xgI1a313WUDtcJ5onx39uaeIe4WeUbkbUrlihLI90pKeFbzoncyvm1UsDITDympPGG5sWAqKao6iFqGB9NHPfGrLNPkBpalLR05etrpwI4dJHe-OdVi-sDV9H7zPWQMZQh4EWNR1aq2gEhnfUbkqQcpRwoHOuA--0Bf882vVT-zviA1PAVXuZD6yzYuu-PQUtjoEboMrPJ_SWAfEEeUINBVb8PycqWoTVS-gtF9MbL_1CGBHAP4"
            width="1" height="1" class="CToWUd" data-bit="iit">
        <div style="display:none;font-size:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden">&nbsp;</div>

        <table style="max-width:560px;background-color:white" role="presentation" border="0" width="100%"
            cellspacing="0" cellpadding="0" align="center">
            <tbody>
                <tr>
                    <td align="left">
                        <p
                            style="margin:32px auto 0;display:block;line-height:1.3;color:#000000;font-family:Montserrat,Helvetica,Arial,sans-serif;font-size:16px;font-weight:300;width:90%;border-bottom:solid #e5e5e5 1px;padding-bottom:20px">
                            <span style="font-weight:bold;font-size:32px">Olá, {{ $aluno->name }}!</span>
                            <br><br>Parabéns pela compra. Segue os dados abaixo
                            com as credênciais de acesso a área de membros. <strong>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        <p
                            style="margin:32px auto 0;display:block;line-height:30px;color:#000000;font-family:Montserrat,Helvetica,Arial,sans-serif;font-size:16px;font-weight:300;width:90%;border-bottom:solid #e5e5e5 1px;padding-bottom:20px">
                            <span style="font-weight:bold;font-size:24px">Dados de acesso:</span><br>
                            Email: {{ $aluno->email }} <br>
                            Senha: {{ $senha }} <br>
                            Acesse: <a href="{{ env('APP_URL') }}/login?form=aluno">Clique aqui para acessar a área de membros</a><br>
                        </p>
                    </td>
                </tr>
                {{-- <tr>
                    <td align="center" valign="top"
                        style="width:100%;display:flex;align-items:center;justify-content:center;height:120px;background:{{ $setting->software_color }}">
                        <p style="color:#ffffff;"></p>
                    </td>
                </tr> --}}
            </tbody>
        </table>
    </div>
</body>

</html>
