@php
    $p = isset($prefix) ? $prefix : 'default';
@endphp

<div class="personal-address {{ $p }}-personal-address">
    <div class="row g-3">
        <div class="col-12 col-md-6">
            <label class="form-label">Nome completo</label>
            <input type="text" class="form-control input-name" id="{{ $p }}_name" name="{{ $p }}[name]"
                placeholder="Seu nome completo">
        </div>
        @error('name')
            <small class="text-danger">{{ $error }}</small>
        @enderror

        <div class="col-12 col-md-6">
            <label class="form-label">CPF</label>
            <input type="text" class="form-control cpf-input" id="{{ $p }}_cpf" name="{{ $p }}[cpf]"
                placeholder="000.000.000-00">
        </div>
        @error('cpf')
            <small class="text-danger">{{ $error }}</small>
        @enderror

        <div class="col-12 col-md-6">
            <label class="form-label">Email</label>
            <input type="email" class="form-control input-email" id="{{ $p }}_email" name="{{ $p }}[email]"
                placeholder="seu@email.com">
        </div>
        @error('email')
            <small class="text-danger">{{ $error }}</small>
        @enderror

        <div class="col-12 col-md-6">
            <label class="form-label">Telefone</label>
            <input type="telefone" class="form-control input-telefone" id="{{ $p }}_telefone" name="{{ $p }}[telefone]"
                placeholder="(00) 00000-0000">
        </div>
        @error('telefone')
            <small class="text-danger">{{ $error }}</small>
        @enderror

        <div class="col-12 col-md-3">
            <label class="form-label">CEP</label>
            <input type="text" class="form-control cep-input" id="{{ $p }}_cep" name="{{ $p }}[cep]"
                placeholder="00000-000">
        </div>
        @error('cep')
            <small class="text-danger">{{ $error }}</small>
        @enderror

        <div class="col-12 col-md-6">
            <label class="form-label">Endereço</label>
            <input type="text" class="form-control input-logradouro" id="{{ $p }}_logradouro"
                name="{{ $p }}[logradouro]" placeholder="Rua, Av, etc.">
        </div>
        @error('logradouro')
            <small class="text-danger">{{ $error }}</small>
        @enderror

        <div class="col-12 col-md-3">
            <label class="form-label">Número</label>
            <input type="text" class="form-control input-numero" id="{{ $p }}_numero" name="{{ $p }}[numero]"
                placeholder="123">
        </div>

        <div class="col-12 col-md-6">
            <label class="form-label">Bairro</label>
            <input type="text" class="form-control input-bairro" id="{{ $p }}_bairro" name="{{ $p }}[bairro]"
                placeholder="Bairro">
        </div>
        @error('bairro')
            <small class="text-danger">{{ $error }}</small>
        @enderror

        <div class="col-12 col-md-4">
            <label class="form-label">Cidade</label>
            <input type="text" class="form-control input-cidade" id="{{ $p }}_cidade" name="{{ $p }}[cidade]"
                placeholder="Cidade">
        </div>
        @error('cidade')
            <small class="text-danger">{{ $error }}</small>
        @enderror

        <div class="col-12 col-md-2">
            <label class="form-label">UF</label>
            <input type="text" class="form-control input-estado" id="{{ $p }}_estado" name="{{ $p }}[estado]"
                placeholder="UF">
        </div>
        @error('estado')
            <small class="text-danger">{{ $error }}</small>
        @enderror
    </div>
</div>