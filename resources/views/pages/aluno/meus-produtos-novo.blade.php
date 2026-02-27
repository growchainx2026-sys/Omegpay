<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Produtos - Área de Membros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #0a0a0a;
            color: #ffffff;
            min-height: 100vh;
        }
        
        .header {
            background: linear-gradient(180deg, rgba(0,0,0,0.9) 0%, transparent 100%);
            padding: 20px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #0b6856;
            text-decoration: none;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .header-avatar-link {
            display: block;
            flex-shrink: 0;
            transition: opacity 0.2s;
        }
        .header-avatar-link:hover {
            opacity: 0.9;
        }
        
        .header-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #0b6856;
            background: #1a1a1a;
            display: block;
        }
        
        .header-greeting-wrap {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            line-height: 1.2;
        }
        .header-greeting-label {
            font-size: 11px;
            opacity: 0.8;
            color: #fff;
        }
        .header-greeting-name {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
        }
        
        .greeting {
            font-size: 14px;
        }
        
        .logout-btn {
            background: transparent;
            border: 1px solid #ffffff;
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .logout-btn:hover {
            background: #ffffff;
            color: #0a0a0a;
        }
        
        .container-main {
            padding: 60px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .page-title {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .page-subtitle {
            font-size: 18px;
            opacity: 0.7;
            margin-bottom: 40px;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .product-card {
            background: #1a1a1a;
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            position: relative;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: linear-gradient(135deg, #0b6856 0%, #0a4d3f 100%);
        }
        
        .product-content {
            padding: 20px;
        }
        
        .product-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .product-description {
            font-size: 14px;
            opacity: 0.7;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .progress-section {
            margin-bottom: 20px;
        }
        
        .progress-label {
            font-size: 12px;
            opacity: 0.7;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
        }
        
        .progress-bar-container {
            width: 100%;
            height: 8px;
            background: rgba(255,255,255,0.1);
            border-radius: 4px;
            overflow: hidden;
        }
        
        .progress-bar-fill {
            height: 100%;
            background: #0b6856;
            transition: width 0.3s;
            border-radius: 4px;
        }
        
        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        
        .access-btn {
            background: #0b6856;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
        }
        
        .access-btn:hover {
            background: #0a4d3f;
            transform: scale(1.05);
        }
        
        .empty-state {
            text-align: center;
            padding: 100px 20px;
            opacity: 0.5;
        }
        
        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
            }
            
            .container-main {
                padding: 30px 20px;
            }
            
            .products-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <a href="/alunos/meus-produtos" class="logo">Área de Membros</a>
        <div class="header-right">
            <div class="header-greeting-wrap">
                <span class="header-greeting-label">Olá,</span>
                <span class="header-greeting-name">{{ $aluno->name }}</span>
            </div>
            <a href="{{ route('aluno.profile') }}" class="header-avatar-link" title="Meu perfil">
                <img src="{{ $aluno->avatar ? asset($aluno->avatar) : asset('default-avatar.png') }}" alt="{{ $aluno->name }}" class="header-avatar" onerror="this.src='{{ asset('default-avatar.png') }}'">
            </a>
            <form method="POST" action="{{ route('aluno.logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="logout-btn">Sair →</button>
        </form>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container-main">
        <h1 class="page-title">Meus Produtos</h1>
        <p class="page-subtitle">Continue de onde parou e complete seus cursos</p>
        
        @if(count($produtosComProgresso) > 0)
            <div class="products-grid">
                @foreach($produtosComProgresso as $item)
                    <div class="product-card" onclick="window.location.href='/alunos/content/{{ $item['produto']->id }}'">
                        <img src="{{ $item['produto']->image && $item['produto']->image != 'produtos/box_default.svg' ? '/storage/' . $item['produto']->image : '/produto-image-default' }}" 
                             alt="{{ $item['produto']->name }}" 
                             class="product-image"
                             onerror="this.style.background='linear-gradient(135deg, #0b6856 0%, #0a4d3f 100%)'">
                        <div class="product-content">
                            <h3 class="product-title">{{ $item['produto']->name }}</h3>
                            <p class="product-description">{{ $item['produto']->description ?? 'Sem descrição' }}</p>
                            
                            @if($item['progresso'] > 0)
                                <div class="progress-section">
                                    <div class="progress-label">
                                        <span>Progresso</span>
                                        <span>{{ $item['progresso'] }}%</span>
                                    </div>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar-fill" style="width: {{ $item['progresso'] }}%"></div>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="product-footer">
                                <span style="font-size: 12px; opacity: 0.7;">
                                    Comprado em {{ $item['pedido']->created_at->format('d/m/Y') }}
                                </span>
                                <a href="/alunos/content/{{ $item['produto']->id }}" class="access-btn" onclick="event.stopPropagation()">
                                    {{ $item['progresso'] > 0 ? 'Continuar' : 'Acessar' }} →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h3>Nenhum produto encontrado</h3>
                <p>Você ainda não adquiriu nenhum curso.</p>
            </div>
        @endif
    </div>
</body>
</html>
