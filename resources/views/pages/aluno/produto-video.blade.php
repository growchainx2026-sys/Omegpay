@php
    $whiteMode = (bool) ($produto->area_member_white_mode ?? false);
    $primaryColor = $produto->area_member_color_primary ?? '#0b6856';
    $bgColor = $produto->area_member_color_background ?? '#0f0f0f';
    $textColor = $produto->area_member_color_text ?? '#ffffff';
@endphp

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sessao->nome }} - {{ $produto->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://www.youtube.com/iframe_api"></script>
    <style>
        :root {
            --primary-color: {{ $primaryColor }};
            --bg-color: {{ $whiteMode ? '#ffffff' : '#0f0f0f' }};
            --text-color: {{ $whiteMode ? '#000000' : '#ffffff' }};
            --card-bg: {{ $whiteMode ? '#f5f5f5' : '#1a1a1a' }};
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: var(--bg-color) !important;
            color: var(--text-color) !important;
            overflow-x: hidden;
            position: relative;
        }
        
        body.has-course-bg::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -1;
            opacity: 0.35;
        }
        
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: var(--bg-color);
            padding: 20px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }
        
        .dark-mode .header {
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        
        .logo {
            font-size: 22px;
            font-weight: bold;
            color: var(--primary-color);
            text-decoration: none;
            white-space: nowrap;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .greeting {
            font-size: 14px;
        }
        
        .logout-btn {
            background: transparent;
            border: 1px solid var(--text-color);
            color: var(--text-color);
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .logout-btn:hover {
            background: var(--text-color);
            color: var(--bg-color);
        }
        
        /* Banner Hero (será escondido quando scrollar) */
        .hero-banner {
            margin-top: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }
        
        .hero-banner.hidden {
            opacity: 0;
            transform: translateY(-100%);
            pointer-events: none;
            position: absolute;
            width: 100%;
            z-index: -1;
        }
        
        .hero-content {
            text-align: center;
            padding: 40px;
        }
        
        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 20px;
        }
        
        .hero-description {
            font-size: 1.2rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 30px;
        }
        
        .btn-play {
            background: #ffffff;
            color: #000000;
            padding: 14px 32px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-play:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        
        /* Player e Trilha do Curso */
        .video-section {
            display: none;
            padding: 40px 0;
            background: var(--bg-color);
            min-height: calc(100vh - 80px);
        }
        
        .video-section.active {
            display: block;
        }
        
        .video-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 60px;
        }
        
        .video-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 40px;
        }
        
        .video-player-container {
            background: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            padding-top: 56.25%;
        }
        
        .video-player-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        
        .video-info {
            margin-top: 24px;
            color: var(--text-color);
        }
        
        .video-info h2 {
            color: var(--text-color);
            font-size: 1.5rem;
            margin-bottom: 12px;
        }
        
        .video-info p {
            color: var(--text-color);
            opacity: 0.85;
            font-size: 1rem;
        }
        
        /* Trilha do Curso */
        .course-track {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 24px;
            position: sticky;
            top: 100px;
            max-height: calc(100vh - 120px);
            overflow-y: auto;
        }
        
        .course-track h3 {
            color: var(--text-color);
            font-size: 1.25rem;
            margin-bottom: 20px;
        }
        
        .course-module {
            margin-bottom: 24px;
        }
        
        .course-module h4 {
            color: var(--text-color);
            opacity: 0.9;
            font-size: 1rem;
            margin-bottom: 12px;
            font-weight: 600;
        }
        
        .course-session-item {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.2s;
            background: var(--bg-color);
            border: 1px solid var(--hover-bg);
        }
        
        .course-session-item:hover {
            background: var(--hover-bg);
        }
        
        .course-session-item.active {
            background: var(--hover-bg);
            border-left: 3px solid var(--primary-color);
        }
        
        .session-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--hover-bg);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            color: var(--text-color);
            font-weight: 600;
        }
        
        .course-session-item.active .session-number {
            background: var(--primary-color);
            color: #ffffff;
        }
        
        .course-session-item.locked {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .course-session-item.locked .session-number {
            background: #64748b;
            color: #ffffff;
        }
        
        .session-info {
            display: inline-block;
            vertical-align: middle;
        }
        
        .session-name {
            color: #e2e8f0;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .session-video-count {
            color: #64748b;
            font-size: 0.75rem;
        }
        
        .course-videos {
            margin-top: 24px;
        }
        
        .course-videos h4 {
            color: #e2e8f0;
            font-size: 1rem;
            margin-bottom: 12px;
            font-weight: 600;
        }
        
        .course-video-item {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.2s;
            background: #0f172a;
        }
        
        .course-video-item:hover {
            background: #1e293b;
        }
        
        .course-video-item.active {
            background: #334155;
            border-left: 3px solid var(--primary-color);
        }
        
        .course-video-item i {
            color: #64748b;
            font-size: 1.2rem;
            margin-right: 12px;
        }
        
        .course-video-item.active i {
            color: var(--primary-color);
        }
        
        .course-video-item.completed {
            background: rgba(16, 185, 129, 0.1);
            border-left: 3px solid #10b981;
        }
        
        .course-video-item.completed i {
            color: #10b981;
        }
        
        .course-video-item.completed .video-title-small {
            color: #34d399;
        }
        
        #markVideoCompletedBtn {
            white-space: nowrap;
        }
        
        #markVideoCompletedBtn.completed {
            background: #10b981;
            border-color: #10b981;
        }
        
        #markVideoCompletedBtn.completed:hover {
            background: #059669;
            border-color: #059669;
        }
        
        .video-title-small {
            color: #e2e8f0;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .video-duration {
            color: #64748b;
            font-size: 0.75rem;
        }
        
        @media (max-width: 1024px) {
            .video-layout {
                grid-template-columns: 1fr;
            }
            
            .course-track {
                position: relative;
                top: 0;
            }
        }
        
        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .video-container {
                padding: 0 20px;
            }
        }
    </style>
</head>
<body class="{{ $whiteMode ? 'white-mode' : 'dark-mode' }} {{ !empty($produto->area_member_course_background) ? 'has-course-bg' : '' }}"
      @if(!empty($produto->area_member_course_background))
      style="--course-bg-image: url('/storage/{{ ltrim($produto->area_member_course_background, '/') }}');"
      @endif>
    @if(!empty($produto->area_member_course_background))
    <style>body.has-course-bg::before { background-image: var(--course-bg-image); }</style>
    @endif
    <!-- Header -->
    <header class="header">
        <a href="/alunos/content/{{ $produto->id }}" class="logo">← {{ $produto->name }}</a>
        <div class="header-right">
            <span class="greeting">Olá, {{ $aluno->name }}!</span>
            <form method="POST" action="{{ route('aluno.logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="logout-btn">Sair →</button>
        </form>
        </div>
    </header>

    <!-- Banner Hero -->
    <div id="heroBanner" class="hero-banner">
        <div class="hero-content">
            <h1 class="hero-title">{{ $sessao->nome }}</h1>
            @if($sessao->descricao)
                <p class="hero-description">{{ $sessao->descricao }}</p>
            @endif
            <button class="btn-play" onclick="playFirstVideo()">
                <i class="fa-solid fa-play me-2"></i> Assistir Agora
            </button>
        </div>
    </div>

    <!-- Player e Trilha do Curso -->
    <div id="videoSection" class="video-section">
        <div class="video-container">
            <div class="video-layout">
                <!-- Player de Vídeo -->
                <div>
                    <div class="video-player-container">
                        <iframe id="youtubePlayer" 
                                src="" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                        </iframe>
                    </div>
                    <div class="video-info">
                        <div class="d-flex justify-content-between align-items-start mb-3" style="flex-wrap: wrap; gap: 16px;">
                            <div style="flex: 1; min-width: 200px;">
                                <h2 id="videoTitle"></h2>
                                <p id="videoDescription"></p>
                            </div>
                            <button id="markVideoCompletedBtn" class="btn btn-success" onclick="markVideoCompleted()" style="display: none; flex-shrink: 0;">
                                <i class="fa-solid fa-check-circle me-2"></i> Marcar como Assistido
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Trilha do Curso -->
                <div class="course-track">
                    <h3><i class="fa-solid fa-list me-2"></i> Trilha do Curso</h3>
                    <div class="course-module">
                        <h4>{{ $modulo->nome }}</h4>
                        <div class="course-sessions">
                            @foreach($todasSessoes as $sessaoItem)
                                <div class="course-session-item {{ $sessaoItem->id == $sessao->id ? 'active' : '' }}" 
                                     data-sessao-id="{{ $sessaoItem->id }}"
                                     onclick="loadSession({{ $sessaoItem->id }})">
                                    <span class="session-number">{{ $loop->iteration }}</span>
                                    <div class="session-info">
                                        <div class="session-name">{{ $sessaoItem->nome }}</div>
                                        <div class="session-video-count">{{ $sessaoItem->videosAtivos->count() }} vídeo(s)</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Lista de Vídeos da Sessão Atual -->
                    <div class="course-videos">
                        <h4>Vídeos desta Sessão</h4>
                        <div id="videosList">
                            @foreach($sessao->videosAtivos->sortBy('ordem') as $video)
                                @php
                                    $progresso = $aluno->progressoVideo($video->id);
                                    $concluido = $progresso && $progresso->concluido;
                                @endphp
                                <div class="course-video-item {{ $loop->first ? 'active' : '' }} {{ $concluido ? 'completed' : '' }}" 
                                     data-video-id="{{ $video->id }}"
                                     data-video-url="{{ $video->url_youtube }}"
                                     data-video-title="{{ $video->titulo }}"
                                     data-video-description="{{ $video->descricao ?? '' }}"
                                     data-video-concluido="{{ $concluido ? '1' : '0' }}"
                                     onclick="loadVideo('{{ $video->url_youtube }}', '{{ addslashes($video->titulo) }}', '{{ addslashes($video->descricao ?? '') }}', {{ $video->id }})">
                                    <i class="fa-solid {{ $concluido ? 'fa-check-circle' : 'fa-play-circle' }}"></i>
                                    <div style="display: inline-block;">
                                        <div class="video-title-small">{{ $video->titulo }}</div>
                                        @if($video->duracao)
                                            <div class="video-duration">{{ gmdate('H:i:s', $video->duracao) }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentVideoId = null;
        
        function playFirstVideo() {
            const firstVideo = document.querySelector('.course-video-item[data-video-id]');
            if (firstVideo) {
                const url = firstVideo.getAttribute('data-video-url');
                const title = firstVideo.getAttribute('data-video-title');
                const description = firstVideo.getAttribute('data-video-description');
                const videoId = firstVideo.getAttribute('data-video-id');
                loadVideo(url, title, description, videoId);
                
                // Esconde banner e mostra seção de vídeo
                const banner = document.getElementById('heroBanner');
                const videoSection = document.getElementById('videoSection');
                
                banner.classList.add('hidden');
                videoSection.classList.add('active');
                
                // Scroll suave para o topo
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }
        
        function loadVideo(url, title, description, videoId) {
            // Converte URL do YouTube para embed
            let embedUrl = url;
            if (url.includes('youtube.com/watch?v=')) {
                const videoIdFromUrl = url.split('v=')[1].split('&')[0];
                embedUrl = `https://www.youtube.com/embed/${videoIdFromUrl}?autoplay=1`;
            } else if (url.includes('youtu.be/')) {
                const videoIdFromUrl = url.split('youtu.be/')[1].split('?')[0];
                embedUrl = `https://www.youtube.com/embed/${videoIdFromUrl}?autoplay=1`;
            } else if (url.includes('/embed/')) {
                embedUrl = url.includes('?') ? url + '&autoplay=1' : url + '?autoplay=1';
            }
            
            // Atualiza player
            document.getElementById('youtubePlayer').src = embedUrl;
            document.getElementById('videoTitle').textContent = title;
            document.getElementById('videoDescription').textContent = description || '';
            
            // Atualiza item ativo
            document.querySelectorAll('.course-video-item').forEach(item => {
                item.classList.remove('active');
            });
            const videoItem = document.querySelector(`[data-video-id="${videoId}"]`);
            if (videoItem) {
                videoItem.classList.add('active');
                
                // Verifica se vídeo está concluído
                const concluido = videoItem.getAttribute('data-video-concluido') === '1';
                const btn = document.getElementById('markVideoCompletedBtn');
                if (btn) {
                    if (concluido) {
                        btn.innerHTML = '<i class="fa-solid fa-check-circle me-2"></i> Vídeo Assistido';
                        btn.classList.add('completed');
                        btn.disabled = true;
                    } else {
                        btn.innerHTML = '<i class="fa-solid fa-check-circle me-2"></i> Marcar como Assistido';
                        btn.classList.remove('completed');
                        btn.disabled = false;
                    }
                    btn.style.display = 'block';
                }
            }
            
            currentVideoId = videoId;
        }
        
        function markVideoCompleted() {
            if (!currentVideoId) return;
            
            const btn = document.getElementById('markVideoCompletedBtn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Salvando...';
            }
            
            // Busca duração do vídeo (se disponível)
            const videoItem = document.querySelector(`[data-video-id="${currentVideoId}"]`);
            const duracao = videoItem ? (videoItem.querySelector('.video-duration')?.textContent || '0') : '0';
            
            // Marca vídeo como concluído
            fetch('/alunos/api/progresso/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    video_id: currentVideoId,
                    tempo_assistido: 100,
                    tempo_total: 100,
                    ultima_posicao: 0
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualiza UI
                    if (btn) {
                        btn.innerHTML = '<i class="fa-solid fa-check-circle me-2"></i> Vídeo Assistido';
                        btn.classList.add('completed');
                    }
                    
                    // Atualiza item na lista
                    if (videoItem) {
                        videoItem.classList.add('completed');
                        videoItem.setAttribute('data-video-concluido', '1');
                        const icon = videoItem.querySelector('i');
                        if (icon) {
                            icon.className = 'fa-solid fa-check-circle';
                        }
                    }
                    
                    // Verifica se pode liberar próxima sessão
                    checkAndUnlockNextSession();
                    
                    // Recarrega página após 1 segundo para atualizar bloqueios
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert('Erro ao marcar vídeo como assistido. Tente novamente.');
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-solid fa-check-circle me-2"></i> Marcar como Assistido';
                    }
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao marcar vídeo como assistido. Tente novamente.');
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-check-circle me-2"></i> Marcar como Assistido';
                }
            });
        }
        
        function checkAndUnlockNextSession() {
            // Esta função pode ser expandida para verificar e liberar próxima sessão
            console.log('Verificando próxima sessão...');
        }
        
        function loadSession(sessaoId) {
            // Recarrega a página com a nova sessão
            window.location.href = '/alunos/content/{{ $produto->id }}?sessao=' + sessaoId + '&modulo={{ $modulo->id }}';
        }
        
        // Auto-play primeiro vídeo se vier de um clique na capa
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('sessao') && urlParams.get('modulo')) {
                // Aguarda um pouco para garantir que o DOM está pronto
                setTimeout(() => {
                    playFirstVideo();
                }, 500);
            } else {
                // Se não há parâmetros, verifica se há vídeo atual
                @if(isset($videoAtual) && $videoAtual)
                    const progresso = @json($aluno->progressoVideo($videoAtual->id));
                    const concluido = progresso && progresso.concluido;
                    if (concluido) {
                        const btn = document.getElementById('markVideoCompletedBtn');
                        if (btn) {
                            btn.innerHTML = '<i class="fa-solid fa-check-circle me-2"></i> Vídeo Assistido';
                            btn.classList.add('completed');
                            btn.disabled = true;
                            btn.style.display = 'block';
                        }
                    }
                @endif
            }
        });
    </script>
</body>
</html>
