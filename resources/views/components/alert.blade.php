@props([
'text',
'color' => 'var(--gateway-primary-color)',
'bg' => 'var(--gateway-opacity2)'
])

<div class="alert alert-info p-2" style="border-left:3px solid {{ $color }};background: {{ $bg }}!important;" role="alert">
    <strong style="color: {{ $color }} !important;"><i class="fa-solid fa-circle-exclamation"></i>&nbsp;
        {{$text}}
    </strong>
</div>