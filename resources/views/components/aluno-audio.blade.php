@props(['file'])
<link rel="stylesheet" type="text/css"
    href="https://cdn.jsdelivr.net/gh/greghub/green-audio-player/dist/css/green-audio-player.min.css">
<script src="https://cdn.jsdelivr.net/gh/greghub/green-audio-player/dist/js/green-audio-player.min.js"></script>

<div class="sound-{{ $file->id }}" style="width: 100%;margin-top: 120px;">
    <audio>
        <source src="/storage/{{ $file->file }}" type="audio/mpeg">
    </audio>
</div>

<script>
    let play = new GreenAudioPlayer('.sound-{{ $file->id }}');
    play.init({
        selector: '.player', // inits Green Audio Player on each audio container that has class "player"
        stopOthersOnPlay: true,
         volume
    });
</script>
