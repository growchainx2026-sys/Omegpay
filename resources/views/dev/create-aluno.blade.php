<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Aluno de Teste - Dev</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 500px;
            width: 100%;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .btn:active {
            transform: translateY(0);
        }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .links {
            margin-top: 20px;
            text-align: center;
        }
        .links a {
            color: #667eea;
            text-decoration: none;
            margin: 0 10px;
            font-size: 14px;
        }
        .links a:hover {
            text-decoration: underline;
        }
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎓 Criar Aluno de Teste</h1>
        <p class="subtitle">Ferramenta de desenvolvimento para criar alunos sem pagamento</p>

        @if(session('success'))
            <div class="alert alert-success">
                {!! session('success') !!}
            </div>
        @endif

        @if($errors->any())
            <div class="alert" style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="info-box">
            💡 <strong>Dica:</strong> Deixe o CPF em branco para gerar um CPF fake automaticamente. 
            Marque "Criar pedido pago" para associar um produto ao aluno.
        </div>

        <form method="POST" action="{{ route('dev.create-aluno.post') }}">
            @csrf
            
            <div class="form-group">
                <label for="name">Nome *</label>
                <input type="text" id="name" name="name" value="{{ old('name', 'Aluno Teste') }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" value="{{ old('email', 'aluno@teste.com') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Senha *</label>
                <input type="password" id="password" name="password" value="{{ old('password', '12345678') }}" required>
            </div>

            <div class="form-group">
                <label for="cpf">CPF (deixe em branco para gerar automaticamente)</label>
                <input type="text" id="cpf" name="cpf" value="{{ old('cpf') }}" placeholder="000.000.000-00">
            </div>

            <div class="form-group">
                <label for="produto_id">Produto (opcional - para associar ao pedido)</label>
                <select id="produto_id" name="produto_id">
                    <option value="">-- Selecione um produto (ou deixe em branco) --</option>
                    @foreach(\App\Models\Produto::where('status', 1)->get() as $produto)
                        <option value="{{ $produto->id }}" {{ old('produto_id') == $produto->id ? 'selected' : '' }}>
                            {{ $produto->name }} - R$ {{ number_format($produto->price, 2, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" id="with_pedido" name="with_pedido" value="1" {{ old('with_pedido') ? 'checked' : 'checked' }}>
                    <label for="with_pedido" style="margin: 0; cursor: pointer;">Criar pedido pago associado</label>
                </div>
            </div>

            <button type="submit" class="btn">✨ Criar Aluno</button>
        </form>

        <div class="links">
            <a href="{{ route('dev.list-alunos') }}">📋 Ver todos os alunos</a>
            <a href="/alunos">🌐 Área de membros</a>
        </div>
    </div>
</body>
</html>
