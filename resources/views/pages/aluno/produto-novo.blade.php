@php
    $whiteMode = (bool) ($produto->area_member_white_mode ?? false);
    $primaryColor = $produto->area_member_color_primary ?? '#0b6856';
    $bgColor = $produto->area_member_color_background ?? '#0f0f0f';
    $textColor = $produto->area_member_color_text ?? '#ffffff';
    $welcomeText = $produto->area_member_welcome_text ?? "Bem-vindo ao curso {$produto->name}!";
@endphp

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $produto->name }} - Área de Membros</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://www.youtube.com/iframe_api"></script>
    <style>
        :root {
            --primary-color: {{ $primaryColor }};
            --bg-color: {{ $whiteMode ? '#ffffff' : '#0f0f0f' }};
            --text-color: {{ $whiteMode ? '#000000' : '#ffffff' }};
            --card-bg: {{ $whiteMode ? '#f5f5f5' : '#1a1a1a' }};
            --hover-bg: {{ $whiteMode ? '#e0e0e0' : '#2a2a2a' }};
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
            background: linear-gradient(180deg, var(--bg-color) 0%, transparent 100%);
            padding: 14px 60px 12px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            transition: background 0.3s, box-shadow 0.3s;
        }
        
        .header.scrolled {
            background: var(--bg-color);
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }
        
        body.dark-mode .header.scrolled {
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
            flex: 0 1 auto;
            min-width: 0;
        }
        
        .header-welcome-progress {
            display: flex;
            flex-direction: column;
            gap: 4px;
            max-width: 280px;
            min-width: 0;
        }
        
        .header-welcome-text {
            font-size: 12px;
            color: var(--text-color);
            opacity: 0.95;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .header-progress-bar {
            height: 14px;
            background: var(--hover-bg);
            border-radius: 999px;
            overflow: hidden;
            position: relative;
            min-width: 80px;
        }
        
        .header-progress-fill {
            height: 100%;
            background: var(--primary-color);
            border-radius: 999px;
            transition: width 0.5s ease;
            position: relative;
            overflow: hidden;
        }
        
        .header-progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 40%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent 0%,
                rgba(255, 255, 255, 0.35) 30%,
                rgba(255, 255, 255, 0.6) 50%,
                rgba(255, 255, 255, 0.35) 70%,
                transparent 100%
            );
            border-radius: 999px;
            animation: header-progress-shine 2.5s ease-in-out infinite;
            pointer-events: none;
        }
        
        @keyframes header-progress-shine {
            0% { transform: translateX(-120%); }
            60% { transform: translateX(-120%); }
            100% { transform: translateX(320%); }
        }
        
        .header-progress-pct {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 9px;
            font-weight: 600;
            color: #fff;
            text-shadow: 0 1px 2px rgba(0,0,0,0.5);
            pointer-events: none;
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
            gap: 16px;
        }
        
        .header-chat-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.2);
            background: transparent;
            color: var(--text-color);
            cursor: pointer;
            transition: all 0.2s;
        }
        .header-chat-btn:hover {
            background: var(--hover-bg);
            border-color: var(--primary-color);
            color: var(--primary-color);
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
            border: 2px solid var(--primary-color);
            background: var(--card-bg);
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
            color: var(--text-color);
        }
        .header-greeting-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-color);
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
        
        /* Chat panel (slide-over) */
        .chat-panel-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 99998;
            opacity: 0;
            transition: opacity 0.25s ease;
            pointer-events: none;
        }
        .chat-panel-overlay.visible { opacity: 1; pointer-events: auto; }
        .chat-panel {
            position: fixed;
            top: 0;
            right: 0;
            width: 100%;
            max-width: 400px;
            height: 100vh;
            background: var(--card-bg);
            border-left: 1px solid rgba(255,255,255,0.08);
            z-index: 99999;
            display: flex;
            flex-direction: column;
            transform: translateX(100%);
            transition: transform 0.25s ease;
        }
        .chat-panel.open { transform: translateX(0); }
        .chat-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .chat-panel-title { font-size: 16px; font-weight: 600; color: var(--text-color); margin: 0; }
        .chat-panel-close {
            width: 36px; height: 36px;
            border: none;
            background: transparent;
            color: var(--text-color);
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }
        .chat-panel-close:hover { background: var(--hover-bg); }
        .chat-panel-messages {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .chat-panel-empty {
            text-align: center;
            color: rgba(255,255,255,0.5);
            padding: 40px 20px;
        }
        .chat-panel-empty i { font-size: 32px; margin-bottom: 8px; display: block; }
        .chat-panel-msg {
            display: flex;
            gap: 10px;
            max-width: 85%;
        }
        .chat-panel-msg.sent { align-self: flex-end; flex-direction: row-reverse; }
        .chat-panel-msg-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: var(--primary-color);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            flex-shrink: 0;
        }
        .chat-panel-msg.sent .chat-panel-msg-avatar { background: var(--hover-bg); color: var(--text-color); }
        .chat-panel-msg-bubble {
            padding: 10px 14px;
            border-radius: 12px;
            background: var(--hover-bg);
            font-size: 14px;
            line-height: 1.4;
        }
        .chat-panel-msg.sent .chat-panel-msg-bubble { background: var(--primary-color); color: #fff; }
        .chat-panel-msg-time { font-size: 11px; opacity: 0.7; margin-top: 4px; }
        .chat-panel-input-wrap {
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }
        .chat-panel-input-wrap form {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }
        .chat-panel-input-wrap textarea {
            flex: 1;
            min-height: 44px;
            max-height: 120px;
            padding: 12px 14px;
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 10px;
            background: var(--bg-color);
            color: var(--text-color);
            font-size: 14px;
            resize: none;
            font-family: inherit;
        }
        .chat-panel-input-wrap textarea:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        .chat-panel-send {
            width: 44px; height: 44px;
            border: none;
            border-radius: 10px;
            background: var(--primary-color);
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .chat-panel-send:hover { opacity: 0.9; }
        
        /* Banner do Curso - Desktop 1920x400 / Mobile 768x400 */
        .hero-section {
            position: relative;
            width: 100%;
            margin: 120px 0 40px 0;
            border-radius: 0;
            overflow: hidden;
            background: var(--card-bg);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        .hero-section.hero-banner-desktop { height: 400px; }
        .hero-section.hero-banner-mobile { height: 250px; }
        @media (max-width: 768px) {
            .hero-section.hero-banner-desktop { height: 250px; margin-top: 100px; margin-bottom: 30px; }
        }
        
        /* Se tem banner mobile: desktop só em telas grandes, mobile só em telas pequenas */
        .hero-section.has-banner { background-color: #1a1a1a; }
        .hero-section.hero-banner-desktop:not(.has-mobile-banner) { display: block; }
        .hero-section.hero-banner-mobile { display: none; }
        @media (max-width: 768px) {
            .hero-section.hero-banner-desktop.has-mobile-banner { display: none; }
            .hero-section.hero-banner-mobile { display: block !important; }
        }
        
        .hero-section:not(.has-banner) {
            display: none;
        }
        
        body.no-banner .content-section {
            padding-top: 140px;
        }
        
        .content-section {
            padding: 60px 60px;
            overflow: visible;
        }
        
        .section-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 30px;
            color: var(--text-color);
        }
        
        /* Layout Estilo Netflix - Módulos como Títulos, Sessões como Capas */
        .netflix-module-row {
            margin-bottom: 40px;
            overflow: visible;
            position: relative;
        }
        
        .netflix-module-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 12px;
        }
        
        .netflix-module-description {
            font-size: 14px;
            color: var(--text-color);
            opacity: 0.85;
            margin-bottom: 20px;
        }
        
        .netflix-module-row.module-locked {
            opacity: 0.6;
        }
        
        .netflix-module-row.module-locked .netflix-sessions-row {
            pointer-events: none;
        }
        .netflix-module-row.module-locked .netflix-session-card {
            pointer-events: auto;
        }
        
        .netflix-sessions-row {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            overflow-y: visible;
            padding: 20px 8px 20px 8px;
            margin: -20px -8px -20px -8px;
            scrollbar-width: thin;
            scrollbar-color: var(--hover-bg) transparent;
        }
        
        .netflix-sessions-row::-webkit-scrollbar {
            height: 8px;
        }
        
        .netflix-sessions-row::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .netflix-sessions-row::-webkit-scrollbar-thumb {
            background: var(--hover-bg);
            border-radius: 4px;
        }
        
        .netflix-session-card {
            flex: 0 0 auto;
            width: 200px;
            cursor: pointer;
            transition: transform 0.2s;
            position: relative;
            z-index: 1;
        }
        
        .netflix-session-card:hover {
            transform: scale(1.05);
            z-index: 10;
        }
        
        .netflix-session-cover {
            width: 100%;
            height: 300px;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            background: var(--card-bg);
            margin-bottom: 8px;
        }
        
        .netflix-session-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        
        .netflix-session-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 2;
        }
        
        .netflix-session-card:hover .netflix-session-overlay {
            opacity: 1;
        }
        
        .netflix-session-overlay i {
            font-size: 48px;
            color: #ffffff;
        }
        
        /* Efeito de luz no hover */
        .netflix-session-card:hover .netflix-session-cover::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.05) 100%);
            z-index: 1;
            pointer-events: none;
            animation: lightSweep 1.5s ease-in-out infinite;
        }
        
        @keyframes lightSweep {
            0% {
                transform: translateX(-100%) skewX(-15deg);
            }
            50% {
                transform: translateX(200%) skewX(-15deg);
            }
            100% {
                transform: translateX(200%) skewX(-15deg);
            }
        }
        
        /* Informações no hover */
        .netflix-session-hover-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.7) 50%, transparent 100%);
            padding: 20px 12px 12px;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 3;
            transform: translateY(10px);
        }
        
        .netflix-session-card:hover .netflix-session-hover-info {
            opacity: 1;
            transform: translateY(0);
        }
        
        .netflix-session-hover-title {
            font-size: 16px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 6px;
            text-align: center;
        }
        
        .netflix-session-hover-count {
            font-size: 12px;
            color: rgba(255,255,255,0.8);
            text-align: center;
        }
        
        .netflix-session-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.5);
            border: 2px dashed rgba(255,255,255,0.2);
        }
        
        .netflix-session-placeholder i {
            font-size: 48px;
            margin-bottom: 8px;
        }
        
        /* Sessão bloqueada */
        .netflix-session-card.locked {
            opacity: 0.5;
            cursor: not-allowed;
            filter: grayscale(0.7);
        }
        
        .netflix-session-card.locked:hover {
            transform: none;
        }
        
        .netflix-session-card.locked .netflix-session-overlay {
            background: rgba(0, 0, 0, 0.7);
        }
        
        .netflix-session-card.locked .netflix-session-overlay i {
            font-size: 36px;
        }
        
        /* Badge "Será liberado em" no módulo */
        .module-release-badge {
            display: inline-block;
            margin-left: 12px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
            background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.08) 100%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            letter-spacing: 0.02em;
        }
        
        /* Data de liberação no hover da capa da sessão */
        .netflix-session-hover-release {
            margin-top: 8px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.9);
            text-align: center;
        }
        
        .sessions-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .session-card {
            background: var(--hover-bg);
            border-radius: 8px;
            padding: 20px;
            transition: all 0.3s;
            border: 1px solid var(--hover-bg);
        }
        
        .session-card:hover {
            background: var(--primary-color);
            color: #ffffff;
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
        }
        
        .session-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .videos-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 15px;
        }
        
        .video-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px;
            background: rgba(255,255,255,0.05);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .video-item:hover {
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        
        .video-thumbnail {
            width: 120px;
            height: 68px;
            border-radius: 4px;
            object-fit: cover;
            background: #333;
        }
        
        .video-info {
            flex: 1;
        }
        
        .video-title {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .video-meta {
            font-size: 12px;
            opacity: 0.7;
        }
        
        .video-progress {
            width: 100%;
            height: 4px;
            background: rgba(255,255,255,0.2);
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
        }
        
        .video-progress-fill {
            height: 100%;
            background: var(--primary-color);
            transition: width 0.3s;
        }
        
        .check-icon {
            color: var(--primary-color);
            font-size: 18px;
        }
        
        /* Modal de Vídeo */
        .video-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: transparent;
            z-index: 2000;
            padding: 40px;
            overflow-y: auto;
        }
        
        .video-modal.active {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .video-modal-content {
            max-width: 1200px;
            width: 100%;
            position: relative;
        }
        
        .video-close {
            position: absolute;
            top: -40px;
            right: 0;
            background: transparent;
            border: none;
            color: #ffffff;
            font-size: 32px;
            cursor: pointer;
            padding: 10px;
        }
        
        .video-player-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            border-radius: 8px;
        }
        
        .video-player-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        
        .video-details {
            margin-top: 30px;
            color: var(--text-color);
        }
        
        .video-details h2 {
            font-size: 28px;
            margin-bottom: 15px;
        }
        
        .video-details p {
            font-size: 16px;
            opacity: 0.9;
            line-height: 1.6;
        }
        
        /* Player e Trilha do Curso - Layout Principal */
        .video-section {
            display: none;
            padding: 40px 60px;
            background: var(--bg-color);
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        
        .video-section.active {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        
        .video-container {
            max-width: 1600px;
            margin: 0 auto;
        }
        
        .video-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 32px;
            margin-bottom: 60px;
        }
        
        .video-section .video-player-container {
            background: var(--card-bg);
            border-radius: 6px;
            overflow: hidden;
            position: relative;
            padding-top: 22%;
            border: 1px solid var(--hover-bg);
        }
        
        .video-section .video-player-container iframe,
        .video-section .video-player-container .yt-embed-wrap {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .video-section .video-player-container .yt-embed-wrap {
            z-index: 0;
        }
        
        .custom-player-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 2;
            background: linear-gradient(transparent, rgba(0,0,0,0.92));
            padding: 16px 12px 12px;
            opacity: 1;
        }
        
        .custom-progress-wrap {
            height: 6px;
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
            cursor: pointer;
            margin-bottom: 10px;
        }
        
        .custom-progress-bar {
            height: 100%;
            background: var(--primary-color);
            border-radius: 3px;
            width: 0%;
            transition: width 0.1s linear;
        }
        
        .custom-player-controls {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .custom-player-controls button {
            background: transparent;
            border: none;
            color: #fff;
            width: 36px;
            height: 36px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }
        
        .custom-player-controls button:hover {
            background: rgba(255,255,255,0.15);
        }
        
        .custom-player-controls button i {
            font-size: 1rem;
            pointer-events: none;
        }
        
        .custom-player-controls .speed-btn {
            width: auto;
            padding: 0 10px;
            font-size: 13px;
            min-width: 48px;
        }
        
        .custom-time {
            font-size: 12px;
            color: rgba(255,255,255,0.8);
            margin-left: auto;
        }
        
        .video-section .video-info {
            margin-top: 20px;
            color: var(--text-color);
        }
        
        .video-section .video-info h2 {
            color: var(--text-color);
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .video-section .video-info p {
            color: var(--text-color);
            opacity: 0.85;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        
        /* Botão Marcar como Assistido - Estilizado */
        #markVideoCompletedBtn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        #markVideoCompletedBtn:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
        }
        
        #markVideoCompletedBtn:active {
            transform: translateY(0);
        }
        
        #markVideoCompletedBtn.btn-secondary {
            background: #475569;
            cursor: not-allowed;
        }
        
        #markVideoCompletedBtn.btn-secondary:hover {
            background: #475569;
            transform: none;
        }
        
        /* Trilha do Curso */
        .course-track {
            background: var(--card-bg);
            border-radius: 6px;
            padding: 24px;
            position: sticky;
            top: 100px;
            max-height: calc(100vh - 120px);
            overflow-y: auto;
            border: 1px solid var(--hover-bg);
        }
        
        .course-track::-webkit-scrollbar {
            width: 6px;
        }
        
        .course-track::-webkit-scrollbar-track {
            background: var(--bg-color);
            border-radius: 3px;
        }
        
        .course-track::-webkit-scrollbar-thumb {
            background: var(--hover-bg);
            border-radius: 3px;
        }
        
        .course-track::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }
        
        .course-track h3 {
            color: var(--text-color);
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--hover-bg);
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
            padding: 14px;
            border-radius: 6px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.2s;
            background: var(--bg-color);
            border: 1px solid var(--hover-bg);
            position: relative;
        }
        
        .course-session-item:hover:not(.locked):not(.completed) {
            background: var(--hover-bg);
            border-color: var(--hover-bg);
        }
        
        .course-session-item.active {
            background: var(--hover-bg);
            border-left: 3px solid var(--primary-color);
            border-color: var(--hover-bg);
        }
        
        .course-session-item.completed {
            background: var(--bg-color);
            border-left: 3px solid #10b981;
            opacity: 0.9;
        }
        
        .course-session-item.completed::after {
            display: none; /* Removido para usar ícone Font Awesome */
        }
        
        .course-session-item.locked {
            opacity: 0.5;
            cursor: not-allowed;
            background: #0a0a0a;
            filter: grayscale(0.3);
        }
        
        .course-session-item.locked .session-number {
            background: #475569;
            color: #64748b;
        }
        
        .course-session-item.locked::before {
            display: none; /* Removido para usar ícone Font Awesome */
        }
        
        .session-status-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
        }
        
        .session-status-icon.completed-icon {
            color: #10b981;
        }
        
        .session-status-icon.locked-icon {
            color: #64748b;
        }
        
        .session-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #334155;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            color: #94a3b8;
            font-weight: 600;
            flex-shrink: 0;
        }
        
        .course-session-item.active .session-number {
            background: var(--primary-color);
            color: #ffffff;
        }
        
        .course-session-item.completed .session-number {
            background: #10b981;
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
            padding: 14px;
            border-radius: 6px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.2s;
            background: #0f172a;
            border: 1px solid transparent;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .course-video-item:hover {
            background: #1e293b;
            border-color: #334155;
        }
        
        .course-video-item.active {
            background: #1e293b;
            border-left: 3px solid var(--primary-color);
            border-color: #334155;
        }
        
        .course-video-item.completed {
            opacity: 0.8;
        }
        
        .course-video-item i {
            font-size: 1.2rem;
            color: #94a3b8;
            flex-shrink: 0;
        }
        
        .course-video-item.active i {
            color: var(--primary-color);
        }
        
        .course-video-item.completed i,
        .course-video-item.watched i {
            color: #10b981;
        }
        
        .course-videos-only {
            padding: 0;
        }
        
        .course-track-session-title {
            color: #e2e8f0;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #334155;
        }
        
        .course-videos-list {
            max-height: 280px;
            overflow-y: auto;
        }
        
        .course-video-icon {
            width: 28px;
            text-align: center;
            flex-shrink: 0;
        }
        
        .course-video-item.watched .course-video-icon i {
            color: #10b981;
        }
        
        .course-video-text {
            flex: 1;
            min-width: 0;
        }
        
        .session-completed-msg {
            padding: 14px;
            background: #0f172a;
            border-radius: 6px;
            text-align: center;
            color: #10b981;
            font-weight: 600;
        }
        
        .video-title-small {
            color: #e2e8f0;
            font-weight: 500;
            font-size: 0.9rem;
            line-height: 1.4;
        }
        
        .video-duration {
            color: #64748b;
            font-size: 0.75rem;
            margin-top: 4px;
        }
        
        /* Botão Concluir Sessão */
        .btn-complete-session {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-color) 0%, #059669 100%);
            border: none;
            color: #ffffff;
            padding: 14px 24px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-complete-session:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
        }
        
        .btn-complete-session:active {
            transform: translateY(0);
        }
        
        .complete-session-container {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #334155;
        }
        
        /* Responsividade para vídeo e trilha */
        @media (max-width: 1200px) {
            .video-layout {
                grid-template-columns: 1fr;
            }
            
            .course-track {
                position: relative;
                top: 0;
                max-height: none;
            }
        }
        
        /* Modal de detalhes do módulo */
        .module-details-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.85);
            z-index: 2000;
            padding: 40px;
            overflow-y: auto;
        }
        
        .module-details-modal.active {
            display: block;
        }
        
        .module-details-content {
            max-width: 1200px;
            margin: 0 auto;
            background: var(--card-bg);
            border-radius: 16px;
            padding: 40px;
            position: relative;
            border: 1px solid var(--hover-bg);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .module-details-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: transparent;
            border: none;
            color: var(--text-color);
            font-size: 32px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s;
        }
        
        .module-details-close:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        /* Modal de Progresso Gamificado */
        .progress-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15,15,15,0.95);
            z-index: 3000;
            align-items: center;
            justify-content: center;
        }
        
        .progress-modal.active {
            display: flex;
        }
        
        .progress-modal-content {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 40px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            animation: slideUp 0.3s;
        }
        
        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .progress-celebration {
            font-size: 64px;
            margin-bottom: 20px;
        }
        
        .progress-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .progress-message {
            font-size: 16px;
            opacity: 0.8;
            margin-bottom: 30px;
        }
        
        .progress-stats {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .stat-label {
            font-size: 14px;
            opacity: 0.7;
            margin-top: 5px;
        }
        
        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
            }
            
            .hero-section {
                height: 250px;
                padding: 30px 30px;
                margin-top: 80px;
                flex-direction: column;
                text-align: center;
            }
            
            .hero-content {
                padding-right: 0;
            }
            
            .hero-title {
                font-size: 32px;
            }
            
            .hero-subtitle {
                font-size: 16px;
            }
            
            .content-section {
                padding: 40px 20px;
                overflow: visible;
            }
            
            .modules-container {
                grid-template-columns: repeat(auto-fill, 150px);
                gap: 16px;
            }
            
            .module-card {
                width: 150px;
                height: 270px;
            }
            
            .module-info h3 {
                font-size: 16px;
            }
            
            .module-info p {
                font-size: 11px;
            }
            
            .sessions-list {
                grid-template-columns: 1fr;
            }
            
            .video-modal {
                padding: 20px;
            }
        }
        
        @media (max-width: 480px) {
            .hero-section {
                height: 220px;
                padding: 20px;
            }
            
            .hero-title {
                font-size: 24px;
            }
            
            .modules-container {
                grid-template-columns: repeat(2, 130px);
                gap: 12px;
                justify-content: center;
            }
            
            .module-card {
                width: 130px;
                height: 230px;
            }
        }
    </style>
</head>
<body class="{{ $whiteMode ? 'white-mode' : 'dark-mode' }} {{ !empty($produto->area_member_course_background) ? 'has-course-bg' : '' }} {{ (empty($produto->area_member_banner) && empty($produto->area_member_banner_mobile)) ? 'no-banner' : '' }}"
      @if(!empty($produto->area_member_course_background))
      style="--course-bg-image: url('/storage/{{ ltrim($produto->area_member_course_background, '/') }}');"
      @endif>
    @if(!empty($produto->area_member_course_background))
    <style>body.has-course-bg::before { background-image: var(--course-bg-image); }</style>
    @endif
    <!-- Header -->
    <header class="header" id="header">
        <div class="header-top">
            <div class="header-left">
                <a href="/alunos/meus-produtos" class="logo">← {{ $produto->name }}</a>
                <div class="header-welcome-progress">
                    <div class="header-welcome-text">{{ $welcomeText }}</div>
                    <div class="header-progress-bar">
                        <div class="header-progress-fill" id="progressFill" style="width: {{ $progressoGeral }}%"></div>
                        <span class="header-progress-pct" id="progressPct">{{ $progressoGeral }}%</span>
                    </div>
                </div>
            </div>
            <div class="header-right">
                <button type="button" class="header-chat-btn" id="headerChatBtn" title="Chat com o instrutor" aria-label="Abrir chat" onclick="window.openChatPanel ? window.openChatPanel() : (document.getElementById('chatPanelOverlay').classList.add('visible'), document.getElementById('chatPanel').classList.add('open')); return false;">
                    <i class="fa-solid fa-comment-dots" style="font-size: 18px;"></i>
                </button>
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
        </div>
    </header>

    {{-- Painel Chat (slide-over) - script autocontido --}}
    <div id="chatPanelOverlay" class="chat-panel-overlay"></div>
    <div id="chatPanel" class="chat-panel" data-produto-id="{{ $produto->id }}" data-csrf="{{ csrf_token() }}" data-aluno-name="{{ addslashes($aluno->name ?? '') }}">
        <div class="chat-panel-header">
            <h3 class="chat-panel-title"><i class="fa-solid fa-comment-dots me-2"></i><span id="chatPanelTitle">Chat</span></h3>
            <button type="button" class="chat-panel-close" aria-label="Fechar"><i class="fa-solid fa-times"></i></button>
        </div>
        <div class="chat-panel-messages" id="chatPanelMessages">
            <div class="chat-panel-empty" id="chatPanelEmpty"><i class="fa-solid fa-comments"></i><p>Nenhuma mensagem ainda.</p></div>
        </div>
        <div class="chat-panel-input-wrap">
            <form id="chatPanelForm">
                <textarea id="chatPanelInput" placeholder="Digite sua mensagem..." rows="1" maxlength="2000"></textarea>
                <button type="submit" class="chat-panel-send" title="Enviar"><i class="fa-solid fa-paper-plane"></i></button>
            </form>
        </div>
    </div>
    <script>
    (function(){
        var panel = document.getElementById('chatPanel');
        var overlay = document.getElementById('chatPanelOverlay');
        var form = document.getElementById('chatPanelForm');
        var btn = document.getElementById('headerChatBtn');
        if (!panel || !form) return;
        var produtoId = panel.getAttribute('data-produto-id') || '';
        var csrf = panel.getAttribute('data-csrf') || '';
        var alunoName = panel.getAttribute('data-aluno-name') || '';
        var produtorName = 'Suporte';
        var lastIds = '';
        var pollTimer = null;
        function esc(s){ var d=document.createElement('div'); d.textContent=s; return d.innerHTML; }
        function loadMessages(first){
            fetch('/alunos/chat/' + produtoId + '/messages', { method: 'GET', credentials: 'same-origin', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r){ if (!r.ok) throw new Error(r.status); return r.json(); })
            .then(function(data){
                if (data.produtor_name) produtorName = data.produtor_name;
                var titleEl = document.getElementById('chatPanelTitle');
                if (titleEl) titleEl.textContent = 'Chat com ' + produtorName;
                var container = document.getElementById('chatPanelMessages');
                var emptyEl = document.getElementById('chatPanelEmpty');
                if (!container) return;
                var ids = (data.messages || []).map(function(m){ return m.id; }).join(',');
                if (!first && ids === lastIds) return;
                lastIds = ids;
                if (!data.messages || data.messages.length === 0) {
                    if (emptyEl) { emptyEl.innerHTML = '<i class="fa-solid fa-comments"></i><p>Nenhuma mensagem ainda. Envie uma!</p>'; emptyEl.style.display = 'block'; }
                    container.querySelectorAll('.chat-panel-msg').forEach(function(el){ el.remove(); });
                    return;
                }
                if (emptyEl) emptyEl.style.display = 'none';
                container.querySelectorAll('.chat-panel-msg').forEach(function(el){ el.remove(); });
                var aIni = (alunoName && alunoName.charAt(0)) ? alunoName.charAt(0).toUpperCase() : 'A';
                var pIni = (produtorName && produtorName.charAt(0)) ? produtorName.charAt(0).toUpperCase() : 'S';
                data.messages.forEach(function(m){
                    var div = document.createElement('div');
                    div.className = 'chat-panel-msg' + (m.sender_type === 'aluno' ? ' sent' : '');
                    div.innerHTML = '<div class="chat-panel-msg-avatar">' + (m.sender_type === 'aluno' ? aIni : pIni) + '</div><div><div class="chat-panel-msg-bubble">' + esc(m.body) + '</div><div class="chat-panel-msg-time">' + esc(m.created_at) + '</div></div>';
                    container.appendChild(div);
                });
                container.scrollTop = container.scrollHeight;
            })
            .catch(function(){ if (first && document.getElementById('chatPanelEmpty')) document.getElementById('chatPanelEmpty').innerHTML = '<i class="fa-solid fa-exclamation-circle"></i><p>Erro ao carregar.</p>'; });
        }
        function sendMessage(e){
            e.preventDefault();
            var input = document.getElementById('chatPanelInput');
            var body = (input && input.value ? input.value : '').trim();
            if (!body) return false;
            if (input) input.value = '';
            var container = document.getElementById('chatPanelMessages');
            var emptyEl = document.getElementById('chatPanelEmpty');
            fetch('/alunos/chat/send', {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ produto_id: parseInt(produtoId, 10), body: body })
            })
            .then(function(r){ if (!r.ok) throw new Error('Erro ' + r.status); return r.json(); })
            .then(function(data){
                if (data.message && container) {
                    if (emptyEl) emptyEl.style.display = 'none';
                    lastIds = (lastIds ? lastIds + ',' : '') + data.message.id;
                    var div = document.createElement('div');
                    div.className = 'chat-panel-msg sent';
                    var aIni = (alunoName && alunoName.charAt(0)) ? alunoName.charAt(0).toUpperCase() : 'A';
                    div.innerHTML = '<div class="chat-panel-msg-avatar">' + aIni + '</div><div><div class="chat-panel-msg-bubble">' + esc(data.message.body) + '</div><div class="chat-panel-msg-time">' + esc(data.message.created_at) + '</div></div>';
                    container.appendChild(div);
                    container.scrollTop = container.scrollHeight;
                }
            })
            .catch(function(err){ alert(err && err.message ? err.message : 'Erro ao enviar.'); });
            return false;
        }
        function openPanel(){
            if (overlay) overlay.classList.add('visible');
            if (panel) panel.classList.add('open');
            document.body.style.overflow = 'hidden';
            loadMessages(true);
            if (pollTimer) clearInterval(pollTimer);
            pollTimer = setInterval(function(){ loadMessages(false); }, 4000);
        }
        function closePanel(){
            if (overlay) overlay.classList.remove('visible');
            if (panel) panel.classList.remove('open');
            document.body.style.overflow = '';
            if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
        }
        overlay.addEventListener('click', closePanel);
        panel.querySelector('.chat-panel-close').addEventListener('click', closePanel);
        form.addEventListener('submit', sendMessage);
        if (btn) btn.addEventListener('click', function(ev){ ev.preventDefault(); openPanel(); return false; });
        window.openChatPanel = openPanel;
        window.closeChatPanel = closePanel;
    })();
    </script>

    <!-- Banner do Curso - Desktop 1920x400 / Mobile 768x400 -->
    @if(!empty($produto->area_member_banner))
    <section class="hero-section hero-banner-desktop has-banner {{ !empty($produto->area_member_banner_mobile) ? 'has-mobile-banner' : '' }}" style="background-image: url('/storage/{{ ltrim($produto->area_member_banner, '/') }}');"></section>
    @endif
    @if(!empty($produto->area_member_banner_mobile))
    <section class="hero-section hero-banner-mobile has-banner" style="background-image: url('/storage/{{ ltrim($produto->area_member_banner_mobile, '/') }}');"></section>
    @endif

    <!-- Video Section (inicialmente oculta) -->
    <section id="videoSection" class="video-section" style="display: none;">
        <div class="video-container">
            <div class="video-layout">
                <!-- Player de Vídeo -->
                <div>
                    <div class="video-player-container">
                        <div id="youtubePlayer" class="yt-embed-wrap"></div>
                        <div class="custom-player-overlay" id="customPlayerOverlay">
                            <div class="custom-progress-wrap" id="customProgressWrap">
                                <div class="custom-progress-bar" id="customProgressBar"></div>
                            </div>
                            <div class="custom-player-controls">
                                <button type="button" id="btnBack10" title="Voltar 10s"><i class="fa-solid fa-rotate-left"></i></button>
                                <button type="button" id="btnPlayPause" title="Play/Pausar"><i class="fa-solid fa-play" id="iconPlayPause"></i></button>
                                <button type="button" id="btnFwd10" title="Avançar 10s"><i class="fa-solid fa-rotate-right"></i></button>
                                <button type="button" class="speed-btn" id="btnSpeed" title="Velocidade">1x</button>
                                <span class="custom-time" id="customTime">0:00 / 0:00</span>
                            </div>
                        </div>
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
                    <div id="courseTrackContent">
                        <!-- Conteúdo será preenchido dinamicamente -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="content-section" id="contentSection">
        @php
            $pedidoAluno = $aluno->pedidos()->where('produto_id', $produto->id)->where('status', 'pago')->orderBy('created_at')->first();
        @endphp
        {{-- Módulos Novos (estrutura nova) - Layout estilo Netflix --}}
        @forelse($produto->modulosAtivos as $modulo)
            @php
                $moduloLiberado = $aluno->moduloLiberado($modulo->id, $produto->id);
                $moduloLiberarEmDias = !$moduloLiberado && $modulo->liberar_em_dias && $pedidoAluno ? $pedidoAluno->created_at->copy()->addDays((int) $modulo->liberar_em_dias) : null;
            @endphp
            <div class="netflix-module-row {{ !$moduloLiberado ? 'module-locked' : '' }}" data-modulo-id="{{ $modulo->id }}">
                <h2 class="netflix-module-title">
                    {{ $modulo->nome }}
                    @if(!$moduloLiberado)
                        <i class="fa-solid fa-lock ms-2" style="font-size: 0.8em; opacity: 0.7;"></i>
                    @endif
                    @if(!$moduloLiberado && $modulo->liberar_em)
                        <span class="module-release-badge" title="Será liberado em breve">Será liberado em: {{ $modulo->liberar_em->format('d/m/Y') }} às {{ $modulo->liberar_em->format('H:i') }}</span>
                    @elseif($moduloLiberarEmDias && now()->lt($moduloLiberarEmDias))
                        <span class="module-release-badge" title="Será liberado em {{ $modulo->liberar_em_dias }} dias">Será liberado em {{ $modulo->liberar_em_dias }} dias ({{ $moduloLiberarEmDias->format('d/m/Y') }})</span>
                    @endif
                </h2>
                @if($modulo->descricao)
                    <p class="netflix-module-description">{{ $modulo->descricao }}</p>
                @endif
                
                <div class="netflix-sessions-row">
                    @foreach($modulo->sessoesAtivas->sortBy('ordem') as $sessao)
                        @php
                            $sessaoLiberada = $aluno->sessaoLiberada($sessao->id, $produto->id);
                        @endphp
                        @php
                            $sessaoLocked = !$sessaoLiberada || !$moduloLiberado;
                            $sessaoLiberarEm = $sessao->liberar_em;
                            $sessaoLiberarEmDiasDate = $sessaoLocked && $sessao->liberar_em_dias && $pedidoAluno ? $pedidoAluno->created_at->copy()->addDays((int) $sessao->liberar_em_dias) : null;
                            $diasParaLiberarSessao = ($sessaoLiberarEmDiasDate && now()->lt($sessaoLiberarEmDiasDate)) ? (int) now()->diffInDays($sessaoLiberarEmDiasDate, false) : null;
                        @endphp
                        <div class="netflix-session-card {{ $sessaoLocked ? 'locked' : '' }}" 
                             data-sessao-id="{{ $sessao->id }}"
                             data-sessao-liberada="{{ $sessaoLiberada && $moduloLiberado ? '1' : '0' }}"
                             data-modulo-id="{{ $modulo->id }}"
                             style="cursor: {{ $sessaoLiberada && $moduloLiberado ? 'pointer' : 'not-allowed' }};">
                            @if($sessao->capa)
                                <div class="netflix-session-cover" onclick="event.stopPropagation(); handleSessionClick({{ $sessao->id }}, {{ $modulo->id }}, {{ $sessaoLiberada && $moduloLiberado ? 'true' : 'false' }})">
                                    <img src="/storage/{{ ltrim($sessao->capa, '/') }}" alt="{{ $sessao->nome }}">
                                    <div class="netflix-session-overlay">
                                        @if($sessaoLocked)
                                            <i class="fa-solid fa-lock"></i>
                                        @else
                                            <i class="fa-solid fa-play"></i>
                                        @endif
                                    </div>
                                    <div class="netflix-session-hover-info">
                                        <div class="netflix-session-hover-title">{{ $sessao->nome }}</div>
                                        @if($sessao->videosAtivos->count() > 0)
                                            <div class="netflix-session-hover-count">{{ $sessao->videosAtivos->count() }} {{ $sessao->videosAtivos->count() == 1 ? 'vídeo' : 'vídeos' }}</div>
                                        @endif
                                        @if($sessaoLocked && $sessaoLiberarEm)
                                            <div class="netflix-session-hover-release">Será liberada em {{ $sessaoLiberarEm->format('d/m/Y') }} às {{ $sessaoLiberarEm->format('H:i') }}</div>
                                        @elseif($sessaoLocked && $sessaoLiberarEmDiasDate && now()->lt($sessaoLiberarEmDiasDate))
                                            <div class="netflix-session-hover-release">Será liberada em {{ $sessao->liberar_em_dias }} dias ({{ $sessaoLiberarEmDiasDate->format('d/m/Y') }})</div>
                                            @if($diasParaLiberarSessao !== null)
                                                <div class="netflix-session-hover-release netflix-session-hover-dias">Daqui a {{ $diasParaLiberarSessao }} {{ $diasParaLiberarSessao == 1 ? 'dia' : 'dias' }}</div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="netflix-session-cover netflix-session-placeholder" onclick="event.stopPropagation(); handleSessionClick({{ $sessao->id }}, {{ $modulo->id }}, {{ $sessaoLiberada && $moduloLiberado ? 'true' : 'false' }})">
                                    <i class="fa-solid fa-image"></i>
                                    <span>Sem capa</span>
                                    <div class="netflix-session-hover-info">
                                        <div class="netflix-session-hover-title">{{ $sessao->nome }}</div>
                                        @if($sessao->videosAtivos->count() > 0)
                                            <div class="netflix-session-hover-count">{{ $sessao->videosAtivos->count() }} {{ $sessao->videosAtivos->count() == 1 ? 'vídeo' : 'vídeos' }}</div>
                                        @endif
                                        @if($sessaoLocked && $sessaoLiberarEm)
                                            <div class="netflix-session-hover-release">Será liberada em {{ $sessaoLiberarEm->format('d/m/Y') }} às {{ $sessaoLiberarEm->format('H:i') }}</div>
                                        @elseif($sessaoLocked && $sessaoLiberarEmDiasDate && now()->lt($sessaoLiberarEmDiasDate))
                                            <div class="netflix-session-hover-release">Será liberada em {{ $sessao->liberar_em_dias }} dias ({{ $sessaoLiberarEmDiasDate->format('d/m/Y') }})</div>
                                            @if($diasParaLiberarSessao !== null)
                                                <div class="netflix-session-hover-release netflix-session-hover-dias">Daqui a {{ $diasParaLiberarSessao }} {{ $diasParaLiberarSessao == 1 ? 'dia' : 'dias' }}</div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 60px 20px; opacity: 0.5; color: #ffffff;">
                <p>Nenhum módulo disponível ainda.</p>
            </div>
        @endforelse
        
        <!-- Video Section (será inserida dinamicamente antes do módulo atual) -->


    <!-- Modal de Detalhes do Módulo -->
    <div class="module-details-modal" id="moduleDetailsModal">
        <div class="module-details-content">
            <button class="module-details-close" onclick="closeModuleDetails()">×</button>
            <div id="moduleDetailsContent">
                <!-- Conteúdo será preenchido via JavaScript -->
            </div>
        </div>
    </div>

    <!-- Modal de Progresso Gamificado -->
    <div class="progress-modal" id="progressModal">
        <div class="progress-modal-content">
            <div class="progress-celebration" id="celebrationEmoji">🎉</div>
            <h2 class="progress-title" id="progressTitle">Parabéns!</h2>
            <p class="progress-message" id="progressMessage">Você completou este vídeo!</p>
            <div class="progress-stats">
                <div class="stat-item">
                    <div class="stat-value" id="videosCompleted">0</div>
                    <div class="stat-label">Vídeos Concluídos</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="progressPercent">0%</div>
                    <div class="stat-label">Progresso Geral</div>
                </div>
            </div>
            <button onclick="closeProgressModal()" style="margin-top: 30px; padding: 12px 30px; background: var(--primary-color); color: #ffffff; border: none; border-radius: 8px; cursor: pointer; font-size: 16px;">
                Continuar Assistindo
            </button>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        // Função para lidar com clique na capa da sessão (deve estar antes do HTML que a usa)
        function handleSessionClick(sessaoId, moduloId, liberada) {
            if (!liberada) {
                alert('Complete as sessões anteriores para acessar este conteúdo.');
                return;
            }
            
            // Carrega dados da sessão via AJAX
            fetch(`/alunos/produto/{{ $produto->id }}/sessao/${sessaoId}`, {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mostra seção de vídeo e carrega conteúdo (scroll será feito dentro da função)
                        loadSessionData(data.sessao, data.modulo, data.videos, data.todasSessoes);
                    } else {
                        alert('Erro ao carregar sessão.');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    // Fallback: redireciona para página de vídeo
                    window.location.href = '/alunos/content/{{ $produto->id }}?sessao=' + sessaoId + '&modulo=' + moduloId;
                });
        }
        
        function loadSessionData(sessao, modulo, videos, todasSessoes) {
            // Esconde outros módulos, mantém apenas o módulo atual
            const allModuleRows = document.querySelectorAll('.netflix-module-row');
            const currentModuloId = modulo.id.toString();
            
            allModuleRows.forEach(row => {
                const rowModuloId = row.getAttribute('data-modulo-id');
                if (rowModuloId && rowModuloId !== currentModuloId) {
                    row.style.display = 'none';
                } else if (rowModuloId === currentModuloId) {
                    // Garante que o módulo atual está visível
                    row.style.display = 'block';
                }
            });
            
            // Insere seção de vídeo antes do módulo atual
            const currentModuleRow = document.querySelector(`[data-modulo-id="${currentModuloId}"]`);
            const videoSection = document.getElementById('videoSection');
            const contentSection = document.querySelector('.content-section');
            
            if (currentModuleRow && videoSection && contentSection) {
                // Remove vídeo de onde estiver
                if (videoSection.parentNode) {
                    videoSection.parentNode.removeChild(videoSection);
                }
                // Insere vídeo antes do módulo atual
                contentSection.insertBefore(videoSection, currentModuleRow);
            }
            
            // Mostra seção de vídeo com animação
            videoSection.style.display = 'block';
            
            // Força reflow para garantir que a animação funcione
            videoSection.offsetHeight;
            
            setTimeout(() => {
                videoSection.classList.add('active');
                
                // Scroll suave para a área de vídeo (não topo da página)
                setTimeout(() => {
                    videoSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 200);
            }, 10);
            
            // Carrega primeiro vídeo
            if (videos && videos.length > 0) {
                const firstVideo = videos[0];
                loadVideo(firstVideo.url_youtube, firstVideo.titulo, firstVideo.descricao || '', firstVideo.id);
            }
            
            // Carrega trilha do curso
            currentSessaoId = sessao.id;
            loadCourseTrack(modulo, todasSessoes, videos, sessao.id);
        }
        
        function getYoutubeId(url) {
            if (!url || typeof url !== 'string') return '';
            url = url.trim();
            var id = '';
            if (url.indexOf('youtube.com/watch') !== -1) {
                var m = url.match(/[?&]v=([a-zA-Z0-9_-]{11})/);
                id = m ? m[1] : '';
            } else if (url.indexOf('youtu.be/') !== -1) {
                var m = url.match(/youtu\.be\/([a-zA-Z0-9_-]{11})/);
                id = m ? m[1] : '';
            } else if (url.indexOf('/embed/') !== -1) {
                var m = url.match(/\/embed\/([a-zA-Z0-9_-]{11})/);
                id = m ? m[1] : '';
            } else if (url.length === 11 && /^[a-zA-Z0-9_-]+$/.test(url)) {
                id = url;
            }
            return id;
        }
        
        var ytPlayer = null;
        var customPlayerProgressInterval = null;
        var playbackRates = [0.5, 0.75, 1, 1.25, 1.5, 1.75, 2];
        var currentRateIndex = 2;
        
        function initCustomPlayerOverlay() {
            var wrap = document.getElementById('customProgressWrap');
            var bar = document.getElementById('customProgressBar');
            var btnPlay = document.getElementById('btnPlayPause');
            var iconPlay = document.getElementById('iconPlayPause');
            var btnBack = document.getElementById('btnBack10');
            var btnFwd = document.getElementById('btnFwd10');
            var btnSpeed = document.getElementById('btnSpeed');
            var timeEl = document.getElementById('customTime');
            
            if (!wrap || !bar || !btnPlay) return;
            
            function updateProgress() {
                if (!ytPlayer || typeof ytPlayer.getCurrentTime !== 'function') return;
                var t = ytPlayer.getCurrentTime();
                var d = ytPlayer.getDuration();
                if (isNaN(t) || isNaN(d) || d <= 0) return;
                var pct = (t / d) * 100;
                bar.style.width = pct + '%';
                timeEl.textContent = formatTime(t) + ' / ' + formatTime(d);
            }
            
            function formatTime(sec) {
                if (isNaN(sec) || sec < 0) return '0:00';
                var m = Math.floor(sec / 60);
                var s = Math.floor(sec % 60);
                return m + ':' + (s < 10 ? '0' : '') + s;
            }
            
            wrap.addEventListener('click', function(e) {
                if (!ytPlayer || typeof ytPlayer.getDuration !== 'function') return;
                var d = ytPlayer.getDuration();
                if (isNaN(d) || d <= 0) return;
                var rect = wrap.getBoundingClientRect();
                var pct = (e.clientX - rect.left) / rect.width;
                var seekTo = Math.max(0, Math.min(1, pct)) * d;
                ytPlayer.seekTo(seekTo, true);
                updateProgress();
            });
            
            btnPlay.addEventListener('click', function() {
                if (!ytPlayer) return;
                var state = ytPlayer.getPlayerState ? ytPlayer.getPlayerState() : -1;
                if (state === 1) {
                    ytPlayer.pauseVideo();
                    iconPlay.className = 'fa-solid fa-play';
                } else {
                    ytPlayer.playVideo();
                    iconPlay.className = 'fa-solid fa-pause';
                }
            });
            
            btnBack.addEventListener('click', function() {
                if (!ytPlayer || typeof ytPlayer.getCurrentTime !== 'function') return;
                var t = ytPlayer.getCurrentTime();
                ytPlayer.seekTo(Math.max(0, t - 10), true);
            });
            
            btnFwd.addEventListener('click', function() {
                if (!ytPlayer || typeof ytPlayer.getCurrentTime !== 'function') return;
                var t = ytPlayer.getCurrentTime();
                var d = ytPlayer.getDuration();
                ytPlayer.seekTo(Math.min(d || 0, t + 10), true);
            });
            
            btnSpeed.addEventListener('click', function() {
                if (!ytPlayer || typeof ytPlayer.setPlaybackRate !== 'function') return;
                currentRateIndex = (currentRateIndex + 1) % playbackRates.length;
                var rate = playbackRates[currentRateIndex];
                ytPlayer.setPlaybackRate(rate);
                btnSpeed.textContent = rate + 'x';
            });
            
            window.customPlayerUpdateProgress = updateProgress;
        }
        
        function startProgressPolling() {
            if (customPlayerProgressInterval) clearInterval(customPlayerProgressInterval);
            customPlayerProgressInterval = setInterval(function() {
                if (window.customPlayerUpdateProgress) window.customPlayerUpdateProgress();
            }, 500);
        }
        
        function stopProgressPolling() {
            if (customPlayerProgressInterval) {
                clearInterval(customPlayerProgressInterval);
                customPlayerProgressInterval = null;
            }
        }
        
        function loadVideo(url, title, description, videoId) {
            var ytId = getYoutubeId(url);
            if (!ytId) {
                alert('URL do v\u00eddeo inv\u00e1lida. Verifique a URL do YouTube.');
                return;
            }
            
            document.getElementById('videoTitle').textContent = title;
            document.getElementById('videoDescription').textContent = description || '';
            
            var playerHost = document.getElementById('youtubePlayer');
            if (!playerHost) return;
            
            function createOrLoadPlayer() {
                if (typeof YT === 'undefined' || !YT.Player) {
                    setTimeout(createOrLoadPlayer, 100);
                    return;
                }
                if (!ytPlayer) {
                    ytPlayer = new YT.Player('youtubePlayer', {
                        height: '100%',
                        width: '100%',
                        videoId: ytId,
                        playerVars: {
                            autoplay: 1,
                            controls: 0,
                            rel: 0,
                            modestbranding: 1,
                            playsinline: 1,
                            showinfo: 0,
                            iv_load_policy: 3
                        },
                        events: {
                            onReady: function() {
                                initCustomPlayerOverlay();
                                startProgressPolling();
                                var iconPlay = document.getElementById('iconPlayPause');
                                if (iconPlay) iconPlay.className = 'fa-solid fa-pause';
                                var btnSpeed = document.getElementById('btnSpeed');
                                if (btnSpeed) btnSpeed.textContent = '1x';
                                currentRateIndex = 2;
                            }
                        }
                    });
                } else {
                    ytPlayer.loadVideoById(ytId);
                    startProgressPolling();
                    var iconPlay = document.getElementById('iconPlayPause');
                    if (iconPlay) iconPlay.className = 'fa-solid fa-pause';
                    var btnSpeed = document.getElementById('btnSpeed');
                    if (btnSpeed) btnSpeed.textContent = '1x';
                    currentRateIndex = 2;
                }
            }
            
            if (ytPlayer && ytPlayer.loadVideoById) {
                ytPlayer.loadVideoById(ytId);
                startProgressPolling();
                var iconPlay = document.getElementById('iconPlayPause');
                if (iconPlay) iconPlay.className = 'fa-solid fa-pause';
                var btnSpeed = document.getElementById('btnSpeed');
                if (btnSpeed) btnSpeed.textContent = '1x';
                currentRateIndex = 2;
            } else {
                createOrLoadPlayer();
            }
            
            document.querySelectorAll('.course-video-item').forEach(function(item) {
                item.classList.remove('active');
            });
            var videoItem = document.querySelector('[data-video-id="' + videoId + '"]');
            if (videoItem) {
                videoItem.classList.add('active');
                var concluido = videoItem.getAttribute('data-video-concluido') === '1';
                var btn = document.getElementById('markVideoCompletedBtn');
                if (btn) {
                    if (concluido) {
                        btn.innerHTML = '<i class="fa-solid fa-check-circle me-2"></i> V\u00eddeo Assistido';
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-secondary');
                        btn.disabled = true;
                    } else {
                        btn.innerHTML = '<i class="fa-solid fa-check-circle me-2"></i> Marcar como Assistido';
                        btn.classList.remove('btn-secondary');
                        btn.classList.add('btn-success');
                        btn.disabled = false;
                    }
                    btn.style.display = 'block';
                }
            }
            
            currentVideoId = videoId;
        }
        
        function loadCourseTrack(modulo, todasSessoes, videos, sessaoAtualId) {
            var trackContent = document.getElementById('courseTrackContent');
            if (!trackContent) return;
            
            var sessaoAtual = todasSessoes.find(function(s) { return s.id == sessaoAtualId; });
            var sessaoNome = sessaoAtual ? sessaoAtual.nome : 'Carregando...';
            
            var html = '<div class="course-videos-only"><h4 class="course-track-session-title">' + sessaoNome + '</h4><div id="videosList" class="course-videos-list">';
            
            videos.forEach(function(video, index) {
                var ytId = getYoutubeId(video.url_youtube);
                var esc = function(s) { return (s || '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;'); };
                var watchedClass = video.concluido ? ' watched' : '';
                html += '<div class="course-video-item' + watchedClass + ' ' + (index === 0 ? 'active' : '') + '" ' +
                    'data-video-id="' + video.id + '" data-youtube-id="' + (ytId || '') + '" ' +
                    'data-video-title="' + esc(video.titulo) + '" data-video-description="' + esc(video.descricao) + '" ' +
                    'data-video-concluido="' + (video.concluido ? '1' : '0') + '" ' +
                    'data-video-url="' + esc(video.url_youtube) + '">' +
                    '<span class="course-video-icon"><i class="fa-solid ' + (video.concluido ? 'fa-check-circle' : 'fa-play-circle') + '"></i></span>' +
                    '<div class="course-video-text">' +
                    '<div class="video-title-small">' + (video.titulo || '') + '</div>' +
                    (video.duracao ? '<div class="video-duration">' + formatDuration(video.duracao) + '</div>' : '') +
                    '</div></div>';
            });
            
            html += '</div>';
            
            var todosVideosConcluidos = videos.length > 0 && videos.every(function(v) { return v.concluido; });
            var sessaoJaConcluida = sessaoAtual && sessaoAtual.concluida;
            
            if (todosVideosConcluidos && videos.length > 0 && !sessaoJaConcluida) {
                html += '<div class="complete-session-container"><button id="completeSessionBtn" class="btn-complete-session" onclick="completeSession(' + sessaoAtualId + ')"><i class="fa-solid fa-check-circle me-2"></i> Concluir Sessão</button></div>';
            } else if (sessaoJaConcluida) {
                html += '<div class="complete-session-container"><div class="session-completed-msg"><i class="fa-solid fa-check-circle me-2"></i> Sessão Concluída</div></div>';
            }
            
            html += '</div>';
            trackContent.innerHTML = html;
            
            var track = document.getElementById('courseTrackContent');
            if (track) {
                track.removeEventListener('click', handleCourseVideoClick);
                track.addEventListener('click', handleCourseVideoClick);
            }
        }
        
        function handleCourseVideoClick(e) {
            var item = e.target.closest('.course-video-item');
            if (!item) return;
            e.preventDefault();
            var ytId = item.getAttribute('data-youtube-id');
            var url = item.getAttribute('data-video-url');
            var title = item.getAttribute('data-video-title') || '';
            var desc = item.getAttribute('data-video-description') || '';
            var videoId = item.getAttribute('data-video-id');
            var urlOrId = ytId || url || '';
            if (urlOrId && videoId) loadVideo(urlOrId, title, desc, parseInt(videoId, 10));
        }
        
        function completeSession(sessaoId) {
            if (!confirm('Tem certeza que deseja concluir esta sessão? Isso liberará a próxima sessão para você.')) {
                return;
            }
            
            const btn = document.getElementById('completeSessionBtn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Concluindo...';
            }
            
            fetch('/alunos/sessao/concluir', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    sessao_id: sessaoId,
                    produto_id: {{ $produto->id }}
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualiza visualmente a sessão como concluída
                    const sessionItem = document.querySelector(`.course-session-item[data-sessao-id="${sessaoId}"]`);
                    if (sessionItem) {
                        sessionItem.classList.add('completed');
                        sessionItem.classList.remove('active');
                    }
                    
                    // Remove botão e mostra mensagem de sucesso
                    if (btn) {
                        btn.parentElement.innerHTML = `
                            <div style="padding: 14px; background: #0f172a; border-radius: 6px; text-align: center; color: #10b981; font-weight: 600;">
                                <i class="fa-solid fa-check-circle me-2"></i> Sessão Concluída
                            </div>
                        `;
                    }
                    
                    // Recarrega a página após 1 segundo para atualizar status das sessões
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert(data.error || 'Erro ao concluir sessão.');
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-solid fa-check-circle me-2"></i> Concluir Sessão';
                    }
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao concluir sessão.');
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-check-circle me-2"></i> Concluir Sessão';
                }
            });
        }
        
        function formatDuration(seconds) {
            const h = Math.floor(seconds / 3600);
            const m = Math.floor((seconds % 3600) / 60);
            const s = seconds % 60;
            if (h > 0) {
                return `${h}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
            }
            return `${m}:${s.toString().padStart(2, '0')}`;
        }
        
        function loadSessionFromTrack(sessaoId) {
            // Recarrega página com nova sessão
            window.location.href = '/alunos/content/{{ $produto->id }}?sessao=' + sessaoId;
        }
        
        function markVideoCompleted() {
            if (!currentVideoId) return;
            
            // Busca dados do vídeo
            const videoItem = document.querySelector(`[data-video-id="${currentVideoId}"]`);
            if (!videoItem) return;
            
            // Marca como concluído usando a API de progresso
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
                    const videoItem = document.querySelector(`[data-video-id="${currentVideoId}"]`);
                    if (videoItem) {
                        videoItem.classList.add('completed');
                        videoItem.setAttribute('data-video-concluido', '1');
                        const icon = videoItem.querySelector('i');
                        if (icon) {
                            icon.className = 'fa-solid fa-check-circle';
                            icon.style.color = '#10b981';
                        }
                    }
                    
                    const btn = document.getElementById('markVideoCompletedBtn');
                    if (btn) {
                        btn.innerHTML = '<i class="fa-solid fa-check-circle me-2"></i> Vídeo Assistido';
                        btn.classList.add('btn-secondary');
                        btn.disabled = true;
                    }
                    
                    // Verifica se todos os vídeos foram concluídos para mostrar botão "Concluir Sessão"
                    checkAllVideosCompleted();
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao marcar vídeo como concluído.');
            });
        }
        
        function checkAllVideosCompleted() {
            const allVideoItems = document.querySelectorAll('.course-video-item');
            const allCompleted = Array.from(allVideoItems).every(item => {
                return item.getAttribute('data-video-concluido') === '1';
            });
            
            if (allCompleted && allVideoItems.length > 0 && currentSessaoId) {
                // Verifica se botão já existe
                if (!document.getElementById('completeSessionBtn')) {
                    const trackContent = document.getElementById('courseTrackContent');
                    if (trackContent) {
                        const completeBtn = document.createElement('div');
                        completeBtn.className = 'complete-session-container';
                        completeBtn.innerHTML = `
                            <button id="completeSessionBtn" class="btn-complete-session" onclick="completeSession(${currentSessaoId})">
                                <i class="fa-solid fa-check-circle me-2"></i> Concluir Sessão
                            </button>
                        `;
                        trackContent.appendChild(completeBtn);
                    }
                }
            }
        }
        
        let currentSessaoId = null;
        
        var currentVideoId = null;
        
        // Função para abrir sessão (estilo Netflix - clica na capa)
        function openSession(sessaoId, moduloId) {
            handleSessionClick(sessaoId, moduloId, true);
        }
    </script>
    <script>
        lucide.createIcons();
        
        let player;
        let currentVideoId = null;
        let progressInterval = null;
        
        // Header scroll effect
        window.addEventListener('scroll', () => {
            const header = document.getElementById('header');
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
        
        // YouTube API
        function onYouTubeIframeAPIReady() {
            // Player será criado quando abrir o vídeo
        }
        
        function openVideo(videoId, embedUrl, title, description) {
            // Se videoId for 0, é um vídeo da área antiga (link direto)
            if (videoId === 0 || !videoId) {
                // Abre em nova aba para vídeos da área antiga
                window.open(embedUrl.replace('/embed/', '/watch?v='), '_blank');
                return;
            }
            
            currentVideoId = videoId;
            document.getElementById('videoTitle').textContent = title;
            document.getElementById('videoDescription').textContent = description || '';
            document.getElementById('videoModal').classList.add('active');
            
            // Cria o player do YouTube
            if (!player) {
                const videoIdFromUrl = embedUrl.match(/embed\/([^?]+)/)?.[1];
                player = new YT.Player('youtubePlayer', {
                    height: '100%',
                    width: '100%',
                    videoId: videoIdFromUrl,
                    playerVars: {
                        autoplay: 1,
                        controls: 1,
                        rel: 0,
                        modestbranding: 1,
                    },
                    events: {
                        'onReady': onPlayerReady,
                        'onStateChange': onPlayerStateChange
                    }
                });
            } else {
                const videoIdFromUrl = embedUrl.match(/embed\/([^?]+)/)?.[1];
                player.loadVideoById(videoIdFromUrl);
            }
        }
        
        function closeVideo() {
            document.getElementById('videoModal').classList.remove('active');
            if (player) {
                player.stopVideo();
            }
            if (progressInterval) {
                clearInterval(progressInterval);
            }
        }
        
        function onPlayerReady(event) {
            // Player pronto
        }
        
        function onPlayerStateChange(event) {
            if (event.data == YT.PlayerState.PLAYING) {
                startProgressTracking();
            } else if (event.data == YT.PlayerState.PAUSED || event.data == YT.PlayerState.ENDED) {
                if (progressInterval) {
                    clearInterval(progressInterval);
                }
                
                if (event.data == YT.PlayerState.ENDED) {
                    checkVideoCompletion();
                }
            }
        }
        
        function startProgressTracking() {
            if (progressInterval) {
                clearInterval(progressInterval);
            }
            
            progressInterval = setInterval(() => {
                if (player && currentVideoId) {
                    const currentTime = player.getCurrentTime();
                    const duration = player.getDuration();
                    
                    updateProgress(currentVideoId, Math.floor(currentTime), Math.floor(duration), Math.floor(currentTime));
                }
            }, 5000); // Atualiza a cada 5 segundos
        }
        
        function updateProgress(videoId, tempoAssistido, tempoTotal, ultimaPosicao) {
            fetch('/alunos/api/progresso/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    video_id: videoId,
                    tempo_assistido: tempoAssistido,
                    tempo_total: tempoTotal,
                    ultima_posicao: ultimaPosicao
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.concluido) {
                    // Vídeo concluído - mostra modal de progresso
                    showProgressModal();
                }
            })
            .catch(error => console.error('Erro ao atualizar progresso:', error));
        }
        
        function checkVideoCompletion() {
            if (currentVideoId) {
                fetch('/alunos/api/progresso/{{ $produto->id }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.progressos[currentVideoId]?.concluido) {
                            showProgressModal();
                        }
                    });
            }
        }
        
        function showProgressModal() {
            fetch('/alunos/api/progresso/{{ $produto->id }}')
                .then(response => response.json())
                .then(data => {
                    const videosConcluidos = Object.values(data.progressos).filter(p => p.concluido).length;
                    const totalVideos = Object.keys(data.progressos).length;
                    
                    document.getElementById('videosCompleted').textContent = videosConcluidos;
                    document.getElementById('progressPercent').textContent = data.progresso_geral + '%';
                    var pf = document.getElementById('progressFill');
                    var pp = document.getElementById('progressPct');
                    if (pf) pf.style.width = data.progresso_geral + '%';
                    if (pp) pp.textContent = data.progresso_geral + '%';
                    
                    // Animações diferentes baseadas no progresso
                    if (data.progresso_geral === 100) {
                        document.getElementById('celebrationEmoji').textContent = '🏆';
                        document.getElementById('progressTitle').textContent = 'Curso Completo!';
                        document.getElementById('progressMessage').textContent = 'Parabéns! Você concluiu todo o curso!';
                    } else if (data.progresso_geral >= 50) {
                        document.getElementById('celebrationEmoji').textContent = '🔥';
                        document.getElementById('progressTitle').textContent = 'Excelente Progresso!';
                        document.getElementById('progressMessage').textContent = 'Você está na metade do caminho! Continue assim!';
                    } else {
                        document.getElementById('celebrationEmoji').textContent = '🎉';
                        document.getElementById('progressTitle').textContent = 'Parabéns!';
                        document.getElementById('progressMessage').textContent = 'Você completou este vídeo! Continue assistindo!';
                    }
                    
                    document.getElementById('progressModal').classList.add('active');
                });
        }
        
        function closeProgressModal() {
            document.getElementById('progressModal').classList.remove('active');
        }
        
        // Fecha modal ao clicar fora
        document.getElementById('videoModal').addEventListener('click', (e) => {
            if (e.target.id === 'videoModal') {
                closeVideo();
            }
        });
        
        document.getElementById('progressModal').addEventListener('click', (e) => {
            if (e.target.id === 'progressModal') {
                closeProgressModal();
            }
        });
        
        // Função para abrir detalhes do módulo
        function openModuleDetails(moduloId) {
            const modulo = modulosData[moduloId];
            if (!modulo) return;
            
            let html = `
                <h2 style="font-size: 32px; margin-bottom: 20px; color: var(--text-color);">${modulo.nome}</h2>
                ${modulo.descricao ? `<p style="font-size: 16px; opacity: 0.8; margin-bottom: 30px; color: var(--text-color);">${modulo.descricao}</p>` : ''}
                <div class="sessions-list">
            `;
            
            modulo.sessoes.forEach(sessao => {
                html += `
                    <div class="session-card">
                        <div class="session-title">${sessao.nome}</div>
                        ${sessao.descricao ? `<p style="font-size: 14px; opacity: 0.7; margin-bottom: 15px;">${sessao.descricao}</p>` : ''}
                        ${sessao.videos.length > 0 ? `
                            <div class="videos-list">
                                ${sessao.videos.map(video => `
                                    <div class="video-item" onclick="openVideo(${video.id}, '${video.embed_url}', '${video.titulo.replace(/'/g, "\\'")}', '${(video.descricao || '').replace(/'/g, "\\'")}'); closeModuleDetails();">
                                        <img src="${video.thumbnail}" alt="${video.titulo}" class="video-thumbnail" onerror="this.src='https://img.youtube.com/vi/' + (getYoutubeId(video.embed_url || video.url_youtube || '') || '') + '/hqdefault.jpg'">
                                        <div class="video-info">
                                            <div class="video-title">
                                                ${video.concluido ? '<span class="check-icon">✓</span>' : ''}
                                                ${video.titulo}
                                            </div>
                                            <div class="video-meta">
                                                ${video.duracao ? new Date(video.duracao * 1000).toISOString().substr(14, 5) : ''}
                                            </div>
                                            ${video.porcentagem > 0 ? `
                                                <div class="video-progress">
                                                    <div class="video-progress-fill" style="width: ${video.porcentagem}%"></div>
                                                </div>
                                            ` : ''}
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        ` : ''}
                    </div>
                `;
            });
            
            html += '</div>';
            
            document.getElementById('moduleDetailsContent').innerHTML = html;
            document.getElementById('moduleDetailsModal').classList.add('active');
            lucide.createIcons();
        }
        
        
        // Função para abrir detalhes de categoria antiga
        function openModuleDetailsOld(categoriaId) {
            // Para categorias antigas, pode implementar similar ou redirecionar
            alert('Módulo da área antiga. Funcionalidade em desenvolvimento.');
        }
        
        // Fechar modal de detalhes
        function closeModuleDetails() {
            document.getElementById('moduleDetailsModal').classList.remove('active');
        }
        
        // Fecha modal ao clicar fora
        document.getElementById('moduleDetailsModal').addEventListener('click', (e) => {
            if (e.target.id === 'moduleDetailsModal') {
                closeModuleDetails();
            }
        });
        
        // Re-inicializa ícones quando necessário
        setInterval(() => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }, 1000);

        // Chat do aluno está no script autocontido logo após o painel (não depende deste bloco)
    </script>
</body>
</html>
