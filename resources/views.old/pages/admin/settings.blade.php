@extends('layouts.app')

@section('title', 'Configurações')

@section('content')
    <div class="header mb-3">
        <h1 class="header-title">
            Configurações
        </h1>
    </div>
    <form class="row" method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Identidade visual</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="software_name" name="software_name"
                                    value="{{ $settings->software_name }}" placeholder="Digite o nome da plataforma">
                                <label for="software_name">Nome da plataforma</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="software_description"
                                    name="software_description" value="{{ $settings->software_description }}"
                                    placeholder="Digite uma descrição para a plataforma">
                                <label for="software_description">Descrição da plataforma</label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="phone_support" name="phone_support"
                                    value="{{ $settings->phone_support }}" placeholder="Digite o contato de suporte">
                                <label for="phone_support">Contato Suporte</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Email (SMTP)</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="mail_host" name="mail_host"
                                    value="{{ $settings->mail_host }}" placeholder="">
                                <label for="mail_host">Host</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="mail_port" name="mail_port"
                                    value="{{ $settings->mail_port }}" placeholder="">
                                <label for="mail_port">Port</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="mail_username" name="mail_username"
                                    value="{{ $settings->mail_username }}">
                                <label for="mail_username">Username</label>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="mail_password" name="mail_password"
                                    value="{{ $settings->mail_password }}">
                                <label for="mail_password">Password</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">APP (Notificações)</h5>
                    <small>Firebase Cloud Messaging (FCM)</small>
                </div>
                <div class="card-body">
                    <div class="row">
                       <div class="col-lg-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="fcm.title" name="fcm.title"
                                    value="{{ $fcm->title }}">
                                <label for="fcm.title">Titulo da notificação</label>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="fcm.body" name="fcm.body"
                                    value="{{ $fcm->body }}">
                                <label for="fcm.body">Body da notificação</label>
                            </div>
                            <small class="text-warning">Atente-se em adcionar a string: {valor} onde será exibido o valor no body</small>
                        </div>
                        @php
                            $fcm_items = [
                                "apiKey",
                                "authDomain",
                                "projectId",
                                "storageBucket",
                                "messagingSenderId",
                                "appId",
                                "measurementId",
                            ];
                        @endphp
                        <div class="col-12 mt-3">
                            <div class="row">
                                @foreach ($fcm_items as $i)
                                <div class="col-12 col-xl-6">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="{{ $i }}">{{ $i }}</span>
                                        <input type="text" class="form-control" 
                                        placeholder="{{ $i }}" aria-label="{{ $i }}" 
                                        aria-describedby="{{ $i }}" name="fcm.{{ $i }}"
                                        value="{{ $fcm->$i }}">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                         <div class="col-xl-12">
                            <label for="input-firebase-config" class="form-label">Firebase Config .json</label>
                            <input id="input-firebase-config" type="file"
                                class="filepond form-control @error('fcm.firebase_config') is-invalid @enderror" name="fcm.firebase_config" hidden
                                value="{{ $fcm->firebase_config }}" accept=".json">
                            <br />
                            <button id="bt-add-firebase-config" type="button" class="w-100 btn btn-success mb-3"
                                onclick="adcionarFirebaseConfig()">Adcionar Firebase Config</button>
                            <small style="display: none;" class="text-success">Firebase Config adcionado</small>
                            @error('cert')
                                <span style="color: red;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-end">
                            <button type="submit" class="btn btn-primary"><i
                                    class="fa-solid fa-save"></i>&nbsp;Salvar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
    <script>
        $("input[id*='taxa_cash_in'], input[id*='taxa_cash_out']").inputmask({
            alias: "decimal",
            integerDigits: 3,
            digits: 2,
            max: 100,
            allowMinus: false,
            digitsOptional: true,           // <- Torna os decimais opcionais na digitação
            placeholder: "",                // <- Campo visualmente limpo ao carregar
            radixPoint: ".",
            removeMaskOnSubmit: true,
            autoGroup: false
        });
        $("input[id*='taxa_fixa'], input[id*='baseline']").inputmask('decimal', {
            alias: 'numeric',
            groupSeparator: '',
            autoGroup: false,
            digits: 2,
            radixPoint: ".",
            digitsOptional: true,
            allowMinus: false,
            prefix: 'R$ ',
            placeholder: '',                 // <- Nada visível no campo por padrão
            removeMaskOnSubmit: true,
            unmaskAsNumber: true
        });

    </script>

    <script>
    function adcionarFirebaseConfig() {
        document.getElementById('input-firebase-config').click();
    }
    document.getElementById('input-firebase-config').addEventListener('change', function(ev) {
        ev.preventDefault();
        document.getElementById('bt-add-firebase-config').innerText = "Alterar Arquivo";
        document.querySelector('#container-btn-cert small').style.display = 'block';
    })
</script>
@endsection