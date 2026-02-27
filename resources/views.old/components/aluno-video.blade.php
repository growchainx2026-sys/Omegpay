@props(['file'])
<link href="https://vjs.zencdn.net/8.23.3/video-js.css" rel="stylesheet" />
<style>
    .video-wrapper {
        width: 100%;
        height: 100%;
        position: relative;
    }

    .video-js {
        width: 100% !important;
        height: 100% !important;
    }
</style>

<div class="video-wrapper">
    <video id="my-video-{{ $file->id }}" 
           class="video-js vjs-fill" 
           controls 
           preload="auto" 
           poster="/storage/{{ $file->cover }}" 
           data-setup="{}">
        <source src="/storage/{{ $file->file }}" type="video/mp4" />
        <p class="vjs-no-js">
            To view this video please enable JavaScript, and consider upgrading to a
            web browser that
            <a href="https://videojs.com/html5-video-support/" target="_blank">
                supports HTML5 video
            </a>
        </p>
    </video>
</div>

<script src="https://vjs.zencdn.net/8.23.3/video.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const video = document.getElementById('my-video-{{ $file->id }}');
    const storageKey = "video-current";   // chave global no localStorage
    const videoId = "{{ $file->id }}";    // chave única para este vídeo

    // Recupera todos os vídeos salvos
    let videos = JSON.parse(localStorage.getItem(storageKey)) || {};

    // Se existir posição salva, aplica
    if (videos[videoId]) {
        video.currentTime = parseFloat(videos[videoId]);
    }

    // Atualiza em tempo real a cada mudança de tempo
    video.addEventListener("timeupdate", () => {
        videos[videoId] = video.currentTime;
        localStorage.setItem(storageKey, JSON.stringify(videos));
    });

    // (Opcional) Limpa quando o vídeo termina
    video.addEventListener("ended", () => {
        delete videos[videoId];
        localStorage.setItem(storageKey, JSON.stringify(videos));
    });
});

 const player{{ $file->id }} = videojs('my-video-{{ $file->id }}');

    // Função para pausar
    function pauseVideo{{ $file->id }}() {
        player{{ $file->id }}.pause();
    }
</script>
